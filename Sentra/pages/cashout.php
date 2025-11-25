<?php
// cashout.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Utilizator';

// Datele planului din URL
$plan_id = $_GET['plan'] ?? 'SSD-START';
$hosting_type = $_GET['type'] ?? 'ssd';
$base_price = floatval($_GET['price'] ?? 2.79);
$plan_name = $_GET['name'] ?? 'SSD START';

// Informații despre planuri bazate pe ID-urile tale
$plan_details = [
    'SSD-START' => [
        'name' => 'SSD START',
        'storage' => '25GB SSD',
        'websites' => 'MAX 3 DOMENII',
        'features' => ['25GB SSD', '1.5GB RAM', '150% vCPU', '3 Domenii']
    ],
    'SSD-PLUS' => [
        'name' => 'SSD PLUS', 
        'storage' => '50GB SSD',
        'websites' => 'MAX 10 DOMENII',
        'features' => ['50GB SSD', '3GB RAM', '300% vCPU', '10 Domenii']
    ],
    'SSD-PRO' => [
        'name' => 'SSD PRO',
        'storage' => '100GB SSD',
        'websites' => 'NELIMITAT',
        'features' => ['100GB SSD', '6GB RAM', '600% vCPU', 'Domenii Nelimitate']
    ],
    'SSD-MAX' => [
        'name' => 'SSD MAX',
        'storage' => '200GB SSD',
        'websites' => 'NELIMITAT', 
        'features' => ['200GB SSD', '12GB RAM', '1200% vCPU', 'Domenii Nelimitate']
    ],
    'NVMe-START' => [
        'name' => 'NVMe START',
        'storage' => '25GB NVMe',
        'websites' => 'MAX 3 DOMENII',
        'features' => ['25GB NVMe', '1.5GB RAM', '200% vCPU', '3 Domenii']
    ],
    'NVMe-PLUS' => [
        'name' => 'NVMe PLUS',
        'storage' => '50GB NVMe', 
        'websites' => 'MAX 10 DOMENII',
        'features' => ['50GB NVMe', '3GB RAM', '400% vCPU', '10 Domenii']
    ],
    'NVMe-PRO' => [
        'name' => 'NVMe PRO',
        'storage' => '100GB NVMe',
        'websites' => 'NELIMITAT',
        'features' => ['100GB NVMe', '6GB RAM', '800% vCPU', 'Domenii Nelimitate']
    ],
    'NVMe-MAX' => [
        'name' => 'NVMe MAX',
        'storage' => '200GB NVMe',
        'websites' => 'NELIMITAT',
        'features' => ['200GB NVMe', '12GB RAM', '1600% vCPU', 'Domenii Nelimitate']
    ]
];

// Obține detaliile planului
$plan_data = $plan_details[$plan_id] ?? $plan_details['SSD-START'];
$final_price = $base_price;

