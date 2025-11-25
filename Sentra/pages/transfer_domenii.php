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
                            <a href="../client/ticket.php"
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
        <!-- Secțiunea principală de transfer domenii -->
        <section class="py-16 md:py-24 bg-gray-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

                <h1 class="text-4xl md:text-6xl font-extrabold text-[#1A1A1A] mb-4 leading-tight">
                    Transfer <span class="text-sentra-cyan">domeniu</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Te-ai săturat de gestionat domenii din multiple conturi ale unor furnizori și prețuri ce lasă de
                    dorit? Transferă-ți domeniul la noi! Asigurăm <strong>transfer rapid</strong>, <strong>gestionare
                        simplă</strong> și <strong>costuri competitive</strong>.
                </p>

                <!-- Formular de transfer domeniu -->
                <form action="initiaza-transfer.php" method="POST"
                    class="flex flex-col md:flex-row gap-3 bg-white p-2 rounded-full shadow-xl hosting-switch-container border border-gray-200/50 mx-auto max-w-3xl">
                    <input type="text" name="domain_transfer"
                        placeholder="Introdu domeniul de transferat (ex: site-ulmeu.ro)" required
                        class="flex-grow p-4 md:py-3 md:px-6 text-lg rounded-full md:rounded-l-full md:rounded-r-none border-2 border-transparent focus:border-sentra-cyan focus:outline-none transition duration-300">

                    <button type="submit"
                        class="btn-primary flex items-center justify-center text-lg px-8 py-3 rounded-full md:rounded-r-full md:rounded-l-none font-semibold whitespace-nowrap">
                        Mai departe <i data-lucide="chevron-right" class="w-5 h-5 ml-2"></i>
                    </button>
                </form>

            </div>
        </section>

        <!-- Secțiunea beneficii transfer -->
        <section class="py-20 md:py-32 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <h2 class="text-3xl md:text-4xl font-extrabold text-center text-[#1A1A1A] mb-12">
                    De ce merită să-ți transferi domeniul la <span class="text-sentra-cyan">Sentra</span>?
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    <!-- Beneficiu 1: Suport rapid -->
                    <div
                        class="flex flex-col p-6 bg-gray-50 border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition duration-300">
                        <div class="text-sentra-cyan mb-4">
                            <i data-lucide="messages-square" class="w-10 h-10"></i>
                        </div>
                        <p class="text-lg text-gray-700 mb-6 flex-grow">
                            Echipa noastră de <strong>suport va rezolva rapid</strong> orice problemă legată de
                            transferul domeniului tău. Tot ce trebuie să faci este să completezi formularul de contact,
                            iar noi îți vom oferi o soluție în câteva minute!
                        </p>
                        <a href="inregistrare_domenii.php"
                            class="text-gray-600 font-semibold hover:text-sentra-cyan hover:underline group inline-flex items-center transition duration-300 mt-auto">
                            Verifică disponibilitatea unui nou domeniu
                            <i data-lucide="arrow-right"
                                class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                        </a>
                    </div>

                    <!-- Beneficiu 2: SEO îmbunătățit -->
                    <div
                        class="flex flex-col p-6 bg-gray-50 border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition duration-300">
                        <div class="text-sentra-cyan mb-4">
                            <i data-lucide="bar-chart-3" class="w-10 h-10"></i>
                        </div>
                        <p class="text-lg text-gray-700 mb-6 flex-grow">
                            <strong>Poziții mai bune în Google</strong>, sunt mai probabile atunci când site-ul
                            îndeplinește criteriile de calitate și performanță. Un audit gratuit pentru domeniul tău te
                            va ajuta să identifici rapid ce elemente pot fi îmbunătățite.
                        </p>
                        <a href="preturi-domenii.php"
                            class="text-gray-600 font-semibold hover:text-sentra-cyan hover:underline group inline-flex items-center transition duration-300 mt-auto">
                            Compară prețurile domeniilor
                            <i data-lucide="arrow-right"
                                class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                        </a>
                    </div>

                    <!-- Beneficiu 3: Economii -->
                    <div
                        class="flex flex-col p-6 bg-gray-50 border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition duration-300">
                        <div class="text-sentra-cyan mb-4">
                            <i data-lucide="wallet" class="w-10 h-10"></i>
                        </div>
                        <p class="text-lg text-gray-700 mb-6 flex-grow">
                            Poți vedea singur <strong>economiile obținute</strong> când transferi un domeniu la Sentra.
                            În multe cazuri, economiile pot ajunge la 30% pe an pentru fiecare domeniu transferat.
                        </p>
                        <a href="../pages/bazacunostinte.php"
                            class="text-gray-600 font-semibold hover:text-sentra-cyan hover:underline group inline-flex items-center transition duration-300 mt-auto">
                            Ai suport gratis la fiecare transfer
                            <i data-lucide="arrow-right"
                                class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                        </a>
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

        // 2. Toggling Dropdown-ul Domenii in Meniul Mobile
        document.getElementById('mobile-domain-toggle').addEventListener('click', () => {
            toggleVisibility('mobile-domain-menu');
        });

        // 3. Toggling Dropdown-ul Suport in Meniul Mobile
        document.getElementById('mobile-support-toggle').addEventListener('click', () => {
            toggleVisibility('mobile-support-menu');
        });
    </script>
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>