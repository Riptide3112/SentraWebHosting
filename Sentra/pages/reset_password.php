<?php
// reset_password.php - Versiune corectată
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_log("🔄 ACCES reset_password.php - Token: " . ($_GET['token'] ?? 'NICIUNUL'));

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// Configurare baza de date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

$token = $_GET['token'] ?? '';
$error = '';
$success = '';
$user_name = '';
$user_email = '';
$valid_token = false;

// Verifică token-ul
if (!empty($token)) {
    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if ($conn->connect_error) {
            throw new Exception('Eroare de conectare la baza de date.');
        }

        $conn->set_charset("utf8mb4");
        
        // VERIFICARE SIMPLIFICATĂ - fără expirare pentru testare
        $stmt = $conn->prepare("
            SELECT pr.*, u.first_name, u.email 
            FROM password_resets pr 
            JOIN users u ON pr.user_id = u.id 
            WHERE pr.token = ? AND pr.used = 0
        ");
        
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $reset_data = $result->fetch_assoc();
            
            // Verifică manual expirarea
            $current_time = time();
            $expires_time = strtotime($reset_data['expires_at']);
            
            error_log("⏰ Timp curent: " . date('Y-m-d H:i:s', $current_time));
            error_log("⏰ Expiră la: " . $reset_data['expires_at']);
            error_log("⏰ Diferență: " . ($expires_time - $current_time) . " secunde");
            
            if ($expires_time > $current_time) {
                $valid_token = true;
                $user_name = $reset_data['first_name'];
                $user_email = $reset_data['email'];
                $user_id = $reset_data['user_id'];
                error_log("✅ TOKEN VALID pentru: " . $user_name);
            } else {
                $error = "Link-ul de resetare a expirat. Solicită unul nou.";
                error_log("❌ TOKEN EXPIRAT");
            }
        } else {
            $error = "Link invalid sau deja folosit.";
            error_log("❌ TOKEN NEGĂSIT sau FOLOSIT");
        }
        
        $stmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        $error = "A apărut o eroare de sistem. Încearcă din nou mai târziu.";
        error_log("💥 EXCEPȚIE: " . $e->getMessage());
    }
} else {
    $error = "Link de resetare invalid.";
}

