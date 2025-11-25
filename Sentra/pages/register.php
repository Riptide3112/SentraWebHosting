<?php
// register.php - Verificare Logare
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra - Creează Cont</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-y: auto;
            min-height: 100vh;
        }

        /* Culoarea principala Sentra Cyan */
        .sentra-cyan {
            background-color: #00BFB2;
        }

        .text-sentra-cyan {
            color: #00BFB2;
        }

        .border-sentra-cyan {
            border-color: #00BFB2;
        }

        /* Gradientul relaxat: de la Gri Închis la Sentra Cyan */
        .gradient-sidebar {
            background: linear-gradient(135deg, #1f2937 0%, #00BFB2 100%);
        }

        /* Fundal dinamic modern pentru web-hosting - TEMATICA VERDE */
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

        /* Linii de conexiune moderne în verde */
        .dynamic-background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                /* Linii de grid verzi subtile */
                linear-gradient(90deg, transparent 95%, rgba(0, 191, 178, 0.15) 100%),
                linear-gradient(90deg, transparent 90%, rgba(5, 150, 105, 0.12) 95%),
                linear-gradient(transparent 95%, rgba(0, 191, 178, 0.15) 100%),
                linear-gradient(transparent 90%, rgba(5, 150, 105, 0.12) 95%),
                /* Puncte de conexiune verzi */
                radial-gradient(circle at 20% 30%, rgba(0, 191, 178, 0.2) 2px, transparent 3px),
                radial-gradient(circle at 50% 70%, rgba(5, 150, 105, 0.18) 2px, transparent 3px),
                radial-gradient(circle at 80% 40%, rgba(0, 191, 178, 0.2) 2px, transparent 3px),
                radial-gradient(circle at 30% 80%, rgba(5, 150, 105, 0.18) 2px, transparent 3px),
                radial-gradient(circle at 70% 20%, rgba(0, 191, 178, 0.2) 2px, transparent 3px);
            background-size:
                50px 50px,
                100px 100px,
                50px 50px,
                100px 100px,
                200px 200px,
                300px 300px,
                250px 250px,
                350px 350px,
                400px 400px;
            opacity: 0.5;
            pointer-events: none;
        }

        /* Animatie pentru linii de date verzi */
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

        /* Particule flotante */
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

        /* Onda animata */
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

        /* Pulsare pentru elemente importante */
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

        /* Rotire continua pentru icon-uri */
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

        /* Containerul principal (Cardul) */
        .main-container {
            max-width: 80rem;
            width: 95%;
            margin: 2rem auto;
            border-radius: 1.5rem;
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 30px rgba(0, 191, 178, 0.1);
            min-height: auto;
            background: white;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            max-height: 90vh;
            /* ← LIMITĂM ÎNĂLȚIMEA */
        }

        /* Containerul principal (Cardul) */
        .main-container {
            max-width: 80rem;
            width: 95%;
            margin: 2rem auto;
            border-radius: 1.5rem;
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 30px rgba(0, 191, 178, 0.1);
            background: white;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            height: 85vh;
            /* ← SCHIMBĂ asta din max-height în height */
        }

        /* Zona de formular CU SCROLL */
        .form-area {
            padding: 2rem 1.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            /* ← PERMITEM SCROLL */
        }

        /* Scrollbar vizibil și stilizat */
        .form-area::-webkit-scrollbar {
            width: 8px;
        }

        .form-area::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
            margin: 5px;
        }

        .form-area::-webkit-scrollbar-thumb {
            background: #00BFB2;
            border-radius: 10px;
            border: 2px solid #f1f1f1;
        }

        .form-area::-webkit-scrollbar-thumb:hover {
            background: #009688;
        }

        /* Pentru Firefox */
        .form-area {
            scrollbar-width: thin;
            scrollbar-color: #00BFB2 #f1f1f1;
        }

        /* Mobile-first adjustments */
        @media (min-width: 768px) {
            .main-container {
                width: 90%;
                margin-top: 3rem;
                margin-bottom: 3rem;
                height: 85vh;
                /* ← La fel aici */
            }
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

        /* Animatie subtilă pentru container */
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

        /* Efect de glow pentru buton */
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

        /* Stil pentru input-uri */
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

        /* Icon styling pentru tematica web-hosting */
        .server-icon {
            background: linear-gradient(135deg, #00BFB2, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Animatie pentru icon-uri */
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

        /* Stiluri pentru Slider (Carusel) */
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

        /* Stil pentru punctele de navigare (Dots) */
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

        fieldset {
            transition: all 0.3s ease;
        }

        fieldset:hover {
            transform: translateY(-2px);
        }
    </style>
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>
</head>

<body class="dynamic-background flex items-center justify-center p-4 relative">

    <!-- Linii de date animate -->
    <div class="data-flow"></div>
    <div class="data-flow"></div>
    <div class="data-flow"></div>
    <div class="data-flow"></div>
    <div class="data-flow"></div>
    <div class="data-flow"></div>

    <!-- Particule flotante -->
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>

    <!-- Onda animata -->
    <div class="wave-animation"></div>

    <?php
    if (isset($_SESSION['notification'])) {
        $type = $_SESSION['notification']['type'];
        $text = $_SESSION['notification']['text'];
        unset($_SESSION['notification']);
        ?>
        <div id="notification" class="fixed top-4 md:top-8 left-1/2 -translate-x-1/2 z-50 px-4 py-3 md:px-6 md:py-3 rounded-xl shadow-lg text-white font-semibold 
               opacity-0 scale-95 transition-all duration-500 max-w-xs md:max-w-md text-sm md:text-base pulse-glow
               <?php echo $type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'; ?>">
            <?php echo htmlspecialchars($text); ?>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const n = document.getElementById("notification");
                setTimeout(() => n.classList.remove("opacity-0", "scale-95"), 50);
                setTimeout(() => n.classList.add("opacity-0", "scale-95"), 3500);
                setTimeout(() => n.remove(), 4200);
            });
        </script>
    <?php }

    $form_data = $_SESSION['form_data'] ?? [];
    ?>

    <div class="flex bg-white main-container overflow-hidden animate-fade-in-up">

        <!-- Sidebar pentru desktop -->
        <div
            class="hidden md:flex md:w-2/5 gradient-sidebar p-6 lg:p-8 xl:p-10 flex-col justify-between items-start text-white relative overflow-hidden">

            <!-- Pattern de server în fundal cu animatie -->
            <div class="absolute inset-0 opacity-10 spin-slow">
                <div class="absolute top-10 left-10 w-20 h-20 border-2 border-white rounded-lg"></div>
                <div class="absolute top-40 right-10 w-16 h-16 border-2 border-white rounded-full"></div>
                <div class="absolute bottom-20 left-20 w-24 h-12 border-2 border-white"></div>
                <div class="absolute bottom-10 right-20 w-12 h-12 border-2 border-white rotate-45"></div>
            </div>

            <div class="w-full relative z-10">
                <a href="index.php"
                    class="flex items-center mb-4 cursor-pointer hover:opacity-90 transition duration-300 transform hover:scale-105">
                    <h1 class="text-4xl lg:text-5xl xl:text-6xl font-extrabold tracking-tight mr-2 text-white">
                        Sentra
                    </h1>
                    <div class="w-3 h-3 lg:w-4 lg:h-4 rounded-full bg-white shadow-xl pulse-glow"></div>
                </a>
                <h2 class="text-xl lg:text-2xl font-semibold mb-4">
                    Creează-ți un cont!
                </h2>
                <p class="text-sm text-gray-200 mt-2">
                    <span class="inline-block icon-bounce">🚀</span> Găzduire NVMe ultra-rapidă •
                    <span class="inline-block icon-bounce">🔒</span> SSL Gratuit •
                    <span class="inline-block icon-bounce">🛟</span> Suport 24/7 •
                    <span class="inline-block icon-bounce">💾</span> Backup Zilnic
                </p>
            </div>

            <!-- SECȚIUNEA NOUTĂȚI ADAUGATĂ -->
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
                    <div id="news-feed-slides" class="relative w-full h-full">
                    </div>
                </div>

                <div id="news-dots" class="mt-3 lg:mt-4 flex justify-center space-x-2">
                </div>
            </div>

            <div class="mt-auto pt-4 lg:pt-6 text-xs w-full border-t border-gray-600 relative z-10">
                <p class="text-gray-300">
                    Ai deja cont?
                    <a href="login.php"
                        class="font-bold border-b border-white hover:text-gray-100 transition duration-300 hover:scale-105 inline-block">
                        Conectează-te aici
                    </a>
                </p>
            </div>
        </div>

        <!-- Form Area CU TOATE FIELDSET-URILE ȘI SCROLL -->
        <div class="w-full md:w-3/5 p-4 sm:p-6 lg:p-8 xl:p-10 form-area">
            <!-- Logo pentru mobile -->
            <div class="md:hidden flex items-center justify-center mb-6">
                <a href="index.php"
                    class="flex items-center cursor-pointer transform hover:scale-105 transition duration-300">
                    <h1 class="text-3xl font-extrabold tracking-tight mr-2 text-sentra-cyan">
                        Sentra
                    </h1>
                    <div class="w-3 h-3 rounded-full sentra-cyan shadow-lg pulse-glow"></div>
                </a>
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold text-[#1A1A1A] mb-6">
                <svg class="w-6 h-6 inline mr-2 server-icon icon-bounce" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Formular de Înregistrare Cont
            </h2>

            <form action="register_process.php" method="POST" class="space-y-6">

                <!-- Fieldset 1: Informații Personale -->
                <fieldset class="border-b border-gray-200 pb-6 custom-input">
                    <legend class="text-xl font-semibold text-sentra-cyan mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 icon-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        1. Informații Personale
                    </legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Prenume <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" required
                                value="<?php echo htmlspecialchars($form_data['first_name'] ?? ''); ?>"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                        <div class="col-span-1">
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Nume <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" required
                                value="<?php echo htmlspecialchars($form_data['last_name'] ?? ''); ?>"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                        <div class="col-span-1">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresă Email <span
                                    class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" required
                                value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                        <div class="col-span-1">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Număr de Telefon
                                <span class="text-red-500">*</span></label>
                            <input type="tel" id="phone" name="phone" required
                                value="<?php echo htmlspecialchars($form_data['phone'] ?? ''); ?>"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                    </div>
                </fieldset>

                <!-- Fieldset 2: Adresă Facturare -->
                <fieldset class="border-b border-gray-200 pb-6 custom-input">
                    <legend class="text-xl font-semibold text-sentra-cyan mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 icon-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        2. Adresă Facturare
                    </legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Nume Companie
                                (Opțional)</label>
                            <input type="text" id="company_name" name="company_name"
                                value="<?php echo htmlspecialchars($form_data['company_name'] ?? ''); ?>"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label for="address_line_1" class="block text-sm font-medium text-gray-700 mb-1">Adresă
                                (Strada) <span class="text-red-500">*</span></label>
                            <input type="text" id="address_line_1" name="address_line_1" required
                                value="<?php echo htmlspecialchars($form_data['address_line_1'] ?? ''); ?>"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label for="address_line_2" class="block text-sm font-medium text-gray-700 mb-1">Adresă
                                (Continuare) (Opțional)</label>
                            <input type="text" id="address_line_2" name="address_line_2"
                                value="<?php echo htmlspecialchars($form_data['address_line_2'] ?? ''); ?>"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                        <div class="col-span-1">
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Oraș / Sector <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="city" name="city" required
                                value="<?php echo htmlspecialchars($form_data['city'] ?? ''); ?>"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                        <div class="col-span-1">
                            <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-1">Cod Poștal <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="zip_code" name="zip_code" required
                                value="<?php echo htmlspecialchars($form_data['zip_code'] ?? ''); ?>"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                        <div class="col-span-1">
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Țara <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="country" name="country" required value="România"
                                value="<?php echo htmlspecialchars($form_data['country'] ?? 'România'); ?>"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                        <div class="col-span-1">
                            <label for="county" class="block text-sm font-medium text-gray-700 mb-1">Județul / Regiune
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="county" name="county" required
                                value="<?php echo htmlspecialchars($form_data['county'] ?? ''); ?>"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                    </div>
                </fieldset>

                <!-- Fieldset 3: Informații Suplimentare -->
                <fieldset class="border-b border-gray-200 pb-6 custom-input">
                    <legend class="text-xl font-semibold text-sentra-cyan mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 icon-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        3. Informații Suplimentare
                    </legend>
                    <p class="text-sm text-gray-500 mb-4">Câmpurile de mai jos sunt <strong>opționale</strong> și
                        necesare doar pentru înregistrarea ca firmă.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <label for="reg_comertului" class="block text-sm font-medium text-gray-700 mb-1">Reg.
                                Comerțului (Opțional)</label>
                            <input type="text" id="reg_comertului" name="reg_comertului"
                                value="<?php echo htmlspecialchars($form_data['reg_comertului'] ?? ''); ?>"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                        <div class="col-span-1">
                            <label for="cod_fiscal" class="block text-sm font-medium text-gray-700 mb-1">Cod Fiscal
                                (Opțional)</label>
                            <input type="text" id="cod_fiscal" name="cod_fiscal"
                                value="<?php echo htmlspecialchars($form_data['cod_fiscal'] ?? ''); ?>"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label for="found_us" class="block text-sm font-medium text-gray-700 mb-1">Unde ai aflat de
                                noi? (Opțional)</label>
                            <select id="found_us" name="found_us"
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                                <option value="" disabled selected>Selectează...</option>
                                <option value="google" <?php echo ($form_data['found_us'] ?? '') === 'google' ? 'selected' : ''; ?>>Google / Motoare de căutare</option>
                                <option value="social" <?php echo ($form_data['found_us'] ?? '') === 'social' ? 'selected' : ''; ?>>Rețele Sociale</option>
                                <option value="recomandare" <?php echo ($form_data['found_us'] ?? '') === 'recomandare' ? 'selected' : ''; ?>>Recomandare</option>
                                <option value="alte_siteuri" <?php echo ($form_data['found_us'] ?? '') === 'alte_siteuri' ? 'selected' : ''; ?>>Alte site-uri</option>
                                <option value="alta" <?php echo ($form_data['found_us'] ?? '') === 'alta' ? 'selected' : ''; ?>>Altă metodă</option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <!-- Fieldset 4: Securitatea Contului -->
                <fieldset class="pb-6 custom-input">
                    <legend class="text-xl font-semibold text-sentra-cyan mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 icon-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        4. Securitatea Contului
                    </legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Parolă <span
                                    class="text-red-500">*</span></label>
                            <input type="password" id="password" name="password" required
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                        <div class="col-span-1">
                            <label for="confirm_password"
                                class="block text-sm font-medium text-gray-700 mb-1">Confirmare Parolă <span
                                    class="text-red-500">*</span></label>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label for="security_question"
                                class="block text-sm font-medium text-gray-700 mb-1">Întrebare de Securitate <span
                                    class="text-red-500">*</span></label>
                            <select id="security_question" name="security_question" required
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                                <option value="" disabled selected>Selectează o întrebare</option>
                                <option value="pet_name">Care a fost numele primului tău animal de companie?</option>
                                <option value="first_car">Care a fost modelul primei tale mașini?</option>
                                <option value="mother_maiden">Care este numele de fată al mamei tale?</option>
                                <option value="childhood_city">În ce oraș ți-ai petrecut copilăria?</option>
                                <option value="best_friend">Care este numele celui mai bun prieten din școală?</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="security_answer" class="block text-sm font-medium text-gray-700 mb-1">Răspunsul
                                Tău <span class="text-red-500">*</span></label>
                            <input type="text" id="security_answer" name="security_answer" required
                                class="custom-input mt-1 block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm outline-none text-sm">
                        </div>
                    </div>
                </fieldset>

                <div class="pt-4">
                    <div class="flex items-start mb-6">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required
                                class="focus:ring-sentra-cyan h-4 w-4 text-sentra-cyan border-gray-300 rounded custom-input">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">
                                Sunt de acord cu
                                <a href="termenisiconditii.php"
                                    class="text-sentra-cyan hover:text-[#009688] underline font-bold">Termenii și
                                    Condițiile</a>
                                și <a href="politica.php"
                                    class="text-sentra-cyan hover:text-[#009688] underline font-bold">Politica de
                                    Confidențialitate</a>.
                            </label>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full sentra-cyan text-white px-4 py-3 rounded-lg font-bold text-base sm:text-lg shadow-md hover:shadow-lg transition duration-300 hover:bg-[#00a89c] btn-glow transform hover:scale-105">
                        <svg class="w-5 h-5 inline mr-2 icon-bounce" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Finalizează Înregistrarea
                    </button>
                </div>
            </form>

            <div class="mt-6 text-sm text-center md:hidden">
                <a href="login.php"
                    class="font-medium text-sentra-cyan hover:text-[#009688] transition duration-300 hover:scale-105 inline-block">
                    Ai deja cont? Conectează-te
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // --- LOGICĂ SLIDER NOUTĂȚI ---
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
                newsData.slice(0, 5).forEach((item, index) => {
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
                        stopAutoSlide();
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

                function stopAutoSlide() {
                    clearInterval(autoSlideInterval);
                }

                function startAutoSlide() {
                    stopAutoSlide();
                    autoSlideInterval = setInterval(() => {
                        let nextSlide = (currentSlide + 1) % slides.length;
                        showSlide(nextSlide);
                    }, slideDuration);
                }

                showSlide(0);
                startAutoSlide();
            }

            // Animatie pentru hover pe card
            const mainContainer = document.querySelector('.main-container');
            if (mainContainer) {
                mainContainer.addEventListener('mouseenter', () => {
                    mainContainer.style.transform = 'translateY(-5px)';
                });

                mainContainer.addEventListener('mouseleave', () => {
                    mainContainer.style.transform = 'translateY(0)';
                });
            }

            // Asigură-te că Iconițele Lucide sunt re-create
            if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>