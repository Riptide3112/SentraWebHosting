<?php
// bazacunostinte.php
// Pagina "Bază de cunoștințe" - Interactivă cu filtrare, căutare și vizualizare modală.
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bază de cunoștințe interactivă - Sentra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

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

        /* Containerul principal al paginii - asigură centrarea */
        .main-content {
            width: 100%;
            max-width: 1200px;
            padding: 3rem 1.5rem 6rem;
            flex-grow: 1;
            margin-left: auto;
            margin-right: auto;
        }

        /* Stil pentru butoanele de categorie (navigare laterală) */
        .category-btn {
            padding: 1rem 1rem;
            cursor: pointer;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .category-btn:hover {
            background-color: #E0F2F1;
        }

        .category-btn.active {
            background-color: #00BFB2;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0, 191, 178, 0.3);
        }

        .category-btn.active .category-icon {
            color: white;
        }

        .category-icon {
            color: #00BFB2;
            transition: color 0.2s;
        }

        .category-btn.active .category-icon {
            color: white;
        }

        /* Stil pentru articolele afișate */
        .article-item {
            background-color: #ffffff;
            padding: 1.5rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #E5E7EB;
            cursor: pointer;
            /* Indicăm că este acționabil */
            transition: border-left-color 0.3s, transform 0.2s;
        }

        .article-item:hover {
            border-left-color: #00BFB2;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .article-title {
            font-weight: 600;
            color: #1A1A1A;
            transition: color 0.3s;
        }

        .article-item:hover .article-title {
            color: #00BFB2;
        }

        /* Stil pentru modal (pop-up articol) */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 100;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .modal-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-content {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 1rem;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.9);
            transition: transform 0.3s;
        }

        .modal-overlay.open .modal-content {
            transform: scale(1);
        }

        /* Stil special pentru a forța footer-ul să ocupe toată lățimea */
        .full-width-footer {
            width: 100%;
        }

        /* Stiluri pentru footer hover */
        .footer-link-hover:hover {
            color: #00BFB2 !important;
            transition: color 0.3s;
            text-decoration: underline;
        }

        .social-icon-link:hover i {
            color: #00BFB2 !important;
            transition: color 0.3s;
        }
    </style>
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>
</head>

<body>

<header class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="../index.php" class="flex-shrink-0 flex items-center">
                <span class="text-3xl font-extrabold text-[#1A1A1A]">Sentra</span>
                <div class="w-2 h-2 ml-1 sentra-cyan rounded-full"></div>
            </a>

            <nav class="hidden md:flex space-x-8">
                <a href="gazduire_web.php"
                    class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">Găzduire Web</a>
                <a href="vps.php"
                    class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">VPS</a>

                <div class="relative group flex items-center">
                    <button id="domainDropdownButton" type="button"
                        class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300 focus:outline-none"
                        aria-expanded="false" aria-haspopup="true">
                        Domenii
                    </button>
                    <div id="domainDropdownMenu"
                        class="absolute left-1/2 transform -translate-x-1/2 mt-2 top-full w-48 bg-white border border-gray-100 rounded-lg shadow-xl py-1 z-50 invisible opacity-0 transition-all duration-300 ease-in-out group-hover:visible group-hover:opacity-100">
                        <a href="inregistrare_domenii.php"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Înregistrare
                            domenii</a>
                        <a href="transfer_domenii.php"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Transfer
                            domenii</a>
                    </div>
                </div>

                <div class="relative group flex items-center">
                    <button id="supportDropdownButton" type="button"
                        class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300 focus:outline-none"
                        aria-expanded="false" aria-haspopup="true">
                        Suport
                    </button>
                    <div id="supportDropdownMenu"
                        class="absolute left-1/2 transform -translate-x-1/2 mt-2 top-full w-48 bg-white border border-gray-100 rounded-lg shadow-xl py-1 z-50 invisible opacity-0 transition-all duration-300 ease-in-out group-hover:visible group-hover:opacity-100">
                        <a href="bazacunostinte.php"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Bază
                            de cunoștințe</a>
                        <a href="../client/ticket.php"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Deschide
                            un Ticket</a>
                    </div>
                </div>
            </nav>

            <div class="hidden md:flex items-center space-x-4">
                <a href="../client/dashboard.php"
                    class="sentra-cyan text-white px-4 py-2 rounded-lg font-semibold shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5">
                    Cont Client
                </a>
            </div>

            <button id="mobile-menu-button" type="button"
                class="md:hidden text-gray-500 hover:text-sentra-cyan focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="md:hidden hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            
            <a href="gazduire_web.php"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-sentra-cyan transition duration-300">
                Găzduire Web
            </a>
            
            <a href="vps.php"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-sentra-cyan transition duration-300">
                VPS
            </a>

            <button id="mobile-domain-toggle" type="button"
                class="w-full text-left flex justify-between items-center px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-sentra-cyan transition duration-300">
                <span>Domenii</span>
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="mobile-domain-menu" class="pl-6 space-y-1 hidden">
                <a href="inregistrare_domenii.php"
                    class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Înregistrare domenii</a>
                <a href="transfer_domenii.php"
                    class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Transfer domenii</a>
            </div>

            <button id="mobile-support-toggle" type="button"
                class="w-full text-left flex justify-between items-center px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-sentra-cyan transition duration-300">
                <span>Suport</span>
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="mobile-support-menu" class="pl-6 space-y-1 hidden">
                <a href="bazacunostinte.php"
                    class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Bază de cunoștințe</a>
                <a href="../client/ticket.php"
                    class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Deschide un Ticket</a>
            </div>
        </div>
        
        <div class="pt-4 pb-3 border-t border-gray-200">
            <a href="../client/dashboard.php"
                class="block mx-4 sentra-cyan text-white px-4 py-2 rounded-lg text-base font-semibold text-center shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5">
                Cont Client
            </a>
        </div>
    </div>
