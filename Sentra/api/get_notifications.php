<?php
// api/get_notifications.php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authentificated']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Conexiunea la baza de date (ajustează detaliile)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    // Returnează eroare 500 pentru erori de DB
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection error']);
    exit;
}
$conn->set_charset("utf8mb4");

// Interogare pentru a prelua notificările detaliate (id, is_read și message sunt esențiale pentru JS)
// Presupunem o coloană 'type' sau 'context' care ajută la alegerea iconiței (dacă nu există, o vei simula ca mai jos)
$sql_details = "SELECT id, message, created_at, is_read, type FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 20";
$stmt_details = $conn->prepare($sql_details);
$stmt_details->bind_param("i", $user_id);
$stmt_details->execute();
$result_details = $stmt_details->get_result();

$data = ['success' => true, 'notifications' => []];

while ($row_details = $result_details->fetch_assoc()) {
    
    // Convertim booleanul (0/1) în tip boolean și definim iconița pentru Lucide Icons
    $is_read = (bool)$row_details['is_read'];
    $type = strtolower($row_details['type'] ?? 'general'); // Asigură-te că tabela ta are o coloană 'type'

    $icon = 'info';
    $color = 'text-blue-600';
    
    if (strpos($type, 'ticket') !== false) {
        $icon = 'message-square';
    } elseif (strpos($type, 'invoice') !== false || strpos($type, 'payment') !== false) {
        $icon = 'wallet';
        $color = $is_read ? 'text-gray-400' : 'text-red-600'; // Facturile sunt importante
    } elseif (strpos($type, 'maintenance') !== false || strpos($type, 'server') !== false) {
        $icon = 'server';
    }
    
    // Structura necesară pentru JavaScript
    $data['notifications'][] = [
        'id'        => (int)$row_details['id'],
        'message'   => $row_details['message'],
        'time'      => $row_details['created_at'], // Ideal, aici ar trebui să formatezi timpul (ex: "acum 5 minute")
        'is_read'   => $is_read,
        'icon'      => $icon,
        'color'     => $color // Culoarea este optionala, dar utila
    ];
}

$stmt_details->close();
$conn->close();

echo json_encode($data);
?>