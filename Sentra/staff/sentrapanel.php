<?php
// sentrapanel.php

// 1. INCLUDEM BARIERA DE SECURITATE
include '../includes/auth_check.php';

// 2. VERIFICĂ ACCESUL: Permite doar rolurilor 'staff' și 'admin'
check_access(['staff', 'admin'], 'dashboard.php');

// 3. Preluăm datele Staff-ului din sesiune
$admin_name = $_SESSION['user_name'] ?? 'Admin';

// 🔑 LINIA CORECTATĂ PENTRU AFISARE: Verificăm ambele chei, dar prioritizăm 'role'
$session_role_key = $_SESSION['role'] ?? $_SESSION['user_role'] ?? 'client';

// Folosim $display_role pentru sidebar
$display_role = ucfirst($session_role_key);

// -----------------------------------------------------------------------
// 🔑 LOGICĂ PENTRU PRELUAREA DATELOR DIN BAZA DE DATE (DB: sentra_db)
// -----------------------------------------------------------------------

// Date de conectare
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

// 4. Conectarea la baza de date
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    // În caz de eroare de conexiune
    $tickets_count = 0;
    $new_users_count = 0;
    $active_services_count = 1245;
    $recent_activity = []; // Setăm la array gol pentru a nu da eroare mai jos
    die("Eroare de conectare la baza de date: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Funcție utilitară pentru a obține un singur număr (COUNT) din DB
function get_single_count($conn, $query)
{
    $result = $conn->query($query);
    if ($result && $row = $result->fetch_row()) {
        return $row[0];
    }
    return 0;
}

// 1. Tickete Noi (Ultimele 24h)
$last_24h = date('Y-m-d H:i:s', strtotime('-24 hours'));
$tickets_count = get_single_count($conn, "SELECT COUNT(*) FROM tickets WHERE created_at >= '$last_24h'");

// 2. Înregistrări Noi (Luna Curentă)
$first_day_of_month = date('Y-m-01 00:00:00');
$new_users_count = get_single_count($conn, "SELECT COUNT(*) FROM users WHERE created_at >= '$first_day_of_month'");

// 3. Servicii Active Totale (Static, pentru a evita eroarea `services` lipsește)
$active_services_count = 1245;


// -----------------------------------------------------------------------
// 4. LOGICĂ PENTRU ACTIVITATE RECENTĂ (Tickete + Clienți Noi) - NOU!
// -----------------------------------------------------------------------

$recent_activity = [];

// 1. Interogare pentru Tickete Noi (JOIN cu users pentru a obține numele clientului)
$query_tickets = "
    SELECT 
        t.id AS item_id, 
        t.created_at, 
        u.first_name, 
        u.last_name, 
        'TICKET' AS activity_type
    FROM tickets t
    JOIN users u ON t.user_id = u.id
";

// 2. Interogare pentru Utilizatori Noi (Numele lor este deja disponibil)
$query_users = "
    SELECT 
        u.id AS item_id, 
        u.created_at, 
        u.first_name, 
        u.last_name, 
        'USER' AS activity_type
    FROM users u
";

// Combinăm ambele interogări și le sortăm după data creării, limitând la ultimele 10 acțiuni
$query_combined = "
    ($query_tickets)
    UNION ALL
    ($query_users)
    ORDER BY created_at DESC
    LIMIT 10
";

$result_recent = $conn->query($query_combined);

if ($result_recent) {
    while ($row = $result_recent->fetch_assoc()) {
        // Formatăm data și ora pentru afișare
        $timestamp = strtotime($row['created_at']);
        $row['data'] = date('d.m.Y', $timestamp);
        $row['ora'] = date('H:i', $timestamp);

        $recent_activity[] = $row;
    }
    $result_recent->free();
}

// 5. ÎNCHIDEM CONEXIUNEA LA BAZA DE DATE
$conn->close();
// -----------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f9;
        }

        .sentra-cyan {
            background-color: #00BFB2;
        }

        .text-sentra-cyan {
            color: #00BFB2;
        }

        .sidebar-link {
            @apply flex items-center space-x-3 p-3 rounded-lg font-semibold text-sm transition duration-150 hover:bg-gray-700 text-gray-300 hover:text-white;
        }
    </style>
</head>

<body class="flex h-screen">

    <?php include 'sentrasidebar.php'; ?>

    <main class="flex-1 p-10 overflow-y-auto">
        <header class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Bun venit, <?php echo htmlspecialchars($admin_name); ?>!</h2>
            <p class="text-gray-500 mt-1">Scurtă prezentare a statusului sistemului Sentra.</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-lg border border-l-4 border-sentra-cyan">
                <p class="text-sm font-medium text-gray-500">Tickete Noi (Ultimele 24h)</p>
                <div class="mt-1 flex items-center justify-between">
                    <span class="text-4xl font-extrabold text-[#1A1A1A]"><?php echo $tickets_count; ?></span>
                    <i data-lucide="inbox" class="w-8 h-8 text-sentra-cyan"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg border border-l-4 border-emerald-500">
                <p class="text-sm font-medium text-gray-500">Înregistrări Noi (Luna Curentă)</p>
                <div class="mt-1 flex items-center justify-between">
                    <span class="text-4xl font-extrabold text-[#1A1A1A]"><?php echo $new_users_count; ?></span>
                    <i data-lucide="user-plus" class="w-8 h-8 text-emerald-500"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg border border-l-4 border-amber-500">
                <p class="text-sm font-medium text-gray-500">Servicii Active Totale</p>
                <div class="mt-1 flex items-center justify-between">
                    <span class="text-4xl font-extrabold text-[#1A1A1A]">1245</span>
                    <i data-lucide="server" class="w-8 h-8 text-amber-500"></i>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <h3 class="text-xl font-bold mb-4">Activitate Recentă</h3>
            <div class="bg-white p-6 rounded-xl shadow-lg space-y-4">

                <?php if (!empty($recent_activity)): ?>
                    <?php foreach ($recent_activity as $activity):
                        // Pregătim variabilele comune
                        $full_name = htmlspecialchars($activity['first_name'] . ' ' . $activity['last_name']);
                        $date_time = '<span class="font-medium text-gray-800">' . htmlspecialchars($activity['data']) . '</span>, ora <span class="font-medium text-gray-800">' . htmlspecialchars($activity['ora']) . '</span>';
                        ?>
                        <p class="text-gray-700 border-b pb-2 last:border-b-0 last:pb-0">
                            <?php if ($activity['activity_type'] === 'TICKET'): ?>
                                Clientul
                                <strong class="text-sentra-cyan"><?php echo $full_name; ?></strong>
                                a creat un ticket cu ID-ul
                                <strong class="text-gray-900">#<?php echo htmlspecialchars($activity['item_id']); ?></strong>
                                la data de <?php echo $date_time; ?>.

                            <?php elseif ($activity['activity_type'] === 'USER'): ?>
                                Nou client
                                <strong class="text-emerald-500">
                                    (ID #<?php echo htmlspecialchars($activity['item_id']); ?>)
                                </strong>
                                a fost înregistrat la data de <?php echo $date_time; ?>. Nume:
                                <strong class="text-gray-900"><?php echo $full_name; ?></strong>.
                            <?php endif; ?>
                        </p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-600">Nu există activitate recentă înregistrată.</p>
                <?php endif; ?>

            </div>
        </div>

    </main>

    <script>
        // Inițializarea Iconițelor Lucide
        if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        }
    </script>
</body>

</html>