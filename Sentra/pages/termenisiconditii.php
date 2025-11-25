<?php
// termenisiconditii.php
// Pagina "Termeni și condiții" cu fundal alb, secțiuni extinse și footer.php
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termeni și condiții - Sentra</title>
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

        /* Stil pentru secțiunile de text */
        .about-card {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            border-left: 6px solid #00BFB2;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .about-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
        }

        .text-section {
            color: #333;
            line-height: 1.7;
            font-size: 1.05rem;
        }

        .card-subtitle {
            font-style: italic;
            font-size: 1.1rem;
            color: #555;
            display: block;
            margin-bottom: 1rem;
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

        /* Stiluri specifice listelor din termeni și condiții */
        .terms-list {
            list-style-type: decimal;
            padding-left: 1.5rem;
            margin-top: 1rem;
        }

        .terms-list li {
            margin-bottom: 0.5rem;
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

        <h2 class="text-5xl font-extrabold mb-8 text-center text-[#1A1A1A] pt-8">
            <span class="text-sentra-cyan">Termeni și condiții:</span> Acordul de furnizare a serviciilor
        </h2>

        <p class="text-center text-gray-600 mb-12 text-lg content-text">
            Ultima actualizare: 15 octombrie 2025. Vă rugăm să citiți cu atenție înainte de a utiliza serviciile
            noastre.
        </p>

        <div class="space-y-10 max-w-4xl mx-auto">

            <!-- Secțiunea 1: Acceptarea termenilor -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="file-check" class="w-6 h-6 mr-3"></i> 1. Acceptarea termenilor
                </h3>
                <span class="card-subtitle">Utilizarea serviciilor noastre implică acceptarea necondiționată a acestui
                    acord.</span>
                <p class="content-text">
                    Prin înregistrarea și utilizarea oricărui serviciu oferit de Sentra (găzduire web, VPS, înregistrare
                    domenii), clientul confirmă că a citit, înțeles și este de acord să respecte toți termenii și
                    condițiile enumerate aici, precum și politica de utilizare acceptabilă (PUA). Sentra își rezervă
                    dreptul de a modifica acești termeni fără notificare prealabilă.
                </p>
            </section>

            <!-- Secțiunea 2: Furnizarea serviciilor -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="server" class="w-6 h-6 mr-3"></i> 2. Furnizarea serviciilor
                </h3>
                <span class="card-subtitle">Detalii despre uptime, resurse și limitări.</span>
                <p class="content-text">
                    Sentra se angajează să furnizeze servicii de găzduire cu un uptime garantat de 99.9% la nivel de
                    rețea. Resursele alocate fiecărui plan sunt specificate clar la momentul achiziției și sunt
                    destinate utilizării exclusive de către client. Orice depășire sau utilizare abuzivă a resurselor,
                    în special CPU și memorie, poate duce la suspendarea temporară a serviciului.
                </p>
                <ul class="terms-list content-text">
                    <li>Utilizarea rețelei trebuie să respecte politica de utilizare acceptabilă.</li>
                    <li>IP-urile sunt alocate pe bază de închiriere și rămân proprietatea Sentra.</li>
                </ul>
            </section>

            <!-- Secțiunea 3: Plăți și facturare -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="credit-card" class="w-6 h-6 mr-3"></i> 3. Plăți și facturare
                </h3>
                <span class="card-subtitle">Toate plățile sunt anticipate și nerambursabile după perioada de
                    garanție.</span>
                <p class="content-text">
                    Serviciile sunt furnizate pe bază de abonament preplătit (lunar, trimestrial, anual). Facturile sunt
                    emise cu 14 zile înainte de data scadenței. Neplata facturii până la data scadenței poate duce la
                    suspendarea serviciului, iar după 7 zile de la scadență, la terminarea definitivă și ștergerea
                    datelor.
                </p>
                <ul class="terms-list content-text">
                    <li>Garanția de returnare a banilor este valabilă 30 de zile pentru serviciile de găzduire web noi.
                    </li>
                    <li>Înregistrarea domeniilor nu beneficiază de garanție de returnare.</li>
                </ul>
            </section>

            <!-- Secțiunea 4: Politica de utilizare acceptabilă -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="shield-alert" class="w-6 h-6 mr-3"></i> 4. Politica de utilizare acceptabilă (PUA)
                </h3>
                <span class="card-subtitle">Activitățile interzise, incluzând spam și conținut ilegal.</span>
                <p class="content-text">
                    Clientul nu va folosi serviciile Sentra pentru a găzdui sau transmite conținut ilegal, abuziv,
                    amenințător, defăimător sau dăunător. Sunt strict interzise activitățile de spam, phishing, minare
                    de criptomonede, sau găzduirea de scripturi botnet. Nerespectarea PUA va duce la suspendarea sau
                    încetarea imediată a contului.
                </p>
            </section>

            <!-- Secțiunea 5: Suspendarea și terminarea serviciului -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="pause" class="w-6 h-6 mr-3"></i> 5. Suspendarea și terminarea serviciului
                </h3>
                <span class="card-subtitle">Dreptul Sentra de a acționa în caz de încălcare a termenilor.</span>
                <p class="content-text">
                    Sentra poate suspenda imediat serviciul fără rambursare dacă se detectează o încălcare gravă a
                    acestor termeni sau a PUA. Terminarea poate fi inițiată de client în orice moment prin intermediul
                    contului client, iar de către Sentra în caz de neplată sau încălcare continuă a obligațiilor.
                </p>
            </section>

            <!-- Secțiunea 6: Garanția datelor și backup -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="hard-drive" class="w-6 h-6 mr-3"></i> 6. Garanția datelor și backup
                </h3>
                <span class="card-subtitle">Responsabilitatea finală a datelor revine clientului.</span>
                <p class="content-text">
                    Deși Sentra efectuează backup-uri automate zilnice ca serviciu suplimentar, responsabilitatea finală
                    pentru integritatea și securitatea datelor aparține clientului. Sentra nu este responsabilă pentru
                    pierderea datelor din cauze externe (ex. erori umane, atacuri cibernetice neprevăzute, etc.).
                    Recomandăm insistent ca toți clienții să păstreze propriile copii de rezervă offline.
                </p>
            </section>

            <!-- Secțiunea 7: Jurisdicție și legea aplicabilă -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="scale" class="w-6 h-6 mr-3"></i> 7. Jurisdicție și legea aplicabilă
                </h3>
                <span class="card-subtitle">Prezentul acord este guvernat de legile din România.</span>
                <p class="content-text">
                    Orice dispută rezultată din sau în legătură cu acest acord va fi soluționată pe cale amiabilă. Dacă
                    o soluție amiabilă nu este posibilă, litigiul va fi supus instanțelor de judecată competente din
                    România.
                </p>
            </section>

            <!-- Secțiunea 8: Contact -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="mail" class="w-6 h-6 mr-3"></i> 8. Contact
                </h3>
                <span class="card-subtitle">Pentru întrebări sau clarificări, contactați-ne oricând.</span>
                <p class="content-text">
                    Pentru orice întrebări referitoare la acești termeni și condiții, vă rugăm să ne contactați prin
                    intermediul paginii de suport sau la adresa de email specificată în secțiunea de contact.
                </p>
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