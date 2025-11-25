<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra - Hosting de Nouă Generație</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>
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
                        Web</a>
                    <a href="vps.php"
                        class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300">VPS</a>

                    <!-- DROPDOWN DOMENII (Activare la HOVER) -->
                    <!-- Clasa group pe părinte și flex pentru a menține zona de hover activă -->
                    <div class="relative group flex items-center">
                        <button id="domainDropdownButton" type="button"
                            class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300 focus:outline-none"
                            aria-expanded="false" aria-haspopup="true">
                            Domenii
                        </button>

                        <!-- Meniul Dropdown: invisible + opacity-0 by default; group-hover activează deschiderea -->
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

                    <!-- DROPDOWN SUPORT (Activare la HOVER) -->
                    <div class="relative group flex items-center">
                        <button id="supportDropdownButton" type="button"
                            class="text-gray-700 hover:text-sentra-cyan font-medium transition duration-300 focus:outline-none"
                            aria-expanded="false" aria-haspopup="true">
                            Suport
                        </button>

                        <!-- Meniul Dropdown: invisible + opacity-0 by default; group-hover activează deschiderea -->
                        <div id="supportDropdownMenu"
                            class="absolute left-1/2 transform -translate-x-1/2 mt-2 top-full w-48 bg-white border border-gray-100 rounded-lg shadow-xl py-1 z-50 invisible opacity-0 transition-all duration-300 ease-in-out group-hover:visible group-hover:opacity-100">
                            <a href="suport.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#1A1A1A] transition duration-150">Întrebări
                                frecvente</a>
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
    </header>

    <main>

        <section id="hero-domaine" class="py-16 md:py-24 bg-white text-center">
            <div class="container mx-auto px-4 max-w-5xl">
                <h1 class="text-4xl md:text-5xl font-extrabold mb-10 text-gray-800">
                    Lista prețurilor domeniilor
                </h1>

                <div class="flex flex-wrap justify-center gap-4">

                    <a href="#functionale"
                        class="px-6 py-3 rounded-full text-lg font-semibold bg-gray-100 text-gray-700 hover:bg-sentra-cyan hover:text-white transition duration-200 shadow-md">
                        Funcționale
                    </a>

                    <a href="#globale"
                        class="px-6 py-3 rounded-full text-lg font-semibold bg-gray-100 text-gray-700 hover:bg-sentra-cyan hover:text-white transition duration-200 shadow-md">
                        Globale
                    </a>

                    <a href="#ntld"
                        class="px-6 py-3 rounded-full text-lg font-semibold bg-gray-100 text-gray-700 hover:bg-sentra-cyan hover:text-white transition duration-200 shadow-md">
                        nTLD
                    </a>

                    <a href="#nationale"
                        class="px-6 py-3 rounded-full text-lg font-semibold bg-gray-100 text-gray-700 hover:bg-sentra-cyan hover:text-white transition duration-200 shadow-md">
                        Naționale
                    </a>

                </div>
                <p class="mt-8 text-sm text-gray-500">
                    Folosește filtrele rapide de mai sus pentru a naviga între categorii.
                    TVA-ul de 21% este deja inclus in prețuri.
                </p>
            </div>
        </section>

        <section id="popular-domains" class="py-16 md:py-24 bg-gray-50">
            <div class="container mx-auto px-4 max-w-5xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-center text-[#1A1A1A] mb-12">
                    Cele mai populare domenii
                </h2>

                <div class="overflow-x-auto bg-white rounded-xl shadow-2xl">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Domeniu
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț primul an
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Transfer
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț reînnoire
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț recuperare
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-gray-900">
                                    <span class="text-sm font-normal text-gray-500 mr-2">Național</span>
                                    <strong class="text-sentra-cyan">.ro</strong>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">7,85 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-gray-900">
                                    <span class="text-sm font-normal text-gray-500 mr-2">Funcțional</span>
                                    <strong class="text-sentra-cyan">.com.ro</strong>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                            <tr class="bg-plus-recommended shadow-inner border-t-2 border-b-2 border-sentra-cyan">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-gray-900">
                                    <span class="text-sm font-normal text-red-500 mr-2">Global / POPULAR</span>
                                    <strong class="text-gray-900">.com</strong>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xl text-red-600 font-extrabold">16,82 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">16,82 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">16,82 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-gray-900">
                                    <span class="text-sm font-normal text-gray-500 mr-2">European</span>
                                    <strong class="text-sentra-cyan">.eu</strong>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">6,66 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">5,45 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">6,66 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">36,30 €</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <p class="mt-10 text-center text-sm text-gray-500">
                    Toate prețurile sunt exprimate în EUR și nu includ TVA. Prețul de recuperare (redemption) se aplică
                    după perioada de grație.
                </p>
            </div>
        </section>

        <section id="functionale" class="py-16 bg-white">
            <div class="container mx-auto px-4 max-w-5xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-800 border-b pb-4 mb-8 text-sentra-cyan">
                    Domenii Funcționale
                </h2>
                <p class="text-gray-600 mb-6">
                    Domeniile funcționale sunt folosite pentru a indica scopul site-ului sub un domeniu național (.ro)
                    și au prețuri uniforme.
                </p>

                <div class="overflow-x-auto bg-white rounded-xl shadow-2xl">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Domeniu
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț primul an
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Transfer
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț reînnoire
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț recuperare
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.com.ro
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.nt.ro</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.org.ro
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.nom.ro
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.info.ro
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.rec.ro
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.arts.ro
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.store.ro
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.www.ro
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.firm.ro
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.tm.ro</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="globale" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4 max-w-5xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-800 border-b pb-4 mb-8 text-sentra-cyan">
                    Domenii Globale
                </h2>
                <p class="text-gray-600 mb-6">
                    Cele mai populare și recunoscute extensii la nivel mondial, esențiale pentru vizibilitatea
                    internațională a afacerii tale.
                </p>

                <div class="overflow-x-auto bg-white rounded-xl shadow-2xl">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Domeniu
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț primul an
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Transfer
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț reînnoire
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț recuperare
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-gray-900">.com</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">16,82 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">16,82 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">16,82 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.net</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">18,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">18,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">18,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.biz</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">24,08 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">24,08 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">24,08 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.eu</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">6,66 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">5,45 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">6,66 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">36,30 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.info</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">30,24 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">30,24 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">30,24 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.org</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">18,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">18,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">18,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="ntld" class="py-16 bg-white">
            <div class="container mx-auto px-4 max-w-5xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-800 border-b pb-4 mb-8 text-sentra-cyan">
                    New Top-Level Domains (nTLD)
                </h2>
                <p class="text-gray-600 mb-6">
                    Extensii de domeniu moderne, specifice industriei sau scopului (de ex. `.store`, `.app`). Oferă mai
                    multă libertate de alegere.
                </p>

                <div class="overflow-x-auto bg-white rounded-xl shadow-2xl">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Domeniu
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț primul an
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Transfer
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț reînnoire
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț recuperare
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.business
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">61,71 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">61,71 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">61,71 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.city</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">34,97 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">34,97 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">34,97 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.gratis
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">35,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">35,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">35,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.news</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">59,29 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">59,29 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">59,29 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.online
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">67,76 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">67,76 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">67,76 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.site</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">3,51 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">181,38 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">48,28 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.space</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">59,29 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">59,29 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">59,29 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.tech</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">90,75 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">90,75 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">90,75 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.website
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">53,24 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">53,24 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">53,24 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.work</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">22,99 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">22,99 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">22,99 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.mobi</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">71,39 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">71,39 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">71,39 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.pro</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">37,51 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">37,51 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">37,51 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">205,70 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.tel</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">35,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">35,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">35,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.agency
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">42,35 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">42,35 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">42,35 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.boutique
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">45,86 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">45,86 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">45,86 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.buzz</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">59,29 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">59,29 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">59,29 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.club</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">45,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">45,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">45,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.company
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">62,92 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">62,92 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">62,92 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.domains
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">59,29 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">59,29 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">59,29 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.email</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">41,14 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">41,14 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">41,14 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.events
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">55,66 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">55,66 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">55,66 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.house</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">56,87 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">56,87 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">56,87 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.marketing
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">54,45 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">54,45 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">54,45 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.report
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">35,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">35,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">35,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.services
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">53,24 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">53,24 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">53,24 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.shoes</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">78,65 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">78,65 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">78,65 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.social
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">52,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">52,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">52,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.technology
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">43,56 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">43,56 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">43,56 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.today</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">41,14 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">41,14 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">41,14 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.vacations
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">52,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">52,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">52,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.wiki</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">42,35 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">42,35 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">42,35 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.xyz</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">32,67 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">32,67 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">32,67 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="nationale" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4 max-w-6xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-800 border-b pb-4 mb-8 text-sentra-cyan">
                    Domenii Naționale (ccTLD)
                </h2>
                <p class="text-gray-600 mb-6">
                    Extensii de domenii specifice fiecărei țări. Vă rugăm să rețineți că unele necesită documente sau
                    prezență locală pentru înregistrare.
                </p>

                <div class="overflow-x-auto bg-white rounded-xl shadow-2xl">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Domeniu
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț primul an
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Transfer
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț reînnoire
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    Preț recuperare
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.at</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">16,94 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">16,94 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">16,94 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">59,29 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.be</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">18,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">18,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">18,03 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">59,29 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.bg</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">156,09 €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">156,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">71,39 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.bz</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">21,78 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">21,78 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">21,78 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.ca</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">19,24 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">19,24 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">19,24 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.ch</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">24,20 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">24,20 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">24,20 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.co</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">61,71 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">61,71 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">61,71 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.cr</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">181,50 €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">181,50 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">181,50 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">254,10 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.cz</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">43,56 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">43,56 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">43,56 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.de</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">54,45 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.dk</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">35,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">47,19 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">35,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">59,29 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.es</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">10,77 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">10,77 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">10,77 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.fi</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">50,82 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">50,82 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">50,82 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.fr</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">19,95 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">19,95 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">19,95 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.gg</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">278,30 €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">278,30 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">278,30 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.hr</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">398,09 €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">398,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">398,09 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.ie</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">119,79 €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">119,79 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">119,79 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.in</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">27,71 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">27,71 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">27,71 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.it</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.lt</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">47,19 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">47,19 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">59,29 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.mc</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">192,39 €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">192,39 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">192,39 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.md</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">223,85 €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">223,85 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">223,85 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.nl</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">36,18 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">42,23 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">36,18 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.no</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">87,12 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">87,12 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">87,12 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.pl</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">32,55 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">32,55 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">32,55 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.pt</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">67,64 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">67,64 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">67,64 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-gray-900"><strong
                                        class="text-sentra-cyan">.ro</strong> (România)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">7,85 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">12,10 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.sa</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">143,99 €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">143,99 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">143,99 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.se</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">71,39 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">71,39 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">71,39 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.sk</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">71,39 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">71,39 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">71,39 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">-</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.tv</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">38,72 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">38,72 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">38,72 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.uk</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">14,52 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">14,52 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">14,52 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.co.uk</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">11,98 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.us</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">14,40 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">14,40 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">14,40 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                            <tr class="hover:bg-plus-recommended">
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-medium text-sentra-cyan">.ws</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800 font-semibold">41,14 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">41,14 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">41,14 €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-800">96,80 €</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <p class="mt-10 text-center text-sm text-gray-500">
                    Toate prețurile sunt exprimate în EUR și nu includ TVA. Prețul de recuperare (redemption) se aplică
                    după perioada de grație.
                </p>
            </div>
        </section>

    </main>

    <?php include 'footer.php'; ?>
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="js/script.js"></script>
</body>

</html>