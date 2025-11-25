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
                            <a href="suport.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                Întrebări frecvente
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

    <main>

        <!-- Secțiunea hero business -->
        <section id="hero-business" class="py-24 md:py-40 bg-white">
            <div class="container mx-auto px-4 text-center max-w-5xl">
                <h1 class="text-5xl md:text-7xl font-extrabold mb-4 leading-tight text-gray-800">
                    Găzduire business: <span class="text-sentra-cyan">viteză, securitate, scală</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 opacity-90 mb-10 font-light">
                    Soluția de hosting premium construită pentru a genera vânzări și a gestiona volume mari de trafic.
                    Uită de downtime.
                </p>
                <a href="#plans"
                    class="inline-block btn-primary font-extrabold text-lg py-4 px-12 rounded-full transition duration-300 transform hover:scale-[1.05]">
                    Treci la performanța business
                </a>
                <p class="mt-8 text-sm text-gray-500">
                    Ideal pentru magazine online (WooCommerce, PrestaShop) și site-uri corporate.
                </p>
            </div>
        </section>

        <!-- Secțiunea beneficii business -->
        <section id="why-business" class="py-20 md:py-32 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-center text-[#1A1A1A] mb-16">
                    Nu-ți lăsa afacerea pe mâna găzduirii standard.
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-4xl mx-auto">

                    <!-- Beneficiu 1: Viteză -->
                    <div class="flex items-start space-x-4">
                        <i data-lucide="zap" class="w-10 h-10 text-sentra-cyan flex-shrink-0 mt-1"></i>
                        <div>
                            <h3 class="text-2xl font-bold mb-2 text-gray-800">Viteză turbo (servere dedicate)</h3>
                            <p class="text-gray-600">Site-ul tău se va încărca instant. Găzduirea business rulează
                                exclusiv pe hardware de ultimă generație cu stocare NVMe și server web LiteSpeed (de 9
                                ori mai rapid decât Apache).</p>
                        </div>
                    </div>

                    <!-- Beneficiu 2: Securitate -->
                    <div class="flex items-start space-x-4">
                        <i data-lucide="lock" class="w-10 h-10 text-sentra-cyan flex-shrink-0 mt-1"></i>
                        <div>
                            <h3 class="text-2xl font-bold mb-2 text-gray-800">Securitate proactivă anti-hacker</h3>
                            <p class="text-gray-600">Inclus: WAF (Web Application Firewall), scanare zilnică
                                anti-malware, backup-uri automate zilnice și protecție DDoS dedicată. Datele clienților
                                tăi sunt în siguranță.</p>
                        </div>
                    </div>

                    <!-- Beneficiu 3: Suport -->
                    <div class="flex items-start space-x-4">
                        <i data-lucide="award" class="w-10 h-10 text-sentra-cyan flex-shrink-0 mt-1"></i>
                        <div>
                            <h3 class="text-2xl font-bold mb-2 text-gray-800">Suport tehnic prioritar 24/7</h3>
                            <p class="text-gray-600">Ești întotdeauna pe primul loc. Primești răspuns în minute, nu în
                                ore, de la ingineri cu experiență, gata să rezolve orice problemă critică imediat.</p>
                        </div>
                    </div>

                    <!-- Beneficiu 4: Uptime -->
                    <div class="flex items-start space-x-4">
                        <i data-lucide="infinity" class="w-10 h-10 text-sentra-cyan flex-shrink-0 mt-1"></i>
                        <div>
                            <h3 class="text-2xl font-bold mb-2 text-gray-800">Uptime garantat de 99.99%</h3>
                            <p class="text-gray-600">Timpul înseamnă bani. Datorită arhitecturii cloud redundante și a
                                monitorizării constante, site-ul tău nu va fi niciodată offline în timpul orelor de
                                vârf.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Secțiunea planuri -->
        <section id="plans" class="py-20 md:py-32 bg-gray-100">
            <div class="container mx-auto px-4">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-center text-[#1A1A1A] mb-12">
                    Planuri optimizate pentru succesul tău
                </h2>
                <p class="text-center text-lg text-gray-600 mb-10">Toate planurile includ stocare NVMe, server LiteSpeed și suport prioritar 24/7.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Plan Business Start -->
                    <div class="bg-white p-8 rounded-xl shadow-2xl flex flex-col transition duration-300 hover:shadow-cyan-400/50">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Business Start</h3>
                        <p class="text-gray-500 mb-6">Perfect pentru site-uri corporate și landing pages cu trafic mediu-mare.</p>
                        <div class="text-4xl font-extrabold text-sentra-cyan mb-6">
                            39€<span class="text-xl font-normal text-gray-500">/lună</span>
                        </div>
                        <ul class="space-y-3 text-gray-700 flex-grow mb-6">
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>4 vCPU cores</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>8 GB RAM dedicat</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>100 GB stocare NVMe</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>5 domenii incluse</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>LiteSpeed Web Server</li>
                        </ul>
                        <a href="checkout.php?plan_id=BUSINESS-START&plan_name=Business Start&amount=39&cycle=lunar&type=business" 
                           class="mt-auto bg-gray-200 text-gray-800 w-full py-3 rounded-xl font-bold text-lg hover:bg-gray-300 transition duration-300 flex items-center justify-center">
                            Comandă 
                            <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                        </a>
                    </div>

                    <!-- Plan Business PRO (Popular) -->
                    <div class="bg-white p-8 rounded-xl shadow-2xl flex flex-col ring-4 ring-sentra-cyan transition duration-300 transform scale-[1.05] relative">
                        <span class="absolute top-0 right-0 bg-sentra-cyan text-white text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-xl">
                            Popular
                        </span>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Business PRO</h3>
                        <p class="text-gray-500 mb-6">Viteza esențială pentru magazine online aglomerate și baze de date mari.</p>
                        <div class="text-4xl font-extrabold text-sentra-cyan mb-6">
                            79€<span class="text-xl font-normal text-gray-500">/lună</span>
                        </div>
                        <ul class="space-y-3 text-gray-700 flex-grow mb-6">
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>8 vCPU cores</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>16 GB RAM dedicat</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>200 GB stocare NVMe</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>Domenii nelimitate</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>IP dedicat inclus</li>
                        </ul>
                        <a href="checkout.php?plan_id=BUSINESS-PRO&plan_name=Business PRO&amount=79&cycle=lunar&type=business" 
                           class="mt-auto btn-primary w-full py-3 rounded-xl font-bold text-lg flex items-center justify-center">
                            Comandă 
                            <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                        </a>
                    </div>

                    <!-- Plan Business Enterprise -->
                    <div class="bg-white p-8 rounded-xl shadow-2xl flex flex-col transition duration-300 hover:shadow-cyan-400/50">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Business Enterprise</h3>
                        <p class="text-gray-500 mb-6">Puterea unui server dedicat, flexibilitatea găzduirii cloud.</p>
                        <div class="text-4xl font-extrabold text-sentra-cyan mb-6">
                            129€<span class="text-xl font-normal text-gray-500">/lună</span>
                        </div>
                        <ul class="space-y-3 text-gray-700 flex-grow mb-6">
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>16 vCPU cores</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>32 GB RAM dedicat</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>400 GB stocare NVMe</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>Domenii nelimitate</li>
                            <li class="flex items-center"><i data-lucide="check" class="w-5 h-5 text-green-500 mr-2"></i>Monitorizare 24/7</li>
                        </ul>
                        <a href="checkout.php?plan_id=BUSINESS-ENTERPRISE&plan_name=Business Enterprise&amount=129&cycle=lunar&type=business" 
                           class="mt-auto bg-gray-200 text-gray-800 w-full py-3 rounded-xl font-bold text-lg hover:bg-gray-300 transition duration-300 flex items-center justify-center">
                            Comandă 
                            <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                        </a>
                    </div>

                </div>

                <p class="mt-12 text-center text-sm text-gray-500">
                    <i data-lucide="heart" class="w-4 h-4 inline mr-1 text-red-500"></i>
                    Toate planurile includ <strong>garanție 30 de zile banii înapoi</strong> și migrare gratuită a site-ului tău.
                </p>
            </div>
        </section>

        <!-- Secțiunea tehnologie -->
        <section id="tehnologie" class="py-20 md:py-32 bg-white">
            <div class="container mx-auto px-4">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-center text-[#1A1A1A] mb-16">
                    Infrastructură construită pentru performanță
                </h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-5xl mx-auto">

                    <!-- Tehnologie 1: Stocare -->
                    <div class="p-6 bg-gray-50 rounded-xl shadow-lg">
                        <i data-lucide="server" class="w-12 h-12 text-sentra-cyan mb-4"></i>
                        <h3 class="text-2xl font-bold mb-3 text-gray-800">Stocare NVMe și RAID 10</h3>
                        <p class="text-gray-600">Folosim exclusiv unități de stocare <strong>NVMe</strong> de ultimă oră, care oferă
                            viteze de citire/scriere de <strong>până la 6 ori mai mari</strong> decât SSD-urile standard. Toate
                            datele sunt securizate prin sistemul <strong>RAID 10</strong> pentru redundanță maximă.</p>
                    </div>

                    <!-- Tehnologie 2: CDN -->
                    <div class="p-6 bg-gray-50 rounded-xl shadow-lg">
                        <i data-lucide="cloud" class="w-12 h-12 text-sentra-cyan mb-4"></i>
                        <h3 class="text-2xl font-bold mb-3 text-gray-800">Rețea globală CDN (gratuit)</h3>
                        <p class="text-gray-600">Site-ul tău va fi încărcat de pe cel mai apropiat server față de
                            vizitator. Prin integrarea gratuită a unui <strong>Content Delivery Network (CDN)</strong>, reducem
                            latența și asigurăm viteze constante, indiferent de locația utilizatorului.</p>
                    </div>

                    <!-- Tehnologie 3: Baze de date -->
                    <div class="p-6 bg-gray-50 rounded-xl shadow-lg">
                        <i data-lucide="database" class="w-12 h-12 text-sentra-cyan mb-4"></i>
                        <h3 class="text-2xl font-bold mb-3 text-gray-800">Bază de date optimizată</h3>
                        <p class="text-gray-600">Motorul <strong>MariaDB</strong> sau <strong>MySQL</strong> este configurat pentru tranzacții
                            rapide. Pe serverele business, bazele de date primesc resurse dedicate pentru a gestiona mii
                            de cereri pe secundă, esențial pentru magazinele online.</p>
                    </div>

                    <!-- Tehnologie 4: Scalare -->
                    <div class="p-6 bg-gray-50 rounded-xl shadow-lg">
                        <i data-lucide="repeat" class="w-12 h-12 text-sentra-cyan mb-4"></i>
                        <h3 class="text-2xl font-bold mb-3 text-gray-800">Scalare instantanee (cloud)</h3>
                        <p class="text-gray-600">Dacă te aștepți la o campanie virală sau la o promoție de Black Friday,
                            poți adăuga <strong>CPU și RAM instantaneu</strong>, direct din panoul de control. Plătești doar pentru
                            resursele suplimentare utilizate.</p>
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