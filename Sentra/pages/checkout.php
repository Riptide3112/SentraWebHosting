<?php
// pages/checkout.php
session_start();

// Auth check - redirecționează dacă nu este logat
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Preluare parametri din URL
$plan_id = $_GET['plan_id'] ?? '';
$plan_name = $_GET['plan_name'] ?? '';
$amount = $_GET['amount'] ?? '0';
$billing_cycle = $_GET['cycle'] ?? 'lunar';
$plan_type = $_GET['type'] ?? 'shared';

// Planuri predefinite - actualizate pentru toate tipurile de planuri
$plans = [
    // Shared Hosting
    'BASIC' => ['name' => 'Basic', 'features' => ['10GB SSD Storage', '1 Website', '100GB Traffic', '5 Conturi Email', '1 GB RAM']],
    'STANDARD' => ['name' => 'Standard', 'features' => ['25GB SSD Storage', '5 Website-uri', '300GB Traffic', '20 Conturi Email', '2 GB RAM']],
    'PRO' => ['name' => 'Pro', 'features' => ['50GB SSD Storage', 'Website-uri Nelimitate', 'Trafic Nelimitat', 'Conturi Email Nelimitate', '4 GB RAM']],
    
    // VPS
    'VPS-MINI' => ['name' => 'VPS Mini', 'features' => ['1 vCPU core', '2 GB RAM dedicat', '50 GB stocare NVMe', 'Trafic nelimitat']],
    'VPS-BUSINESS' => ['name' => 'VPS Business', 'features' => ['4 vCPU cores', '8 GB RAM dedicat', '160 GB stocare NVMe', 'Trafic nelimitat']],
    'VPS-PRO' => ['name' => 'VPS Pro', 'features' => ['8 vCPU cores', '16 GB RAM dedicat', '300 GB stocare NVMe', 'Trafic nelimitat']],
    'VPS-ENTERPRISE' => ['name' => 'VPS Enterprise', 'features' => ['12 vCPU cores', '24 GB RAM dedicat', '500 GB stocare NVMe', 'Trafic nelimitat']],
    
    // Business
    'BUSINESS-START' => ['name' => 'Business Start', 'features' => ['4 vCPU cores', '8 GB RAM dedicat', '100 GB stocare NVMe', '5 domenii incluse', 'LiteSpeed Web Server']],
    'BUSINESS-PRO' => ['name' => 'Business PRO', 'features' => ['8 vCPU cores', '16 GB RAM dedicat', '200 GB stocare NVMe', 'Domenii nelimitate', 'IP dedicat inclus']],
    'BUSINESS-ENTERPRISE' => ['name' => 'Business Enterprise', 'features' => ['16 vCPU cores', '32 GB RAM dedicat', '400 GB stocare NVMe', 'Domenii nelimitate', 'Monitorizare 24/7']]
];

// Dacă plan_id este valid, setează automat datele
$selected_plan = null;
if (array_key_exists($plan_id, $plans)) {
    $selected_plan = $plans[$plan_id];
    $plan_name = $selected_plan['name'];
}

// Procesare formular plată
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_number = $_POST['card_number'] ?? '';
    $expiry_date = $_POST['expiry_date'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    $card_holder = $_POST['card_holder'] ?? '';
    $payment_method = $_POST['payment_method'] ?? 'card';
    
    // Procesează plata
    $payment_success = processPayment($amount, $card_number, $plan_name);
    
    if ($payment_success) {
        // Salvare în baza de date - Ieșire pentru tabelul subscriptions
        $subscription_data = saveSubscriptionToDatabase(
            $_SESSION['user_id'],
            $plan_id,
            $plan_name,
            $amount,
            $billing_cycle,
            $payment_method,
            $plan_type
        );
        
        if ($subscription_data) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'text' => "Plata a fost procesată cu succes! Serviciul $plan_name a fost activat."
            ];
            header("Location: ../client/user_services.php");
            exit;
        } else {
            $error = "Eroare la salvarea abonamentului. Contactați suportul.";
        }
    } else {
        $error = "Plata a eșuat. Vă rugăm să încercați din nou.";
    }
}

