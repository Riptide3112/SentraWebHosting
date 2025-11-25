<?php
session_start();

// 1. Conectare la baza de date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $_SESSION['notification'] = ['type' => 'error', 'text' => 'Eroare la conectarea bazei de date!'];
    header("Location: register.php");
    exit;
}
$conn->set_charset("utf8mb4");

// 2. Preluare și curățare date
$form_fields = [
    'first_name',
    'last_name',
    'email',
    'phone',
    'company_name',
    'address_line_1',
    'address_line_2',
    'city',
    'postal_code',
    'country',
    'county',
    'reg_comert',
    'cod_fiscal',
    'found_us',
    'security_question',
    'security_answer'
];

$form_data = [];
foreach ($form_fields as $field) {
    // Aplicăm trim() pe datele de formular, dar nu și pe parolă
    $form_data[$field] = isset($_POST[$field]) ? trim($_POST[$field]) : '';
}

// Preluăm parola și confirmarea (FĂRĂ trim() - esențial pentru parola hashuita)
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$_SESSION['form_data'] = $form_data; // salvăm pentru refill

// 3. Validări de bază
foreach (['first_name', 'last_name', 'email', 'password', 'confirm_password'] as $required) {
    if (empty($_POST[$required])) {
        $_SESSION['notification'] = ['type' => 'error', 'text' => 'Toate câmpurile marcate cu * sunt obligatorii!'];
        header("Location: register.php");
        exit;
    }
}

if ($password !== $confirm_password) {
    $_SESSION['notification'] = ['type' => 'error', 'text' => 'Parolele nu coincid!'];
    header("Location: register.php");
    exit;
}

// 4. Verificare email existent
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $_POST['email']);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    $_SESSION['notification'] = ['type' => 'error', 'text' => 'Acest email este deja folosit!'];
    header("Location: register.php");
    exit;
}
$check->close();

// 5. Hash parolă
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 6. Inserare DB
$stmt = $conn->prepare("
    INSERT INTO users 
    (first_name, last_name, email, phone, company_name, address_line_1, address_line_2, city, postal_code, country, county, reg_comert, cod_fiscal, found_us, password, security_question, security_answer)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssssssssssssssss",
    $form_data['first_name'],
    $form_data['last_name'],
    $form_data['email'],
    $form_data['phone'],
    $form_data['company_name'],
    $form_data['address_line_1'],
    $form_data['address_line_2'],
    $form_data['city'],
    $form_data['postal_code'],
    $form_data['country'],
    $form_data['county'],
    $form_data['reg_comert'],
    $form_data['cod_fiscal'],
    $form_data['found_us'],
    $hashed_password,
    $form_data['security_question'],
    $form_data['security_answer']
);

if ($stmt->execute()) {

    // ⭐ LOGICA DE AUTENTIFICARE DUPĂ ÎNREGISTRARE ⭐

    // Preluarea ID-ului noului utilizator
    $user_id = $conn->insert_id;

    // SECURITATE: Prevenire Session Fixation
    session_regenerate_id(true);

    // SETAREA SESIUNII (Autentificarea utilizatorului)
    $_SESSION['user_id'] = $user_id;
    $_SESSION['email'] = $form_data['email'];
    $_SESSION['user_name'] = $form_data['first_name'];
    $_SESSION['loggedin'] = true;

    unset($_SESSION['form_data']); // golim datele completate
    $_SESSION['notification'] = ['type' => 'success', 'text' => 'Contul a fost creat cu succes!'];

    // REDIRECȚIONARE LA DASHBOARD
    header("Location: ../client/dashboard.php");

} else {
    $_SESSION['notification'] = ['type' => 'error', 'text' => 'Eroare la înregistrare!'];
    header("Location: register.php");
}

$stmt->close();
$conn->close();
exit;
?>