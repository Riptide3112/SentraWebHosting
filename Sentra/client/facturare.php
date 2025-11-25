<?php
// facturare.php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
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
$stmt = $conn->prepare("SELECT first_name, last_name, email, company_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user_data = $result->fetch_assoc();
} else {
    session_destroy();
    header("Location: login.php");
    exit;
}
$stmt->close();

// Preluare facturi
$invoices = [];
$has_invoices_table = $conn->query("SHOW TABLES LIKE 'invoices'")->num_rows > 0;

if ($has_invoices_table) {
    $stmt_invoices = $conn->prepare("
        SELECT id, invoice_number, amount, due_date, status, created_at 
        FROM invoices 
        WHERE user_id = ? 
        ORDER BY created_at DESC
    ");
    $stmt_invoices->bind_param("i", $user_id);
    $stmt_invoices->execute();
    $invoices = $stmt_invoices->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_invoices->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra - Facturare</title>
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

        .animate-card-pop {
            opacity: 0;
            transform: scale(0.8) translateY(20px);
            animation: cardPop 0.5s ease-out forwards;
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

        @keyframes fadeIn {
            to {
                opacity: 1;
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
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#00BFB2] mb-4">
                        Facturare
                    </h1>
                    <p class="text-gray-600 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed animate-fade-in-up">
                        Gestionează-ți facturile și plățile într-un mod simplu și eficient
                    </p>
                </div>

                <!-- Stat Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Facturi -->
                    <div class="bg-white rounded-2xl shadow-lg border-l-4 border-blue-500 p-6 hover:shadow-xl transition-all duration-300 animate-card-pop delay-100">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <polyline points="14,2 14,8 20,8" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Facturi</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo count($invoices); ?></p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                            Toate facturile
                        </div>
                    </div>

                    <!-- Facturi Restante -->
                    <div class="bg-white rounded-2xl shadow-lg border-l-4 border-red-500 p-6 hover:shadow-xl transition-all duration-300 animate-card-pop delay-200">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mr-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 8v4l3 3" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="12" r="10" stroke="#EF4444" stroke-width="2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Facturi Restante</p>
                                <p class="text-2xl font-bold text-red-600 mt-1">
                                    <?php 
                                        $pending_count = 0;
                                        foreach ($invoices as $invoice) {
                                            if ($invoice['status'] === 'pending') $pending_count++;
                                        }
                                        echo $pending_count;
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            Necesită plată
                        </div>
                    </div>

                    <!-- Total de Plată -->
                    <div class="bg-white rounded-2xl shadow-lg border-l-4 border-amber-500 p-6 hover:shadow-xl transition-all duration-300 animate-card-pop delay-300">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mr-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="2" y="5" width="20" height="14" rx="2" stroke="#F59E0B" stroke-width="2"/>
                                    <rect x="2" y="10" width="20" height="3" fill="#F59E0B"/>
                                    <circle cx="18" cy="15" r="2" fill="#F59E0B"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total de Plată</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">
                                    <?php
                                        $total_amount = 0;
                                        foreach ($invoices as $invoice) {
                                            if ($invoice['status'] === 'pending') {
                                                $total_amount += $invoice['amount'];
                                            }
                                        }
                                        echo number_format($total_amount, 2) . ' RON';
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <div class="w-2 h-2 bg-amber-500 rounded-full mr-2"></div>
                            Suma restantă
                        </div>
                    </div>

                    <!-- Facturi Plătite -->
                    <div class="bg-white rounded-2xl shadow-lg border-l-4 border-green-500 p-6 hover:shadow-xl transition-all duration-300 animate-card-pop delay-400">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <polyline points="22,4 12,14.01 9,11.01" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Facturi Plătite</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">
                                    <?php 
                                        $paid_count = 0;
                                        foreach ($invoices as $invoice) {
                                            if ($invoice['status'] === 'paid') $paid_count++;
                                        }
                                        echo $paid_count;
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            Achitate cu succes
                        </div>
                    </div>
                </div>

                <!-- Lista Facturi -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8 animate-fade-in-up delay-500">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-[#00BFB2] rounded-xl flex items-center justify-center mr-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <polyline points="14,2 14,8 20,8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Facturile Mele</h3>
                                <p class="text-gray-600">Toate facturile și plățile tale</p>
                            </div>
                        </div>
                        <button class="bg-[#00BFB2] text-white px-6 py-3 rounded-xl font-semibold hover:bg-[#00a89c] transition-colors duration-200">
                            Descarcă Raport
                        </button>
                    </div>

                    <?php if (count($invoices) > 0): ?>
                        <div class="space-y-4">
                            <?php foreach ($invoices as $index => $invoice): ?>
                                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 animate-fade-in-up" style="animation-delay: <?php echo ($index * 0.1) + 0.2; ?>s">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-6">
                                            <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="#6B7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <polyline points="14,2 14,8 20,8" stroke="#6B7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900">Factura #<?php echo htmlspecialchars($invoice['invoice_number']); ?></h4>
                                                <p class="text-gray-600 text-sm mt-1">
                                                    Emisă: <?php echo date('d.m.Y', strtotime($invoice['created_at'])); ?> | 
                                                    Scadență: <?php echo date('d.m.Y', strtotime($invoice['due_date'])); ?>
                                                </p>
                                                <p class="text-lg font-bold text-gray-900 mt-2">
                                                    <?php echo number_format($invoice['amount'], 2); ?> RON
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-4">
                                            <span class="px-4 py-2 rounded-full text-sm font-semibold <?php 
                                                echo $invoice['status'] === 'paid' ? 'bg-green-100 text-green-800' : 
                                                     ($invoice['status'] === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800');
                                            ?>">
                                                <?php 
                                                    echo $invoice['status'] === 'paid' ? 'Plătită' : 
                                                         ($invoice['status'] === 'overdue' ? 'Restantă' : 'În așteptare');
                                                ?>
                                            </span>
                                            
                                            <?php if ($invoice['status'] !== 'paid'): ?>
                                                <button class="bg-[#00BFB2] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#00a89c] transition-colors duration-200">
                                                    Plătește
                                                </button>
                                            <?php endif; ?>
                                            
                                            <button class="text-gray-500 hover:text-[#00BFB2] transition-colors duration-200 p-2">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <polyline points="7,10 12,15 17,10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12 animate-fade-in">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <polyline points="14,2 14,8 20,8" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-900 mb-2">Nu există facturi</h4>
                            <p class="text-gray-600">Momentul nu ai nicio factură de plată.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="client-panel-footer-alignment">
        <?php include 'footer.php'; ?>
    </div>

    <script>
// Sistem de actualizare în timp real
class RealTimeUpdater {
    constructor() {
        this.updateInterval = 30000; // 30 de secunde
        this.isUpdating = false;
        this.init();
    }

    init() {
        // Actualizează imediat la încărcare
        this.updateData();
        
        // Setează actualizarea periodică
        setInterval(() => this.updateData(), this.updateInterval);
        
        // Ascultă evenimente de pe pagină care necesită actualizare
        this.setupEventListeners();
    }

    async updateData() {
        if (this.isUpdating) return;
        
        this.isUpdating = true;
        
        try {
            const response = await fetch(`../api/api_fetch_data.php?type=invoices&t=${Date.now()}`);
            const data = await response.json();
            
            if (data.success) {
                this.updateInvoiceStats(data.invoice_stats);
                this.updateInvoiceList(data.invoices);
                this.updateLastUpdateTime(data.timestamp);
            } else {
                console.error('Eroare la actualizare:', data.error);
            }
        } catch (error) {
            console.error('Eroare de rețea:', error);
        } finally {
            this.isUpdating = false;
        }
    }

    updateInvoiceStats(stats) {
        // Actualizează cardurile de statistici
        const statElements = {
            'total': document.querySelector('.stat-card:nth-child(1) .text-2xl'),
            'pending': document.querySelector('.stat-card:nth-child(2) .text-2xl'),
            'total_amount': document.querySelector('.stat-card:nth-child(3) .text-2xl'),
            'paid': document.querySelector('.stat-card:nth-child(4) .text-2xl')
        };

        if (statElements.total) statElements.total.textContent = stats.total;
        if (statElements.pending) statElements.pending.textContent = stats.pending;
        if (statElements.total_amount) statElements.total_amount.textContent = `${stats.total_amount.toFixed(2)} RON`;
        if (statElements.paid) statElements.paid.textContent = stats.paid;

        // Animație de actualizare
        this.animateUpdate(statElements.total);
    }

    updateInvoiceList(invoices) {
        const container = document.querySelector('.space-y-4');
        if (!container) return;

        if (invoices.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 animate-fade-in">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <polyline points="14,2 14,8 20,8" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Nu există facturi</h4>
                    <p class="text-gray-600">Momentul nu ai nicio factură de plată.</p>
                </div>
            `;
            return;
        }

        let html = '';
        invoices.forEach((invoice, index) => {
            const statusText = invoice.status === 'paid' ? 'Plătită' : 
                             invoice.status === 'overdue' ? 'Restantă' : 'În așteptare';
            const statusClass = invoice.status === 'paid' ? 'bg-green-100 text-green-800' : 
                              invoice.status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800';

            html += `
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300" data-invoice-id="${invoice.id}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="#6B7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <polyline points="14,2 14,8 20,8" stroke="#6B7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Factura #${this.escapeHtml(invoice.invoice_number)}</h4>
                                <p class="text-gray-600 text-sm mt-1">
                                    Emisă: ${this.formatDate(invoice.created_at)} | 
                                    Scadență: ${this.formatDate(invoice.due_date)}
                                </p>
                                <p class="text-lg font-bold text-gray-900 mt-2">
                                    ${parseFloat(invoice.amount).toFixed(2)} RON
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <span class="px-4 py-2 rounded-full text-sm font-semibold ${statusClass}">
                                ${statusText}
                            </span>
                            
                            ${invoice.status !== 'paid' ? `
                                <button class="bg-[#00BFB2] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#00a89c] transition-colors duration-200 pay-invoice" data-invoice-id="${invoice.id}">
                                    Plătește
                                </button>
                            ` : ''}
                            
                            <button class="text-gray-500 hover:text-[#00BFB2] transition-colors duration-200 p-2 download-invoice" data-invoice-id="${invoice.id}">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <polyline points="7,10 12,15 17,10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        this.setupInvoiceEventListeners();
    }

    updateLastUpdateTime(timestamp) {
        // Poți adăuga un indicator de ultima actualizare dacă dorești
        console.log('Date actualizate la:', timestamp);
    }

    animateUpdate(element) {
        if (element) {
            element.classList.add('scale-110');
            setTimeout(() => {
                element.classList.remove('scale-110');
            }, 300);
        }
    }

    setupEventListeners() {
        // Reîmprospătează manual la click pe buton (poți adăuga un buton pentru asta)
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-action="refresh"]')) {
                this.updateData();
                this.showNotification('Datele se actualizează...', 'sentra-cyan');
            }
        });
    }

    setupInvoiceEventListeners() {
        // Event listeners pentru butoanele din facturi
        document.addEventListener('click', (e) => {
            const payBtn = e.target.closest('.pay-invoice');
            if (payBtn) {
                const invoiceId = payBtn.dataset.invoiceId;
                this.payInvoice(invoiceId);
            }

            const downloadBtn = e.target.closest('.download-invoice');
            if (downloadBtn) {
                const invoiceId = downloadBtn.dataset.invoiceId;
                this.downloadInvoice(invoiceId);
            }
        });
    }

    async payInvoice(invoiceId) {
        this.showNotification('Se procesează plata...', 'sentra-cyan');
        
        // Aici poți face o cerere către backend pentru procesarea plății
        setTimeout(() => {
            this.showNotification('Redirecționare către procesatorul de plată...', 'blue');
            // window.location.href = `cashout.php?invoice_id=${invoiceId}`;
        }, 1000);
    }

    downloadInvoice(invoiceId) {
        this.showNotification('Se descarcă factura...', 'blue');
        // window.location.href = `download_invoice.php?id=${invoiceId}`;
    }

    showNotification(message, color = 'green') {
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
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('ro-RO');
    }

    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
}

// Inițializează sistemul de actualizare
document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
    new RealTimeUpdater();
    
    // Adaugă buton de refresh manual (opțional)
    const headerSection = document.querySelector('.mb-8.sm\\:mb-12');
    if (headerSection) {
        const refreshBtn = document.createElement('button');
        refreshBtn.className = 'bg-[#00BFB2] text-white px-4 py-2 rounded-lg font-semibold hover:bg-[#00a89c] transition-colors duration-200 mt-4';
        refreshBtn.innerHTML = '<i data-lucide="refresh-cw" class="w-5 h-4 mr-2"></i> Actualizează';
        refreshBtn.setAttribute('data-action', 'refresh');
        headerSection.appendChild(refreshBtn);
        lucide.createIcons();
    }
});
</script>

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
                        window.location.href = 'cashout.php';
                    }, 2000);
                } else if (action === 'download') {
                    showNotification('Fișierul PDF s-a descărcat cu succes în directorul tău.', 'blue');
                }
            });
        });
    </script>
</body>

</html>