function processPayment($amount, $card_number, $plan_name) {
    // Aici vei integra cu API-ul tău de plăți
    // Momentan returnăm true pentru simulare
    sleep(2);
    return true;
}

function createPaymentsTable($conn) {
    try {
        // Verifică dacă tabelul payments există
        $check_table = $conn->query("SHOW TABLES LIKE 'payments'");
        if ($check_table->rowCount() === 0) {
            // Creează tabelul payments
            $create_table = $conn->prepare("
                CREATE TABLE payments (
                    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    user_id INT(11) NOT NULL,
                    subscription_id INT(11),
                    invoice_number VARCHAR(50),
                    amount DECIMAL(10,2) NOT NULL,
                    currency VARCHAR(3) DEFAULT 'RON',
                    payment_method VARCHAR(50),
                    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
                    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id) ON DELETE SET NULL
                )
            ");
            $create_table->execute();
            error_log("Tabelul payments a fost creat cu succes.");
        }
    } catch (PDOException $e) {
        error_log("Eroare la crearea tabelului payments: " . $e->getMessage());
    }
}

function saveSubscriptionToDatabase($user_id, $plan_id, $plan_name, $amount, $billing_cycle, $payment_method, $plan_type) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sentra_db";
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Creează tabelul payments dacă nu există
        createPaymentsTable($conn);
        
        // Calculează datele pentru abonament
        $start_date = date('Y-m-d H:i:s');
        $end_date = date('Y-m-d H:i:s', strtotime('+1 month'));
        $next_billing_date = date('Y-m-d H:i:s', strtotime('+1 month'));
        
        // 1. INSERARE ÎN TABELUL SUBSCRIPTIONS
        $stmt = $conn->prepare("INSERT INTO subscriptions 
            (user_id, plan_type, plan_name, price, billing_cycle, status, 
             start_date, end_date, next_billing_date, payment_method, service_type, created_at) 
            VALUES (:user_id, :plan_type, :plan_name, :price, :billing_cycle, 'active',
                    :start_date, :end_date, :next_billing_date, :payment_method, :service_type, NOW())");
        
        $stmt->execute([
            ':user_id' => $user_id,
            ':plan_type' => $plan_id,
            ':plan_name' => $plan_name,
            ':price' => $amount,
            ':billing_cycle' => $billing_cycle,
            ':start_date' => $start_date,
            ':end_date' => $end_date,
            ':next_billing_date' => $next_billing_date,
            ':payment_method' => $payment_method,
            ':service_type' => $plan_type
        ]);
        
        $subscription_id = $conn->lastInsertId();
        
        // 2. GENERARE NUMĂR FACTURĂ
        $invoice_number = 'INV-' . date('Ymd') . '-' . str_pad($subscription_id, 6, '0', STR_PAD_LEFT);
        
        // 3. INSERARE ÎN TABELUL INVOICES
        $invoice_stmt = $conn->prepare("INSERT INTO invoices 
            (user_id, subscription_id, invoice_number, amount, currency, status, due_date) 
            VALUES (:user_id, :subscription_id, :invoice_number, :amount, 'RON', 'paid', DATE_ADD(NOW(), INTERVAL 30 DAY))");
        
        $invoice_stmt->execute([
            ':user_id' => $user_id,
            ':subscription_id' => $subscription_id,
            ':invoice_number' => $invoice_number,
            ':amount' => $amount
        ]);
        
        // 4. INSERARE ÎN TABELUL PAYMENTS (doar dacă tabelul există)
        try {
            $payment_stmt = $conn->prepare("INSERT INTO payments 
                (user_id, subscription_id, invoice_number, amount, currency, payment_method, status, payment_date) 
                VALUES (:user_id, :subscription_id, :invoice_number, :amount, 'RON', :payment_method, 'completed', NOW())");
            
            $payment_stmt->execute([
                ':user_id' => $user_id,
                ':subscription_id' => $subscription_id,
                ':invoice_number' => $invoice_number,
                ':amount' => $amount,
                ':payment_method' => $payment_method
            ]);
        } catch (PDOException $e) {
            error_log("Eroare la inserarea în payments: " . $e->getMessage());
            // Continuă procesul chiar dacă inserarea în payments eșuează
        }
        
        // 5. INSERARE ÎN TABELUL SERVICES
        $service_name = $plan_name . ' - ' . ucfirst($plan_type);
        $service_type = $plan_type === 'vps' ? 'vps' : ($plan_type === 'business' ? 'business' : 'gazduire_web');
        
        $service_stmt = $conn->prepare("INSERT INTO services 
            (user_id, subscription_id, service_name, service_type, status, price, expires_at) 
            VALUES (:user_id, :subscription_id, :service_name, :service_type, 'active', :price, DATE_ADD(NOW(), INTERVAL 1 MONTH))");
        
        $service_stmt->execute([
            ':user_id' => $user_id,
            ':subscription_id' => $subscription_id,
            ':service_name' => $service_name,
            ':service_type' => $service_type,
            ':price' => $amount
        ]);
        
        // 6. ACTUALIZARE TABEL USERS (dacă ai coloana current_subscription_id)
        // Verifică dacă coloana există înainte de a încerca să o actualizezi
        $check_column = $conn->query("SHOW COLUMNS FROM users LIKE 'current_subscription_id'");
        if ($check_column->rowCount() > 0) {
            $update_user = $conn->prepare("UPDATE users SET current_subscription_id = :subscription_id WHERE id = :user_id");
            $update_user->execute([
                ':subscription_id' => $subscription_id,
                ':user_id' => $user_id
            ]);
        }
        
        return $subscription_id;
        
    } catch(PDOException $e) {
        error_log("Eroare la salvarea abonamentului: " . $e->getMessage());
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Sentra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sentra-cyan {
            background-color: #00BFB2;
        }
        .font-inter {
            font-family: 'Inter', sans-serif;
        }
        
        /* Stiluri pentru notificare */
        .notification {
            position: fixed;
            top: 100px;
            right: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 20px;
            max-width: 400px;
            z-index: 1000;
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border-left: 4px solid #ef4444;
        }
        
        .notification.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .notification.success {
            border-left-color: #10b981;
        }
        
        .notification.error {
            border-left-color: #ef4444;
        }
        
        .notification-content {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .notification-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .notification.success .notification-icon {
            background: #10b981;
        }
        
        .notification.error .notification-icon {
            background: #ef4444;
        }
        
        .notification-message {
            flex: 1;
        }
        
        .notification-close {
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .notification-close:hover {
            background: #f3f4f6;
            color: #374151;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-inter">
    <!-- Notificare -->
    <div id="notification" class="notification hidden">
        <div class="notification-content">
            <div class="notification-icon">
                <i class="fas fa-exclamation-circle text-white text-xs"></i>
            </div>
            <div class="notification-message">
                <h4 class="font-semibold text-gray-900 text-sm" id="notification-title">Eroare</h4>
                <p class="text-gray-600 text-sm mt-1" id="notification-message"></p>
            </div>
            <button class="notification-close" onclick="hideNotification()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="../index.php" class="flex-shrink-0 flex items-center">
                    <span class="text-3xl font-extrabold text-[#1A1A1A]">Sentra</span>
                    <div class="w-2 h-2 ml-1 sentra-cyan rounded-full"></div>
                </a>
                <div class="text-sm text-gray-600 flex items-center">
                    <i class="fas fa-lock text-green-500 mr-2"></i>
                    Checkout Securizat
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb simplu -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li>
                        <a href="../index.php" class="hover:text-[#00BFB2] transition-colors">Acasă</a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <span class="text-[#00BFB2] font-medium">Finalizare Comandă</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Finalizare Comandă</h1>
            <p class="text-gray-600 mt-2">Completează detaliile de plată pentru a activa serviciul</p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Detalii Comandă -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Plan Selectat -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-[#00BFB2] p-6 text-white">
                        <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($plan_name); ?></h2>
                        <p class="opacity-90">Abonament <?php echo ucfirst($billing_cycle); ?></p>
                    </div>
                    
                    <div class="p-6">
                        <?php if ($selected_plan && isset($selected_plan['features'])): ?>
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-star text-yellow-500 mr-2"></i>
                            Caracteristici Include:
                        </h3>
                        <ul class="space-y-3">
                            <?php foreach ($selected_plan['features'] as $feature): ?>
                            <li class="flex items-center text-gray-700">
                                <i class="fas fa-check text-green-500 mr-3 w-5"></i>
                                <?php echo htmlspecialchars($feature); ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Formular Plată -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-credit-card text-[#00BFB2] mr-3"></i>
                        Detalii Plată
                    </h2>
                    
                    <?php if (isset($error)): ?>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                                <p class="text-red-700"><?php echo $error; ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-6" id="payment-form">
                        <!-- Metodă de Plată -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Metodă de Plată
                            </label>
                            <div class="grid grid-cols-3 gap-4">
                                <label class="relative">
                                    <input type="radio" name="payment_method" value="card" class="sr-only" checked>
                                    <div class="border-2 border-[#00BFB2] rounded-lg p-4 text-center cursor-pointer bg-blue-50 payment-method-card">
                                        <i class="fas fa-credit-card text-[#00BFB2] text-xl mb-2"></i>
                                        <p class="text-sm font-medium">Card Bancar</p>
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="payment_method" value="paypal" class="sr-only">
                                    <div class="border-2 border-gray-200 rounded-lg p-4 text-center cursor-pointer transition-all hover:border-[#00BFB2] payment-method-paypal">
                                        <i class="fab fa-paypal text-blue-500 text-xl mb-2"></i>
                                        <p class="text-sm font-medium">PayPal</p>
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="payment_method" value="transfer" class="sr-only">
                                    <div class="border-2 border-gray-200 rounded-lg p-4 text-center cursor-pointer transition-all hover:border-[#00BFB2] payment-method-transfer">
                                        <i class="fas fa-university text-green-500 text-xl mb-2"></i>
                                        <p class="text-sm font-medium">Transfer</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Detalii Card -->
                        <div id="card-details">
                            <!-- Detinator Card -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nume detinator card *
                                </label>
                                <input type="text" name="card_holder" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFB2] focus:border-transparent transition-all"
                                    placeholder="EX: POPESCU ION">
                            </div>

                            <!-- Număr Card -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Număr card *
                                </label>
                                <div class="relative">
                                    <input type="text" name="card_number" required maxlength="19"
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFB2] focus:border-transparent transition-all"
                                        placeholder="1234 5678 9012 3456"
                                        oninput="formatCardNumber(this)">
                                    <i class="fas fa-credit-card absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Data expirării -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Data expirării *
                                    </label>
                                    <input type="text" name="expiry_date" required maxlength="5"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFB2] focus:border-transparent transition-all"
                                        placeholder="MM/AA"
                                        oninput="formatExpiryDate(this)">
                                </div>

                                <!-- CVV -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        CVV *
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="cvv" required maxlength="3"
                                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFB2] focus:border-transparent transition-all"
                                            placeholder="123">
                                        <i class="fas fa-question-circle absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-help" title="3 cifre pe spatele cardului"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buton Plată -->
                        <button type="submit"
                            class="w-full bg-[#00BFB2] text-white py-4 px-6 rounded-lg font-bold text-lg hover:bg-[#00a89c] transition-all duration-200 mt-6">
                            <i class="fas fa-lock mr-2"></i>
                            Finalizează Plata - <?php echo number_format($amount * 1.21, 2); ?> €
                        </button>

                        <div class="text-center">
                            <p class="text-xs text-gray-500 flex items-center justify-center">
                                <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                                Plata se face în mod securizat SSL. Datele tale sunt protejate.
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sumar Comandă -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 sticky top-24">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-receipt text-[#00BFB2] mr-2"></i>
                            Sumar Comandă
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Plan:</span>
                            <span class="font-semibold"><?php echo htmlspecialchars($plan_name); ?></span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Perioadă:</span>
                            <span class="font-semibold"><?php echo ucfirst($billing_cycle); ?></span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Cost abonament</span>
                                <span><?php echo number_format($amount, 2); ?> €</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Taxă setup</span>
                                <span class="text-green-600">0.00 €</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>TVA (21%)</span>
                                <span><?php echo number_format($amount * 0.21, 2); ?> €</span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span>Total</span>
                                <span class="text-[#00BFB2] text-xl">
                                    <?php echo number_format($amount * 1.21, 2); ?> €
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 bg-gray-50 rounded-b-xl">
                        <div class="flex items-center text-sm text-gray-600 mb-3">
                            <i class="fas fa-sync-alt mr-2 text-[#00BFB2]"></i>
                            <span>Reînnoire automată lunară</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clock mr-2 text-[#00BFB2]"></i>
                            <span>Activare imediată după plată</span>
                        </div>
                    </div>
                </div>

                <!-- Asistență -->
                <div class="bg-blue-50 rounded-xl border border-blue-200 p-6 mt-6">
                    <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                        <i class="fas fa-headset mr-2 text-blue-600"></i>
                        Asistență 24/7
                    </h4>
                    <p class="text-blue-700 text-sm mb-2">
                        <i class="fas fa-phone mr-2"></i>
                        +40 123 456 789
                    </p>
                    <p class="text-blue-700 text-sm">
                        <i class="fas fa-envelope mr-2"></i>
                        support@sentra.ro
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Formatare număr card
        function formatCardNumber(input) {
            let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = '';
            
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }
            
            input.value = formattedValue;
        }

        // Formatare data expirării
        function formatExpiryDate(input) {
            let value = input.value.replace(/\D/g, '');
            
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            
            input.value = value;
        }

        // Schimbare metodă de plată
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Reset toate border-urile
                document.querySelectorAll('[class*="payment-method-"]').forEach(el => {
                    el.classList.remove('border-[#00BFB2]', 'bg-blue-50');
                    el.classList.add('border-gray-200');
                });
                
                // Adaugă border la cel selectat
                const selected = document.querySelector(`.payment-method-${this.value}`);
                selected.classList.add('border-[#00BFB2]', 'bg-blue-50');
                selected.classList.remove('border-gray-200');
                
                // Ascunde/arată detaliile cardului
                const cardDetails = document.getElementById('card-details');
                if (this.value === 'card') {
                    cardDetails.style.display = 'block';
                } else {
                    cardDetails.style.display = 'none';
                }
            });
        });

        // Funcții pentru notificare
        function showNotification(message, type = 'error') {
            const notification = document.getElementById('notification');
            const title = document.getElementById('notification-title');
            const messageEl = document.getElementById('notification-message');
            
            notification.className = `notification ${type}`;
            title.textContent = type === 'success' ? 'Succes' : 'Eroare';
            messageEl.textContent = message;
            
            // Actualizează iconița
            const icon = notification.querySelector('.notification-icon i');
            icon.className = type === 'success' ? 'fas fa-check text-white text-xs' : 'fas fa-exclamation-circle text-white text-xs';
            
            notification.classList.remove('hidden');
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            // Ascunde automat după 5 secunde
            setTimeout(() => {
                hideNotification();
            }, 5000);
        }

        function hideNotification() {
            const notification = document.getElementById('notification');
            notification.classList.remove('show');
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 500);
        }

        // Validare formular
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            if (paymentMethod === 'card') {
                const cardNumber = document.querySelector('[name="card_number"]').value.replace(/\s/g, '');
                const expiryDate = document.querySelector('[name="expiry_date"]').value;
                const cvv = document.querySelector('[name="cvv"]').value;
                const cardHolder = document.querySelector('[name="card_holder"]').value;
                
                let isValid = true;
                let errorMessage = '';
                
                if (cardNumber.length !== 16) {
                    isValid = false;
                    errorMessage = 'Numărul cardului trebuie să aibă 16 cifre.';
                } else if (!/^\d{2}\/\d{2}$/.test(expiryDate)) {
                    isValid = false;
                    errorMessage = 'Data expirării trebuie să fie în format MM/AA.';
                } else if (cvv.length !== 3) {
                    isValid = false;
                    errorMessage = 'CVV-ul trebuie să aibă 3 cifre.';
                } else if (cardHolder.trim().length < 3) {
                    isValid = false;
                    errorMessage = 'Numele de pe card este obligatoriu.';
                }
                
                if (!isValid) {
                    e.preventDefault();
                    showNotification(errorMessage, 'error');
                    return;
                }
            }
        });

        // Animație la încărcare
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.bg-white').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    el.style.transition = 'all 0.6s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 100);
            });
        });
    </script>
</body>
</html>