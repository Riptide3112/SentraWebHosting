<?php
// get_ticket_details.php - Backend pentru AJAX (Staff/Admin)

session_start();
header('Content-Type: application/json');

// VERIFICARE SIMPLĂ DE ROL - DOAR STAFF/ADMIN
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['staff', 'admin'])) {
    echo json_encode(['success' => false, 'message' => 'Acces restricționat. Doar staff și admin pot vedea tichete.']);
    exit;
}

$response = ['success' => false, 'message' => ''];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $response['message'] = 'ID-ul tichetului lipsește sau nu este valid.';
    echo json_encode($response);
    exit;
}

$ticket_id = (int) $_GET['id'];

// Conexiune la baza de date
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

// 1️⃣ PRELUARE DETALII TICHET - STAFF/ADMIN POT VEDEA TOATE TICHETELE
$sql_ticket = "SELECT 
                    t.id AS ticket_id, t.subject, t.status, t.created_at, t.content AS initial_content,
                    u.first_name, u.last_name, u.email
                FROM tickets t
                JOIN users u ON t.user_id = u.id
                WHERE t.id = ?"; // FĂRĂ verificarea user_id!

$stmt_ticket = $conn->prepare($sql_ticket);
if (!$stmt_ticket) {
    $response['message'] = "Eroare la pregătirea interogării.";
    echo json_encode($response);
    exit;
}

$stmt_ticket->bind_param("i", $ticket_id); // DOAR ticket_id, nu și user_id
$stmt_ticket->execute();
$result_ticket = $stmt_ticket->get_result();

if ($result_ticket->num_rows === 0) {
    $response['message'] = "Tichetul nu a fost găsit.";
    echo json_encode($response);
    $conn->close();
    exit;
}

$ticket = $result_ticket->fetch_assoc();
$ticket['ticket_id'] = (int) $ticket['ticket_id'];

// 2️⃣ CONSTRUIREA ISTORICULUI DE MESAJE
$messages = [];

$sql_replies = "SELECT content, created_at, sender_role
                FROM ticket_messages
                WHERE ticket_id = ?
                ORDER BY created_at ASC";
$stmt_replies = $conn->prepare($sql_replies);
$stmt_replies->bind_param("i", $ticket_id);
$stmt_replies->execute();
$result_replies = $stmt_replies->get_result();

// ✅ Dacă există mesaje în tabela ticket_messages, le folosim pe acelea
if ($result_replies->num_rows > 0) {
    while ($reply = $result_replies->fetch_assoc()) {
        $messages[] = [
            'content' => $reply['content'],
            'created_at' => date('d M Y, H:i', strtotime($reply['created_at'])),
            'user_role' => $reply['sender_role']
        ];
    }
} else {
    // 🟡 Dacă nu există mesaje secundare, folosim conținutul inițial din `tickets`
    $messages[] = [
        'content' => $ticket['initial_content'],
        'created_at' => date('d M Y, H:i', strtotime($ticket['created_at'])),
        'user_role' => 'client'
    ];
}

// 3️⃣ RETURNARE JSON CĂTRE FRONTEND
$response['success'] = true;
$response['ticket'] = $ticket;
$response['messages'] = $messages;

$conn->close();
echo json_encode($response);
?>