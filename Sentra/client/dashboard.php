<?php
// client/dashboard.php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit;
}

$user_role = trim(strtolower($_SESSION['user_role'] ?? 'client'));
$is_staff_or_admin = ($user_role === 'staff' || $user_role === 'admin');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";
$user_id = $_SESSION['user_id'];

// Conectare la DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Eroare de conectare la baza de date.");
}
$conn->set_charset("utf8mb4");

// Preluare date utilizator
$user_data = [];
$address_full = "";

$stmt = $conn->prepare("
    SELECT first_name, last_name, email, phone, company_name, address_line_1, address_line_2
    FROM users 
    WHERE id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user_data = $result->fetch_assoc();
    $address_full = trim($user_data['address_line_1'] . "\n" . $user_data['address_line_2']);
    $_SESSION['user_name'] = $user_data['first_name'] . ' ' . $user_data['last_name'];
} else {
    session_destroy();
    header("Location: ../pages/login.php");
    exit;
}
$stmt->close();

// Preluare date reale pentru dashboard
// 1. Număr tickete deschise
$stmt_tickets = $conn->prepare("
    SELECT COUNT(*) as count 
    FROM tickets 
    WHERE user_id = ? AND status IN ('open', 'in_progress', 'answered')
");
$stmt_tickets->bind_param("i", $user_id);
$stmt_tickets->execute();
$tickets_count = $stmt_tickets->get_result()->fetch_assoc()['count'];
$stmt_tickets->close();

// 2. Facturi în așteptare (dacă ai tabelul invoices)
$invoices_count = 0;
$has_invoices_table = $conn->query("SHOW TABLES LIKE 'invoices'")->num_rows > 0;
if ($has_invoices_table) {
    $stmt_invoices = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM invoices 
        WHERE user_id = ? AND status = 'pending'
    ");
    $stmt_invoices->bind_param("i", $user_id);
    $stmt_invoices->execute();
    $invoices_count = $stmt_invoices->get_result()->fetch_assoc()['count'];
    $stmt_invoices->close();
}

// 3. Servicii active (dacă ai tabelul services)
$services_count = 0;
$has_services_table = $conn->query("SHOW TABLES LIKE 'services'")->num_rows > 0;
if ($has_services_table) {
    $stmt_services = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM services 
        WHERE user_id = ? AND status = 'active'
    ");
    $stmt_services->bind_param("i", $user_id);
    $stmt_services->execute();
    $services_count = $stmt_services->get_result()->fetch_assoc()['count'];
    $stmt_services->close();
}

// 4. Ultimele activități (din tickete și login)
$stmt_activities = $conn->prepare("
    (SELECT 
        'ticket' as type,
        CONCAT('Tichet #', id) as action,
        subject as details,
        created_at
    FROM tickets 
    WHERE user_id = ?
    ORDER BY created_at DESC 
    LIMIT 3)
    
    UNION ALL
    
    (SELECT 
        'login' as type,
        'Autentificare reușită' as action,
        CONCAT('IP: ', ?) as details,
        NOW() as created_at
    )
    
    ORDER BY created_at DESC 
    LIMIT 4
");
$client_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$stmt_activities->bind_param("is", $user_id, $client_ip);
$stmt_activities->execute();
$activities = $stmt_activities->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_activities->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>

    <style>
        /* CSS general, Culori și Font */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 0;
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

        /* Gradientul Meniului Lateral (Sidebar Fix) */
        .gradient-sidebar {
            background: linear-gradient(135deg, #1f2937 0%, #00BFB2 100%);
            min-height: 100vh;
            max-height: 100vh;
            overflow-y: auto;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 20;
        }

        /* Containerul principal (Panoul) */
        .main-container {
            width: 100%;
            display: flex;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* Conținutul principal (dreapta) */
        .content-wrapper {
            flex-grow: 1;
            padding: 1rem;
            margin-left: 0;
            min-height: calc(100vh - 40px);
            background: transparent;
            display: flex;
            flex-direction: column;
        }

        /* Zona de conținut din dreapta */
        .content-area {
            flex-grow: 1;
            overflow-y: auto;
            padding-bottom: 2rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Card-uri moderne */
        .modern-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #00BFB2 0%, #009688 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* FIX PENTRU FOOTER */
        .client-panel-footer-alignment {
            width: 100%;
            background: linear-gradient(135deg, #1A1A1A 0%, #2D2D2D 100%);
        }

        /* Stat cards styling */
        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .stat-card.tickets::before {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
        }

        .stat-card.services::before {
            background: linear-gradient(135deg, #00BFB2 0%, #009688 100%);
        }

        .stat-card.invoices::before {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        }

        .stat-card.status::before {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        /* Quick action cards */
        .quick-action-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            border-left: 4px solid;
        }

        .quick-action-card.tickets {
            border-left-color: #3B82F6;
        }

        .quick-action-card.facturare {
            border-left-color: #00BFB2;
        }

        .quick-action-card.servicii {
            border-left-color: #10B981;
        }

        .quick-action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* Activities section */
        .activities-section {
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.95) 0%, rgba(241, 245, 249, 0.85) 100%);
            backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-left: 4px solid #F59E0B;
        }

        /* Status badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .status-active {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
        }

        .status-pending {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: white;
        }

        .status-suspended {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: white;
        }

        /* Smooth Transitions */
        * {
            transition-property: color, background-color, border-color, transform, opacity;
            transition-duration: 200ms;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Buton modern */
        .modern-button {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: none;
            cursor: pointer;
        }

        .modern-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        /* Icon styles for activities */
        .activity-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .icon-welcome {
            background: linear-gradient(135deg, #00BFB2 0%, #009688 100%);
        }

        .icon-services {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }

        .icon-ticket {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
        }

        .icon-login {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        }

        /* Mobile-first responsive adjustments */
        @media (min-width: 768px) {
            .content-wrapper {
                padding: 2rem;
                margin-left: 20%;
            }

            .content-area {
                padding: 3rem;
            }

            .client-panel-footer-alignment {
                width: 80%;
                margin-left: 20%;
            }
        }

        @media (min-width: 1024px) {
            .content-wrapper {
                margin-left: 20%;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <?php include 'dashboard_sidebar.php'; ?>

        <div class="content-wrapper">
            <div id="content-area" class="content-area">
                <!-- Header Section -->
                <div class="mb-8 sm:mb-12 text-center animate-fade-in-down">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#00BFB2] mb-4 animate-scale-in">
        Dashboard Sentra
    </h1>
                    <p class="text-gray-600 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed animate-fade-in-up">
                        Bine ai revenit în panoul tău de control. Aici poți vedea o prezentare generală a contului tău
                        și accesa rapid serviciile importante.
                    </p>
                </div>

                <!-- Welcome Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8 mb-8 animate-slide-in-left">
                    <div class="flex items-center">
                        <div
                            class="w-14 h-14 bg-gradient-to-r from-cyan-500 to-teal-500 rounded-xl flex items-center justify-center mr-4">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="white" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="12" cy="7" r="4" stroke="white" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Salut,
                                <?php echo htmlspecialchars($user_data['first_name']); ?>!</h2>
                            <p class="text-gray-600 mt-1">Cod client: <span
                                    class="font-semibold text-cyan-600"><?php echo htmlspecialchars($user_id); ?></span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Stat Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Tickete Active -->
                    <div
                        class="bg-white rounded-2xl shadow-lg border-l-4 border-blue-500 p-6 hover:shadow-xl transition-all duration-300 animate-card-pop delay-100">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"
                                        stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M13 5v2" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M13 17v2" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M13 11v2" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tickete Active</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo $tickets_count; ?></p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                            Necesită atenția ta
                        </div>
                    </div>

                    <!-- Servicii Active -->
                    <div
                        class="bg-white rounded-2xl shadow-lg border-l-4 border-cyan-500 p-6 hover:shadow-xl transition-all duration-300 animate-card-pop delay-200">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center mr-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="2" y="3" width="20" height="6" rx="1" stroke="#06B6D4" stroke-width="2" />
                                    <rect x="2" y="11" width="20" height="6" rx="1" stroke="#06B6D4" stroke-width="2" />
                                    <rect x="2" y="19" width="20" height="6" rx="1" stroke="#06B6D4" stroke-width="2" />
                                    <circle cx="5" cy="6" r="1" fill="#06B6D4" />
                                    <circle cx="5" cy="14" r="1" fill="#06B6D4" />
                                    <circle cx="5" cy="22" r="1" fill="#06B6D4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Servicii Active</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo $services_count; ?></p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <div class="w-2 h-2 bg-cyan-500 rounded-full mr-2"></div>
                            În funcțiune
                        </div>
                    </div>

                    <!-- Facturi Restante -->
                    <div
                        class="bg-white rounded-2xl shadow-lg border-l-4 border-red-500 p-6 hover:shadow-xl transition-all duration-300 animate-card-pop delay-300">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mr-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"
                                        stroke="#EF4444" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <polyline points="14,2 14,8 20,8" stroke="#EF4444" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <line x1="16" y1="13" x2="8" y2="13" stroke="#EF4444" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <line x1="16" y1="17" x2="8" y2="17" stroke="#EF4444" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <polyline points="10,9 9,9 8,9" stroke="#EF4444" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Facturi Restante
                                </p>
                                <p class="text-2xl font-bold text-red-600 mt-1"><?php echo $invoices_count; ?></p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            Necesită plată
                        </div>
                    </div>

                    <!-- Status Server -->
                    <div
                        class="bg-white rounded-2xl shadow-lg border-l-4 border-green-500 p-6 hover:shadow-xl transition-all duration-300 animate-card-pop delay-400">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" stroke="#10B981" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Status Server</p>
                                <p class="text-xl font-bold text-gray-900 mt-1 flex items-center">
                                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                    Operațional
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="mr-2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="#10B981" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Toate sistemele funcționează
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mb-8 animate-slide-in-right">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Acțiuni Rapide</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Deschide Tichet -->
                        <a href="ticket.php"
                            class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 group animate-bounce-in delay-100">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-blue-500 transition-colors duration-300">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="group-hover:scale-110 transition-transform duration-300">
                                        <path
                                            d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"
                                            stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="group-hover:stroke-white" />
                                        <path d="M13 5v2" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="group-hover:stroke-white" />
                                        <path d="M13 17v2" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="group-hover:stroke-white" />
                                        <path d="M13 11v2" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="group-hover:stroke-white" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="font-semibold text-gray-900 text-lg group-hover:text-blue-600 transition-colors duration-300">
                                        Deschide Tichet</p>
                                    <p class="text-gray-600 text-sm mt-1">Cere ajutor pentru probleme tehnice</p>
                                </div>
                            </div>
                        </a>

                        <!-- Plătește Facturi -->
                        <a href="facturare.php"
                            class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 group animate-bounce-in delay-200">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-cyan-500 transition-colors duration-300">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="group-hover:scale-110 transition-transform duration-300">
                                        <rect x="2" y="5" width="20" height="14" rx="2" stroke="#06B6D4"
                                            stroke-width="2" class="group-hover:stroke-white" />
                                        <rect x="2" y="10" width="20" height="3" fill="#06B6D4"
                                            class="group-hover:fill-white" />
                                        <circle cx="18" cy="15" r="2" fill="#06B6D4" class="group-hover:fill-white" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="font-semibold text-gray-900 text-lg group-hover:text-cyan-600 transition-colors duration-300">
                                        Plătește Facturi</p>
                                    <p class="text-gray-600 text-sm mt-1">Gestionează plățile restante</p>
                                </div>
                            </div>
                        </a>

                        <!-- Servicii Active -->
                        <a href="../client/user_services.php"
                            class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 group animate-bounce-in delay-300">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-green-500 transition-colors duration-300">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="group-hover:scale-110 transition-transform duration-300">
                                        <rect x="2" y="3" width="20" height="6" rx="1" stroke="#10B981" stroke-width="2"
                                            class="group-hover:stroke-white" />
                                        <rect x="2" y="11" width="20" height="6" rx="1" stroke="#10B981"
                                            stroke-width="2" class="group-hover:stroke-white" />
                                        <rect x="2" y="19" width="20" height="6" rx="1" stroke="#10B981"
                                            stroke-width="2" class="group-hover:stroke-white" />
                                        <circle cx="5" cy="6" r="1" fill="#10B981" class="group-hover:fill-white" />
                                        <circle cx="5" cy="14" r="1" fill="#10B981" class="group-hover:fill-white" />
                                        <circle cx="5" cy="22" r="1" fill="#10B981" class="group-hover:fill-white" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="font-semibold text-gray-900 text-lg group-hover:text-green-600 transition-colors duration-300">
                                        Servicii Active</p>
                                    <p class="text-gray-600 text-sm mt-1">Vezi și gestionează serviciile</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Ultimele Activități -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8 animate-fade-in-up delay-500">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mr-4">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2" stroke="#F59E0B" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Ultimele Activități</h3>
                            <p class="text-gray-600">Istoricul recent al activităților tale</p>
                        </div>
                    </div>

                    <?php if (count($activities) > 0): ?>
                        <div class="space-y-4">
                            <!-- Activitate Servicii -->
                            <?php if ($services_count > 0): ?>
                                <div
                                    class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-white transition-colors duration-200 animate-fade-in delay-600">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="2" y="3" width="20" height="6" rx="1" stroke="#10B981" stroke-width="2" />
                                            <rect x="2" y="11" width="20" height="6" rx="1" stroke="#10B981" stroke-width="2" />
                                            <rect x="2" y="19" width="20" height="6" rx="1" stroke="#10B981" stroke-width="2" />
                                            <circle cx="5" cy="6" r="1" fill="#10B981" />
                                            <circle cx="5" cy="14" r="1" fill="#10B981" />
                                            <circle cx="5" cy="22" r="1" fill="#10B981" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">Servicii Active</p>
                                        <p class="text-sm text-gray-600 mt-1">Ai <?php echo $services_count; ?>
                                            servici<?php echo $services_count == 1 ? 'u' : 'i'; ?>
                                            activ<?php echo $services_count == 1 ? '' : 'e'; ?></p>
                                    </div>
                                    <span class="text-sm text-gray-500"><?php echo date('d.m.Y'); ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- Activități din baza de date -->
                            <?php foreach ($activities as $index => $activity): ?>
                                <div
                                    class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-white transition-colors duration-200 animate-fade-in delay-<?php echo 700 + ($index * 100); ?>">
                                    <div
                                        class="w-10 h-10 <?php echo $activity['type'] === 'ticket' ? 'bg-blue-100' : 'bg-amber-100'; ?> rounded-lg flex items-center justify-center mr-4">
                                        <?php if ($activity['type'] === 'ticket'): ?>
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"
                                                    stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M13 5v2" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M13 17v2" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M13 11v2" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        <?php else: ?>
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke="#F59E0B"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <polyline points="10,17 15,12 10,7" stroke="#F59E0B" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <line x1="15" y1="12" x2="3" y2="12" stroke="#F59E0B" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">
                                            <?php echo htmlspecialchars($activity['action']); ?></p>
                                        <?php if (!empty($activity['details'])): ?>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <?php echo htmlspecialchars($activity['details']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <span
                                        class="text-sm text-gray-500"><?php echo date('d.m.Y H:i', strtotime($activity['created_at'])); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8 animate-fade-in">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke="#9CA3AF"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <p class="text-gray-500">Nu există activități recente.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="client-panel-footer-alignment">
        <?php include '../pages/footer.php'; ?>
    </div>

    <style>
        /* Animații personalizate */
        .animate-fade-in-down {
            opacity: 0;
            transform: translateY(-20px);
            animation: fadeInDown 0.6s ease-out forwards;
        }

        .animate-fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-slide-in-left {
            opacity: 0;
            transform: translateX(-30px);
            animation: slideInLeft 0.6s ease-out forwards;
        }

        .animate-slide-in-right {
            opacity: 0;
            transform: translateX(30px);
            animation: slideInRight 0.6s ease-out forwards;
        }

        .animate-scale-in {
            opacity: 0;
            transform: scale(0.9);
            animation: scaleIn 0.6s ease-out forwards;
        }

        .animate-card-pop {
            opacity: 0;
            transform: scale(0.8) translateY(20px);
            animation: cardPop 0.5s ease-out forwards;
        }

        .animate-bounce-in {
            opacity: 0;
            transform: scale(0.8);
            animation: bounceIn 0.6s ease-out forwards;
        }

        .animate-fade-in {
            opacity: 0;
            animation: fadeIn 0.8s ease-out forwards;
        }

        /* Delay classes */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-600 { animation-delay: 0.6s; }
        .delay-700 { animation-delay: 0.7s; }
        .delay-800 { animation-delay: 0.8s; }
        .delay-900 { animation-delay: 0.9s; }

        /* Keyframes */
        @keyframes fadeInDown {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes cardPop {
            0% {
                opacity: 0;
                transform: scale(0.8) translateY(20px);
            }
            50% {
                transform: scale(1.05) translateY(-5px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
    </style>

    <script>
        // Animație suplimentară pentru elementele care apar la scroll
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0) scale(1)';
                    }
                });
            }, observerOptions);

            // Observă toate elementele cu clase de animație
            document.querySelectorAll('[class*="animate-"]').forEach(element => {
                observer.observe(element);
            });
        });
    </script>
</body>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            lucide.createIcons();

            // Animations for elements on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Apply animations to cards
            document.querySelectorAll('.modern-card, .stat-card, .quick-action-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });

            // Notifications
            function showNotification(message, color = 'green', callback = null) {
                const notification = document.createElement('div');
                const bgColor = color === 'sentra-cyan' ? 'bg-[#00BFB2]' :
                    color === 'yellow' ? 'bg-yellow-500' :
                        color === 'blue' ? 'bg-blue-600' :
                            color === 'red' ? 'bg-red-600' : 'bg-green-500';

                notification.className = `fixed top-5 right-5 p-4 rounded-lg text-white ${bgColor} shadow-xl transition-opacity duration-300 z-50 opacity-0`;
                notification.textContent = message;
                document.body.appendChild(notification);

                void notification.offsetWidth;
                notification.style.opacity = '1';

                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        notification.remove();
                        if (callback) callback();
                    }, 300);
                }, 3000 - 300);
            }

            // Event handlers for actions
            document.getElementById('content-area').addEventListener('click', (e) => {
                const target = e.target.closest('[data-action]');
                if (!target) return;

                const action = target.getAttribute('data-action');
                if (action === 'pay') {
                    showNotification('Securizăm conexiunea. Redirecționare către procesatorul de plată...', 'sentra-cyan');
                    setTimeout(() => {
                        window.location.href = '../pages/cashout.php';
                    }, 2000);
                } else if (action === 'download') {
                    showNotification('Fișierul PDF s-a descărcat cu succes în directorul tău.', 'blue');
                }
            });
        });
    </script>
</body>

</html>