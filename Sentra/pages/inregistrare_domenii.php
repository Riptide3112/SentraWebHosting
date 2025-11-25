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
        <!-- Secțiunea principală de verificare domeniu -->
        <section class="py-16 md:py-24 bg-gray-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

                <h1 class="text-4xl md:text-6xl font-extrabold text-[#1A1A1A] mb-4 leading-tight">
                    Verificare <span class="text-sentra-cyan">domeniu web</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Verifică disponibilitatea și securizează-ți numele pe internet în doar câteva secunde.
                </p>

                <!-- Formular de verificare domeniu -->
                <form action="verificare-domeniu.php" method="GET"
                    class="flex flex-col md:flex-row gap-3 bg-white p-2 rounded-full shadow-xl hosting-switch-container border border-gray-200/50 mx-auto max-w-3xl">
                    <input type="text" name="domain" placeholder="Introdu domeniul dorit (ex: site-ulmeu)" required
                        class="flex-grow p-4 md:py-3 md:px-6 text-lg rounded-full md:rounded-l-full md:rounded-r-none border-2 border-transparent focus:border-sentra-cyan focus:outline-none transition duration-300">

                    <select name="tld"
                        class="p-4 md:py-3 md:px-2 text-lg rounded-full md:rounded-none md:w-auto w-full border-2 border-transparent bg-white focus:border-sentra-cyan focus:outline-none transition duration-300">
                        <option value=".ro">.ro</option>
                        <option value=".com">.com</option>
                        <option value=".eu">.eu</option>
                        <option value=".net">.net</option>
                        <option value=".org">.org</option>
                        <option value=".online">.online</option>
                    </select>

                    <button type="submit"
                        class="btn-primary flex items-center justify-center text-lg px-8 py-3 rounded-full md:rounded-r-full md:rounded-l-none font-semibold whitespace-nowrap">
                        Verifică <i data-lucide="search" class="w-5 h-5 ml-2"></i>
                    </button>
                </form>

                <div class="mt-6 text-lg font-semibold text-[#1A1A1A]">
                    Domeniul <span class="text-sentra-cyan">.ro</span> de la <span
                        class="text-xl text-red-600 font-extrabold">3.38 €</span> (cu TVA)
                </div>

            </div>
        </section>

        <!-- Secțiunea promoțională cu discount -->
        <section class="py-12 bg-gray-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="p-6 md:p-8 bg-gray-100 border border-sentra-cyan rounded-xl shadow-sm transition duration-300 hover:shadow-md">
                    <p class="text-lg font-medium text-gray-800 leading-relaxed text-center">
                        În fiecare vineri, beneficiezi de o reducere de <span
                            class="font-bold text-sentra-cyan">72%</span> la domeniile noi <span
                            class="font-bold text-sentra-cyan">.eu</span> înregistrate prin <span
                            class="font-bold text-sentra-cyan">Sentra</span> folosind codul <span
                            class="font-extrabold text-[#1A1A1A] bg-gray-200 p-1 rounded-md ml-1 border border-gray-300 whitespace-nowrap">Sentra31</span>.
                        Discountul se aplică doar pentru primul an.
                    </p>
                </div>
            </div>
        </section>

        <!-- Secțiunea beneficii -->
        <section class="py-20 md:py-32 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <h2 class="text-3xl md:text-4xl font-extrabold text-center text-[#1A1A1A] mb-12">
                    De ce să alegi <span class="text-sentra-cyan">Sentra</span> pentru domeniul tău?
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    <!-- Beneficiu 1: Viteză -->
                    <div class="flex flex-col items-center text-center p-6">
                        <div class="text-sentra-cyan mb-4">
                            <i data-lucide="zap" class="w-10 h-10"></i>
                        </div>
                        <p class="text-lg text-gray-700">
                            <span class="font-bold">Introdu domeniul web rapid și ușor</span> în motorul nostru de
                            căutare – vei găsi numele dorit… cu viteza luminii!
                        </p>
                    </div>

                    <!-- Beneficiu 2: Securitate -->
                    <div class="flex flex-col items-center text-center p-6">
                        <div class="text-sentra-cyan mb-4">
                            <i data-lucide="shield-check" class="w-10 h-10"></i>
                        </div>
                        <p class="text-lg text-gray-700">
                            <span class="font-bold">Înregistrarea domeniului la <span
                                    class="text-sentra-cyan">Sentra</span></span> îți oferă siguranță în
                            utilizare și o rezistență sporită la atacuri.
                        </p>
                    </div>

                    <!-- Beneficiu 3: Audit gratuit -->
                    <div class="flex flex-col items-center text-center p-6">
                        <div class="text-sentra-cyan mb-4">
                            <i data-lucide="clipboard-list" class="w-10 h-10"></i>
                        </div>
                        <p class="text-lg text-gray-700">
                            Oferim un <span class="font-bold">audit gratuit</span> pentru domeniul web sub care îți vei
                            lansa noua pagină.
                        </p>
                    </div>

                </div>

                <div class="text-center mt-16">
                    <a href="preturi-domenii.php"
                        class="text-lg text-gray-600 font-semibold hover:text-sentra-cyan hover:underline group inline-flex items-center transition duration-300">
                        Vezi cât costă domeniul tău
                        <i data-lucide="arrow-right"
                            class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                    </a>
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