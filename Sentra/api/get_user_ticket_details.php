<?php
// get_user_ticket_details.php

session_start();
header('Content-Type: application/json');

// 1. VERIFICAREA AUTENTIFICĂRII
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Autentificare necesară.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$ticket_id = $_GET['id'] ?? null;

if (!$ticket_id || !is_numeric($ticket_id)) {
    echo json_encode(['success' => false, 'message' => 'ID tichet invalid.']);
    exit;
}

// 2. CONECTARE LA DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Eroare de conectare la baza de date.']);
    exit;
}
$conn->set_charset("utf8mb4");

// 3. VALIDAREA PROPRIETĂȚII TICHETULUI
// Verificăm dacă tichetul există ȘI dacă aparține utilizatorului curent
$sql_ticket = "SELECT id, user_id FROM tickets WHERE id = ? AND user_id = ?";
$stmt_ticket = $conn->prepare($sql_ticket);
$stmt_ticket->bind_param("ii", $ticket_id, $user_id);
$stmt_ticket->execute();
$result_ticket = $stmt_ticket->get_result();

if ($result_ticket->num_rows === 0) {
    $stmt_ticket->close();
    $conn->close();
    echo json_encode(['success' => false, 'message' => 'Tichetul nu există sau nu îți aparține.']);
    exit;
}
$stmt_ticket->close();


// 4. PRELUAREA MESAJELOR (DIN TABELUL ticket_messages)
// Ne asigurăm că preluăm user_id pentru a determina cine a trimis mesajul
$sql_messages = "SELECT user_id, content, created_at FROM ticket_messages WHERE ticket_id = ? ORDER BY created_at ASC";
$stmt_messages = $conn->prepare($sql_messages);
$stmt_messages->bind_param("i", $ticket_id);
$stmt_messages->execute();
$result_messages = $stmt_messages->get_result();
$messages = [];

// Adaugă mesajul inițial al tichetului (din tabelul 'tickets')
$sql_initial = "SELECT user_id, content, created_at FROM tickets WHERE id = ?";
$stmt_initial = $conn->prepare($sql_initial);
$stmt_initial->bind_param("i", $ticket_id);
$stmt_initial->execute();
$result_initial = $stmt_initial->get_result();
if ($initial = $result_initial->fetch_assoc()) {
    // Mesajul inițial este întotdeauna al clientului
    $messages[] = [
        'content' => $initial['content'],
        'created_at' => $initial['created_at'],
        'is_client' => true // Marcat ca mesaj al clientului
    ];
}
$stmt_initial->close();


// Procesează mesajele de răspuns
while ($row = $result_messages->fetch_assoc()) {
    $is_client = ($row['user_id'] == $user_id);
    $messages[] = [
        'content' => $row['content'],
        'created_at' => $row['created_at'],
        'is_client' => $is_client // TRUE dacă user_id este al clientului curent
    ];
}

$stmt_messages->close();
$conn->close();

// 5. RĂSPUNSUL FINAL
echo json_encode([
    'success' => true,
    'messages' => $messages
]);
?>