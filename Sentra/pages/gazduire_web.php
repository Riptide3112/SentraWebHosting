<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra - hosting de nouă generație</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>
    
    <style>
        /* Stiluri principale - păstrate din original */
        body {
            font-family: 'Inter', sans-serif;
        }

        .sentra-cyan { background-color: #00BFB2; }
        .text-sentra-cyan { color: #00BFB2; }
        .border-sentra-cyan { border-color: #00BFB2; }

        /* Butoane stocare - păstrate din original */
        .storage-button.active {
            background-color: rgba(0, 191, 178, 0.9);
            color: white;
            box-shadow: 0 8px 20px rgba(0, 191, 178, 0.5);
            border-color: #00BFB2;
            transform: scale(1.02);
        }

        .storage-button:not(.active) {
            background-color: white;
            color: #4A5568;
            border: 2px solid #CBD5E0;
            transition: all 0.3s ease;
        }

        /* Panouri active - păstrate din original */
        .panel-nvme.active-panel,
        .panel-ssd.active-panel {
            border-left: 5px solid #00BFB2;
        }

        /* Blocuri domenii - păstrate din original */
        .domain-block {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .domain-block:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        /* Uniformizare text pentru consistență */
        .content-text {
            font-size: 1.125rem;
            line-height: 1.7;
            color: #4B5563;
        }
    </style>
</head>

<body class="antialiased min-h-screen">

    <!-- Header actualizat -->
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
                        <a href="ticket.php"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Deschide
                            un Ticket</a>
                    </div>
                </div>
            </nav>

            <div class="hidden md:flex items-center space-x-4">
                <a href="dashboard.php"
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
                <a href="ticket.php"
                    class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Deschide un Ticket</a>
            </div>
        </div>
        
        <div class="pt-4 pb-3 border-t border-gray-200">
            <a href="dashboard.php"
                class="block mx-4 sentra-cyan text-white px-4 py-2 rounded-lg text-base font-semibold text-center shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5">
                Cont Client
            </a>
        </div>
    </div>
</header>

    <main>
        <!-- Secțiunea hero - conținut original păstrat -->
        <section class="pt-20 pb-16 md:pt-28 md:pb-20 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-4xl md:text-5xl font-extrabold text-[#1A1A1A] mb-4 leading-tight">
                    Ghidul tău interactiv: De ce viteza de stocare îți face sau îți distruge site-ul.
                </h1>
                <p class="content-text mb-6">
                    În lumea găzduirii web, nu toate stocările sunt create egal. Diferența dintre un site lent și unul rapid se reduce adesea la o singură literă: <strong>N</strong> din <strong>NVMe</strong>. Iată cum să alegi corect, în funcție de nevoile reale ale proiectului tău.
                </p>
                <div class="flex items-center text-sm text-gray-500">
                    <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                    <span>Publicat: 2 octombrie 2025</span>
                </div>
            </div>
        </section>

        <!-- Secțiunea comparare tehnologii - conținut complet păstrat -->
        <section class="py-16 bg-gray-50 border-t border-b border-gray-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h3 class="text-3xl md:text-4xl font-extrabold text-[#1A1A1A] mb-4">
                        1. Compară tehnologiile de stocare
                    </h3>
                    <p class="content-text">
                        Apasă pe opțiunea de mai jos pentru a înțelege exact ce înseamnă fiecare și cum îți afectează performanța site-ului.
                    </p>
                </div>

                <!-- Switch Container - Butoane Uniforme și CONTURATE, FOARTE mari -->
                <div id="storage-switch" class="flex justify-center mb-12">
                    <div class="flex bg-gray-100 border border-gray-300 rounded-2xl p-2 shadow-2xl space-x-2">
                        <button data-storage-type="ssd" class="storage-button w-64 px-10 py-5 text-2xl font-extrabold rounded-xl transition duration-300 hover:opacity-90">
                            SSD (SATA)
                        </button>
                        <button data-storage-type="nvme" class="storage-button w-64 px-10 py-5 text-2xl font-extrabold rounded-xl transition duration-300 hover:opacity-90">
                            NVMe (PCIe)
                        </button>
                    </div>
                </div>

                <!-- Panouri de conținut (ACUM PE TOATĂ LĂȚIMEA) -->
                <div class="flex flex-col gap-8 content-text">

                    <!-- Panou SSD (SATA) - Se va întinde pe w-full -->
                    <div id="ssd-content" class="panel-ssd w-full p-4 md:p-6 transition duration-500" style="display: none;">
                        <h4 class="text-3xl font-extrabold text-[#1A1A1A] mb-4 flex items-center">
                            <i data-lucide="hard-drive" class="w-8 h-8 mr-3 text-sentra-cyan"></i>
                            SSD-ul clasic: acces rapid, dar cu limite de scalare
                        </h4>
                        <p class="mb-4">
                            SSD-urile pe interfață SATA reprezintă o bază excelentă și sunt de peste 5 ori mai rapide decât vechile HDD-uri. Sunt ideale pentru site-uri mici, personale sau bloguri cu trafic previzibil. Totuși, interfața <strong>SATA III</strong> limitează viteza de transfer la maximum <strong>~550 MB/s</strong>. În condiții de trafic intens și operațiuni multiple pe baza de date (precum pe un magazin online), această limită poate crește <em>latența</em>.
                        </p>
                        <p class="font-bold text-sentra-cyan">
                            RECOMANDARE: Suficient pentru proiecte personale, bloguri mici sau site-uri statice. Pentru scalabilitate sau E-commerce, consultați opțiunea NVMe.
                        </p>
                    </div>

                    <!-- Panou NVMe (PCIe) - Se va întinde pe w-full -->
                    <div id="nvme-content" class="panel-nvme active-panel w-full p-4 md:p-6 transition duration-500">
                        <h4 class="text-3xl font-extrabold text-[#1A1A1A] mb-4 flex items-center">
                            <i data-lucide="zap" class="w-8 h-8 mr-3 text-sentra-cyan"></i>
                            NVMe: performanța de top fără compromis
                        </h4>
                        <p class="mb-4">
                            NVMe utilizează magistrala <strong>PCI Express (PCIe)</strong>, comunicând direct cu procesorul. Aceasta oferă viteze de citire/scriere de <strong>până la 7.000 MB/s</strong> (de 10-14 ori mai rapid). Beneficiul major este performanța <strong>IOPS (Operațiuni pe Secundă)</strong>, care este uriașă. Astfel, baza de date poate procesa mii de cereri simultane, asigurând un timp de răspuns instantaneu.
                        </p>
                        <p class="font-bold text-sentra-cyan">
                            AVANTAJ: <strong>TTFB excelent</strong> (sub 100ms) și capacitatea de a susține vârfuri masive de trafic și tranzacții critice, esențial pentru E-commerce.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CONTINUAREA ARTICOLULUI (Fluid, stil blog) - conținut complet păstrat -->
        <section class="py-20 md:py-28 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

                <h2 class="text-3xl md:text-4xl font-extrabold text-[#1A1A1A] mb-8">
                    1.1. Cerințele reale: ce resurse îți trebuie
                </h2>

                <p class="content-text mb-10">
                    Alegerea stocării (SSD sau NVMe) nu este singura decizie. Trebuie să asiguri și resurse suficiente (RAM și CPU) pentru a susține aplicația. Mai jos, vezi cum arată cerințele pentru fiecare tip de site. (Conținutul se filtrează automat, bazat pe alegerea ta de mai sus)
                </p>

                <!-- BLOCURI DE CONȚINUT FILTRATE EXTINSE -->
                <div class="space-y-12" id="content-container">

                    <!-- Bloc 1: Site de Prezentare (Mic/Local) - SSD -->
                    <div class="content-block p-8 bg-gray-50 rounded-xl border border-gray-200 shadow-lg" data-requirement="ssd">
                        <h4 class="text-2xl font-bold text-sentra-cyan mb-3 flex items-center"><i data-lucide="building-2" class="w-6 h-6 mr-3"></i> Site de prezentare local (trafic foarte redus)</h4>
                        <p class="content-text mb-4">
                            Pentru o firmă mică, un PFA sau un site vitrină, un <strong>SSD</strong> este de cele mai multe ori suficient. Viteza este decentă, iar volumul mic de date și trafic nu justifică neapărat costul suplimentar al NVMe-ului.
                        </p>
                        <ul class="list-disc list-inside space-y-2 content-text pl-6 border-l-4 border-gray-400">
                            <li><strong>Resurse tipice:</strong> găzduire shared economică (1 GB RAM, 1 core CPU).</li>
                            <li><strong>Recomandare stocare:</strong> SSD (SATA). Minim necesar și eficient din punct de vedere al costului.</li>
                        </ul>
                    </div>

                    <!-- Bloc 2: Blog sau Portofoliu - MINIM SSD -->
                    <div class="content-block p-8 bg-gray-50 rounded-xl border border-gray-200 shadow-lg" data-requirement="ssd">
                        <h4 class="text-2xl font-bold text-sentra-cyan mb-3 flex items-center"><i data-lucide="rss" class="w-6 h-6 mr-3"></i> Blog personal sau portofoliu (trafic redus/mediu)</h4>
                        <p class="content-text mb-4">
                            Aceste site-uri beneficiază enorm de pe urma unui <strong>NVMe</strong> pentru încărcarea rapidă a bazei de date și a imaginilor. Dacă folosești stocare SATA, așteaptă-te la întârzieri vizibile la încărcare în perioadele de vârf de trafic sau la administrare (dashboard).
                        </p>
                        <ul class="list-disc list-inside space-y-2 content-text pl-6 border-l-4 border-gray-400">
                            <li><strong>Resurse tipice:</strong> găzduire shared standard (1-2 GB RAM, 1-2 core CPU).</li>
                            <li><strong>Recomandare stocare:</strong> NVMe este esențial pentru viteză constantă. SSD-ul SATA este minimul absolut.</li>
                        </ul>
                    </div>

                    <!-- Bloc 3: Magazin Online - NECESITĂ NVMe -->
                    <div class="content-block p-8 bg-gray-50 rounded-xl border border-gray-200 shadow-lg" data-requirement="nvme">
                        <h4 class="text-2xl font-bold text-sentra-cyan mb-3 flex items-center"><i data-lucide="shopping-cart" class="w-6 h-6 mr-3"></i> Magazin online (E-commerce - WooCommerce, PrestaShop)</h4>
                        <p class="content-text mb-4">
                            <strong>E-commerce-ul nu poate funcționa pe stocare lentă!</strong> Fiecare tranzacție este o operațiune I/O critică. O bază de date lentă (SATA) înseamnă erori de checkout și clienți frustrați. <strong>NVMe-ul este OBLIGATORIU</strong> pentru a gestiona operațiunile intense.
                        </p>
                        <ul class="list-disc list-inside space-y-2 content-text pl-6 border-l-4 border-sentra-cyan">
                            <li><strong>Resurse tipice:</strong> găzduire business sau VPS (4+ GB RAM, 4+ core CPU).</li>
                            <li><strong>Recomandare stocare:</strong> <strong>NVMe OBLIGATORIU</strong>. Cere IOPS mare.</li>
                        </ul>
                    </div>

                    <!-- Bloc 4: Aplicații Web Complexe - NECESITĂ NVMe -->
                    <div class="content-block p-8 bg-gray-50 rounded-xl border border-gray-200 shadow-lg" data-requirement="nvme">
                        <h4 class="text-2xl font-bold text-sentra-cyan mb-3 flex items-center"><i data-lucide="server" class="w-6 h-6 mr-3"></i> Aplicații custom (SaaS, API-uri, forumuri mari)</h4>
                        <p class="content-text mb-4">
                            Acestea necesită de obicei un mediu de rulare izolat (VPS) și un timp de răspuns API de ordinul milisecundelor. NVMe oferă latența minimă necesară pentru a asigura scalabilitatea și performanța constantă.
                        </p>
                        <ul class="list-disc list-inside space-y-2 content-text pl-6 border-l-4 border-sentra-cyan">
                            <li><strong>Resurse tipice:</strong> VPS sau cloud hosting. Acces root.</li>
                            <li><strong>Recomandare stocare:</strong> NVMe. Vitală pentru latența scăzută a API-urilor.</li>
                        </ul>
                    </div>

                    <!-- Bloc 5: Platforme de Învățare/Streaming - NECESITĂ NVMe (Trafic și I/O masive) -->
                    <div class="content-block p-8 bg-gray-50 rounded-xl border border-gray-200 shadow-lg" data-requirement="nvme">
                        <h4 class="text-2xl font-bold text-sentra-cyan mb-3 flex items-center"><i data-lucide="graduation-cap" class="w-6 h-6 mr-3"></i> Platforme eLearning / streaming (trafic masiv)</h4>
                        <p class="content-text mb-4">
                            Aceste sisteme (Moodle, platforme de cursuri) generează un număr imens de citiri/scriere pe disc (accesarea cursurilor, înregistrarea progresului). Latența NVMe este crucială pentru a gestiona fluid simultan sute de utilizatori activi.
                        </p>
                        <ul class="list-disc list-inside space-y-2 content-text pl-6 border-l-4 border-sentra-cyan">
                            <li><strong>Resurse tipice:</strong> VPS sau dedicat (8+ GB RAM, 6+ core CPU).</li>
                            <li><strong>Recomandare stocare:</strong> NVMe. Necesară pentru IOPS mare și sarcini de citire/scriere concurente.</li>
                        </ul>
                    </div>

                </div>

                <!-- SECȚIUNEA NOUĂ: DOMENII (TLD-uri) -->
                <hr class="my-20 border-gray-300">

                <h2 class="text-3xl md:text-4xl font-extrabold text-[#1A1A1A] mb-8">
                    2. Alege extensia de domeniu potrivită (TLD)
                </h2>
                <p class="content-text mb-10">
                    Extensia domeniului (ceea ce vine după punct) definește identitatea și aria ta geografică. Iată o comparație a celor mai populare TLD-uri:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                    <!-- .RO -->
                    <div class="domain-block p-6 bg-white rounded-xl border-t-4 border-[#FF4B4B] shadow-lg">
                        <h4 class="font-extrabold text-3xl text-[#FF4B4B] mb-2 flex items-center">.RO</h4>
                        <p class="text-lg font-semibold text-gray-800 mb-2">Domeniul național</p>
                        <p class="text-sm text-gray-600">
                            <strong>Ideal pentru:</strong> afaceri care operează exclusiv sau preponderent în <strong>România</strong>. Semnalează clar motoarelor de căutare (Google) că audiența țintă este cea locală.
                        </p>
                    </div>

                    <!-- .COM -->
                    <div class="domain-block p-6 bg-white rounded-xl border-t-4 border-[#4CAF50] shadow-lg">
                        <h4 class="font-extrabold text-3xl text-[#4CAF50] mb-2 flex items-center">.COM</h4>
                        <p class="text-lg font-semibold text-gray-800 mb-2">Comercial global</p>
                        <p class="text-sm text-gray-600">
                            <strong>Ideal pentru:</strong> orice tip de afacere cu planuri de <strong>expansiune internațională</strong> sau care se adresează unei audiențe globale. Este cel mai recunoscut TLD din lume.
                        </p>
                    </div>

                    <!-- .NET -->
                    <div class="domain-block p-6 bg-white rounded-xl border-t-4 border-[#00BCD4] shadow-lg">
                        <h4 class="font-extrabold text-3xl text-[#00BCD4] mb-2 flex items-center">.NET</h4>
                        <p class="text-lg font-semibold text-gray-800 mb-2">Network (tehnologie/servicii)</p>
                        <p class="text-sm text-gray-600">
                            <strong>Ideal pentru:</strong> companii de <strong>tehnologie</strong>, furnizori de servicii, rețele sau aplicații. Este adesea folosit ca alternativă la .com când acesta este deja ocupat.
                        </p>
                    </div>

                    <!-- .ORG -->
                    <div class="domain-block p-6 bg-white rounded-xl border-t-4 border-[#FFC107] shadow-lg">
                        <h4 class="font-extrabold text-3xl text-[#FFC107] mb-2 flex items-center">.ORG</h4>
                        <p class="text-lg font-semibold text-gray-800 mb-2">Organizații (non-profit)</p>
                        <p class="text-sm text-gray-600">
                            <strong>Ideal pentru:</strong> <strong>organizații non-profit</strong>, asociații, proiecte comunitare, muzee sau instituții care nu vând produse, ci oferă informații.
                        </p>
                    </div>

                    <!-- .EU -->
                    <div class="domain-block p-6 bg-white rounded-xl border-t-4 border-[#1976D2] shadow-lg">
                        <h4 class="font-extrabold text-3xl text-[#1976D2] mb-2 flex items-center">.EU</h4>
                        <p class="text-lg font-semibold text-gray-800 mb-2">Uniunea Europeană</p>
                        <p class="text-sm text-gray-600">
                            <strong>Ideal pentru:</strong> companii care operează în <strong>mai multe țări din UE</strong> sau care doresc să își sublinieze identitatea europeană. Necesită o prezență legală în UE/SEE.
                        </p>
                    </div>

                    <!-- .ONLINE -->
                    <div class="domain-block p-6 bg-white rounded-xl border-t-4 border-[#FF5722] shadow-lg">
                        <h4 class="font-extrabold text-3xl text-[#FF5722] mb-2 flex items-center">.ONLINE</h4>
                        <p class="text-lg font-semibold text-gray-800 mb-2">Prezență generală (modern)</p>
                        <p class="text-sm text-gray-600">
                            <strong>Ideal pentru:</strong> bloguri, portofolii, startup-uri sau afaceri noi care doresc un nume de domeniu ușor de reținut și modern. Are o disponibilitate excelentă a numelui dorit.
                        </p>
                    </div>

                </div>

                <!-- SECȚIUNEA DE ÎNCHEIERE ȘI ELEMENTE CRITICE -->
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#1A1A1A] mt-20 mb-8">
                    Dincolo de viteză. Securitatea și suportul.
                </h2>
                <p class="content-text mb-10">
                    Chiar și cea mai rapidă găzduire devine inutilă fără un suport tehnic solid și o securitate de neclintit. Nu uita de acești trei piloni esențiali:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 content-text">
                    <div class="p-6 bg-sentra-cyan/10 rounded-xl border border-sentra-cyan/50 shadow-sm">
                        <h4 class="font-bold text-lg text-sentra-cyan mb-2 flex items-center"><i data-lucide="lock" class="w-5 h-5 mr-2"></i> Protecție DDoS L7 și WAF</h4>
                        <p class="text-sm">
                            Asigură-te că furnizorul oferă protecție avansată <strong>DDoS (nivelul 7 - aplicație)</strong>, care blochează atacurile complexe specifice site-urilor. Un <strong>WAF (Web Application Firewall)</strong> activ este obligatoriu.
                        </p>
                    </div>
                    <div class="p-6 bg-sentra-cyan/10 rounded-xl border border-sentra-cyan/50 shadow-sm">
                        <h4 class="font-bold text-lg text-sentra-cyan mb-2 flex items-center"><i data-lucide="rotate-ccw" class="w-5 h-5 mr-2"></i> Backup zilnic off-site</h4>
                        <p class="text-sm">
                            Backup-ul trebuie să fie <strong>zilnic</strong> și stocat pe un server complet <strong>extern (off-site)</strong> față de cel principal. Solicită o retenție de minim <strong>14 zile</strong>.
                        </p>
                    </div>
                    <div class="p-6 bg-sentra-cyan/10 rounded-xl border border-sentra-cyan/50 shadow-sm">
                        <h4 class="font-bold text-lg text-sentra-cyan mb-2 flex items-center"><i data-lucide="life-buoy" class="w-5 h-5 mr-2"></i> Suport tehnic 24/7 de specialitate</h4>
                        <p class="text-sm">
                            Alege un furnizor cu suport real <strong>24/7/365</strong>, cu tehnicieni specializați în administrarea serverelor și optimizarea bazelor de date.
                        </p>
                    </div>
                </div>

            </div>
        </section>
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

        <!-- Script pentru funcționalități interactive - păstrat din original -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Inițializare iconițe
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

                // Configurare selector stocare
                const ssdButton = document.querySelector('[data-storage-type="ssd"]');
                const nvmeButton = document.querySelector('[data-storage-type="nvme"]');
                const ssdContent = document.getElementById('ssd-content');
                const nvmeContent = document.getElementById('nvme-content');
                const contentBlocks = document.querySelectorAll('.content-block');

                // Funcție pentru filtrare conținut
                function filterContent(requiredType) {
                    contentBlocks.forEach(item => {
                        const itemRequirement = item.getAttribute('data-requirement');
                        
                        if (requiredType === 'ssd') {
                            item.style.display = itemRequirement === 'ssd' ? 'block' : 'none';
                        } else {
                            item.style.display = 'block';
                        }
                    });

                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }

                // Funcție pentru setare stare activă
                function setActiveStorage(type) {
                    // Actualizare butoane
                    document.querySelectorAll('.storage-button').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    const activeButton = type === 'nvme' ? nvmeButton : ssdButton;
                    const activeContent = type === 'nvme' ? nvmeContent : ssdContent;
                    const inactiveContent = type === 'nvme' ? ssdContent : nvmeContent;

                    activeButton.classList.add('active');

                    // Actualizare panouri
                    nvmeContent.classList.remove('active-panel');
                    ssdContent.classList.remove('active-panel');
                    
                    activeContent.style.display = 'block';
                    activeContent.classList.add('active-panel');
                    inactiveContent.style.display = 'none';

                    // Filtrare conținut
                    filterContent(type);
                }

                // Stare inițială
                setActiveStorage('nvme');

                // Event listeners
                ssdButton.addEventListener('click', () => setActiveStorage('ssd'));
                nvmeButton.addEventListener('click', () => setActiveStorage('nvme'));
            });
        </script>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html> 