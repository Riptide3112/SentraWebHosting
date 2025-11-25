<?php
// forgot_password.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: ../client/dashboard.php");
    exit;
}

// Configurare baza de date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

// Funcție pentru trimiterea email-ului
function sendPasswordResetEmail($to, $firstName, $resetLink)
{
    $subject = "Resetare parolă - Sentra";
    
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { 
                font-family: 'Inter', Arial, sans-serif; 
                background: #f8fafc;
                margin: 0; 
                padding: 0; 
                color: #1f2937;
            }
            .container { 
                max-width: 500px; 
                margin: 0 auto; 
                background: white; 
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                border: 1px solid #e5e7eb;
            }
            .header { 
                background: #00BFB2; 
                padding: 30px 20px; 
                color: white; 
                text-align: center; 
            }
            .content { 
                padding: 30px; 
                line-height: 1.6;
            }
            .button { 
                display: block; 
                background: #00BFB2; 
                color: white; 
                padding: 14px 0; 
                text-decoration: none; 
                border-radius: 8px; 
                font-weight: 600; 
                text-align: center;
                margin: 25px 0;
                width: 100%;
            }
            .footer { 
                background: #f1f5f9; 
                padding: 20px; 
                text-align: center; 
                color: #64748b; 
                font-size: 12px;
                border-top: 1px solid #e5e7eb;
            }
            .code-box {
                background: #f8fafc;
                border: 1px solid #e5e7eb;
                border-radius: 6px;
                padding: 12px;
                margin: 15px 0;
                word-break: break-all;
                font-family: monospace;
                color: #059669;
                font-size: 12px;
                text-align: center;
            }
            .note {
                background: #f0fdf4;
                border: 1px solid #bbf7d0;
                border-radius: 6px;
                padding: 12px;
                margin: 15px 0;
                color: #166534;
                font-size: 13px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1 style='margin: 0; font-size: 24px;'>Sentra Hosting</h1>
                <p style='margin: 5px 0 0 0; opacity: 0.9;'>Resetare parolă</p>
            </div>
            
            <div class='content'>
                <p><strong>Salut {$firstName},</strong></p>
                
                <p>Ai solicitat resetarea parolei pentru contul tău Sentra.</p>
                
                <a href='{$resetLink}' class='button'>Resetează parola</a>
                
                <p style='text-align: center; margin-bottom: 5px;'>sau copiază link-ul:</p>
                <div class='code-box'>{$resetLink}</div>
                
                <div class='note'>
                    <strong>⚠️ Link-ul expiră în 15 minute</strong><br>
                    Pentru asistență: support@sentra.ro
                </div>
            </div>
            
            <div class='footer'>
                <p style='margin: 0;'>© " . date('Y') . " Sentra Hosting</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Headers pentru email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Sentra <noreply@sentra.ro>" . "\r\n";
    $headers .= "Reply-To: support@sentra.ro" . "\r\n";

    // Trimite email-ul
    $result = mail($to, $subject, $message, $headers);

    if ($result) {
        error_log("✅ Email trimis către: " . $to);
        return true;
    } else {
        error_log("❌ Eroare email către: " . $to);
        return false;
    }
}

// Procesare formular
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'text' => 'Adresa de email nu este validă.'
        ];
    } else {
        try {
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                throw new Exception('Eroare de conectare la baza de date.');
            }

            $conn->set_charset("utf8mb4");

            // Verifică dacă email-ul există
            $stmt = $conn->prepare("SELECT id, first_name FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            $success_message = 'Dacă adresa de email este corectă, vei primi un email cu instrucțiunile pentru resetarea parolei.';

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Generează token unic
                $token = bin2hex(random_bytes(50));
                $expires = date('Y-m-d H:i:s', time() + 900);

                // Șterge token-uri vechi
                $delete_stmt = $conn->prepare("DELETE FROM password_resets WHERE user_id = ? OR email = ?");
                $delete_stmt->bind_param("is", $user['id'], $email);
                $delete_stmt->execute();
                $delete_stmt->close();

                // Salvează noul token
                $insert_stmt = $conn->prepare("INSERT INTO password_resets (user_id, email, token, expires_at) VALUES (?, ?, ?, ?)");
                $insert_stmt->bind_param("isss", $user['id'], $email, $token, $expires);

                if ($insert_stmt->execute()) {
                    $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/Sentra/pages/reset_password.php?token=" . $token;
                    $email_sent = sendPasswordResetEmail($email, $user['first_name'], $reset_link);

                    if (!$email_sent) {
                        error_log("Eroare trimitere email pentru: " . $email);
                    }
                }
                $insert_stmt->close();
            }

            $stmt->close();
            $conn->close();

            $_SESSION['notification'] = [
                'type' => 'success',
                'text' => $success_message
            ];

        } catch (Exception $e) {
            error_log("Eroare resetare parolă: " . $e->getMessage());
            $_SESSION['notification'] = [
                'type' => 'error',
                'text' => 'A apărut o eroare. Încearcă din nou mai târziu.'
            ];
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// LOGICA DE AFISARE NOTIFICARE
$notification = $_SESSION['notification'] ?? null;
unset($_SESSION['notification']);
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra - Recuperare Parolă</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

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
            0% {
                transform: translateX(-100px) translateY(-100px) rotate(0deg);
                opacity: 0;
            }

            50% {
                opacity: 0.4;
            }

            100% {
                transform: translateX(100px) translateY(100px) rotate(360deg);
                opacity: 0;
            }
        }

        .data-flow {
            position: absolute;
            width: 3px;
            height: 120px;
            background: linear-gradient(to bottom, transparent, #00BFB2, #059669, transparent);
            animation: dataFlow 6s linear infinite;
            border-radius: 2px;
        }

        .data-flow:nth-child(1) {
            left: 10%;
            top: 20%;
            animation-delay: 0s;
        }

        .data-flow:nth-child(2) {
            left: 30%;
            top: 60%;
            animation-delay: 1.5s;
        }

        .data-flow:nth-child(3) {
            left: 70%;
            top: 30%;
            animation-delay: 3s;
        }

        .data-flow:nth-child(4) {
            left: 85%;
            top: 70%;
            animation-delay: 4.5s;
        }

        .data-flow:nth-child(5) {
            left: 50%;
            top: 10%;
            animation-delay: 2s;
        }

        .data-flow:nth-child(6) {
            left: 20%;
            top: 80%;
            animation-delay: 5s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .floating-particle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(0, 191, 178, 0.6);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .floating-particle:nth-child(1) {
            left: 15%;
            top: 25%;
            animation-delay: 0s;
        }

        .floating-particle:nth-child(2) {
            left: 75%;
            top: 65%;
            animation-delay: 2s;
        }

        .floating-particle:nth-child(3) {
            left: 40%;
            top: 85%;
            animation-delay: 4s;
        }

        .floating-particle:nth-child(4) {
            left: 90%;
            top: 35%;
            animation-delay: 6s;
        }

        .floating-particle:nth-child(5) {
            left: 60%;
            top: 15%;
            animation-delay: 1s;
        }

        @keyframes wave {
            0% {
                transform: translateX(-100%) scaleY(0.5);
            }

            50% {
                transform: translateX(0%) scaleY(1);
            }

            100% {
                transform: translateX(100%) scaleY(0.5);
            }
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

        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(0, 191, 178, 0.3);
            }

            50% {
                box-shadow: 0 0 30px rgba(0, 191, 178, 0.6), 0 0 40px rgba(5, 150, 105, 0.4);
            }
        }

        .pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }

        @keyframes spin-slow {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .spin-slow {
            animation: spin-slow 20s linear infinite;
        }

        .custom-input {
            transition: all 0.3s ease-in-out;
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

        .news-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            opacity: 0;
            visibility: hidden;
            transition: all 0.8s ease-in-out;
            transform: translateX(20px);
        }

        .news-slide.active {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }

        .slider-dot {
            width: 10px;
            height: 10px;
            background-color: #ffffff55;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .slider-dot.active {
            background-color: #fff;
            transform: scale(1.3);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .slider-dot:hover {
            transform: scale(1.2);
            background-color: #ffffff88;
        }

        .main-container {
            max-width: 68rem;
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
            animation: pulse-glow 4s ease-in-out infinite;
        }

        .form-area {
            padding: 2rem 1.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            overflow-y: auto;
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

        @media (min-width: 1024px) {
            .main-container {
                width: 85%;
            }

            .form-area {
                padding: 3rem;
            }
        }

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

        @keyframes buttonGlow {

            0%,
            100% {
                box-shadow: 0 4px 15px rgba(0, 191, 178, 0.4);
            }

            50% {
                box-shadow: 0 6px 25px rgba(0, 191, 178, 0.7), 0 0 30px rgba(5, 150, 105, 0.4);
            }
        }

        .btn-glow {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            animation: buttonGlow 3s ease-in-out infinite;
        }

        .btn-glow::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.8s;
        }

        .btn-glow:hover::before {
            left: 100%;
        }

        .btn-glow:hover {
            transform: translateY(-3px);
            animation: none;
            box-shadow: 0 8px 30px rgba(0, 191, 178, 0.8);
        }

        .server-icon {
            background: linear-gradient(135deg, #00BFB2, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes iconBounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-5px);
            }

            60% {
                transform: translateY(-3px);
            }
        }

        .icon-bounce:hover {
            animation: iconBounce 1s;
        }
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
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

    <?php if ($notification): ?>
        <div id="notification" class="fixed top-4 md:top-8 left-1/2 -translate-x-1/2 z-50 px-4 py-3 md:px-6 md:py-3 rounded-xl shadow-lg text-white font-semibold 
               opacity-0 scale-95 transition-all duration-500 max-w-xs md:max-w-md text-sm md:text-base pulse-glow
               <?php echo $notification['type'] === 'success' ? 'bg-emerald-500' : 'bg-rose-500'; ?>">
            <?php echo htmlspecialchars($notification['text']); ?>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const n = document.getElementById("notification");
                setTimeout(() => n.classList.remove("opacity-0", "scale-95"), 50);
                setTimeout(() => n.classList.add("opacity-0", "scale-95"), 3500);
                setTimeout(() => n.remove(), 4200);
            });
        </script>
    <?php endif; ?>

    <div class="flex bg-white main-container overflow-hidden animate-fade-in-up">

        <!-- Sidebar pentru desktop -->
        <div
            class="hidden md:flex md:w-2/5 gradient-sidebar p-6 lg:p-8 xl:p-10 flex-col justify-between items-start text-white relative overflow-hidden">

            <div class="absolute inset-0 opacity-10 spin-slow">
                <div class="absolute top-10 left-10 w-20 h-20 border-2 border-white rounded-lg"></div>
                <div class="absolute top-40 right-10 w-16 h-16 border-2 border-white rounded-full"></div>
                <div class="absolute bottom-20 left-20 w-24 h-12 border-2 border-white"></div>
                <div class="absolute bottom-10 right-20 w-12 h-12 border-2 border-white rotate-45"></div>
            </div>

            <div class="w-full relative z-10">
                <a href="../index.php"
                    class="flex items-center mb-4 cursor-pointer hover:opacity-90 transition duration-300 transform hover:scale-105">
                    <h1 class="text-4xl lg:text-5xl xl:text-6xl font-extrabold tracking-tight mr-2 text-white">Sentra
                    </h1>
                    <div class="w-3 h-3 lg:w-4 lg:h-4 rounded-full bg-white shadow-xl pulse-glow"></div>
                </a>
                <h2 class="text-xl lg:text-2xl xl:text-3xl font-semibold mb-4">Recuperează-ți parola!</h2>
                <p class="text-sm text-gray-200 mt-2">
                    <span class="inline-block icon-bounce">🔐</span> Securitate maximă •
                    <span class="inline-block icon-bounce">⚡</span> Proces rapid •
                    <span class="inline-block icon-bounce">🛡️</span> Protecție date
                </p>
            </div>

            <!-- Noutăți Section -->
            <div class="w-full my-auto news-carousel-wrapper pt-6 lg:pt-8 relative z-10">
                <h3
                    class="text-sm lg:text-md font-bold mb-3 lg:mb-4 text-center text-gray-200 flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2 icon-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Noutăți Hosting
                </h3>

                <div class="news-container relative" style="height: 120px;">
                    <div id="news-feed-slides" class="relative w-full h-full"></div>
                </div>

                <div id="news-dots" class="mt-3 lg:mt-4 flex justify-center space-x-2"></div>
            </div>

            <div class="mt-auto pt-4 lg:pt-6 text-xs w-full border-t border-gray-600 relative z-10">
                <p class="text-gray-300">
                    Vrei să te autentifici?
                    <a href="login.php"
                        class="font-bold border-b border-white hover:text-gray-100 transition duration-300 hover:scale-105 inline-block">
                        Conectează-te aici
                    </a>
                </p>
            </div>
        </div>

        <!-- Form Area -->
        <div class="w-full md:w-3/5 p-4 sm:p-6 lg:p-8 xl:p-10 form-area">
            <!-- Logo pentru mobile -->
            <div class="md:hidden flex items-center justify-center mb-6">
                <a href="../index.php"
                    class="flex items-center cursor-pointer transform hover:scale-105 transition duration-300">
                    <h1 class="text-3xl font-extrabold tracking-tight mr-2 text-sentra-cyan">Sentra</h1>
                    <div class="w-3 h-3 rounded-full sentra-cyan shadow-lg pulse-glow"></div>
                </a>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-[#1A1A1A] mb-6 text-center md:text-left">
                <svg class="w-6 h-6 inline mr-2 server-icon icon-bounce" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
                Recuperare Parolă
            </h2>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST"
                class="space-y-4 sm:space-y-6 max-w-md w-full mx-auto md:mx-0">

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-400 icon-bounce" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Introdu adresa de email asociată contului tău. Îți vom trimite un link securizat pentru
                                resetarea parolei.
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-500 icon-bounce" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        Adresă Email
                    </label>
                    <input type="email" id="email" name="email" required
                        class="custom-input mt-1 block w-full px-3 py-2.5 sm:px-4 sm:py-3 border border-gray-300 rounded-lg shadow-sm outline-none text-sm sm:text-base"
                        placeholder="adresa.ta@exemplu.ro">
                </div>

                <div class="pt-2 sm:pt-4">
                    <button type="submit"
                        class="w-full sentra-cyan text-white px-4 py-3 rounded-lg font-bold text-base sm:text-lg shadow-md hover:shadow-lg transition duration-300 hover:bg-[#00a89c] btn-glow transform hover:scale-105">
                        <svg class="w-5 h-5 inline mr-2 icon-bounce" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        Trimite link de resetare
                    </button>
                </div>

                <div class="mt-4 sm:mt-6 text-sm text-center">
                    <a href="login.php"
                        class="font-medium text-sentra-cyan hover:text-[#009688] transition duration-300 hover:scale-105 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1 icon-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Înapoi la autentificare
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const newsData = [
                { date: "01.10.2025", text: "🚀 Lansare: Găzduire Web pe servere NVMe de ultimă generație. Cel mai rapid hosting din România!" },
                { date: "15.09.2025", text: "🎉 Promotie! Reducere 25% la toate planurile VPS noi. Folosește codul SENTRA25." },
                { date: "01.09.2025", text: "🔒 Upgrade de securitate: cPanel integrat cu ModSecurity 3.0. Protecție sporită anti-hacking." },
                { date: "15.08.2025", text: "⚡ Noutăți: Am adăugat suport pentru PHP 8.4 Beta. Testează cele mai noi funcții!" },
                { date: "01.08.2025", text: "🛠️ Mentenanță planificată la rețeaua din București (03:00, 25 Octombrie). Fără întreruperi majore." },
            ];

            const slidesContainer = document.getElementById('news-feed-slides');
            const dotsContainer = document.getElementById('news-dots');
            const slideDuration = 6000;
            let currentSlide = 0;
            let autoSlideInterval;

            if (slidesContainer) {
                newsData.forEach((item, index) => {
                    const slide = document.createElement('div');
                    slide.className = `news-slide ${index === 0 ? 'active' : ''}`;
                    slide.innerHTML = `
                        <div class="p-2 sm:p-3">
                            <p class="text-xs font-semibold text-white/70 mb-1">${item.date}</p>
                            <p class="text-sm font-medium leading-relaxed">${item.text}</p>
                        </div>
                    `;
                    slidesContainer.appendChild(slide);

                    const dot = document.createElement('div');
                    dot.className = `slider-dot ${index === 0 ? 'active' : ''}`;
                    dot.setAttribute('data-slide', index);
                    dot.addEventListener('click', () => {
                        clearInterval(autoSlideInterval);
                        showSlide(index);
                        startAutoSlide();
                    });
                    dotsContainer.appendChild(dot);
                });

                const slides = slidesContainer.querySelectorAll('.news-slide');
                const dots = dotsContainer.querySelectorAll('.slider-dot');

                function showSlide(index) {
                    slides.forEach((slide, i) => {
                        slide.classList.remove('active');
                        dots[i].classList.remove('active');
                    });
                    currentSlide = index;
                    slides[currentSlide].classList.add('active');
                    dots[currentSlide].classlist.add('active');
                }

                function startAutoSlide() {
                    autoSlideInterval = setInterval(() => {
                        let nextSlide = (currentSlide + 1) % slides.length;
                        showSlide(nextSlide);
                    }, slideDuration);
                }

                showSlide(0);
                startAutoSlide();
            }

            const mainContainer = document.querySelector('.main-container');
            if (mainContainer) {
                mainContainer.addEventListener('mouseenter', () => {
                    mainContainer.style.transform = 'translateY(-5px)';
                });
                mainContainer.addEventListener('mouseleave', () => {
                    mainContainer.style.transform = 'translateY(0)';
                });
            }

            if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>