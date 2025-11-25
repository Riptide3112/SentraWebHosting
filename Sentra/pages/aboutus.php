<?php
// aboutus.php
// Pagina "Despre Noi" - Prezintă serviciile și valorile companiei Sentra
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Despre noi - Sentra</title>

    <!-- Resources -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        /* Variabile CSS pentru consistență */
        :root {
            --sentra-cyan: #00BFB2;
            --text-dark: #1A1A1A;
            --text-gray: #333;
            --bg-light: #f8f8f8;
            --white: #ffffff;
        }

        /* Stiluri de bază */
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .sentra-cyan {
            background-color: var(--sentra-cyan);
        }

        .text-sentra-cyan {
            color: var(--sentra-cyan);
        }

        .border-sentra-cyan {
            border-color: var(--sentra-cyan);
        }

        /* Header */
        header {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        /* Container principal */
        .main-content {
            width: 100%;
            max-width: 1200px;
            padding: 3rem 1.5rem 6rem;
            margin: 0 auto;
            flex-grow: 1;
        }

        /* Card-uri pentru secțiuni */
        .about-card {
            background-color: var(--white);
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            border-left: 6px solid var(--sentra-cyan);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .about-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
        }

        /* Text styling */
        .text-section {
            color: var(--text-gray);
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

        /* Icon styling */
        .section-icon {
            width: 1.5rem;
            height: 1.5rem;
            margin-right: 0.75rem;
        }

        /* Footer styling */
        .full-width-footer {
            width: 100%;
            margin-top: auto;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 2rem 1rem 4rem;
            }

            .about-card {
                padding: 1.5rem;
            }

            h2 {
                font-size: 2.5rem;
            }
        }
    </style>
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
    </header>

    <!-- Conținut principal -->
    <main class="main-content">
        <h2 class="text-5xl font-extrabold mb-16 text-center text-[#1A1A1A] pt-8">
            <span class="text-sentra-cyan">Sentra:</span> Arhitectura viitorului digital
        </h2>

        <div class="space-y-10 max-w-4xl mx-auto">
            <!-- Secțiunea 1: Viziune -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i class="section-icon" data-lucide="sparkles"></i>
                    Viziunea fondatoare Sentra
                </h3>
                <span class="card-subtitle">Construim pe principiul: excelența nu este negociabilă.</span>
                <p class="text-section">
                    Sentra s-a născut din dorința de a elimina compromisul dintre viteză și fiabilitate.
                    Am construit o infrastructură unde proiectul tău poate domina mediul online, susținut
                    de tehnologie de neegalat.
                </p>
            </section>

            <!-- Secțiunea 2: Stocare -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i class="section-icon" data-lucide="hard-drive"></i>
                    Stocare full NVMe RAID
                </h3>
                <span class="card-subtitle">Viteze de 10 ori mai mari. Fără timpi morți.</span>
                <p class="text-section">
                    Folosim stocare NVMe RAID care elimină blocajele I/O, garantând că bazele de date
                    și fișierele site-ului tău sunt accesate cu viteză uluitoare.
                </p>
            </section>

            <!-- Secțiunea 3: Securitate -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i class="section-icon" data-lucide="shield-check"></i>
                    Fortăreața digitală 24/7
                </h3>
                <span class="card-subtitle">Apărare proactivă, nu doar reactivă.</span>
                <p class="text-section">
                    Securitatea este implementată pe multiple niveluri: protecție DDoS, firewall-uri
                    avansate și monitorizare proactivă a intruziunilor.
                </p>
            </section>

            <!-- Secțiunea 4: Transparență -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i class="section-icon" data-lucide="eye"></i>
                    Politica ușilor deschise
                </h3>
                <span class="card-subtitle">Cunoști exact ceea ce plătești și de ce.</span>
                <p class="text-section">
                    Transparența este o valoare fundamentală. Platforma oferă control total asupra
                    facturării și resurselor, cu vizualizare în timp real.
                </p>
            </section>

            <!-- Secțiunea 5: Suport -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i class="section-icon" data-lucide="message-square"></i>
                    Expertiza tehnică imediată
                </h3>
                <span class="card-subtitle">Vorbim cu ingineri, nu cu scripturi predefinite.</span>
                <p class="text-section">
                    Echipa de suport este formată din ingineri certificați, disponibili non-stop
                    pentru asistență tehnică specializată.
                </p>
            </section>

            <!-- Secțiunea 6: Scalabilitate -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i class="section-icon" data-lucide="bar-chart-2"></i>
                    Rețea proiectată pentru creștere
                </h3>
                <span class="card-subtitle">Resurse care evoluează odată cu succesul tău.</span>
                <p class="text-section">
                    Infrastructura Cloud VPS permite scalarea resurselor în timp real, fără migrații
                    complexe sau perioade de nefuncționare.
                </p>
            </section>

            <!-- Secțiunea 7: Sustenabilitate -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i class="section-icon" data-lucide="leaf"></i>
                    Hosting responsabil și durabil
                </h3>
                <span class="card-subtitle">Performanță înaltă, impact ecologic redus.</span>
                <p class="text-section">
                    Centrele de date utilizează tehnologii de eficiență energetică, reducând semnificativ
                    amprenta de carbon.
                </p>
            </section>

            <!-- Secțiunea 8: Backup -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i class="section-icon" data-lucide="copy"></i>
                    Redundanță garantată
                </h3>
                <span class="card-subtitle">Datele tale sunt mereu în siguranță.</span>
                <p class="text-section">
                    Toate serviciile beneficiază de backup-uri automate stocate în multiple locații
                    geografice pentru siguranță maximă.
                </p>
            </section>

            <!-- Secțiunea 9: Performanță -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i class="section-icon" data-lucide="cpu"></i>
                    Puterea de calcul premium
                </h3>
                <span class="card-subtitle">Procesoare dedicate pentru viteză maximă.</span>
                <p class="text-section">
                    Infrastructura rulează pe procesoare de clasă enterprise (Intel Xeon Gold, AMD EPYC)
                    pentru sarcini de lucru complexe.
                </p>
            </section>

            <!-- Secțiunea 10: Misiune -->
            <section class="about-card">
                <h3 class="text-2xl font-bold mb-1 text-sentra-cyan flex items-center">
                    <i class="section-icon" data-lucide="award"></i>
                    Misiunea noastră finală
                </h3>
                <span class="card-subtitle">Concentrarea ta pe business, responsabilitatea noastră pentru tehnic.</span>
                <p class="text-section">
                    Scopul Sentra este să fie partenerul tău invizibil și indispensabil, eliberându-ți
                    timpul pentru a te concentra pe creșterea afacerii tale.
                </p>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <div class="full-width-footer">
        <?php include 'footer.php'; ?>
    </div>

    <!-- Script pentru inițializarea iconițelor -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inițializare iconițe Lucide
            lucide.createIcons();

            // Log pentru debugging
            console.log('Sentra About Us page loaded successfully');
        });
    </script>
</body>

</html>