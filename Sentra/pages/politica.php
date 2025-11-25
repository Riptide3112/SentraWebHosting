<?php
// politica.php
// Pagina "Politica de confidențialitate" cu fundal alb, secțiuni extinse și footer.php
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politica de confidențialitate - Sentra</title>
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

        /* Stiluri specifice listelor din confidențialitate */
        .policy-list {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin-top: 1rem;
        }

        .policy-list li {
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
            <span class="text-sentra-cyan">Politica de confidențialitate:</span> Protecția datelor personale
        </h2>

        <p class="text-center text-gray-600 mb-12 text-lg content-text">
            Angajamentul nostru este de a trata datele dumneavoastră cu cea mai mare seriozitate și securitate.
        </p>

        <div class="space-y-10 max-w-4xl mx-auto">

            <!-- Secțiunea 1: Ce date colectăm -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="lock" class="w-6 h-6 mr-3"></i> 1. Ce date colectăm
                </h3>
                <span class="card-subtitle">Datele necesare pentru furnizarea serviciilor și facturare.</span>
                <p class="content-text">
                    Colectăm date personale strict necesare pentru înregistrarea contului, furnizarea serviciilor de
                    găzduire și conformarea cu obligațiile legale de facturare. Acestea includ, dar nu se limitează la:
                </p>
                <ul class="policy-list content-text">
                    <li>Nume complet, adresă de email, număr de telefon (pentru contact și notificări)</li>
                    <li>Detalii de facturare (adresă, CUI/CIF dacă este cazul)</li>
                    <li>Date de trafic și logare (adrese IP, informații despre browser) pentru securitate și diagnosticare</li>
                </ul>
            </section>

            <!-- Secțiunea 2: Cum utilizăm datele -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="settings" class="w-6 h-6 mr-3"></i> 2. Cum utilizăm datele
                </h3>
                <span class="card-subtitle">Scopul utilizării datelor este exclusiv pentru funcționarea serviciilor.</span>
                <p class="content-text">
                    Datele colectate sunt folosite în următoarele scopuri principale:
                </p>
                <ul class="policy-list content-text">
                    <li>Procesarea comenzilor și gestionarea contului de client</li>
                    <li>Asigurarea suportului tehnic și rezolvarea problemelor de serviciu</li>
                    <li>Emiterea facturilor și gestionarea plăților</li>
                    <li>Notificări privind modificările serviciilor sau actualizările de securitate</li>
                </ul>
            </section>

            <!-- Secțiunea 3: Politica cookies -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="cookie" class="w-6 h-6 mr-3"></i> 3. Politica cookies
                </h3>
                <span class="card-subtitle">Utilizăm cookie-uri esențiale și de analiză, cu consimțământul dumneavoastră.</span>
                <p class="content-text">
                    Website-ul Sentra folosește cookie-uri pentru a îmbunătăți experiența de navigare, pentru
                    autentificare (esențiale) și pentru a colecta statistici anonime privind utilizarea site-ului (de
                    performanță). Puteți gestiona sau dezactiva cookie-urile prin setările browser-ului dumneavoastră,
                    dar acest lucru poate afecta funcționalitatea platformei de client.
                </p>
            </section>

            <!-- Secțiunea 4: Dezvăluirea către terți -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="users" class="w-6 h-6 mr-3"></i> 4. Dezvăluirea către terți
                </h3>
                <span class="card-subtitle">Datele nu sunt vândute. Sunt partajate doar cu parteneri esențiali.</span>
                <p class="content-text">
                    Sentra nu vinde, nu închiriază și nu transferă datele personale către terți în scopuri de marketing.
                    Partajarea datelor are loc doar cu entități strict necesare pentru furnizarea serviciilor, cum ar fi:
                </p>
                <ul class="policy-list content-text">
                    <li>Procesatori de plăți (pentru tranzacții sigure)</li>
                    <li>Registrari de domenii (pentru înregistrarea numelui de domeniu)</li>
                    <li>Autorități legale, la cerere, conform legii</li>
                </ul>
            </section>

            <!-- Secțiunea 5: Securitatea datelor -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="shield" class="w-6 h-6 mr-3"></i> 5. Securitatea datelor
                </h3>
                <span class="card-subtitle">Măsuri tehnice și organizaționale avansate de protecție.</span>
                <p class="content-text">
                    Am implementat măsuri de securitate de top, inclusiv criptare SSL, firewall-uri de ultimă generație
                    și acces restricționat la date, pentru a proteja informațiile personale împotriva accesului
                    neautorizat, modificării, dezvăluirii sau distrugerii. Toate conexiunile la platforma de client sunt
                    securizate.
                </p>
            </section>

            <!-- Secțiunea 6: Drepturile dumneavoastră GDPR -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="user-check" class="w-6 h-6 mr-3"></i> 6. Drepturile dumneavoastră GDPR
                </h3>
                <span class="card-subtitle">Aveți control deplin asupra datelor dumneavoastră.</span>
                <p class="content-text">
                    În calitate de utilizator, beneficiați de următoarele drepturi, pe care le puteți exercita
                    contactând echipa noastră de suport:
                </p>
                <ul class="policy-list content-text">
                    <li>Dreptul de acces (să cereți o copie a datelor pe care le deținem)</li>
                    <li>Dreptul la rectificare (să corectați datele inexacte)</li>
                    <li>Dreptul la ștergere (dreptul de a fi uitat, sub rezerva obligațiilor legale de păstrare)</li>
                    <li>Dreptul la restricționarea prelucrării și dreptul la portabilitate</li>
                </ul>
            </section>

            <!-- Secțiunea 7: Perioada de păstrare -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="calendar" class="w-6 h-6 mr-3"></i> 7. Perioada de păstrare
                </h3>
                <span class="card-subtitle">Păstrăm datele cât timp este necesar pentru scopurile legale și comerciale.</span>
                <p class="content-text">
                    Vom păstra datele dumneavoastră personale pe durata activă a contului de client. După închiderea
                    contului, datele de facturare vor fi păstrate conform legislației fiscale în vigoare (de obicei 5
                    până la 10 ani), în timp ce datele de contact și de logare vor fi șterse sau anonimizate.
                </p>
            </section>

            <!-- Secțiunea 8: Modificări ale politicii -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i data-lucide="refresh-cw" class="w-6 h-6 mr-3"></i> 8. Modificări ale politicii
                </h3>
                <span class="card-subtitle">Vă vom notifica despre orice schimbare majoră.</span>
                <p class="content-text">
                    Această politică de confidențialitate poate fi actualizată periodic. Vă vom notifica cu privire la
                    orice schimbări semnificative prin email sau printr-un anunț vizibil pe site, înainte ca
                    modificările să intre în vigoare. Continuarea utilizării serviciilor după aceste notificări
                    reprezintă acceptarea noilor termeni.
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