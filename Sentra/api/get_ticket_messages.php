<?php
// ! MĂSURI DE SIGURANȚĂ CRITICE: Oprește afișarea oricărui Warning/Notice PHP care strică JSON-ul
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL); 
// !

header('Content-Type: application/json');
session_start();

// Date de conectare (asigură-te că sunt corecte!)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

// 1. VERIFICARE AUTENTIFICARE
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); 
    echo json_encode(['error' => 'Sesiune expirata. Te rugam sa te reautentifici.']);
    exit;
}
$user_id = $_SESSION['user_id'];
$ticket_id = $_GET['ticket_id'] ?? null;

// 2. VALIDARE INPUT
if (empty($ticket_id) || !is_numeric($ticket_id)) {
    http_response_code(400); 
    echo json_encode(['error' => 'ID tichet invalid.']);
    exit;
}

// 3. CONECTARE LA DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    // Dacă conexiunea eșuează, returnează un JSON curat.
    echo json_encode(['error' => 'Eroare fatala de conectare la baza de date.']);
    exit;
}
$conn->set_charset("utf8mb4");

// 4. VERIFICARE AUTORIZAȚIE (Proprietarul Tichetului)
$sql_check = "SELECT user_id FROM tickets WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);

// Verificare suplimentară de eroare SQL
if (!$stmt_check) {
    http_response_code(500);
    echo json_encode(['error' => 'Eroare SQL: Verificati tabela `tickets` (coloana user_id).']);
    $conn->close();
    exit;
}

$stmt_check->bind_param("i", $ticket_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$ticket_owner = $result_check->fetch_assoc();
$stmt_check->close();

if (!$ticket_owner || $ticket_owner['user_id'] != $user_id) {
    http_response_code(403); 
    echo json_encode(['error' => 'Nu aveti permisiunea de a accesa acest tichet.']);
    $conn->close();
    exit;
}

// 5. PRELUARE MESAJELOR
// ATENTIE: coloanele `user_role` și `content` sunt esențiale pentru JS!
$sql_messages = "SELECT sender_id AS user_id, sender_role AS user_role, content, created_at FROM ticket_messages WHERE ticket_id = ? ORDER BY created_at ASC";
$stmt_messages = $conn->prepare($sql_messages);

if (!$stmt_messages) {
    http_response_code(500); 
    echo json_encode(['error' => 'Eroare SQL la preluarea mesajelor. Verificati coloanele tabelei `ticket_messages`.']);
    $conn->close();
    exit;
}

$stmt_messages->bind_param("i", $ticket_id);
$stmt_messages->execute();
$result_messages = $stmt_messages->get_result();

$messages = $result_messages->fetch_all(MYSQLI_ASSOC);

$stmt_messages->close();
$conn->close();

// 6. RASPUNS SUCCES (Întotdeauna JSON curat)
echo json_encode(['success' => true, 'messages' => $messages]);
?>