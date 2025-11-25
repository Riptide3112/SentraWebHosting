<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra - Hosting de Nouă Generație</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>
</head>

<body class="antialiased min-h-screen">

    <header class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="index.php" class="flex-shrink-0 flex items-center">
                <span class="text-3xl font-extrabold text-[#1A1A1A]">Sentra</span>
                <div class="w-2 h-2 ml-1 sentra-cyan rounded-full"></div>
            </a>

            <nav class="hidden md:flex space-x-8">
                <a href="pages/gazduire_web.php"
                    class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">Găzduire Web</a>
                <a href="pages/vps.php"
                    class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">VPS</a>

                <div class="relative group flex items-center">
                    <button id="domainDropdownButton" type="button"
                        class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300 focus:outline-none"
                        aria-expanded="false" aria-haspopup="true">
                        Domenii
                    </button>
                    <div id="domainDropdownMenu"
                        class="absolute left-1/2 transform -translate-x-1/2 mt-2 top-full w-48 bg-white border border-gray-100 rounded-lg shadow-xl py-1 z-50 invisible opacity-0 transition-all duration-300 ease-in-out group-hover:visible group-hover:opacity-100">
                        <a href="pages/inregistrare_domenii.php"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Înregistrare
                            domenii</a>
                        <a href="pages/transfer_domenii.php"
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
                        <a href="pages/bazacunostinte.php"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Bază
                            de cunoștințe</a>
                        <a href="client/ticket.php"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Deschide
                            un Ticket</a>
                    </div>
                </div>
            </nav>

            <div class="hidden md:flex items-center space-x-4">
                <a href="client/dashboard.php"
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
            
            <a href="pages/gazduire_web.php"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-sentra-cyan transition duration-300">
                Găzduire Web
            </a>
            
            <a href="pages/vps.php"
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
                <a href="pages/inregistrare_domenii.php"
                    class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Înregistrare domenii</a>
                <a href="pages/transfer_domenii.php"
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
                <a href="pages/bazacunostinte.php"
                    class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Bază de cunoștințe</a>
                <a href="client/ticket.php"
                    class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Deschide un Ticket</a>
            </div>
        </div>
        
        <div class="pt-4 pb-3 border-t border-gray-200">
            <a href="client/dashboard.php"
                class="block mx-4 sentra-cyan text-white px-4 py-2 rounded-lg text-base font-semibold text-center shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5">
                Cont Client
            </a>
        </div>
    </div>
