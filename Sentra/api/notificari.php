<?php
// /api/notificari.php
session_start();
header('Content-Type: application/json');

// 🔐 Conectare DB
$conn = new mysqli("localhost", "root", "", "sentra_db");
$conn->set_charset("utf8mb4");
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id === 0) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// 🟢 POST: Marcare ca citit (individual / toate) sau ștergere
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    switch ($data['action'] ?? '') {
        case 'mark_all_read':
            $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            echo json_encode(['success' => true, 'count' => $stmt->affected_rows]);
            $stmt->close();
            exit;

        case 'mark_read':
            if (!isset($data['notif_id'])) {
                echo json_encode(['success' => false, 'message' => 'notif_id missing']);
                exit;
            }
            $notif_id = (int)$data['notif_id'];
            $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $notif_id, $user_id);
            $stmt->execute();
            echo json_encode(['success' => true, 'rows' => $stmt->affected_rows]);
            $stmt->close();
            exit;

        case 'delete_read':
            $stmt = $conn->prepare("DELETE FROM notifications WHERE user_id = ? AND is_read = 1");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            echo json_encode(['success' => true, 'count' => $stmt->affected_rows]);
            $stmt->close();
            exit;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            exit;
    }
}

// 🟣 GET: Returnează notificările
$stmt = $conn->prepare("
    SELECT id, message, link, is_read, created_at, related_id
    FROM notifications
    WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT 20
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notificari = [];
while ($row = $result->fetch_assoc()) {
    // Link implicit din DB
    $link = $row['link'] ?? '#';
    $is_ticket = false;

    // 🔴 MODIFICARE CRITICĂ: Actualizat căile către noul locație ticket.php
    if (!empty($row['related_id'])) {
        $related_id = (int)$row['related_id'];
        // SOLUȚIE: Actualizat calea către client/ticket.php
        $link = '../client/ticket.php?open_ticket=' . $related_id;
        $is_ticket = true;
    } else {
        // Dacă link-ul există dar conține "view_ticket", îl înlocuim cu "client/ticket"
        if (!empty($row['link']) && strpos($row['link'], 'view_ticket') !== false) {
            // Extrage ID-ul din link-ul vechi
            if (preg_match('/id=(\d+)/', $row['link'], $matches)) {
                $link = 'client/ticket.php?open_ticket=' . $matches[1];
            } else {
                $link = 'client/ticket.php';
            }
            $is_ticket = true;
        }
        // Dacă link-ul conține deja ticket.php, asigură-te că are parametrul corect
        elseif (!empty($row['link']) && strpos($row['link'], 'ticket.php') !== false) {
            if (preg_match('/open_ticket=(\d+)/', $row['link'], $matches)) {
                $link = '../client/ticket.php?open_ticket=' . $matches[1];
            } else {
                $link = '../client/ticket.php';
            }
            $is_ticket = true;
        }
    }

    $notificari[] = [
        'id' => (int)$row['id'],
        'mesaj' => $row['message'],
        'url' => $link,
        'data_creare' => date('d.m.Y H:i', strtotime($row['created_at'])),
        'is_read' => (int)$row['is_read'],
        'is_ticket' => $is_ticket
    ];
}

echo json_encode($notificari);
$stmt->close();
$conn->close();
?>