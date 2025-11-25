<?php
// api/ticket_actions.php - Backend pentru AJAX (Salvează răspunsuri și acțiuni)

include '../includes/auth_check.php';
// Permite doar staff-ului și admin-ului
check_access(['staff', 'admin']);

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// Verifică metoda și datele
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $response['message'] = 'Metodă invalidă.';
    echo json_encode($response);
    exit;
}

// Preluare date JSON din corpul cererii
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['action']) || !isset($data['ticket_id']) || !is_numeric($data['ticket_id'])) {
    $response['message'] = 'Date insuficiente sau ticket ID invalid.';
    echo json_encode($response);
    exit;
}

$ticket_id = (int) $data['ticket_id'];
$action = $data['action'];
$sender_id = $_SESSION['user_id'];
$sender_role = $_SESSION['role'] ?? $_SESSION['user_role'] ?? 'staff';
$sender_name = $_SESSION['user_name'] ?? 'Staff';
$sender_first_name = $_SESSION['first_name'] ?? $sender_name;

// Conexiunea la baza de date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $response['message'] = "Eroare de conectare la baza de date.";
    echo json_encode($response);
    exit;
}
$conn->set_charset("utf8mb4");

/**
 * Functie interna pentru a genera si insera o notificare profesionala.
 */
function insert_notification($conn, $ticket_id, $client_id, $type, $subject, $staff_name = '', $staff_role = '') {
    $notif_message = "";
    $notif_link = "client/ticket.php" . $ticket_id; // Actualizat calea

    // Formatează rolul staff-ului pentru afișare
    $formatted_role = '';
    if ($staff_role === 'admin') {
        $formatted_role = 'Administrator';
    } elseif ($staff_role === 'staff') {
        $formatted_role = 'Staff Suport';
    }

    switch ($type) {
        case 'staff_reply':
            if ($staff_name && $formatted_role) {
                $notif_message = "{$staff_name} ({$formatted_role}) a răspuns la tichetul #{$ticket_id}: \"{$subject}\"";
            } else {
                $notif_message = "Un membru al echipei a răspuns la tichetul #{$ticket_id}: \"{$subject}\"";
            }
            break;
            
        case 'ticket_closed':
            if ($staff_name && $formatted_role) {
                $notif_message = "{$staff_name} ({$formatted_role}) a închis tichetul #{$ticket_id}: \"{$subject}\"";
            } else {
                $notif_message = "Tichetul #{$ticket_id} \"{$subject}\" a fost închis";
            }
            break;
            
        case 'ticket_reopened':
            if ($staff_name && $formatted_role) {
                $notif_message = "{$staff_name} ({$formatted_role}) a redeschis tichetul #{$ticket_id}: \"{$subject}\"";
            } else {
                $notif_message = "Tichetul #{$ticket_id} \"{$subject}\" a fost redeschis";
            }
            break;
            
        case 'ticket_in_progress':
            if ($staff_name && $formatted_role) {
                $notif_message = " {$staff_name} ({$formatted_role}) a preluat tichetul #{$ticket_id}: \"{$subject}\"";
            } else {
                $notif_message = "Tichetul #{$ticket_id} \"{$subject}\" este acum în curs de rezolvare";
            }
            break;
            
        default:
            return;
    }

    $stmt_notif = $conn->prepare("
        INSERT INTO notifications (user_id, type, message, link, is_read, related_id) 
        VALUES (?, ?, ?, ?, 0, ?)
    ");
    $stmt_notif->bind_param("isssi", $client_id, $type, $notif_message, $notif_link, $ticket_id);
    $stmt_notif->execute();
    $stmt_notif->close();
}

/**
 * Functie pentru a obtine numele complet al utilizatorului
 */
function get_user_full_name($conn, $user_id) {
    $stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if ($user) {
        return trim($user['first_name'] . ' ' . $user['last_name']);
    }
    return '';
}

try {
    // Preluare ID client și Subiect
    $client_id = null;
    $subject = 'Tichet #'.$ticket_id;

    $stmt_info = $conn->prepare("SELECT user_id, subject FROM tickets WHERE id = ?");
    $stmt_info->bind_param("i", $ticket_id);
    $stmt_info->execute();
    $result_info = $stmt_info->get_result();
    $ticket_info = $result_info->fetch_assoc();
    $client_id = $ticket_info['user_id'] ?? null;
    $subject = htmlspecialchars($ticket_info['subject'] ?? 'Tichet #'.$ticket_id);
    $stmt_info->close();

    // Obține numele complet al staff-ului
    $staff_full_name = get_user_full_name($conn, $sender_id);
    if (!$staff_full_name) {
        $staff_full_name = $sender_first_name;
    }

    if ($action === 'reply' && isset($data['message'])) {
        // --- 1. SALVEAZĂ RĂSPUNSUL ---

        $message_content = trim($data['message']);

        // Inserare răspuns în tabela ticket_messages
        $stmt_insert = $conn->prepare("INSERT INTO ticket_messages (ticket_id, sender_id, sender_role, content) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("iiss", $ticket_id, $sender_id, $sender_role, $message_content);

        if ($stmt_insert->execute()) {

            // Actualizează statusul tichetului
            $stmt_update = $conn->prepare("UPDATE tickets SET status = 'answered', last_updated = NOW() WHERE id = ? AND status IN ('open', 'in_progress')");
            $stmt_update->bind_param("i", $ticket_id);
            $stmt_update->execute();

            // GENERARE NOTIFICARE PROFESIONALĂ
            if ($client_id !== null) {
                insert_notification($conn, $ticket_id, $client_id, 'staff_reply', $subject, $staff_full_name, $sender_role);
            }

            $response['success'] = true;
            $response['message'] = 'Răspunsul a fost trimis cu succes!';
        } else {
            $response['message'] = 'Eroare la salvarea răspunsului: ' . $stmt_insert->error;
        }
        $stmt_insert->close();

    } elseif ($action === 'close') {
        // --- 2. ÎNCHIDE TICHTETUL ---

        $stmt_close = $conn->prepare("UPDATE tickets SET status = 'closed', last_updated = NOW() WHERE id = ?");
        $stmt_close->bind_param("i", $ticket_id);

        if ($stmt_close->execute()) {
            
            // GENERARE NOTIFICARE PROFESIONALĂ
            if ($client_id !== null) {
                insert_notification($conn, $ticket_id, $client_id, 'ticket_closed', $subject, $staff_full_name, $sender_role);
            }

            $response['success'] = true;
            $response['message'] = "Tichetul #{$ticket_id} a fost închis.";
        } else {
            $response['message'] = 'Eroare la închiderea tichetului: ' . $stmt_close->error;
        }
        $stmt_close->close();

    } elseif ($action === 'reopen') {
        // --- 3. REDESCHIDE TICHTETUL ---

        $sql = "UPDATE tickets SET status = 'open', last_updated = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $ticket_id);

        if ($stmt->execute()) {
            
            // GENERARE NOTIFICARE PROFESIONALĂ
            if ($client_id !== null) {
                insert_notification($conn, $ticket_id, $client_id, 'ticket_reopened', $subject, $staff_full_name, $sender_role);
            }

            $response['success'] = true;
            $response['message'] = 'Tichet redeschis.';
        } else {
            $response['message'] = 'Eroare la actualizarea bazei de date: ' . $conn->error;
        }
        $stmt->close();

    } elseif ($action === 'take_over') {
        // --- 4. PRELUARE TICHET (nouă acțiune) ---
        
        $stmt_take = $conn->prepare("UPDATE tickets SET status = 'in_progress', last_updated = NOW() WHERE id = ? AND status = 'open'");
        $stmt_take->bind_param("i", $ticket_id);

        if ($stmt_take->execute() && $stmt_take->affected_rows > 0) {
            
            // GENERARE NOTIFICARE PROFESIONALĂ
            if ($client_id !== null) {
                insert_notification($conn, $ticket_id, $client_id, 'ticket_in_progress', $subject, $staff_full_name, $sender_role);
            }

            $response['success'] = true;
            $response['message'] = 'Tichet preluat cu succes.';
        } else {
            $response['message'] = 'Tichetul nu a putut fi preluat sau a fost deja preluat.';
        }
        $stmt_take->close();

    } else {
        $response['message'] = 'Acțiune necunoscută.';
    }

} catch (Exception $e) {
    $response['message'] = 'Eroare la procesare: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);
?>