// Procesează formularul de resetare
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    if ($new_password !== $confirm_password) {
        $error = "Parolele nu coincid!";
    } elseif (strlen($new_password) < 6) {
        $error = "Parola trebuie să aibă minim 6 caractere!";
    } elseif (!$valid_token) {
        $error = "Sesiunea a expirat. Solicită un nou link de resetare.";
    } else {
        try {
            $conn = new mysqli($servername, $username, $password, $dbname);
            
            if ($conn->connect_error) {
                throw new Exception('Eroare de conectare la baza de date.');
            }

            // Actualizează parola
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $hashed_password, $user_id);
            
            if ($update_stmt->execute()) {
                // Marchează token-ul ca folosit
                $conn->query("UPDATE password_resets SET used = 1 WHERE token = '$token'");
                
                $success = "Parola a fost resetată cu succes! Vei fi redirecționat către autentificare...";
                $valid_token = false;
                
                header("Refresh: 3; URL=login.php");
            }
            
            $update_stmt->close();
            $conn->close();
            
        } catch (Exception $e) {
            $error = "A apărut o eroare la resetarea parolei.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resetare Parolă - Sentra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-y: auto;
            min-height: 100vh;
        }

        .sentra-cyan {
            background-color: #00BFB2;
        }

        .text-sentra-cyan {
            color: #00BFB2;
        }

        .gradient-sidebar {
            background: linear-gradient(135deg, #1f2937 0%, #00BFB2 100%);
        }

        .dynamic-background {
            background: 
                linear-gradient(135deg, #059669 0%, #00BFB2 50%, #0d9488 100%),
                radial-gradient(circle at 20% 80%, rgba(0, 191, 178, 0.4) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(5, 150, 105, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(13, 148, 136, 0.25) 0%, transparent 50%);
            background-blend-mode: overlay, screen, screen, normal;
            position: relative;
            min-height: 100vh;
            overflow: hidden;
        }

        .dynamic-background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                linear-gradient(90deg, transparent 95%, rgba(0, 191, 178, 0.15) 100%),
                linear-gradient(90deg, transparent 90%, rgba(5, 150, 105, 0.12) 95%),
                linear-gradient(transparent 95%, rgba(0, 191, 178, 0.15) 100%),
                linear-gradient(transparent 90%, rgba(5, 150, 105, 0.12) 95%),
                radial-gradient(circle at 20% 30%, rgba(0, 191, 178, 0.2) 2px, transparent 3px),
                radial-gradient(circle at 50% 70%, rgba(5, 150, 105, 0.18) 2px, transparent 3px),
                radial-gradient(circle at 80% 40%, rgba(0, 191, 178, 0.2) 2px, transparent 3px),
                radial-gradient(circle at 30% 80%, rgba(5, 150, 105, 0.18) 2px, transparent 3px),
                radial-gradient(circle at 70% 20%, rgba(0, 191, 178, 0.2) 2px, transparent 3px);
            background-size: 
                50px 50px, 100px 100px, 50px 50px, 100px 100px,
                200px 200px, 300px 300px, 250px 250px, 350px 350px, 400px 400px;
            opacity: 0.5;
            pointer-events: none;
        }

        @keyframes dataFlow {
            0% { transform: translateX(-100px) translateY(-100px) rotate(0deg); opacity: 0; }
            50% { opacity: 0.4; }
            100% { transform: translateX(100px) translateY(100px) rotate(360deg); opacity: 0; }
        }

        .data-flow {
            position: absolute;
            width: 3px;
            height: 120px;
            background: linear-gradient(to bottom, transparent, #00BFB2, #059669, transparent);
            animation: dataFlow 6s linear infinite;
            border-radius: 2px;
        }

        .data-flow:nth-child(1) { left: 10%; top: 20%; animation-delay: 0s; }
        .data-flow:nth-child(2) { left: 30%; top: 60%; animation-delay: 1.5s; }
        .data-flow:nth-child(3) { left: 70%; top: 30%; animation-delay: 3s; }
        .data-flow:nth-child(4) { left: 85%; top: 70%; animation-delay: 4.5s; }
        .data-flow:nth-child(5) { left: 50%; top: 10%; animation-delay: 2s; }
        .data-flow:nth-child(6) { left: 20%; top: 80%; animation-delay: 5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .floating-particle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(0, 191, 178, 0.6);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .floating-particle:nth-child(1) { left: 15%; top: 25%; animation-delay: 0s; }
        .floating-particle:nth-child(2) { left: 75%; top: 65%; animation-delay: 2s; }
        .floating-particle:nth-child(3) { left: 40%; top: 85%; animation-delay: 4s; }
        .floating-particle:nth-child(4) { left: 90%; top: 35%; animation-delay: 6s; }
        .floating-particle:nth-child(5) { left: 60%; top: 15%; animation-delay: 1s; }

        @keyframes wave {
            0% { transform: translateX(-100%) scaleY(0.5); }
            50% { transform: translateX(0%) scaleY(1); }
            100% { transform: translateX(100%) scaleY(0.5); }
        }

        .wave-animation {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 100px;
            background: linear-gradient(90deg, transparent, rgba(0, 191, 178, 0.1), transparent);
            animation: wave 15s linear infinite;
            opacity: 0.3;
        }

        .main-container {
            max-width: 1000px;
            max-height: 90vh;
            width: 95%;
            margin: 2rem auto;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 30px rgba(0, 191, 178, 0.1);
            background: white;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .main-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #00BFB2, #059669, #00BFB2);
            border-radius: 1.6rem;
            z-index: -1;
            opacity: 0.1;
        }

        .form-area {
            padding: 2rem 1.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        @media (min-width: 768px) {
            .main-container {
                width: 90%;
                margin-top: 3rem;
                margin-bottom: 3rem;
                min-height: 550px;
                max-height: 80vh;
            }
            .form-area {
                padding: 3rem 2.5rem;
            }
        }

        .custom-input {
            transition: all 0.3s ease-in-out;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 15px;
        }

        .custom-input:focus {
            border-color: #00BFB2;
            box-shadow: 0 0 0 3px rgba(0, 191, 178, 0.25);
            transform: translateY(-2px);
        }

        .custom-input:hover {
            border-color: #00BFB2;
            transform: translateY(-1px);
        }

        .btn-glow {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            background: #00BFB2;
            border: none;
            padding: 16px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-glow:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 191, 178, 0.8);
            background: #009688;
        }

        .password-strength {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: #ef4444; width: 33%; }
        .strength-medium { background: #f59e0b; width: 66%; }
        .strength-strong { background: #10b981; width: 100%; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
    </style>
</head>

<body class="dynamic-background flex items-center justify-center p-4 relative">

    <!-- Elemente animate de fundal -->
    <div class="data-flow"></div>
    <div class="data-flow"></div>
    <div class="data-flow"></div>
    <div class="data-flow"></div>
    <div class="data-flow"></div>
    <div class="data-flow"></div>

    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>

    <div class="wave-animation"></div>

    <div class="flex bg-white main-container overflow-hidden animate-fade-in-up">

        <!-- Sidebar pentru desktop -->
        <div class="hidden md:flex md:w-2/5 gradient-sidebar p-8 flex-col justify-between items-start text-white relative overflow-hidden">

            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 left-10 w-20 h-20 border-2 border-white rounded-lg"></div>
                <div class="absolute top-40 right-10 w-16 h-16 border-2 border-white rounded-full"></div>
                <div class="absolute bottom-20 left-20 w-24 h-12 border-2 border-white"></div>
            </div>

            <div class="w-full relative z-10">
                <a href="index.php" class="flex items-center mb-6 cursor-pointer hover:opacity-90 transition duration-300">
                    <h1 class="text-4xl font-extrabold tracking-tight mr-2 text-white">Sentra</h1>
                    <div class="w-3 h-3 rounded-full bg-white shadow-xl"></div>
                </a>
                <h2 class="text-2xl font-semibold mb-4">Resetează-ți parola!</h2>
                <p class="text-sm text-gray-200">
                    <span>🔐</span> Securitate maximă • 
                    <span>⚡</span> Proces rapid • 
                    <span>🛡️</span> Protecție date
                </p>
            </div>

            <div class="w-full">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 mb-6">
                    <h3 class="text-sm font-bold mb-2 text-center text-white">Sfaturi de securitate</h3>
                    <ul class="text-xs text-gray-200 space-y-1">
                        <li>• Folosește cel puțin 8 caractere</li>
                        <li>• Combina litere, cifre și simboluri</li>
                        <li>• Nu folosi parole evidente</li>
                    </ul>
                </div>
            </div>

            <div class="mt-auto pt-4 text-xs w-full border-t border-gray-600 relative z-10">
                <p class="text-gray-300">
                    Vrei să te autentifici?
                    <a href="login.php" class="font-bold border-b border-white hover:text-gray-100 transition duration-300">
                        Conectează-te aici
                    </a>
                </p>
            </div>
        </div>

        <!-- Form Area -->
        <div class="w-full md:w-3/5 p-6 lg:p-8 form-area">
            <!-- Logo pentru mobile -->
            <div class="md:hidden flex items-center justify-center mb-6">
                <a href="index.php" class="flex items-center cursor-pointer">
                    <h1 class="text-3xl font-extrabold tracking-tight mr-2 text-sentra-cyan">Sentra</h1>
                    <div class="w-3 h-3 rounded-full sentra-cyan shadow-lg"></div>
                </a>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 text-center md:text-left">
                <i class="fas fa-key text-sentra-cyan mr-2"></i>
                Resetare Parolă
            </h2>

            <?php if ($error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700"><?php echo htmlspecialchars($error); ?></p>
                            <?php if (strpos($error, 'expirat') !== false || strpos($error, 'invalid') !== false): ?>
                                <a href="forgot_password.php" class="text-red-600 hover:text-red-500 text-sm font-medium mt-2 inline-block">
                                    <i class="fas fa-redo mr-1"></i>
                                    Solicită un nou link de resetare
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700"><?php echo htmlspecialchars($success); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($valid_token && !$success): ?>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-shield text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Salut <strong><?php echo htmlspecialchars($user_name); ?></strong>! 
                                Alege o nouă parolă securizată.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" class="space-y-6 max-w-md w-full mx-auto md:mx-0">
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-500 mr-1"></i>
                            Parolă nouă
                        </label>
                        <input type="password" id="new_password" name="new_password" required minlength="6"
                            class="custom-input w-full"
                            placeholder="Minim 6 caractere"
                            oninput="checkPasswordStrength(this.value)">
                        <div class="password-strength">
                            <div class="strength-bar" id="passwordStrength"></div>
                        </div>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-500 mr-1"></i>
                            Confirmă parola
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6"
                            class="custom-input w-full"
                            placeholder="Introdu parola din nou"
                            oninput="checkPasswordMatch()">
                        <div id="passwordMatch" class="text-sm mt-2"></div>
                    </div>

                    <button type="submit" class="btn-glow w-full">
                        <i class="fas fa-key mr-2"></i>
                        Resetează parola
                    </button>
                </form>
            <?php endif; ?>

            <?php if (!$valid_token && !$success && !$error): ?>
                <div class="text-center py-8">
                    <i class="fas fa-lock text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600 mb-4">Link invalid sau expirat.</p>
                    <a href="forgot_password.php" class="text-sentra-cyan hover:text-[#009688] font-medium">
                        <i class="fas fa-redo mr-1"></i>
                        Solicită un nou link de resetare
                    </a>
                </div>
            <?php endif; ?>

            <div class="mt-6 text-center">
                <a href="login.php" class="text-sentra-cyan hover:text-[#009688] text-sm font-medium">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Înapoi la autentificare
                </a>
            </div>
        </div>
    </div>

    <script>
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('passwordStrength');
            let strength = 0;
            
            if (password.length >= 6) strength += 1;
            if (password.length >= 8) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            strengthBar.className = 'strength-bar';
            if (password.length === 0) {
                strengthBar.style.width = '0%';
            } else if (strength <= 2) {
                strengthBar.className += ' strength-weak';
            } else if (strength <= 4) {
                strengthBar.className += ' strength-medium';
            } else {
                strengthBar.className += ' strength-strong';
            }
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchElement = document.getElementById('passwordMatch');
            
            if (confirmPassword.length === 0) {
                matchElement.innerHTML = '';
            } else if (password === confirmPassword) {
                matchElement.innerHTML = '<span class="text-green-600"><i class="fas fa-check mr-1"></i>Parolele coincid</span>';
            } else {
                matchElement.innerHTML = '<span class="text-red-600"><i class="fas fa-times mr-1"></i>Parolele nu coincid</span>';
            }
        }
    </script>
</body>
</html>