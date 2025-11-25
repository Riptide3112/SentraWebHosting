<?php
// api/update_settings.php

// 1. Sesiunea și Verificarea Autentificării
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Setează datele de conectare
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

// Conexiunea la baza de date
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    error_log("DB Connection Error: " . $conn->connect_error);
    $_SESSION['notification'] = [
        'type' => 'error',
        'text' => 'Eroare critică de conectare la baza de date.'
    ];
    header("Location: ../client/user_settings.php");
    exit;
}
$conn->set_charset("utf8mb4");

// 2. VALIDAREA ȘI PREGĂTIREA DATELOR
$data = [];
// Câmpuri obligatorii (corespund coloanelor)
$required_fields = [
    'first_name',
    'last_name',
    'phone',
    'address_line_1',
    'city',
    'postal_code',
    'country',
    'county'
];
// Câmpuri opționale (corespund coloanelor)
$optional_fields = [
    'company_name',
    'address_line_2',
    'reg_comert',
    'cod_fiscal' // ⚠️ ATENȚIE: am folosit reg_comert
];

foreach ($required_fields as $field) {
    if (empty(trim($_POST[$field] ?? ''))) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'text' => "Câmpul '" . str_replace('_', ' ', $field) . "' este obligatoriu și nu poate fi lăsat gol."
        ];
        $conn->close();
        header("Location: ../client/user_settings.php");
        exit;
    }
    $data[$field] = $conn->real_escape_string(trim($_POST[$field]));
}

foreach ($optional_fields as $field) {
    $data[$field] = $conn->real_escape_string(trim($_POST[$field] ?? ''));
}


// 3. CONSTRUIREA ȘI EXECUTAREA INTEROGĂRII
$sql = "UPDATE users SET 
        first_name = ?, last_name = ?, phone = ?, company_name = ?, 
        address_line_1 = ?, address_line_2 = ?, city = ?, postal_code = ?, 
        country = ?, county = ?, reg_comert = ?, cod_fiscal = ? 
        WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    $_SESSION['notification'] = ['type' => 'error', 'text' => 'Eroare la pregătirea interogării SQL.'];
    $conn->close();
    header("Location: ../client/user_settings.php");
    exit;
}

// BIND PARAMETERS (ss s s s s s s s s s s i = 12 string + 1 integer)
$stmt->bind_param(
    "ssssssssssssi",
    $data['first_name'],
    $data['last_name'],
    $data['phone'],
    $data['company_name'],
    $data['address_line_1'],
    $data['address_line_2'],
    $data['city'],
    $data['postal_code'],
    $data['country'],
    $data['county'],
    $data['reg_comert'],
    $data['cod_fiscal'],
    $user_id
);

if ($stmt->execute()) {
    // 4. Succes
    $_SESSION['notification'] = [
        'type' => 'success',
        'text' => 'Setările contului au fost actualizate cu succes!'
    ];
} else {
    // 5. Eroare la execuție
    error_log("Execute failed: " . $stmt->error);
    $_SESSION['notification'] = [
        'type' => 'error',
        'text' => 'A apărut o eroare la actualizarea bazei de date. Te rugăm să încerci din nou.'
    ];
}

$stmt->close();
$conn->close();

// 6. Redirecționare
header("Location: ../client/user_settings.php");
exit;
?>