<?php
// contact.php
// Pagina "Contact" cu fundal alb, detalii complete și hartă, incluzând footer.php
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Sentra</title>
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

        /* Stil pentru secțiunile de contact */
        .contact-card {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-top: 6px solid #00BFB2;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: transform 0.3s;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
        }

        /* Stil pentru secțiunea de hartă */
        .map-container {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-top: 3rem;
            width: 100%;
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

        /* Link-uri de contact hover */
        .contact-link:hover {
            color: #00BFB2;
            text-decoration: underline;
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
                    <a href="gazduire_web.php" class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">
                        Găzduire web
                    </a>
                    <a href="vps.php" class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">
                        VPS
                    </a>

                    <!-- Dropdown domenii -->
                    <div class="relative group flex items-center">
                        <button type="button" class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">
                            Domenii
                        </button>
                        <div class="absolute left-1/2 transform -translate-x-1/2 mt-2 top-full w-48 bg-white border border-gray-100 rounded-lg shadow-xl py-1 z-50 invisible opacity-0 transition-all duration-300 group-hover:visible group-hover:opacity-100">
                            <a href="inregistrare_domenii.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                Înregistrare domenii
                            </a>
                            <a href="transfer_domenii.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                Transfer domenii
                            </a>
                        </div>
                    </div>

                    <!-- Dropdown suport -->
                    <div class="relative group flex items-center">
                        <button type="button" class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">
                            Suport
                        </button>
                        <div class="absolute left-1/2 transform -translate-x-1/2 mt-2 top-full w-48 bg-white border border-gray-100 rounded-lg shadow-xl py-1 z-50 invisible opacity-0 transition-all duration-300 group-hover:visible group-hover:opacity-100">
                            <a href="bazacunostinte.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                Bază de cunoștințe
                            </a>
                            <a href="ticket.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                Deschide un ticket
                            </a>
                        </div>
                    </div>
                </nav>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="dashboard.php" class="sentra-cyan text-white px-4 py-2 rounded-lg font-semibold shadow-lg hover:shadow-xl transition duration-300">
                        Cont client
                    </a>
                </div>

                <button id="mobile-menu-button" type="button" class="md:hidden text-gray-500 hover:text-sentra-cyan">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <main class="main-content">

        <h2 class="text-5xl font-extrabold mb-8 text-center text-[#1A1A1A] pt-8">
            <span class="text-sentra-cyan">Contact:</span> Suntem aici pentru tine
        </h2>

        <p class="text-center text-gray-600 mb-12 text-lg max-w-2xl mx-auto content-text">
            Ai nevoie de asistență tehnică imediată sau de informații suplimentare? Alege cea mai rapidă metodă de
            contact.
        </p>

        <!-- Grid metode de contact -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">

            <!-- Card suport tehnic -->
            <section class="contact-card">
                <i data-lucide="life-buoy" class="w-10 h-10 text-sentra-cyan mb-4"></i>
                <h3 class="text-xl font-bold mb-2 text-[#1A1A1A]">Suport tehnic (24/7)</h3>
                <span class="content-text text-sm font-semibold text-gray-500 mb-4">Recomandat pentru probleme urgente</span>
                <p class="content-text mb-4">
                    Cea mai rapidă cale de a primi ajutor de la un inginer. Vă rugăm să utilizați platforma de ticketing.
                </p>
                <a href="ticket.php"
                    class="text-white sentra-cyan px-6 py-3 rounded-full font-bold transition duration-300 hover:opacity-90">
                    Deschide un ticket
                </a>
            </section>

            <!-- Card email și vânzări -->
            <section class="contact-card">
                <i data-lucide="mail" class="w-10 h-10 text-sentra-cyan mb-4"></i>
                <h3 class="text-xl font-bold mb-2 text-[#1A1A1A]">Email și vânzări</h3>
                <span class="content-text text-sm font-semibold text-gray-500 mb-4">Răspuns în max. 2 ore lucrătoare</span>
                <p class="content-text mb-4">
                    Pentru întrebări despre produse, oferte personalizate sau parteneriate.
                </p>
                <p class="text-lg font-semibold">
                    <a href="mailto:vanzari@sentra.ro"
                        class="contact-link transition duration-200">vanzari@sentra.ro</a>
                </p>
            </section>

            <!-- Card telefon -->
            <section class="contact-card">
                <i data-lucide="phone" class="w-10 h-10 text-sentra-cyan mb-4"></i>
                <h3 class="text-xl font-bold mb-2 text-[#1A1A1A]">Telefon</h3>
                <span class="content-text text-sm font-semibold text-gray-500 mb-4">Luni - vineri, 9:00 - 17:00 (ora României)</span>
                <p class="content-text mb-4">
                    Pentru solicitări generale sau consultanță rapidă.
                </p>
                <p class="text-lg font-semibold">
                    <a href="tel:+40332800800" class="contact-link transition duration-200">+40 332 800 800</a>
                </p>
            </section>

        </div>

        <!-- Secțiunea sediu și hartă -->
        <section class="mt-20">
            <h3 class="text-3xl font-extrabold text-[#1A1A1A] text-center mb-10">
                Sediul central și adresa poștală
            </h3>

            <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-xl border-l-4 border-sentra-cyan mb-8 text-center">
                <i data-lucide="map-pin" class="w-8 h-8 text-sentra-cyan mx-auto mb-3"></i>
                <h4 class="text-xl font-bold mb-1">Sentra Hosting S.R.L.</h4>
                <p class="content-text">
                    Bulevardul Tudor Vladimirescu, Nr. 42<br>
                    Clădirea United Business Center 3 (UBC 3), Etaj 2<br>
                    Iași, 700259, România
                </p>
            </div>

            <div class="map-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2712.986389650379!2d27.585573476839358!3d47.16450552084534!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40b21a329d4d53fd%3A0x286358cc10f3c582!2sUnited%20Business%20Center%203%20(UBC%203)!5e0!3m2!1sen!2sro!4v1700000000000!5m2!1sen!2sro"
                    width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>

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