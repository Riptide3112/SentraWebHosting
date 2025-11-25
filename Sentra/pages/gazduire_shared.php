<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra - hosting de nouă generație</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
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
                    <a href="gazduire_web.php"
                        class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">Găzduire
                        web</a>
                    <a href="vps.php"
                        class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">VPS</a>

                    <!-- Dropdown domenii (activare la hover) -->
                    <div class="relative group flex items-center">
                        <button id="domainDropdownButton" type="button"
                            class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300 focus:outline-none"
                            aria-expanded="false" aria-haspopup="true">
                            Domenii
                        </button>

                        <!-- Meniul dropdown: invisible + opacity-0 by default; group-hover activează deschiderea -->
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

                    <!-- Dropdown suport (activare la hover) -->
                    <div class="relative group flex items-center">
                        <button id="supportDropdownButton" type="button"
                            class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300 focus:outline-none"
                            aria-expanded="false" aria-haspopup="true">
                            Suport
                        </button>

                        <!-- Meniul dropdown: invisible + opacity-0 by default; group-hover activează deschiderea -->
                        <div id="supportDropdownMenu"
                            class="absolute left-1/2 transform -translate-x-1/2 mt-2 top-full w-48 bg-white border border-gray-100 rounded-lg shadow-xl py-1 z-50 invisible opacity-0 transition-all duration-300 ease-in-out group-hover:visible group-hover:opacity-100">
                            <a href="bazacunostinte.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Bază de cunoștințe</a>
                            <a href="ticket.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Deschide
                                un ticket</a>
                        </div>
                    </div>
                </nav>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="dashboard.php"
                        class="sentra-cyan text-white px-4 py-2 rounded-lg font-semibold shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5">
                        Cont client
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
    </header>

    <main class="min-h-screen bg-[#f8f8f8] font-['Inter']">

        <!-- Secțiunea 1: Titlu și propunerea de valoare (hero section) -->
        <section id="hero" class="py-20 md:py-32 bg-gray-50">
            <div class="container mx-auto px-4 text-center max-w-4xl">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight text-gray-800">
                    Găzduire shared <span class="text-sentra-cyan">rapidă și accesibilă</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-600 opacity-90 mb-8">
                    Soluția perfectă și cea mai ieftină pentru a-ți lansa blogul sau site-ul de prezentare. Obține
                    performanță de top, mentenanță zero și securitate inclusă.
                </p>
                <a href="#plans"
                    class="inline-block btn-primary font-bold text-lg py-4 px-10 rounded-full transition duration-300 transform hover:scale-[1.02]">
                    Alege planul tău
                </a>
                <p class="mt-6 text-sm text-gray-500 font-medium">
                    Garanție 30 de zile banii înapoi.
                </p>
            </div>
        </section>

        <!-- Secțiunea 2: Caracteristici cheie (beneficii) -->
        <section id="features" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 max-w-7xl">
                <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-12">
                    De ce să ne alegi pe noi?
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">

                    <!-- Grup 1: Performanță și viteză -->
                    <div class="p-6 bg-white rounded-xl shadow-lg border-t-4 border-sentra-cyan">
                        <h3 class="text-xl font-bold text-sentra-cyan mb-4">Performanță și viteză</h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <span class="nvme-dot mr-2 mt-2 flex-shrink-0"></span>
                                <span class="font-semibold text-gray-900">Stocare NVMe ultra-rapidă</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span class="font-semibold text-gray-900">Uptime 99.9% garantat</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span class="font-semibold text-gray-900">CDN inclus (gratuit)</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Grup 2: Securitate și protecție -->
                    <div class="p-6 bg-white rounded-xl shadow-lg border-t-4 border-sentra-cyan">
                        <h3 class="text-xl font-bold text-sentra-cyan mb-4">Securitate și protecție</h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span class="font-semibold text-gray-900">Certificat SSL gratuit</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span class="font-semibold text-gray-900">Protecție anti-DDoS</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span class="font-semibold text-gray-900">Scanare malware zilnică</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Grup 3: Ușurință în utilizare -->
                    <div class="p-6 bg-white rounded-xl shadow-lg border-t-4 border-sentra-cyan">
                        <h3 class="text-xl font-bold text-sentra-cyan mb-4">Ușurință în utilizare</h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span class="font-semibold text-gray-900">Panou de control cPanel</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span class="font-semibold text-gray-900">Instalare 1-click</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span class="font-semibold text-gray-900">Migrare gratuită</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Grup 4: Suport tehnic -->
                    <div class="p-6 bg-white rounded-xl shadow-lg border-t-4 border-sentra-cyan">
                        <h3 class="text-xl font-bold text-sentra-cyan mb-4">Suport tehnic</h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span class="font-semibold text-gray-900">Suport tehnic 24/7</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span class="font-semibold text-gray-900">Asistență promptă</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span class="font-semibold text-gray-900">Chat, email și telefon</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Secțiunea 3: Comparația planurilor (tabel de prețuri) -->
        <section id="plans" class="py-16 md:py-24 bg-gray-100">
            <div class="container mx-auto px-4 max-w-6xl">
                <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-12">
                    Comparația planurilor noastre
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Plan Basic -->
                    <div class="bg-white p-8 rounded-xl shadow-2xl flex flex-col transition duration-300 hover:shadow-cyan-400/50">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Basic</h3>
                        <p class="text-gray-500 mb-6">Un singur site, perfect pentru început.</p>
                        <div class="text-4xl font-extrabold text-sentra-cyan mb-6">
                            2.99€<span class="text-xl font-normal text-gray-500">/lună</span>
                        </div>
                        <ul class="space-y-3 text-gray-700 flex-grow mb-6">
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>10 GB SSD Storage</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>1 Website</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>100 GB Traffic</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>5 Conturi Email</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>1 GB RAM</li>
                        </ul>
                        <a href="checkout.php?plan_id=BASIC&plan_name=Basic&amount=2.99&cycle=lunar&type=shared" 
                           class="mt-auto bg-gray-200 text-gray-800 w-full py-3 rounded-xl font-bold text-lg hover:bg-gray-300 transition duration-300 flex items-center justify-center">
                            Comandă 
                            <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                        </a>
                    </div>

                    <!-- Plan Standard (Popular) -->
                    <div class="bg-white p-8 rounded-xl shadow-2xl flex flex-col ring-4 ring-sentra-cyan transition duration-300 transform scale-[1.05] relative">
                        <span class="absolute top-0 right-0 bg-sentra-cyan text-white text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-xl">
                            Popular
                        </span>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Standard</h3>
                        <p class="text-gray-500 mb-6">Ideal pentru business-uri mici și multiple proiecte.</p>
                        <div class="text-4xl font-extrabold text-sentra-cyan mb-6">
                            4.99€<span class="text-xl font-normal text-gray-500">/lună</span>
                        </div>
                        <ul class="space-y-3 text-gray-700 flex-grow mb-6">
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>25 GB SSD Storage</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>5 Website-uri</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>300 GB Traffic</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>20 Conturi Email</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>2 GB RAM</li>
                        </ul>
                        <a href="checkout.php?plan_id=STANDARD&plan_name=Standard&amount=4.99&cycle=lunar&type=shared" 
                           class="mt-auto btn-primary w-full py-3 rounded-xl font-bold text-lg flex items-center justify-center">
                            Comandă 
                            <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                        </a>
                    </div>

                    <!-- Plan Pro -->
                    <div class="bg-white p-8 rounded-xl shadow-2xl flex flex-col transition duration-300 hover:shadow-cyan-400/50">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Pro</h3>
                        <p class="text-gray-500 mb-6">Putere maximă pentru proiecte mari sau agenții.</p>
                        <div class="text-4xl font-extrabold text-sentra-cyan mb-6">
                            9.99€<span class="text-xl font-normal text-gray-500">/lună</span>
                        </div>
                        <ul class="space-y-3 text-gray-700 flex-grow mb-6">
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>50 GB SSD Storage</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>Website-uri Nelimitate</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>Trafic Nelimitat</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>Conturi Email Nelimitate</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>4 GB RAM</li>
                        </ul>
                        <a href="checkout.php?plan_id=PRO&plan_name=Pro&amount=9.99&cycle=lunar&type=shared" 
                           class="mt-auto bg-gray-200 text-gray-800 w-full py-3 rounded-xl font-bold text-lg hover:bg-gray-300 transition duration-300 flex items-center justify-center">
                            Comandă 
                            <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Elementul de export a fost eliminat -->
            </div>
        </section>

        <!-- Secțiunea 4: Detalii tehnice aprofundate (specs) -->
        <section id="tech-specs" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 max-w-4xl">
                <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-10">
                    Detalii tehnice aprofundate
                </h2>

                <div class="bg-gray-50 p-8 rounded-xl shadow-lg space-y-6">
                    <!-- Spec 1: Versiuni PHP -->
                    <div class="border-b border-gray-200 pb-3">
                        <h3 class="text-xl font-semibold text-gray-800">Versiuni PHP suportate</h3>
                        <p class="text-gray-700 mt-1">
                            PHP 7.4, 8.0, 8.1, 8.2. <span class="font-medium text-sentra-cyan">(Selector de versiuni
                                disponibil în cPanel)</span>.
                        </p>
                    </div>

                    <!-- Spec 2: Backup automat -->
                    <div class="border-b border-gray-200 pb-3">
                        <h3 class="text-xl font-semibold text-gray-800">Backup automat</h3>
                        <p class="text-gray-700 mt-1">
                            Backup-uri zilnice complete, cu restaurare simplă din panoul de control.
                        </p>
                    </div>

                    <!-- Spec 3: Acces și protocoale -->
                    <div class="border-b border-gray-200 pb-3">
                        <h3 class="text-xl font-semibold text-gray-800">Acces și protocoale</h3>
                        <p class="text-gray-700 mt-1">
                            Acces FTP/SFTP și SSH.
                        </p>
                    </div>

                    <!-- Spec 4: Localizare data center -->
                    <div class="border-b border-gray-200 pb-3">
                        <h3 class="text-xl font-semibold text-gray-800">Localizare data center</h3>
                        <p class="text-gray-700 mt-1">
                            Mențiune despre locația principală (ex: România, Germania).
                        </p>
                    </div>

                    <!-- Spec 5: Suport tehnologii -->
                    <div class="pb-3">
                        <h3 class="text-xl font-semibold text-gray-800">Suport tehnologii</h3>
                        <p class="text-gray-700 mt-1">
                            Suport Perl, Python, Node.js și Cron Jobs.
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php include 'footer.php'; ?>
    <script>
        // Functie helper pentru a deschide/inchide elemente prin toggle 'hidden'
        const toggleVisibility = (elementId) => {
            document.getElementById(elementId).classList.toggle('hidden');
        };

        // 1. Toggling Meniul Principal Mobile (Hamburger Icon)
        document.getElementById('mobile-menu-button').addEventListener('click', () => {
            toggleVisibility('mobile-menu');
        });

        // --------------------------------------------------------------------------------
        // LOGICA DROPDOWN GENERALĂ (Domenii & Suport)
        // --------------------------------------------------------------------------------
        
        /**
         * Setează funcționalitatea de dropdown pentru un buton și un meniu specific.
         * @param {string} buttonId - ID-ul butonului.
         * @param {string} menuId - ID-ul meniului.
         */
        function setupDropdown(buttonId, menuId) {
            const button = document.getElementById(buttonId);
            const menu = document.getElementById(menuId);

            if (button && menu) {
                // Asigură-te că meniul începe invizibil (dacă îi lipsesc clasele din HTML)
                if (!menu.classList.contains('invisible')) {
                    menu.classList.add('invisible', 'opacity-0');
                }
                
                const toggleDropdown = (isOpening) => {
                    if (isOpening) {
                        // Închide toate celelalte dropdown-uri înainte de a-l deschide pe acesta
                        closeAllDropdowns(buttonId);
                        
                        menu.classList.remove('invisible', 'opacity-0');
                        menu.classList.add('visible', 'opacity-100');
                        button.setAttribute('aria-expanded', 'true');
                    } else {
                        menu.classList.remove('visible', 'opacity-100');
                        menu.classList.add('invisible', 'opacity-0');
                        button.setAttribute('aria-expanded', 'false');
                    }
                };
                
                // Logica pentru închiderea celorlalte meniuri
                function closeAllDropdowns(excludeButtonId) {
                     // Listează ID-urile tuturor butoanelor/meniurilor dropdown pe care le gestionezi
                    const dropdowns = [
                        { button: 'domainDropdownButton', menu: 'domainDropdownMenu' },
                        { button: 'supportDropdownButton', menu: 'supportDropdownMenu' }
                    ];
                    
                    dropdowns.forEach(({ button: otherButtonId, menu: otherMenuId }) => {
                        if (otherButtonId !== excludeButtonId) {
                            const otherMenu = document.getElementById(otherMenuId);
                            const otherButton = document.getElementById(otherButtonId);
                            if (otherMenu && otherMenu.classList.contains('visible')) {
                                otherMenu.classList.remove('visible', 'opacity-100');
                                otherMenu.classList.add('invisible', 'opacity-0');
                                if (otherButton) otherButton.setAttribute('aria-expanded', 'false');
                            }
                        }
                    });
                }

                button.addEventListener('click', (event) => {
                    event.preventDefault(); 
                    event.stopPropagation(); 
                    const isExpanded = button.getAttribute('aria-expanded') === 'true';
                    toggleDropdown(!isExpanded);
                });

                document.addEventListener('click', (event) => {
                    if (menu.classList.contains('visible') && !menu.contains(event.target) && !button.contains(event.target)) {
                        toggleDropdown(false);
                    }
                });
            }
        }
        
        // Inițializarea dropdown-urilor
        setupDropdown('domainDropdownButton', 'domainDropdownMenu');
        setupDropdown('supportDropdownButton', 'supportDropdownMenu');

        // Re-creare iconițe după ce s-au actualizat butoanele
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
    </script>
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>