</header>

    <main class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <div class="lg:col-span-6 text-center lg:text-left">
                    <p class="text-sm font-semibold tracking-wider text-sentra-cyan uppercase mb-3">
                        VITEZĂ • SECURITATE • SCALABILITATE
                    </p>
                    <h1 class="text-5xl md:text-6xl font-extrabold text-[#1A1A1A] leading-tight mb-6">
                        Hosting de <span class="text-sentra-cyan">Nouă Generație</span> pentru Proiectul Tău
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-xl mx-auto lg:mx-0">
                        Obține performanță maximă cu infrastructura Sentra. Găzduire optimizată pentru viteza de
                        încărcare și uptime 99.99%.
                    </p>

                    <div
                        class="flex flex-col sm:flex-row justify-center lg:justify-start space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="pages/register.php"
                            class="sentra-cyan text-white px-8 py-4 rounded-xl font-bold text-lg shadow-2xl shadow-[#00BFB2]/50 hover:shadow-[#00BFB2]/70 transition duration-300 transform hover:scale-[1.02]">
                            Creează Cont
                        </a>
                        <a href="#abonamente"
                            class="bg-white border border-gray-300 text-gray-800 px-8 py-4 rounded-xl font-medium text-lg hover:border-sentra-cyan hover:text-sentra-cyan transition duration-300">
                            Comandă Hosting
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-6 flex justify-center lg:justify-end">
                    <div
                        class="w-full max-w-md p-8 bg-white rounded-2xl card-shadow transform transition duration-500 hover:rotate-1">
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <span class="p-3 sentra-cyan rounded-full">
                                    <i data-lucide="zap" class="text-white w-6 h-6"></i>
                                </span>
                                <div>
                                    <h3 class="text-xl font-semibold text-[#1A1A1A]">Viteză Fără Compromis</h3>
                                    <p class="text-gray-600 text-sm">Servere ultra-rapide și SSD-uri NVMe.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <span class="p-3 sentra-cyan rounded-full">
                                    <i data-lucide="shield-check" class="text-white w-6 h-6"></i>
                                </span>
                                <div>
                                    <h3 class="text-xl font-semibold text-[#1A1A1A]">Securitate Avansată</h3>
                                    <p class="text-gray-600 text-sm">Protecție DDoS și certificate SSL gratuite.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <span class="p-3 sentra-cyan rounded-full">
                                    <i data-lucide="infinity" class="text-white w-6 h-6"></i>
                                </span>
                                <div>
                                    <h3 class="text-xl font-semibold text-[#1A1A1A]">Uptime Garantat</h3>
                                    <p class="text-gray-600 text-sm">Suntem online mereu, 99.99% garantat.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <section id="abonamente" class="py-20 md:py-32 bg-[#f8f8f8]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="text-sm font-semibold tracking-wider text-sentra-cyan uppercase mb-3">
                    ALEGE PLANUL PERFECT
                </p>
                <h2 class="text-4xl md:text-5xl font-extrabold text-[#1A1A1A] mb-4">
                    Alege dintre găzduire web cu stocare SSD sau stocare NVMe rapidă
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Toate planurile noastre vin cu certificare SSL, migrare asistată și suport 24/7.
                </p>
            </div>

            <div class="flex justify-center mb-12">
                <div class="flex p-2 hosting-switch-container rounded-full shadow-lg max-w-lg mx-auto"
                    id="hosting-switch">
                    <div id="ssd-option" class="py-3 px-6 rounded-full text-center cursor-pointer relative"
                        data-type="ssd">
                    </div>
                    <div id="nvme-option" class="py-3 px-6 rounded-full text-center cursor-pointer relative"
                        data-type="nvme">
                    </div>
                </div>
            </div>
            <div id="pricing-cards-container"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
            </div>

            <div class="flex justify-center mt-16">
                <div class="flex items-center space-x-6 text-xl">
                    <span id="tva-off-label"
                        class="text-gray-500 font-semibold cursor-pointer transition duration-300 hover:text-gray-700"
                        data-tva="false">
                        Prețuri fără TVA
                    </span>

                    <div id="tva-switch"
                        class="w-14 h-8 sentra-cyan rounded-full flex items-center p-1 cursor-pointer transition duration-300">
                        <div id="tva-switch-handle"
                            class="w-6 h-6 bg-white rounded-full shadow-md transform transition duration-300"></div>
                    </div>

                    <span id="tva-on-label" class="text-[#1A1A1A] font-bold cursor-pointer" data-tva="true">
                        Prețuri cu TVA (21%)
                    </span>
                </div>
            </div>
            <div class="text-center mt-8 max-w-4xl mx-auto text-sm text-gray-500">
                <p>
                    * Plata anuală include domeniu .RO gratuit în primul an. Reînnoirea domeniului se face la tariful
                    standard.
                    Reducerea de 44% se aplică o singură dată (6/12 luni), reînnoirea fiind la prețul standard.
                    Prețurile nu includ TVA 21%.
                </p>
            </div>
        </div>
    </section>

    <section class="py-20 md:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-extrabold text-[#1A1A1A] mb-4">
                    De Ce Să Alegi <span class="text-sentra-cyan">Sentra</span>?
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Descoperă cele patru avantaje majore care ne diferențiază pe piața de hosting.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-16 items-center mb-24">

                <div class="md:col-span-6 relative rounded-3xl overflow-hidden card-shadow bg-gray-50">
                    <div class="relative pt-[75%] md:pt-[100%]">
                        <img src="assets/images/performanta.png" alt="Performanță NVMe și Panou de Control cPanel"
                            class="absolute inset-0 w-full h-full object-cover transition duration-500 hover:scale-105"
                            onerror="this.onerror=null; this.src='https://placehold.co/800x600/00BFB2/ffffff?text=NVMe+cPanel';">
                    </div>
                </div>

                <div class="md:col-span-6 text-center md:text-left p-2">
                    <span class="inline-block text-sentra-cyan mb-4">
                        <i data-lucide="zap" class="w-8 h-8 benefit-icon"></i>
                    </span>
                    <h3 class="text-3xl lg:text-4xl font-extrabold text-[#1A1A1A] mb-4">
                        Performanță Extremă și Administrare Ușoară
                    </h3>
                    <p class="text-lg text-gray-600 mb-6">
                        Infrastructura noastră rulează exclusiv pe <span class="font-bold">discuri NVMe
                            ultra-rapide</span>, asigurând timpi de răspuns sub 50ms, esențiali pentru o experiență de
                        utilizare fluidă. Chiar și cu această putere, gestionarea rămâne simplă: ai acces la <span
                            class="font-bold">cPanel</span>, cel mai intuitiv și complet panou de control, perfect
                        pentru începători și experți deopotrivă.
                    </p>
                    <a href="#" class="text-sentra-cyan font-bold hover:underline group inline-flex items-center">
                        Vezi detalii despre infrastructură
                        <i data-lucide="arrow-right"
                            class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-16 items-center mb-24">

                <div class="md:col-span-6 text-center md:text-left order-2 md:order-1 p-2">
                    <span class="inline-block text-sentra-cyan mb-4">
                        <i data-lucide="message-square" class="w-8 h-8 benefit-icon"></i>
                    </span>
                    <h3 class="text-3xl lg:text-4xl font-extrabold text-[#1A1A1A] mb-4">
                        Suport Uman 24/7/365, Nu Doar Roboți
                    </h3>
                    <p class="text-lg text-gray-600 mb-6">
                        Când ai o problemă urgentă, ai nevoie de un răspuns imediat, nu de un *chatbot* sau o așteptare
                        de 24 de ore. Echipa noastră de experți este disponibilă <span class="font-bold">non-stop,
                            inclusiv în weekend-uri și sărbători</span>. Garantăm asistență tehnică rapidă și eficientă,
                        rezolvând orice incident pentru a menține site-ul tău online și operațional.
                    </p>
                    <a href="pages/contact.php"
                        class="text-sentra-cyan font-bold hover:underline group inline-flex items-center">
                        Contactează-ne acum
                        <i data-lucide="arrow-right"
                            class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>

                <div
                    class="md:col-span-6 relative rounded-3xl overflow-hidden card-shadow bg-gray-50 order-1 md:order-2">
                    <div class="relative pt-[75%] md:pt-[100%]">
                        <img src="assets/images/suport.png" alt="Suport Tehnic 24/7"
                            class="absolute inset-0 w-full h-full object-cover transition duration-500 hover:scale-105"
                            onerror="this.onerror=null; this.src='https://placehold.co/800x600/00BFB2/ffffff?text=24/7+Suport';">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-16 items-center mb-24">

                <div class="md:col-span-6 relative rounded-3xl overflow-hidden card-shadow bg-gray-50">
                    <div class="relative pt-[75%] md:pt-[100%]">
                        <img src="assets/images/clasare.png" alt="Optimizare pentru clasare superioară în Google"
                            class="absolute inset-0 w-full h-full object-cover transition duration-500 hover:scale-105"
                            onerror="this.onerror=null; this.src='https://placehold.co/800x600/00BFB2/ffffff?text=Google+Ranking';">
                    </div>
                </div>

                <div class="md:col-span-6 text-center md:text-left p-2">
                    <span class="inline-block text-sentra-cyan mb-4">
                        <i data-lucide="trending-up" class="w-8 h-8 benefit-icon"></i>
                    </span>
                    <h3 class="text-3xl lg:text-4xl font-extrabold text-[#1A1A1A] mb-4">
                        Vizibilitate Crescută: Mai Ușor de Găsit în Google
                    </h3>
                    <p class="text-lg text-gray-600 mb-6">
                        Viteza de încărcare nu înseamnă doar o experiență mai bună, ci și un factor <span
                            class="font-bold">major de clasare (SEO)</span> pentru Google. Găzduirea Sentra este
                        optimizată la nivel de server pentru a-ți oferi cel mai rapid timp de încărcare posibil, ajutând
                        site-ul tău să câștige în fața concurenței și să fie afișat pe poziții superioare în rezultatele
                        căutării.
                    </p>
                    <a href="#" class="text-sentra-cyan font-bold hover:underline group inline-flex items-center">
                        Află de ce viteza contează pentru SEO
                        <i data-lucide="arrow-right"
                            class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-16 items-center">

                <div class="md:col-span-6 text-center md:text-left order-2 md:order-1 p-2">
                    <span class="inline-block text-sentra-cyan mb-4">
                        <i data-lucide="rocket" class="w-8 h-8 benefit-icon"></i>
                    </span>
                    <h3 class="text-3xl lg:text-4xl font-extrabold text-[#1A1A1A] mb-4">
                        Lansare Rapidă și Migrare Gratuită, Fără Downtime
                    </h3>
                    <p class="text-lg text-gray-600 mb-6">
                        Începe rapid! Cu instrumentele noastre (precum Softaculous), poți instala platforme ca WordPress
                        în doar câteva clicuri. Dacă ai deja un site găzduit în altă parte, echipa Sentra îți oferă
                        <span class="font-bold">migrare completă, gratuită și asistată</span>, asigurându-se că trecerea
                        la noi se face fără întreruperi (zero *downtime*).
                    </p>
                    <a href="client/ticket.php"
                        class="text-sentra-cyan font-bold hover:underline group inline-flex items-center">
                        Solicită o migrare gratuită
                        <i data-lucide="arrow-right"
                            class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>

                <div
                    class="md:col-span-6 relative rounded-3xl overflow-hidden card-shadow bg-gray-50 order-1 md:order-2">
                    <div class="relative pt-[75%] md:pt-[100%]">
                        <img src="assets/images/migrare.png" alt="Lansare Site în Câteva Minute"
                            class="absolute inset-0 w-full h-full object-cover transition duration-500 hover:scale-105"
                            onerror="this.onerror=null; this.src='https://placehold.co/800x600/00BFB2/ffffff?text=Lansare+Rapida';">
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12">
                Ce Spun Clienții Noștri
            </h2>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper pb-10">

                    <div class="swiper-slide h-auto p-2">
                        <div
                            class="h-full bg-gray-50 p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 border-t-4 border-sentra-cyan">
                            <div class="flex items-center mb-4">
                                <div
                                    class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-xl font-bold text-gray-600 mr-4">
                                    <i data-lucide="edit" class="w-6 h-6 text-sentra-cyan"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-lg text-gray-900">Mihai Popescu</p>
                                    <p class="text-sm text-gray-500">Antreprenor Web</p>
                                </div>
                            </div>
                            <p class="text-gray-700 italic">
                                "Viteza site-ului meu a crescut remarcabil de când am trecut la Sentra. Suportul tehnic
                                este rapid și extrem de bine pregătit. Recomand cu încredere hostingul NVMe!"
                            </p>
                            <div class="flex mt-4">
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide h-auto p-2">
                        <div
                            class="h-full bg-plus-recommended p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 border-t-4 border-sentra-cyan">
                            <div class="flex items-center mb-4">
                                <div
                                    class="w-12 h-12 rounded-full bg-sentra-cyan flex items-center justify-center text-xl font-bold text-white mr-4">
                                    <i data-lucide="users" class="w-6 h-6 text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-lg text-gray-900">Andreea Ionescu</p>
                                    <p class="text-sm text-gray-500">Blogger & Creator de Conținut</p>
                                </div>
                            </div>
                            <p class="text-gray-700 italic">
                                "Cea mai bună alegere pentru blogul meu! Nu am avut niciodată downtime, iar interfața de
                                administrare este foarte ușor de folosit. Prețurile sunt competitive pentru calitatea
                                oferită."
                            </p>
                            <div class="flex mt-4">
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide h-auto p-2">
                        <div
                            class="h-full bg-gray-50 p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 border-t-4 border-sentra-cyan">
                            <div class="flex items-center mb-4">
                                <div
                                    class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-xl font-bold text-gray-600 mr-4">
                                    <i data-lucide="code" class="w-6 h-6 text-sentra-cyan"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-lg text-gray-900">Cristian Dinu</p>
                                    <p class="text-sm text-gray-500">Dezvoltator Software</p>
                                </div>
                            </div>
                            <p class="text-gray-700 italic">
                                "Folosesc Sentra pentru proiectele mele personale. Mă impresionează simplitatea și
                                performanța. Opțiunea de SSD a fost de ajuns pentru mine, excelent raport
                                calitate-preț."
                            </p>
                            <div class="flex mt-4">
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide h-auto p-2">
                        <div
                            class="h-full bg-gray-50 p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 border-t-4 border-sentra-cyan">
                            <div class="flex items-center mb-4">
                                <div
                                    class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-xl font-bold text-gray-600 mr-4">
                                    <i data-lucide="edit" class="w-6 h-6 text-sentra-cyan"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-lg text-gray-900">Mihai Popescu</p>
                                    <p class="text-sm text-gray-500">Antreprenor Web</p>
                                </div>
                            </div>
                            <p class="text-gray-700 italic">
                                "Viteza site-ului meu a crescut remarcabil de când am trecut la Sentra. Suportul tehnic
                                este rapid și extrem de bine pregătit. Recomand cu încredere hostingul NVMe!"
                            </p>
                            <div class="flex mt-4">
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide h-auto p-2">
                        <div
                            class="h-full bg-plus-recommended p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 border-t-4 border-sentra-cyan">
                            <div class="flex items-center mb-4">
                                <div
                                    class="w-12 h-12 rounded-full bg-sentra-cyan flex items-center justify-center text-xl font-bold text-white mr-4">
                                    <i data-lucide="users" class="w-6 h-6 text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-lg text-gray-900">Andreea Ionescu</p>
                                    <p class="text-sm text-gray-500">Blogger & Creator de Conținut</p>
                                </div>
                            </div>
                            <p class="text-gray-700 italic">
                                "Cea mai bună alegere pentru blogul meu! Nu am avut niciodată downtime, iar interfața de
                                administrare este foarte ușor de folosit. Prețurile sunt competitive pentru calitatea
                                oferită."
                            </p>
                            <div class="flex mt-4">
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide h-auto p-2">
                        <div
                            class="h-full bg-gray-50 p-6 rounded-xl shadow-lg hover:shadow-2xl transition duration-300 border-t-4 border-sentra-cyan">
                            <div class="flex items-center mb-4">
                                <div
                                    class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-xl font-bold text-gray-600 mr-4">
                                    <i data-lucide="code" class="w-6 h-6 text-sentra-cyan"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-lg text-gray-900">Cristian Dinu</p>
                                    <p class="text-sm text-gray-500">Dezvoltator Software</p>
                                </div>
                            </div>
                            <p class="text-gray-700 italic">
                                "Folosesc Sentra pentru proiectele mele personale. Mă impresionează simplitatea și
                                performanța. Opțiunea de SSD a fost de ajuns pentru mine, excelent raport
                                calitate-preț."
                            </p>
                            <div class="flex mt-4">
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279L12 18.896l-7.416 3.917 1.48-8.279L.001 9.306l8.332-1.151z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>

        </div>
    </section>
    <section id="faq" class="py-20 md:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="text-sm font-semibold tracking-wider text-sentra-cyan uppercase mb-3">
                    CLARIFICĂRI RAPIDE
                </p>
                <h2 class="text-4xl md:text-5xl font-extrabold text-[#1A1A1A]">
                    Întrebări Frecvente
                </h2>
                <p class="text-xl text-gray-600 mt-4 max-w-3xl mx-auto">
                    Dacă nu găsești răspunsul aici, contactează echipa noastră de suport 24/7.
                </p>
            </div>

            <div class="max-w-4xl mx-auto space-y-4">

                <div class="bg-gray-50 p-5 rounded-xl shadow-md border-l-4 border-sentra-cyan faq-item" data-index="1">
                    <button
                        class="w-full flex justify-between items-center text-left font-bold text-lg text-gray-800 focus:outline-none faq-question">
                        <span>Ce diferență există între Găzduirea SSD și NVMe?</span>
                        <i data-lucide="plus"
                            class="w-6 h-6 text-sentra-cyan faq-icon transition-transform duration-300"></i>
                    </button>
                    <div class="faq-answer max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                        <p class="pt-4 text-gray-600 border-t border-gray-200 mt-4">
                            <span class="font-semibold">SSD (Solid State Drive)</span> oferă viteză mult superioară
                            HDD-urilor clasice. <span class="font-semibold">NVMe (Non-Volatile Memory Express)</span>
                            este o tehnologie de stocare de ultimă generație, conectată direct la placa de bază, oferind
                            viteze de citire/scriere de până la 5 ori mai mari decât SSD-ul SATA standard. Recomandăm
                            NVMe pentru performanță maximă, esențială pentru SEO și aplicații complexe.
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl shadow-md border-l-4 border-sentra-cyan faq-item" data-index="2">
                    <button
                        class="w-full flex justify-between items-center text-left font-bold text-lg text-gray-800 focus:outline-none faq-question">
                        <span>Oferiți migrare gratuită de la alt provider?</span>
                        <i data-lucide="plus"
                            class="w-6 h-6 text-sentra-cyan faq-icon transition-transform duration-300"></i>
                    </button>
                    <div class="faq-answer max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                        <p class="pt-4 text-gray-600 border-t border-gray-200 mt-4">
                            Da, absolut! Echipa noastră oferă <span class="font-semibold">migrare completă, gratuită și
                                asistată</span> pentru site-ul tău. Ne ocupăm de transferul tuturor fișierelor și
                            bazelor de date, asigurând un proces fără întreruperi (zero downtime). Tot ce trebuie să
                            faci este să ne contactezi după plasarea comenzii.
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl shadow-md border-l-4 border-sentra-cyan faq-item" data-index="3">
                    <button
                        class="w-full flex justify-between items-center text-left font-bold text-lg text-gray-800 focus:outline-none faq-question">
                        <span>Ce panou de control folosiți pentru administrare?</span>
                        <i data-lucide="plus"
                            class="w-6 h-6 text-sentra-cyan faq-icon transition-transform duration-300"></i>
                    </button>
                    <div class="faq-answer max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                        <p class="pt-4 text-gray-600 border-t border-gray-200 mt-4">
                            Folosim <span class="font-semibold">cPanel</span>, cel mai popular și robust panou de
                            control din industrie. Acesta îți permite să gestionezi domenii, conturi de email, baze de
                            date, backup-uri și să instalezi aplicații (precum WordPress, Joomla) cu un singur click
                            (Softaculous).
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl shadow-md border-l-4 border-sentra-cyan faq-item" data-index="4">
                    <button
                        class="w-full flex justify-between items-center text-left font-bold text-lg text-gray-800 focus:outline-none faq-question">
                        <span>Este TVA-ul inclus în prețurile afișate?</span>
                        <i data-lucide="plus"
                            class="w-6 h-6 text-sentra-cyan faq-icon transition-transform duration-300"></i>
                    </button>
                    <div class="faq-answer max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                        <p class="pt-4 text-gray-600 border-t border-gray-200 mt-4">
                            Prețurile afișate inițial sunt <span class="font-semibold">fără TVA</span>. Poți folosi
                            comutatorul de sub planurile de prețuri pentru a vedea instantaneu prețurile finale care
                            includ TVA (21%). Clienții din UE înregistrați în scopuri de TVA pot beneficia de taxare
                            inversă.
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl shadow-md border-l-4 border-sentra-cyan faq-item" data-index="5">
                    <button
                        class="w-full flex justify-between items-center text-left font-bold text-lg text-gray-800 focus:outline-none faq-question">
                        <span>Ce garanție de uptime oferiți?</span>
                        <i data-lucide="plus"
                            class="w-6 h-6 text-sentra-cyan faq-icon transition-transform duration-300"></i>
                    </button>
                    <div class="faq-answer max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                        <p class="pt-4 text-gray-600 border-t border-gray-200 mt-4">
                            Oferim o garanție de <span class="font-semibold">99.9% Uptime</span> lunar. Infrastructura
                            noastră este monitorizată constant pentru a ne asigura că site-ul tău este online și
                            disponibil 24 de ore din 24, 7 zile din 7.
                        </p>
                    </div>
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

    <?php include 'pages/footer.php'; ?>
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="assets/js/script.js"></script>
</body>

</html>