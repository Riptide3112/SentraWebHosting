<?php
// api/reply_to_ticket.php
// Gestioneaza trimiterea unui raspuns la un tichet

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL); 

header('Content-Type: application/json');
session_start();

// Date de conectare
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

// 1. VERIFICARE AUTENTIFICARE & METODA
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Metoda incorecta.']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); 
    echo json_encode(['error' => 'Sesiune expirata.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'] ?? 'client';

// 2. VALIDARE INPUT
$ticket_id = $_POST['ticket_id'] ?? null;
$content = trim($_POST['content'] ?? '');

if (empty($ticket_id) || !is_numeric($ticket_id) || empty($content)) {
    http_response_code(400); 
    echo json_encode(['error' => 'Date invalide.']);
    exit;
}

// 3. CONECTARE LA DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Eroare de conectare la baza de date.']);
    exit;
}
$conn->set_charset("utf8mb4");

// 4. VERIFICARE AUTORIZAȚIE (similar cu get_ticket_messages.php)
$sql_check = "SELECT user_id, status FROM tickets WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $ticket_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$ticket_data = $result_check->fetch_assoc();
$stmt_check->close();

if (!$ticket_data || $ticket_data['user_id'] != $user_id) {
    http_response_code(403); 
    echo json_encode(['error' => 'Nu aveti permisiunea de a raspunde la acest tichet.']);
    $conn->close();
    exit;
}

// 5. INSERARE MESAJ
// Folosim coloanele corecte: sender_id și sender_role
$sql_insert = "INSERT INTO ticket_messages (ticket_id, sender_id, sender_role, content) VALUES (?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);

if (!$stmt_insert) {
    http_response_code(500);
    echo json_encode(['error' => 'Eroare SQL la inserarea mesajului.']);
    $conn->close();
    exit;
}

$stmt_insert->bind_param("iiss", $ticket_id, $user_id, $user_role, $content);
$success = $stmt_insert->execute();
$stmt_insert->close();

if (!$success) {
    http_response_code(500);
    echo json_encode(['error' => 'Eroare la salvarea mesajului.']);
    $conn->close();
    exit;
}

// 6. ACTUALIZARE STATUS (OPȚIONAL)
// Dacă un client răspunde, marcăm tichetul ca 'awaiting_staff' (implicit 'open' e ok)
// Dacă un tichet era 'resolved' sau 'closed', o replica îl poate redeschide
if ($ticket_data['status'] === 'closed' || $ticket_data['status'] === 'resolved') {
    $new_status = 'open';
    $sql_update = "UPDATE tickets SET status = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $new_status, $ticket_id);
    $stmt_update->execute();
    $stmt_update->close();
}

$conn->close();

// 7. RASPUNS SUCCES
echo json_encode(['success' => true, 'message' => 'Răspuns trimis!']);
?>