// Procesare formular de plată
$payment_success = false;
$payment_error = '';
$form_data = [
    'card_number' => '',
    'card_holder' => '',
    'expiry_date' => '',
    'cvv' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_data['card_number'] = preg_replace('/\s+/', '', $_POST['card_number'] ?? '');
    $form_data['card_holder'] = trim($_POST['card_holder'] ?? '');
    $form_data['expiry_date'] = trim($_POST['expiry_date'] ?? '');
    $form_data['cvv'] = trim($_POST['cvv'] ?? '');
    $post_plan_id = $_POST['plan_id'] ?? 'SSD-START';
    $post_price = floatval($_POST['price'] ?? 2.79);
    
    // Validare CVV special
    if ($form_data['cvv'] === '3112') {
        $payment_success = true;
    } else {
        $payment_error = 'Plata a fost respinsă. Te rugăm să verifici datele cardului și să încerci din nou.';
    }
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
    <title>Sentra - Finalizare Plată</title>
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

        .card-preview {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 1rem;
            color: white;
            position: relative;
            overflow: hidden;
            padding: 1.5rem;
        }

        .card-preview::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }

        .success-animation {
            animation: successScale 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) both;
        }

        @keyframes successScale {
            0% { transform: scale(0); opacity: 0; }
            70% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
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
        <div class="hidden md:flex md:w-2/5 gradient-sidebar p-6 lg:p-8 xl:p-10 flex-col justify-between items-start text-white relative overflow-hidden">

            <div class="absolute inset-0 opacity-10 spin-slow">
                <div class="absolute top-10 left-10 w-20 h-20 border-2 border-white rounded-lg"></div>
                <div class="absolute top-40 right-10 w-16 h-16 border-2 border-white rounded-full"></div>
                <div class="absolute bottom-20 left-20 w-24 h-12 border-2 border-white"></div>
                <div class="absolute bottom-10 right-20 w-12 h-12 border-2 border-white rotate-45"></div>
            </div>

            <div class="w-full relative z-10">
                <a href="index.php"
                    class="flex items-center mb-4 cursor-pointer hover:opacity-90 transition duration-300 transform hover:scale-105">
                    <h1 class="text-4xl lg:text-5xl xl:text-6xl font-extrabold tracking-tight mr-2 text-white">Sentra
                    </h1>
                    <div class="w-3 h-3 lg:w-4 lg:h-4 rounded-full bg-white shadow-xl pulse-glow"></div>
                </a>
                <h2 class="text-xl lg:text-2xl xl:text-3xl font-semibold mb-4">
                    <?php echo $payment_success ? 'Plată Confirmată!' : 'Finalizare Plată'; ?>
                </h2>
                <p class="text-sm text-gray-200 mt-2">
                    <?php if ($payment_success): ?>
                        <span class="inline-block icon-bounce">🎉</span> Hosting activat •
                        <span class="inline-block icon-bounce">🚀</span> Gata de utilizare •
                        <span class="inline-block icon-bounce">🔒</span> Securizat
                    <?php else: ?>
                        <span class="inline-block icon-bounce">💳</span> Plată securizată •
                        <span class="inline-block icon-bounce">⚡</span> Proces rapid •
                        <span class="inline-block icon-bounce">🛡️</span> Protecție date
                    <?php endif; ?>
                </p>
            </div>

            <!-- Noutăți Section -->
            <div class="w-full my-auto news-carousel-wrapper pt-6 lg:pt-8 relative z-10">
                <h3 class="text-sm lg:text-md font-bold mb-3 lg:mb-4 text-center text-gray-200 flex items-center justify-center">
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
                    <a href="dashboard.php"
                        class="font-bold border-b border-white hover:text-gray-100 transition duration-300 hover:scale-105 inline-block">
                        ← Înapoi la Dashboard
                    </a>
                </p>
            </div>
        </div>

        <!-- Form Area -->
        <div class="w-full md:w-3/5 p-4 sm:p-6 lg:p-8 xl:p-10 form-area">
            <!-- Logo pentru mobile -->
            <div class="md:hidden flex items-center justify-center mb-6">
                <a href="index.php"
                    class="flex items-center cursor-pointer transform hover:scale-105 transition duration-300">
                    <h1 class="text-3xl font-extrabold tracking-tight mr-2 text-sentra-cyan">Sentra</h1>
                    <div class="w-3 h-3 rounded-full sentra-cyan shadow-lg pulse-glow"></div>
                </a>
            </div>

            <?php if ($payment_success): ?>
                <!-- Success State -->
                <div class="text-center py-8 success-animation">
                    <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 pulse-glow">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#1A1A1A] mb-4">
                        <svg class="w-8 h-8 inline mr-2 server-icon icon-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Plată Reușită!
                    </h2>

                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 max-w-md mx-auto">
                        <div class="flex items-center justify-center text-sm text-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Suma de <?php echo $final_price; ?>€ a fost procesată cu succes
                        </div>
                    </div>

                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        Felicitări! Contul tău de hosting a fost activat. Poți începe să îți încarci site-ul imediat.
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-md mx-auto">
                        <a href="dashboard.php" 
                           class="sentra-cyan text-white py-3 px-6 rounded-lg font-semibold hover:bg-[#00a89c] transition-all duration-300 transform hover:scale-105 text-center">
                            📊 Dashboard
                        </a>
                        <a href="upload_site.php" 
                           class="border-2 border-[#00BFB2] text-[#00BFB2] py-3 px-6 rounded-lg font-semibold hover:bg-[#00BFB2] hover:text-white transition-all duration-300 text-center">
                            🚀 Încarcă Site
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <!-- Payment Form -->
                <h2 class="text-2xl sm:text-3xl font-bold text-[#1A1A1A] mb-6 text-center md:text-left">
                    <svg class="w-6 h-6 inline mr-2 server-icon icon-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Finalizare Plată
                </h2>

                <!-- Order Summary - Varianta extinsă -->
<div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-6 shadow-sm">
    <div class="flex items-start justify-between">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                    </svg>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Plan <?php echo $plan_data['name']; ?></h3>
                <div class="flex items-baseline mb-3">
                    <span class="text-2xl font-extrabold text-sentra-cyan"><?php echo $final_price; ?>€</span>
                    <span class="text-gray-600 ml-1">/lună</span>
                </div>
                
                <!-- Specificații principale -->
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-gray-700"><?php echo $plan_data['storage']; ?></span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-gray-700"><?php echo $plan_data['websites']; ?></span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-gray-700"><?php echo strtoupper($hosting_type); ?> Storage</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-gray-700">SSL Inclus</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Badge tip hosting -->
        <div class="flex-shrink-0">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                   <?php echo $hosting_type === 'nvme' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'; ?>">
                <?php echo $hosting_type === 'nvme' ? '⚡ NVMe' : '💾 SSD'; ?>
            </span>
        </div>
    </div>
</div>

                <form method="POST" class="space-y-6">
                    <!-- Card Preview -->
                    <div class="card-preview">
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <div class="text-white/80 text-sm">Număr Card</div>
                                    <div class="text-white text-lg md:text-xl font-mono tracking-wider">
                                        <?php echo !empty($form_data['card_number']) ? 
                                            chunk_split($form_data['card_number'], 4, ' ') : 
                                            '•••• •••• •••• ••••'; ?>
                                    </div>
                                </div>
                                <div class="w-12 h-8 bg-white/20 rounded flex items-center justify-center">
                                    <span class="text-white text-xs font-semibold">CVV</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <div class="text-white/80 text-sm">Nume pe Card</div>
                                    <div class="text-white font-medium">
                                        <?php echo !empty($form_data['card_holder']) ? 
                                            strtoupper($form_data['card_holder']) : 
                                            'YOUR NAME'; ?>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-white/80 text-sm">Expiră</div>
                                    <div class="text-white font-medium">
                                        <?php echo !empty($form_data['expiry_date']) ? 
                                            $form_data['expiry_date'] : 
                                            'MM/AA'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden fields for plan data -->
                    <input type="hidden" name="plan_id" value="<?php echo $plan_id; ?>">
                    <input type="hidden" name="price" value="<?php echo $final_price; ?>">
                    <input type="hidden" name="hosting_type" value="<?php echo $hosting_type; ?>">

                    <!-- Card Details Form -->
                    <div class="space-y-4">
                        <div>
                            <label for="card_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Număr Card
                            </label>
                            <input type="text" 
                                   id="card_number"
                                   name="card_number" 
                                   value="<?php echo htmlspecialchars($form_data['card_number']); ?>"
                                   placeholder="1234 5678 9012 3456"
                                   class="custom-input w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none text-sm"
                                   required
                                   maxlength="19">
                        </div>

                        <div>
                            <label for="card_holder" class="block text-sm font-medium text-gray-700 mb-2">
                                Nume pe Card
                            </label>
                            <input type="text" 
                                   id="card_holder"
                                   name="card_holder" 
                                   value="<?php echo htmlspecialchars($form_data['card_holder']); ?>"
                                   placeholder="ION POPESCU"
                                   class="custom-input w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none text-sm"
                                   required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Data expirării
                                </label>
                                <input type="text" 
                                       id="expiry_date"
                                       name="expiry_date" 
                                       value="<?php echo htmlspecialchars($form_data['expiry_date']); ?>"
                                       placeholder="MM/AA"
                                       class="custom-input w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none text-sm"
                                       required
                                       maxlength="5">
                            </div>
                            <div>
                                <label for="cvv" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cod CVV
                                </label>
                                <input type="text" 
                                       id="cvv"
                                       name="cvv" 
                                       value="<?php echo htmlspecialchars($form_data['cvv']); ?>"
                                       placeholder="123"
                                       class="custom-input w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none text-sm"
                                       required
                                       maxlength="4">
                                <p class="text-xs text-gray-500 mt-2">
                                    3 sau 4 cifre pe spatele cardului
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <?php if ($payment_error): ?>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-red-700 text-sm"><?php echo htmlspecialchars($payment_error); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Security Notice -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <div class="text-sm text-yellow-700">
                                <strong>Plată securizată:</strong> Toate tranzacțiile sunt protejate cu criptare SSL. 
                                Nu stocăm detaliile cardului.
                            </div>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start">
                        <input id="terms" name="terms" type="checkbox" required
                               class="w-4 h-4 text-sentra-cyan focus:ring-sentra-cyan border-gray-300 rounded mt-1">
                        <label for="terms" class="ml-3 text-sm text-gray-600">
                            Sunt de acord cu 
                            <a href="#" class="text-sentra-cyan hover:underline font-medium">termenii și condițiile</a>
                            și confirm că am peste 18 ani
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full sentra-cyan text-white px-4 py-4 rounded-lg font-bold text-lg shadow-md hover:shadow-lg transition duration-300 hover:bg-[#00a89c] btn-glow transform hover:scale-105">
                        <svg class="w-5 h-5 inline mr-2 icon-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Finalizează Plata - <?php echo $final_price; ?>€
                    </button>

                    <div class="text-center">
                        <a href="dashboard.php" class="text-sentra-cyan hover:underline text-sm font-medium inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Înapoi la Dashboard
                        </a>
                    </div>
                </form>
            <?php endif; ?>
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
                    dots[currentSlide].classList.add('active');
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

            // Formatare automata card
            const cardNumberInput = document.getElementById('card_number');
            const expiryDateInput = document.getElementById('expiry_date');
            
            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                    let formattedValue = value.match(/.{1,4}/g)?.join(' ');
                    if (formattedValue) {
                        e.target.value = formattedValue;
                    }
                });
            }
            
            if (expiryDateInput) {
                expiryDateInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\//g, '').replace(/[^0-9]/gi, '');
                    if (value.length >= 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    e.target.value = value;
                });
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

            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>