</header>

    <main class="main-content">

        <h2 class="text-5xl font-extrabold mb-4 text-center text-[#1A1A1A] pt-8">
            <span class="text-sentra-cyan">Biblioteca digitală:</span> Baza de cunoștințe
        </h2>

        <p class="text-center text-gray-600 mb-12 text-xl max-w-4xl mx-auto font-medium">
            <strong>Găsiți soluția imediat.</strong> Explorați ghidurile noastre complete, scrise de inginerii Sentra.
            Începeți prin căutare sau alegeți o categorie.
        </p>

        <div class="flex flex-col lg:flex-row gap-8">

            <div class="w-full lg:w-1/4 bg-white p-6 rounded-xl shadow-lg h-fit sticky top-24">
                <h3 class="text-xl font-bold mb-4 border-b pb-2 text-[#1A1A1A]">Categorii</h3>
                <div id="category-list">
                    <button class="category-btn active" data-category="toate">
                        <i data-lucide="blocks" class="category-icon w-5 h-5 mr-3"></i> Toate articolele
                    </button>
                    <button class="category-btn" data-category="gazduire">
                        <i data-lucide="server" class="category-icon w-5 h-5 mr-3 text-sentra-cyan"></i> Găzduire web
                    </button>
                    <button class="category-btn" data-category="cpanel">
                        <i data-lucide="layout-dashboard" class="category-icon w-5 h-5 mr-3 text-sentra-cyan"></i>
                        cPanel & tools
                    </button>
                    <button class="category-btn" data-category="domenii">
                        <i data-lucide="at-sign" class="category-icon w-5 h-5 mr-3 text-sentra-cyan"></i> Domenii & DNS
                    </button>
                    <button class="category-btn" data-category="securitate">
                        <i data-lucide="shield-check" class="category-icon w-5 h-5 mr-3 text-sentra-cyan"></i>
                        Securitate & SSL
                    </button>
                    <button class="category-btn" data-category="facturare">
                        <i data-lucide="wallet" class="category-icon w-5 h-5 mr-3 text-sentra-cyan"></i> Plăți &
                        facturare
                    </button>
                    <button class="category-btn" data-category="email">
                        <i data-lucide="mail" class="category-icon w-5 h-5 mr-3 text-sentra-cyan"></i> Conturi email
                    </button>
                </div>
            </div>

            <div class="w-full lg:w-3/4">

                <div class="relative flex items-center mb-8">
                    <i data-lucide="search" class="w-6 h-6 text-gray-400 absolute left-4"></i>
                    <input type="text" id="search-input"
                        placeholder="Căutați un ghid specific (ex: eroare 500, instalare SSL, migrare)"
                        class="w-full py-4 pl-12 pr-4 text-lg border-2 border-gray-200 rounded-full focus:outline-none focus:ring-4 focus:ring-[#00BFB2]/20 focus:border-sentra-cyan transition duration-300 shadow-md">
                </div>

                <div id="articles-container" class="space-y-4">
                </div>

                <div id="no-results-message"
                    class="hidden text-center p-12 bg-white rounded-xl shadow-lg border-l-4 border-red-400 mt-8">
                    <i data-lucide="alert-triangle" class="w-10 h-10 text-red-500 mx-auto mb-4"></i>
                    <h4 class="text-2xl font-bold mb-2">Ne pare rău, nu am găsit rezultate.</h4>
                    <p class="text-gray-600">Vă rugăm să reformulați căutarea. Dacă nu găsiți răspunsul, deschideți un
                        ticket de suport.</p>
                </div>
            </div>
        </div>

    </main>

    <div class="full-width-footer">
        <?php
        // Includerea footer.php (fără modificări interne)
        include 'footer.php';
        ?>
    </div>

    <div id="article-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="flex justify-between items-start border-b pb-4 mb-4">
                <div>
                    <span id="modal-category"
                        class="text-sm font-semibold px-3 py-1 rounded-full text-white sentra-cyan"></span>
                    <h3 id="modal-title" class="text-3xl font-extrabold mt-2 text-[#1A1A1A]"></h3>
                </div>
                <button id="close-modal-btn" class="text-gray-500 hover:text-gray-900 transition duration-150">
                    <i data-lucide="x" class="w-8 h-8"></i>
                </button>
            </div>
            <div id="modal-body" class="text-lg text-gray-700 leading-relaxed space-y-4">
            </div>
            <div class="mt-6 pt-4 border-t text-center">
                <p class="text-sm text-gray-500">Informația a fost utilă? Vă stăm la dispoziție pentru orice altă
                    întrebare!</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            lucide.createIcons();

            // 1. Baza de date extinsă (25 articole)
            const articles = [
                {
                    id: 1, category: 'gazduire', title: 'Migrarea site-ului WordPress: pas cu pas',
                    snippet: 'Ghid detaliat care descrie procesul de transfer al întregului site WordPress de la alt furnizor la Sentra fără downtime.',
                    content: `Procesul de migrare este gratuit și asistat. Vă rugăm să ne furnizați credențialele de acces la cPanel-ul vechiului furnizor. 
                            <ol class="list-decimal list-inside ml-4 mt-2">
                                <li>Comandați pachetul de găzduire dorit.</li>
                                <li>Deschideți un ticket de migrare și atașați datele de acces.</li>
                                <li>Echipa noastră va clona întregul site și baza de date.</li>
                                <li>Vă vom notifica când site-ul este gata pentru testare pe serverul nostru.</li>
                            </ol>
                            Acest proces durează în mod obișnuit între 1 și 4 ore, în funcție de mărimea site-ului.`
                },
                {
                    id: 2, category: 'cpanel', title: 'Instalarea CMS-urilor populare cu Softaculous',
                    snippet: 'Aflați cum să folosiți instalatorul automat Softaculous din cPanel pentru a pune în funcțiune site-ul dvs. în câteva minute.',
                    content: `Softaculous este un instalator de aplicații care vă permite să instalați peste 400 de scripturi web (inclusiv WordPress, Joomla, PrestaShop) direct din cPanel, cu un singur click. Găsiți pictograma "Softaculous Apps Installer" în secțiunea "Software" a panoului cPanel.`
                },
                {
                    id: 3, category: 'securitate', title: 'Cum activez sau forțez HTTPS (certificat SSL gratuit)',
                    snippet: 'Instrucțiuni pentru activarea și forțarea certificatului Let\'s Encrypt SSL pe domeniul dumneavoastră prin cPanel.',
                    content: `Toate pachetele de găzduire Sentra includ certificate SSL gratuite Let's Encrypt. 
                            Pentru activare, navigați în cPanel la secțiunea **"Securitate"** și selectați **"SSL/TLS Status"**. De aici puteți rula instalarea automată. Pentru a forța traficul pe HTTPS, puteți utiliza instrumentul "Domains" din cPanel sau adăugați o regulă de redirecționare în fișierul **.htaccess**.`
                },
                {
                    id: 4, category: 'domenii', title: 'Cum schimb name-server domeniului meu',
                    snippet: 'Ghid pentru actualizarea nameserverelor (DNS) în panoul de client Sentra, esențial pentru a lega domeniul de găzduire.',
                    content: `Name-server corecte ale Sentra sunt: **ns1.sentra.ro** și **ns2.sentra.ro**. Dacă domeniul este înregistrat la un alt registrar, accesați panoul lor de control și înlocuiți name-server existente cu cele de mai sus. Rețineți că propagarea DNS poate dura până la 48 de ore la nivel global.`
                },
                {
                    id: 5, category: 'gazduire', title: 'Rezolvarea erorii 500 internal server error',
                    snippet: 'Cei mai frecvenți pași de depanare, inclusiv verificarea fișierului .htaccess și setarea permisiunilor de fișiere.',
                    content: `Eroarea 500 este una generică. Cauzele principale sunt: 
                            <ul class="list-disc list-inside ml-4 mt-2">
                                <li>**Fișier .htaccess defect:** Redenumiți temporar fișierul (.htaccess_old) pentru a verifica dacă eroarea dispare.</li>
                                <li>**Permisiuni incorecte:** Asigurați-vă că fișierele au permisiunea **644** și directoarele **755**.</li>
                                <li>**Limită de memorie PHP:** Măriți limita de memorie din **MultiPHP INI Editor** în cPanel.</li>
                            </ul>
                            Dacă problema persistă, vă rugăm să deschideți un ticket.`
                },
                {
                    id: 6, category: 'facturare', title: 'Cum achit factura și verific data scadenței',
                    snippet: 'Detalii despre metodele de plată disponibile și cum accesați istoricul facturilor din Zona de client Sentra.',
                    content: `Facturile pot fi achitate din Zona de client, secțiunea "Facturare". Acceptăm plata prin card bancar (online, procesare instantanee), transfer bancar (OP) sau PayPal. Vă rugăm să rețineți că serviciile se pot suspenda la 7 zile după data scadenței, dacă plata nu este efectuată.`
                },
                {
                    id: 7, category: 'email', title: 'Crearea și configurarea adreselor de email profesionale',
                    snippet: 'Tutorial pentru a crea o adresă email personalizată (@domeniultau.ro) și pentru a o configura în Outlook/Gmail.',
                    content: `Adresele de email se creează din cPanel, secțiunea "Email Accounts". Pentru configurarea în clientul dvs. (Outlook, Thunderbird), folosiți setările standard: **IMAP Port 993 (SSL)** și **SMTP Port 465 (SSL)**. Serverul de intrare/ieșire este numele domeniului dvs. (ex: mail.domeniultau.ro).`
                },
                {
                    id: 8, category: 'securitate', title: 'Protecția împotriva atacurilor brute force cu cPHulk',
                    snippet: 'Aflați cum să folosiți instrumentele de securitate din cPanel pentru a bloca încercările repetate de logare eșuate.',
                    content: `cPHulk este un instrument automatizat care detectează și blochează adresele IP care încearcă în mod repetat să ghicească parolele cPanel, email sau FTP. Este activat implicit pe serverele Sentra, oferind un strat suplimentar de securitate.`
                },
                {
                    id: 9, category: 'gazduire', title: 'Optimizarea bazei de date MySQL cu phpMyAdmin',
                    snippet: 'Sfaturi și pași pentru a curăța și optimiza tabelele bazei de date pentru o viteză sporită.',
                    content: `Dacă folosiți MySQL/MariaDB, tabelele mari se pot fragmenta în timp. Accesați phpMyAdmin din cPanel, selectați baza de date și rulați comanda **"Optimize Table"** pentru a elibera spațiul nefolosit și a îmbunătăți timpul de interogare.`
                },
                {
                    id: 10, category: 'domenii', title: 'Cum adaug un subdomeniu în cPanel',
                    snippet: 'Instrucțiuni rapide pentru a crea un subdomeniu și a-l atașa unui folder specific din contul dumneavoastră de găzduire.',
                    content: `În cPanel, navigați la secțiunea "Domenii" și selectați "Subdomains". Introduceți numele subdomeniului (ex: blog) și alegeți domeniul principal. Document Root va fi creat automat (ex: public_html/blog), unde puteți instala fișierele site-ului.`
                },
                {
                    id: 11, category: 'cpanel', title: 'Setarea unei sarcini programate (Cron Job)',
                    snippet: 'Cum să automatizați sarcini repetitive (ex: curățarea cache-ului sau sincronizarea datelor) folosind Cron Jobs în cPanel.',
                    content: `Cron Jobs sunt comenzi programate care rulează automat la intervale specificate. Accesați "Cron Jobs" din cPanel, setați intervalul de timp (ex: o dată pe oră) și introduceți calea completă către scriptul pe care doriți să-l executați (de obicei un fișier PHP).`
                },
                {
                    id: 12, category: 'gazduire', title: 'Verificarea jurnalelor de eroare (Error Logs)',
                    snippet: 'Aflați unde se găsesc și cum să interpretați fișierele de jurnal pentru a depana erorile din PHP.',
                    content: `Log-urile de eroare sunt esențiale pentru depanare. Ele se găsesc de obicei în directorul **public_html** sau pot fi accesate direct din cPanel, secțiunea "Metrics" > "Errors". Verificați întotdeauna cele mai recente intrări pentru a identifica scriptul sursă al problemei.`
                },
                {
                    id: 13, category: 'securitate', title: 'Crearea și restaurarea backup-urilor personale',
                    snippet: 'Ghid pentru utilizarea instrumentului JetBackup/R1Soft din cPanel pentru backup-uri la cerere.',
                    content: `Deși Sentra efectuează backup-uri zilnice, puteți genera sau restaura un backup la cerere din instrumentul **JetBackup/R1Soft** din cPanel. Puteți alege să restaurați fișiere individuale, baze de date sau întregul cont, fără intervenția suportului.`
                },
                {
                    id: 14, category: 'domenii', title: 'Transferul unui domeniu .RO (procedura RoTLD)',
                    snippet: 'Pașii necesari pentru a iniția transferul unui domeniu .RO de la registrarul actual la Sentra.',
                    content: `Pentru domenii .RO, este necesar un **AuthID** (cod de transfer) de la registrarul curent. După ce inițiați comanda de transfer la noi, vom folosi acest cod pentru a cere validarea la RoTLD. Transferul este confirmat de obicei în câteva ore după validare.`
                },
                {
                    id: 15, category: 'email', title: 'Configurare filtre anti-spam (SpamAssassin)',
                    snippet: 'Cum să ajustați setările SpamAssassin pentru a bloca emailurile nedorite la nivel de server.',
                    content: `SpamAssassin este un filtru puternic integrat în cPanel. Îl găsiți la secțiunea "Email". Puteți seta scorul de sensibilitate (recomandat între 4 și 5) și puteți adăuga adrese de email sau domenii pe lista neagră sau pe lista albă.`
                },
                {
                    id: 16, category: 'facturare', title: 'Reînnoirea automată și cardul salvat',
                    snippet: 'Informații despre cum să activați plata automată a serviciilor pentru a evita suspendarea din cauza întârzierilor.',
                    content: `Puteți salva detaliile cardului în Zona de client (securizat prin procesatorul de plăți). Activarea reînnoirii automate asigură că serviciul este plătit cu 7 zile înainte de data scadenței, garantând continuitatea fără întreruperi.`
                },
                {
                    id: 17, category: 'gazduire', title: 'Schimbarea versiunii PHP (MultiPHP Manager)',
                    snippet: 'Cum puteți selecta rapid versiunea de PHP (7.4, 8.1, 8.2, 8.3) pentru fiecare domeniu găzduit.',
                    content: `Majoritatea aplicațiilor moderne necesită PHP 8.x pentru performanță optimă. În cPanel, navigați la **"MultiPHP Manager"** și selectați versiunea dorită pentru directorul rădăcină (public_html) sau pentru subdomenii specifice.`
                },
                {
                    id: 18, category: 'securitate', title: 'Blocarea accesului pe adresă IP (.htaccess)',
                    snippet: 'Metodă manuală pentru a restricționa accesul la anumite fișiere sau directoare pentru adrese IP specifice.',
                    content: `Pentru a bloca o anumită adresă IP, adăugați următoarele linii în fișierul .htaccess: 
                            <pre class="bg-gray-100 p-3 rounded mt-2">Order Allow,Deny<br>Deny from 192.168.1.1<br>Allow from All</pre>
                            Acest lucru este util pentru a bloca vizitatorii sau roboții rău intenționați.`
                },
                {
                    id: 19, category: 'cpanel', title: 'Utilizarea File Manager (managerul de fișiere)',
                    snippet: 'Ghid de bază pentru încărcarea, editarea și gestionarea fișierelor site-ului direct din interfața web.',
                    content: `File Manager din cPanel oferă o interfață grafică similară cu exploratorul de fișiere de pe desktop. Puteți încărca (Upload), șterge, edita (Editor HTML) sau schimba permisiunile fișierelor direct din browser, fără a fi nevoie de un client FTP.`
                },
                {
                    id: 20, category: 'domenii', title: 'Configurare înregistrări DNS avansate (A, CNAME, MX)',
                    snippet: 'Instrucțiuni pentru modificarea înregistrărilor DNS avansate pentru a le direcționa către servicii externe (ex: Office 365).',
                    content: `Accesați **"Zone Editor"** în cPanel. De aici puteți adăuga înregistrări DNS de tip A (adresă IP), CNAME (alias) ou MX (server de email). Feriți-vă să modificați înregistrările MX dacă folosiți serviciul de email Sentra.`
                },
                {
                    id: 21, category: 'email', title: 'Depanare: de ce nu primesc email-uri',
                    snippet: 'Checklist cu cele mai comune motive pentru care nu primiți sau nu trimiteți email-uri (MX, spațiu, filtre).',
                    content: `Verificați următoarele: 
                            <ol class="list-decimal list-inside ml-4 mt-2">
                                <li>**Spațiul:** Contul de email nu este plin?</li>
                                <li>**Înregistrările MX:** Sunt corecte și indică spre serverul Sentra?</li>
                                <li>**SpamAssassin:** Este prea agresiv și blochează email-urile legitime?</li>
                                <li>**Firewall-ul local:** Vă blochează clientul de email (Outlook/Thunderbird)?</li>
                            </ol>`
                },
                {
                    id: 22, category: 'gazduire', title: 'Ce este un Addon Domain și cum îl folosesc',
                    snippet: 'Diferența între Addon Domain, Subdomeniu și Alias, și cum să găzduiți mai multe site-uri pe un singur cont.',
                    content: `Un **Addon Domain** vă permite să găzduiți un domeniu complet nou (cu un site diferit) în cadrul aceluiași cont de găzduire. El funcționează ca un director separat. Îl creați din secțiunea **"Domains"** din cPanel, după ce ați schimbat name-server domeniului respectiv.`
                },
                {
                    id: 23, category: 'cpanel', title: 'Ghid de utilizare a instrumentului de optimizare a site-ului (Optimize Website)',
                    snippet: 'Cum puteți comprima conținutul site-ului (Gzip) direct din cPanel pentru a accelera încărcarea.',
                    content: `Instrumentul **"Optimize Website"** permite activarea compresiei **Gzip** pentru fișierele HTML, CSS și JS. Acest lucru reduce dramatic timpul de transfer al datelor, îmbunătățind scorul PageSpeed și experiența utilizatorilor.`
                },
                {
                    id: 24, category: 'facturare', title: 'Pot solicita restituirea banilor (garanția de 30 de zile)',
                    snippet: 'Detaliile complete despre politica de rambursare de 30 de zile pentru găzduirea web.',
                    content: `Oferim o garanție de rambursare a banilor de 30 de zile pentru pachetele de găzduire web noi. Pentru a solicita rambursarea, vă rugăm să deschideți un ticket în termen de 30 de zile de la data achiziției. **Atenție:** Garanția nu acoperă înregistrarea domeniilor sau licențele software.`
                },
                {
                    id: 25, category: 'securitate', title: 'Înțelegerea jurnalelor de acces (Access Logs)',
                    snippet: 'Cum să monitorizați traficul site-ului și să identificați vizitatorii și roboții cu ajutorul log-urilor de acces.',
                    content: `Jurnalele de acces înregistrează fiecare solicitare HTTP către serverul dumneavoastră. Le puteți folosi pentru a vedea ce IP-uri vă accesează site-ul, ce fișiere sunt solicitate și ce erori 404 apar. Sunt accesibile via FTP sau prin cPanel (Raw Access Logs).`
                },
            ];

            // 2. Elemente DOM
            const articlesContainer = document.getElementById('articles-container');
            const searchInput = document.getElementById('search-input');
            const categoryButtons = document.querySelectorAll('.category-btn');
            const noResultsMessage = document.getElementById('no-results-message');

            // Elemente modal
            const modalOverlay = document.getElementById('article-modal');
            const closeModalBtn = document.getElementById('close-modal-btn');
            const modalTitle = document.getElementById('modal-title');
            const modalCategory = document.getElementById('modal-category');
            const modalBody = document.getElementById('modal-body');

            let currentFilter = 'toate';
            const categoryMap = {
                'toate': 'Toate articolele',
                'gazduire': 'Găzduire web',
                'cpanel': 'cPanel & Tools',
                'domenii': 'Domenii & DNS',
                'securitate': 'Securitate & SSL',
                'facturare': 'Plăți & Facturare',
                'email': 'Conturi Email'
            };

            // 3. Funcția de afișare și generare HTML
            const renderArticles = (filteredArticles) => {
                articlesContainer.innerHTML = '';

                if (filteredArticles.length === 0) {
                    noResultsMessage.classList.remove('hidden');
                    return;
                }
                noResultsMessage.classList.add('hidden');

                filteredArticles.forEach(article => {
                    const articleDiv = document.createElement('div');
                    articleDiv.classList.add('article-item', 'block');
                    articleDiv.setAttribute('data-article-id', article.id); // Adaugă ID-ul
                    articleDiv.innerHTML = `
                        <div class="flex items-start">
                            <i data-lucide="book-open-text" class="w-5 h-5 text-sentra-cyan mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="article-title text-lg">${article.title}</h4>
                                <p class="text-gray-500 text-sm mt-1">${article.snippet}</p>
                            </div>
                        </div>
                    `;
                    // Adaugă event listener pentru deschiderea modalului
                    articleDiv.addEventListener('click', () => openArticleModal(article.id));

                    articlesContainer.appendChild(articleDiv);
                });
                lucide.createIcons(); // Re-render Lucide icons
            };

            // 4. Logica modal (open/close)
            const openArticleModal = (articleId) => {
                const article = articles.find(a => a.id === articleId);
                if (!article) return;

                modalTitle.textContent = article.title;
                modalCategory.textContent = categoryMap[article.category] || 'General';
                modalBody.innerHTML = article.content; // Injectează conținutul detaliat

                modalOverlay.classList.add('open');
                document.body.style.overflow = 'hidden'; // Blochează scroll-ul pe fundal
                lucide.createIcons(); // Re-render pentru iconițele din modal
            };

            const closeArticleModal = () => {
                modalOverlay.classList.remove('open');
                document.body.style.overflow = ''; // Permite scroll-ul pe fundal
            };

            closeModalBtn.addEventListener('click', closeArticleModal);
            modalOverlay.addEventListener('click', (e) => {
                // Închide modalul doar dacă se dă click pe overlay (nu pe conținut)
                if (e.target.id === 'article-modal') {
                    closeArticleModal();
                }
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modalOverlay.classList.contains('open')) {
                    closeArticleModal();
                }
            });


            // 5. Logica de filtrare și căutare
            categoryButtons.forEach(button => {
                button.addEventListener('click', () => {
                    categoryButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    currentFilter = button.getAttribute('data-category');
                    searchInput.value = '';

                    filterAndSearch();
                });
            });

            const filterAndSearch = () => {
                const searchTerm = searchInput.value.toLowerCase().trim();

                const results = articles.filter(article => {
                    const matchesCategory = currentFilter === 'toate' || article.category === currentFilter;

                    if (!matchesCategory) return false;
                    if (searchTerm === '') return true;

                    // Căutarea în titlu, snippet, conținut și cuvinte cheie
                    const searchString = (article.title + ' ' + article.snippet + ' ' + article.content + ' ' + article.keywords.join(' ')).toLowerCase();
                    return searchString.includes(searchTerm);
                });

                renderArticles(results);
            };

            searchInput.addEventListener('input', filterAndSearch);

            // Inițializează pagina
            filterAndSearch();
        });
    </script>
    <script>
    // Functie helper pentru a deschide/inchide elemente prin toggle 'hidden'
    const toggleVisibility = (elementId) => {
        document.getElementById(elementId).classList.toggle('hidden');
    };

    // 1. Toggling Meniul Principal Mobile (Hamburger Icon)
    document.getElementById('mobile-menu-button').addEventListener('click', () => {
        toggleVisibility('mobile-menu');
    });

    // 2. Toggling Dropdown-ul Domenii in Meniul Mobile
    document.getElementById('mobile-domain-toggle').addEventListener('click', () => {
        toggleVisibility('mobile-domain-menu');
    });

    // 3. Toggling Dropdown-ul Suport in Meniul Mobile
    document.getElementById('mobile-support-toggle').addEventListener('click', () => {
        toggleVisibility('mobile-support-menu');
    });
</script>
</body>

</html>