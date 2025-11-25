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
                <a href="../index.php" class="flex-shrink-0 flex items-center">
                    <span class="text-3xl font-extrabold text-[#1A1A1A]">Sentra</span>
                    <div class="w-2 h-2 ml-1 sentra-cyan rounded-full"></div>
                </a>

                <nav class="hidden md:flex space-x-8">
                    <a href="gazduire_web.php"
                        class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">Găzduire
                        Web</a>
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
                        class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Înregistrare
                        domenii</a>
                    <a href="transfer_domenii.php"
                        class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Transfer
                        domenii</a>
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
                        class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Bază
                        de cunoștințe</a>
                    <a href="ticket.php"
                        class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Deschide
                        un Ticket</a>
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

        <!-- Secțiunea hero VPS -->
        <section id="hero" class="py-20 md:py-32 bg-gray-50">
            <div class="container mx-auto px-4 text-center max-w-4xl">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight text-gray-800">
                    Găzduire VPS cu <span class="text-sentra-cyan">Resurse garantate</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-600 opacity-90 mb-8">
                    Soluția perfectă când shared hosting-ul nu mai face față. Obțineți resurse dedicate, performanță
                    predictibilă și control complet la nivel de server.
                </p>
                <a href="#plans"
                    class="inline-block btn-primary font-bold text-lg py-4 px-10 rounded-full transition duration-300 transform hover:scale-[1.02]">
                    Alege serverul tău virtual
                </a>
                <p class="mt-6 text-sm text-gray-500 font-medium">
                    Perfect pentru magazine online, aplicații și trafic mare.
                </p>
            </div>
        </section>

        <!-- Secțiunea avantaje VPS -->
        <section id="avantaje" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-center text-[#1A1A1A] mb-12">
                    Putere dedicată, fără compromisuri
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">

                    <!-- Avantaj 1: Resurse garantate -->
                    <div class="p-6 rounded-xl shadow-lg border-t-4 border-sentra-cyan">
                        <i data-lucide="cpu" class="w-10 h-10 text-sentra-cyan mx-auto mb-4"></i>
                        <h3 class="text-xl font-bold mb-3 text-gray-800">Resurse 100% garantate</h3>
                        <p class="text-gray-600">RAM-ul și CPU-ul alocate serverului tău virtual îți aparțin exclusiv.
                            Fără „vecini zgomotoși” care să-ți afecteze performanța.</p>
                    </div>

                    <!-- Avantaj 2: Securitate -->
                    <div class="p-6 rounded-xl shadow-lg border-t-4 border-sentra-cyan">
                        <i data-lucide="shield" class="w-10 h-10 text-sentra-cyan mx-auto mb-4"></i>
                        <h3 class="text-xl font-bold mb-3 text-gray-800">Izolare și securitate</h3>
                        <p class="text-gray-600">Fiecare VPS rulează independent, oferind un strat superior de
                            securitate și izolare față de celelalte mașini virtuale de pe serverul fizic.</p>
                    </div>

                    <!-- Avantaj 3: Control root -->
                    <div class="p-6 rounded-xl shadow-lg border-t-4 border-sentra-cyan">
                        <i data-lucide="settings" class="w-10 h-10 text-sentra-cyan mx-auto mb-4"></i>
                        <h3 class="text-xl font-bold mb-3 text-gray-800">Control la nivel root</h3>
                        <p class="text-gray-600">Instalează orice software, configurează orice serviciu și ajustează
                            setările serverului exact cum ai nevoie. Libertate totală de configurare.</p>
                    </div>

                </div>
            </div>
        </section>

        <!-- Secțiunea planuri VPS -->
        <section id="plans" class="py-16 md:py-24 bg-gray-100">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-center text-[#1A1A1A] mb-12">
                    Alege planul tău de găzduire VPS
                </h2>
                <p class="text-center text-lg text-gray-600 mb-10">Toate planurile includ stocare rapidă NVMe, acces
                    root și lățime de bandă de 1 Gbps.</p>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                    <!-- Plan VPS Mini -->
                    <div
                        class="bg-white p-8 rounded-xl shadow-2xl flex flex-col transition duration-300 hover:shadow-cyan-400/50">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">VPS Mini</h3>
                        <p class="text-gray-500 mb-6">Ideal pentru testare și medii de dezvoltare.</p>
                        <div class="text-4xl font-extrabold text-sentra-cyan mb-6">
                            12.99€<span class="text-xl font-normal text-gray-500">/lună</span>
                        </div>
                        <ul class="space-y-3 text-gray-700 flex-grow mb-6">
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>1 vCPU core</li>
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>2 GB RAM dedicat</li>
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>50 GB stocare NVMe</li>
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>Trafic nelimitat</li>
                        </ul>
                        <a href="checkout.php?plan_id=VPS-MINI&plan_name=VPS Mini&amount=12.99&cycle=lunar&type=vps" 
                           class="mt-auto bg-gray-200 text-gray-800 w-full py-3 rounded-xl font-bold text-lg hover:bg-gray-300 transition duration-300 flex items-center justify-center">
                            Comandă 
                            <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                        </a>
                    </div>

                    <!-- Plan VPS Business (Popular) -->
                    <div
                        class="bg-white p-8 rounded-xl shadow-2xl flex flex-col ring-4 ring-sentra-cyan transition duration-300 transform scale-[1.05] relative">
                        <span
                            class="absolute top-0 right-0 bg-sentra-cyan text-white text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-xl">
                            Popular
                        </span>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">VPS Business</h3>
                        <p class="text-gray-500 mb-6">Recomandat pentru majoritatea site-urilor cu trafic mare.</p>
                        <div class="text-4xl font-extrabold text-sentra-cyan mb-6">
                            29.99€<span class="text-xl font-normal text-gray-500">/lună</span>
                        </div>
                        <ul class="space-y-3 text-gray-700 flex-grow mb-6">
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>4 vCPU cores</li>
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>8 GB RAM dedicat</li>
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>160 GB stocare NVMe</li>
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>Trafic nelimitat</li>
                        </ul>
                        <a href="checkout.php?plan_id=VPS-BUSINESS&plan_name=VPS Business&amount=29.99&cycle=lunar&type=vps" 
                           class="mt-auto btn-primary w-full py-3 rounded-xl font-bold text-lg flex items-center justify-center">
                            Comandă 
                            <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                        </a>
                    </div>

                    <!-- Plan VPS Pro -->
                    <div
                        class="bg-white p-8 rounded-xl shadow-2xl flex flex-col transition duration-300 hover:shadow-cyan-400/50">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">VPS Pro</h3>
                        <p class="text-gray-500 mb-6">Pentru magazine online complexe și aplicații solicitante.</p>
                        <div class="text-4xl font-extrabold text-sentra-cyan mb-6">
                            49.99€<span class="text-xl font-normal text-gray-500">/lună</span>
                        </div>
                        <ul class="space-y-3 text-gray-700 flex-grow mb-6">
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>8 vCPU cores</li>
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>16 GB RAM dedicat</li>
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>300 GB stocare NVMe</li>
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>Trafic nelimitat</li>
                        </ul>
                        <a href="checkout.php?plan_id=VPS-PRO&plan_name=VPS Pro&amount=49.99&cycle=lunar&type=vps" 
                           class="mt-auto bg-gray-200 text-gray-800 w-full py-3 rounded-xl font-bold text-lg hover:bg-gray-300 transition duration-300 flex items-center justify-center">
                            Comandă 
                            <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                        </a>
                    </div>

                    <!-- Plan VPS Enterprise -->
                    <div
                        class="bg-white p-8 rounded-xl shadow-2xl flex flex-col transition duration-300 hover:shadow-cyan-400/50">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">VPS Enterprise</h3>
                        <p class="text-gray-500 mb-6">Soluția maximă pentru servere de jocuri sau SaaS.</p>
                        <div class="text-4xl font-extrabold text-sentra-cyan mb-6">
                            79.99€<span class="text-xl font-normal text-gray-500">/lună</span>
                        </div>
                        <ul class="space-y-3 text-gray-700 flex-grow mb-6">
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>12 vCPU cores</li>
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>24 GB RAM dedicat</li>
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>500 GB stocare NVMe</li>
                            <li class="flex items-center"><i data-lucide="check"
                                    class="w-5 h-5 text-green-500 mr-2"></i>Trafic nelimitat</li>
                        </ul>
                        <a href="checkout.php?plan_id=VPS-ENTERPRISE&plan_name=VPS Enterprise&amount=79.99&cycle=lunar&type=vps" 
                           class="mt-auto bg-gray-200 text-gray-800 w-full py-3 rounded-xl font-bold text-lg hover:bg-gray-300 transition duration-300 flex items-center justify-center">
                            Comandă 
                            <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                        </a>
                    </div>

                </div>

                <p class="mt-12 text-center text-sm text-gray-500">
                    <i data-lucide="alert-triangle" class="w-4 h-4 inline mr-1 text-red-500"></i>
                    Notă: găzduirea VPS necesită cunoștințe minime de administrare a serverului.
                </p>
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

        // 2. Toggling Dropdown-ul Domenii in Meniul Mobile
        document.getElementById('mobile-domain-toggle').addEventListener('click', () => {
            toggleVisibility('mobile-domain-menu');
        });

        // 3. Toggling Dropdown-ul Suport in Meniul Mobile
        document.getElementById('mobile-support-toggle').addEventListener('click', () => {
            toggleVisibility('mobile-support-menu');
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