<?php
// login_process.php - Versiune Finală Curată cu Roluri

session_start();

// 1. Conectare la baza de date
$conn = new mysqli("localhost", "root", "", "sentra_db");
if ($conn->connect_error) {
    $_SESSION['notification'] = ['type' => 'error', 'text' => "Eroare de sistem: Conectarea la baza de date a eșuat."];
    header("Location: login.php");
    exit;
}
$conn->set_charset("utf8mb4");

// 2. Preluare și curățare date
$email = trim(strtolower($_POST['email'] ?? ''));
// ATENȚIE: Nu se aplică trim() pe parola hash-uită. Se folosește valoarea brută.
$password_input = $_POST['password'] ?? '';

$remember_me = isset($_POST['remember_me']);
$error_message = "Email sau parolă incorectă.";

// Validare de bază
if (empty($email) || empty($password_input)) {
    $_SESSION['notification'] = ['type' => 'error', 'text' => $error_message];
    $conn->close();
    header("Location: login.php");
    exit;
}

// 3. Interogare Bază de Date - AM ADAUGAT COLOANA 'role'
$stmt = $conn->prepare("SELECT id, password, first_name, role FROM users WHERE email = ?");

if (!$stmt) {
    $_SESSION['notification'] = ['type' => 'error', 'text' => "Eroare de sistem: Pregătirea interogării a eșuat."];
    $conn->close();
    header("Location: login.php");
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // 4. VERIFICARE PAROLĂ
    if (password_verify($password_input, $user['password'])) {

        // SECURITATE: Prevenire Session Fixation
        session_regenerate_id(true);

        // Autentificare reușită
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $email;
        $_SESSION['user_name'] = $user['first_name'];
        $_SESSION['loggedin'] = true;

        // 🔑 PAS CRITIC: SALVĂM ROLUL ÎN SESIUNE (Curățat și Normalizat)
        $_SESSION['user_role'] = trim(strtolower($user['role']));

        // Logica Remember Me (Setare cookie-uri sigure)
        if ($remember_me) {
            setcookie('remember_me', $user['id'], time() + (30 * 24 * 60 * 60), "/", "", false, true);
        } else {
            setcookie('remember_me', '', time() - 3600, "/");
        }

        $stmt->close();
        $conn->close();

        // 5. REDIRECȚIONARE către dashboard pentru TOȚI
header("Location: ../client/dashboard.php");
exit;
    }
}

// 6. Logica de eroare finală (pentru parole incorecte sau emailuri negăsite)
$_SESSION['notification'] = ['type' => 'error', 'text' => $error_message];
if (isset($stmt))
    $stmt->close();
$conn->close();
header("Location: login.php");
exit;
?>