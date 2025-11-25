<?php
// networkstatus.php
// Pagina "Starea rețelei" (status page) cu afișare publică a serviciilor și istoric de incidente.
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Starea rețelei - Sentra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>

    <style>
        /* Culori și font */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f8f8;
            color: #1A1A1A;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .sentra-cyan {
            background-color: #00BFB2;
        }

        .text-sentra-cyan {
            color: #00BFB2;
        }

        .border-sentra-cyan {
            border-color: #00BFB2;
        }

        /* Culori pentru status */
        .status-operational {
            background-color: #10B981;
        }

        .text-status-operational {
            color: #10B981;
        }

        .bg-status-light {
            background-color: #D1FAE5;
        }

        .status-degraded {
            background-color: #FBBF24;
        }

        .text-status-degraded {
            color: #FBBF24;
        }

        /* Header */
        header {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        /* Containerul principal al paginii */
        .main-content {
            width: 100%;
            max-width: 1200px;
            padding: 3rem 1.5rem 6rem;
            flex-grow: 1;
            margin-left: auto;
            margin-right: auto;
        }

        /* Stil pentru cardurile de serviciu */
        .service-card {
            background-color: #ffffff;
            padding: 1.5rem 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid #E5E7EB;
            transition: border-left-color 0.3s;
        }

        .service-card.operational {
            border-left-color: #10B981;
        }

        .service-card.degraded {
            border-left-color: #FBBF24;
        }

        .service-card.maintenance {
            border-left-color: #00BFB2;
        }

        /* Stil pentru secțiunea de incidente */
        .incident-card {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            border-left: 6px solid #F3F4F6;
        }

        .incident-card.active {
            border-left: 6px solid #F87171;
        }

        /* Stil special pentru a forța footer-ul să ocupe toată lățimea */
        .full-width-footer {
            width: 100%;
        }

        /* Stiluri pentru hover pe link-uri footer */
        .footer-link-hover:hover {
            color: #00BFB2 !important;
            transition: color 0.3s;
            text-decoration: underline;
        }

        .social-icon-link:hover i {
            color: #00BFB2 !important;
            transition: color 0.3s;
        }

        /* Uniformizare text */
        .content-text {
            font-size: 1.125rem;
            line-height: 1.7;
            color: #4B5563;
        }
    </style>
</head>

<body>

    <!-- Header actualizat -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="index.php" class="flex-shrink-0 flex items-center">
                    <span class="text-3xl font-extrabold text-[#1A1A1A]">Sentra</span>
                    <div class="w-2 h-2 ml-1 sentra-cyan rounded-full"></div>
                </a>

                <nav class="hidden md:flex space-x-8">
                    <a href="gazduire_web.php"
                        class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">
                        Găzduire web
                    </a>
                    <a href="vps.php" class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">
                        VPS
                    </a>

                    <!-- Dropdown domenii -->
                    <div class="relative group flex items-center">
                        <button type="button"
                            class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">
                            Domenii
                        </button>
                        <div
                            class="absolute left-1/2 transform -translate-x-1/2 mt-2 top-full w-48 bg-white border border-gray-100 rounded-lg shadow-xl py-1 z-50 invisible opacity-0 transition-all duration-300 group-hover:visible group-hover:opacity-100">
                            <a href="inregistrare_domenii.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                Înregistrare domenii
                            </a>
                            <a href="transfer_domenii.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                Transfer domenii
                            </a>
                        </div>
                    </div>

                    <!-- Dropdown suport -->
                    <div class="relative group flex items-center">
                        <button type="button"
                            class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">
                            Suport
                        </button>
                        <div
                            class="absolute left-1/2 transform -translate-x-1/2 mt-2 top-full w-48 bg-white border border-gray-100 rounded-lg shadow-xl py-1 z-50 invisible opacity-0 transition-all duration-300 group-hover:visible group-hover:opacity-100">
                            <a href="bazacunostinte.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                Bază de cunoștințe
                            </a>
                            <a href="ticket.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                Deschide un ticket
                            </a>
                        </div>
                    </div>
                </nav>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="dashboard.php"
                        class="sentra-cyan text-white px-4 py-2 rounded-lg font-semibold shadow-lg hover:shadow-xl transition duration-300">
                        Cont client
                    </a>
                </div>

                <button id="mobile-menu-button" type="button" class="md:hidden text-gray-500 hover:text-sentra-cyan">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <main class="main-content">

        <h2 class="text-5xl font-extrabold mb-6 text-center text-[#1A1A1A] pt-8">
            <span class="text-sentra-cyan">Starea rețelei:</span> Uptime în timp real
        </h2>

        <p class="text-center text-gray-600 mb-12 text-lg max-w-3xl mx-auto content-text">
            Verificați starea de funcționare a tuturor componentelor noastre. Această pagină este actualizată la fiecare
            5 minute.
        </p>

        <!-- Banner status general -->
        <div
            class="max-w-4xl mx-auto p-6 rounded-xl mb-12 flex items-center justify-center bg-status-light border border-status-operational">
            <i data-lucide="check-circle" class="w-8 h-8 status-operational mr-4 text-white p-1 rounded-full"></i>
            <span class="text-2xl font-bold text-status-operational">
                Toate sistemele funcționează normal
            </span>
        </div>

        <!-- Lista servicii -->
        <div class="max-w-4xl mx-auto space-y-4">
            <h3 class="text-2xl font-bold mb-4 text-[#1A1A1A] border-b pb-2">Status servicii principale</h3>

            <!-- Serviciu 1: Găzduire web -->
            <div class="service-card operational">
                <div class="flex items-center">
                    <i data-lucide="server" class="w-6 h-6 text-gray-500 mr-4"></i>
                    <span class="text-lg font-semibold">Găzduire web (shared hosting)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full status-operational mr-2"></div>
                    <span class="font-medium text-status-operational">Operațional</span>
                </div>
            </div>

            <!-- Serviciu 2: Baze de date -->
            <div class="service-card operational">
                <div class="flex items-center">
                    <i data-lucide="database" class="w-6 h-6 text-gray-500 mr-4"></i>
                    <span class="text-lg font-semibold">Servere baze de date (MySQL/MariaDB)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full status-operational mr-2"></div>
                    <span class="font-medium text-status-operational">Operațional</span>
                </div>
            </div>

            <!-- Serviciu 3: Email -->
            <div class="service-card operational">
                <div class="flex items-center">
                    <i data-lucide="mail" class="w-6 h-6 text-gray-500 mr-4"></i>
                    <span class="text-lg font-semibold">Serviciu email (SMTP/POP3/IMAP)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full status-operational mr-2"></div>
                    <span class="font-medium text-status-operational">Operațional</span>
                </div>
            </div>

            <!-- Serviciu 4: VPS -->
            <div class="service-card operational">
                <div class="flex items-center">
                    <i data-lucide="cloud" class="w-6 h-6 text-gray-500 mr-4"></i>
                    <span class="text-lg font-semibold">Infrastructură cloud VPS</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full status-operational mr-2"></div>
                    <span class="font-medium text-status-operational">Operațional</span>
                </div>
            </div>

            <!-- Serviciu 5: Monitorizare rețea -->
            <div class="service-card degraded">
                <div class="flex items-center">
                    <i data-lucide="shield" class="w-6 h-6 text-gray-500 mr-4"></i>
                    <span class="text-lg font-semibold">Monitorizare rețea (protecție DDoS)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full status-degraded mr-2"></div>
                    <span class="font-medium text-status-degraded">Performanță degradată</span>
                </div>
            </div>
        </div>

        <!-- Istoric incidente -->
        <div class="max-w-4xl mx-auto mt-16">
            <h3 class="text-3xl font-extrabold text-[#1A1A1A] mb-8 border-b pb-3">Istoric incidente și mentenanță</h3>

            <!-- Incident activ -->
            <section class="incident-card active">
                <div class="flex items-center mb-3">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-red-500 mr-3"></i>
                    <h4 class="text-xl font-bold text-red-700">Degradare serviciu DNS (activ)</h4>
                </div>
                <p class="text-sm text-gray-500 mb-3">2 octombrie 2025, 11:30 EEST</p>
                <p class="content-text">
                    Investigăm o problemă intermitentă de rezoluție DNS care afectează un număr mic de clienți din
                    Europa. Majoritatea serviciilor rămân operaționale, dar propagarea noilor înregistrări este
                    încetinită. Echipa de rețea lucrează la izolarea și remedierea cauzei.
                </p>
                <div class="mt-3 text-right">
                    <span class="text-sm font-semibold text-red-500">Stare: investigație</span>
                </div>
            </section>

            <!-- Mentenanță programată -->
            <section class="incident-card" style="border-left-color: #00BFB2;">
                <div class="flex items-center mb-3">
                    <i data-lucide="settings" class="w-6 h-6 text-sentra-cyan mr-3"></i>
                    <h4 class="text-xl font-bold text-sentra-cyan">Mentenanță programată server 3</h4>
                </div>
                <p class="text-sm text-gray-500 mb-3">25 septembrie 2025, 02:00 - 04:00 EEST</p>
                <p class="content-text">
                    Mentenanță hardware planificată pentru actualizarea firmware-ului pe serverul de găzduire web 3.
                    Operațiunea s-a încheiat cu succes la ora 03:45, cu un downtime total de 4 minute. Toate serviciile
                    au fost restaurate la performanța optimă.
                </p>
                <div class="mt-3 text-right">
                    <span class="text-sm font-semibold text-gray-500">Stare: finalizat</span>
                </div>
            </section>

            <!-- Incident rezolvat -->
            <section class="incident-card">
                <div class="flex items-center mb-3">
                    <i data-lucide="check-circle" class="w-6 h-6 text-gray-400 mr-3"></i>
                    <h4 class="text-xl font-bold text-gray-700">Problemă minoră backup VPS</h4>
                </div>
                <p class="text-sm text-gray-500 mb-3">10 septembrie 2025, 18:00 EEST</p>
                <p class="content-text">
                    Un job de backup automat pentru VPS-uri a eșuat intermitent din cauza unei erori la nivel de stocare
                    secundară. Erorile au fost identificate și remediate în 30 de minute. Nu a fost înregistrată nicio
                    pierdere de date.
                </p>
                <div class="mt-3 text-right">
                    <span class="text-sm font-semibold text-gray-500">Stare: finalizat</span>
                </div>
            </section>

        </div>

    </main>

    <div class="full-width-footer">
        <?php
        // Includerea footer.php
        include 'footer.php';
        ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Inițializarea Lucide Icons
            lucide.createIcons();
        });
    </script>
</body>

</html>