<?php
// api/create_announcement.php - Unificat: CREATE (Adaugă) și DELETE (Șterge)
header('Content-Type: application/json');
session_start();

// Date de conectare la baza de date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

$response = ['success' => false, 'message' => ''];

// 1. VERIFICARE ACCES ȘI METODĂ
$logged_in_user_role = strtolower($_SESSION['user_role'] ?? 'client');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Metodă invalidă.';
    echo json_encode($response);
    exit;
}

// Permite operațiunile doar pentru Admin
if ($logged_in_user_role !== 'admin') {
    $response['message'] = 'Acces interzis. Doar administratorii pot efectua modificări.';
    echo json_encode($response);
    exit;
}

// Conexiune la baza de date
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $response['message'] = 'Eroare de conectare la baza de date.';
    echo json_encode($response);
    exit;
}
$conn->set_charset("utf8mb4");

// 2. DETECTARE OPERAȚIE
$announcement_id_to_delete = intval($_POST['id'] ?? 0);
$is_delete_operation = ($announcement_id_to_delete > 0 && !isset($_POST['title']));

if ($is_delete_operation) {
    // --- OPERAȚIA DELETE ---
    $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $announcement_id_to_delete);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response['success'] = true;
            $response['message'] = 'Anunț șters cu succes!';
        } else {
            $response['message'] = 'Anunțul nu a fost găsit sau nu a putut fi șters.';
        }
    } else {
        $response['message'] = 'Eroare la ștergerea anunțului: ' . $conn->error;
    }
    
    $stmt->close();
    
} else {
    // --- OPERAȚIA CREATE ---
    
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category = trim($_POST['target_role'] ?? 'Altele'); 
    
    if (empty($title) || empty($content)) {
        $response['message'] = 'Titlul și conținutul anunțului sunt obligatorii pentru creare.';
        $conn->close();
        echo json_encode($response);
        exit;
    }

    $valid_categories = ['Servicii', 'Regulament', 'Clienti', 'Altele'];
    if (!in_array($category, $valid_categories)) {
        $response['message'] = 'Categorie invalidă.';
        $conn->close();
        echo json_encode($response);
        exit;
    }

    $status = 'active';
    
    $stmt = $conn->prepare("INSERT INTO announcements (title, content, target_role, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $content, $category, $status);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Anunț postat cu succes!';
    } else {
        $response['message'] = 'Eroare la salvarea anunțului: ' . $conn->error;
    }

    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>