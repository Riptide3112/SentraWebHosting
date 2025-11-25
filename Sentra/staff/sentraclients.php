<?php
// sentraclients.php - Listare și Gestiune Utilizatori (Staff/Admin)

// 1. INCLUDEM BARIERA DE SECURITATE
include '../includes/auth_check.php';

// 2. VERIFICĂ ACCESUL: Permite doar rolurilor 'staff' și 'admin'
check_access(['staff', 'admin'], 'dashboard.php');

// 3. Preluăm datele Staff-ului din sesiune și Rolul Utilizatorului Logat

// Determinăm cheia corectă a sesiunii pentru rol (verificăm 'role' și 'user_role')
$session_role_key = $_SESSION['role'] ?? $_SESSION['user_role'] ?? 'client';

$admin_name = $_SESSION['user_name'] ?? 'Admin';

// 🔑 CORECȚIE 1 (pentru sidebar): Definirea variabilei $display_role
$display_role = ucfirst($session_role_key);

// 🔑 CORECȚIE 2 (pentru logica internă): Folosim rolul cu litere mici
$logged_in_user_role = strtolower($session_role_key);


// 🔑 LOGICĂ PENTRU CĂUTARE ȘI FILTRARE
$search_query = $_GET['search_query'] ?? '';
$status_filter = $_GET['status_filter'] ?? 'all';

// 4. Conexiunea la baza de date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Eroare de conectare la baza de date: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 5. CONSTRUIREA INTEROGĂRII SQL CU FILTRE
// Păstrăm toate coloanele pentru a le putea afișa în pop-up
$sql_base = "SELECT 
    id, first_name, last_name, email, phone, 
    company_name, address_line_1, address_line_2, city, postal_code, country, 
    county, reg_comert, cod_fiscal, found_us
    FROM users";
$where_clauses = [];
$params = [];
$param_types = '';

// Adaugă clauza de căutare
if (!empty($search_query)) {
    $where_clauses[] = "(LOWER(first_name) LIKE ? OR LOWER(last_name) LIKE ? OR LOWER(email) LIKE ? OR LOWER(company_name) LIKE ?)";
    $search_term = '%' . strtolower($search_query) . '%';
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $param_types .= 'ssss';
}

$sql = $sql_base;
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}
$sql .= " LIMIT 20";

// Execuția interogării
$users = [];
if (!empty($params)) {
    $stmt = $conn->prepare($sql);

    // Corecția debind_param a fost eliminată pentru a funcționa cu PHP 8.0+
    $bind_params = array_merge([$param_types], $params);
    $stmt->bind_param(...$bind_params);

    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();

// Funcție utilitară pentru a extrage detaliile complete pentru pop-up
function get_user_details($user)
{
    // Excludem câmpurile sensibile sau inutile pentru afișarea publică/administrare
    $excluded_fields = ['password', 'security_question', 'security_answer'];

    $details = [];
    foreach ($user as $key => $value) {
        if (!in_array($key, $excluded_fields)) {
            // Folosim un switch/case pentru a formata numele cheilor (titlurile tabelului pop-up)
            switch ($key) {
                case 'id':
                    $label = 'ID Client';
                    break;
                case 'first_name':
                    $label = 'Prenume';
                    break;
                case 'last_name':
                    $label = 'Nume de Familie';
                    break;
                case 'email':
                    $label = 'Adresă Email';
                    break;
                case 'phone':
                    $label = 'Telefon';
                    break;
                case 'company_name':
                    $label = 'Nume Companie';
                    break;
                case 'address_line_1':
                    $label = 'Adresă Linia 1';
                    break;
                case 'address_line_2':
                    $label = 'Adresă Linia 2';
                    break;
                case 'city':
                    $label = 'Oraș';
                    break;
                case 'postal_code':
                    $label = 'Cod Poștal';
                    break;
                case 'country':
                    $label = 'Țara';
                    break;
                case 'county':
                    $label = 'Județ';
                    break;
                case 'reg_comert':
                    $label = 'Reg. Comerț';
                    break;
                case 'cod_fiscal':
                    $label = 'Cod Fiscal/CUI';
                    break;
                case 'found_us':
                    $label = 'Sursa Client';
                    break;
                default:
                    $label = ucfirst(str_replace('_', ' ', $key));
            }
            $details[$label] = htmlspecialchars($value ?? '-');
        }
    }
    return $details;
}
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra Panel - Utilizatori</title>
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

        .sidebar-link.active {
            @apply bg-gray-700 text-white;
        }

        /* STIL NOU: Ascunde scrollbar-ul lateral (overflow-x) la nivel de corp */
        main {
            overflow-x: hidden;
        }

        .overflow-x-auto {
            overflow-x: auto;
        }

        /* Stil pentru Modal (Pop-up) */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 25px;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body class="flex h-screen">

    <?php
    $current_page = 'sentraclients.php';
    include 'sentrasidebar.php';
    ?>

    <main class="flex-1 p-10 overflow-y-auto">
        <header class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Gestiunea Utilizatorilor</h2>
                <p class="text-gray-500 mt-1">Vizualizați, editați și gestionați toate conturile din sistem.</p>
            </div>
        </header>

        <form method="GET" action="sentraclients.php">
            <div class="bg-white p-4 rounded-xl shadow-lg mb-6 flex space-x-4">
                <input type="text" name="search_query" placeholder="Caută după nume sau email..."
                    class="flex-1 p-3 border border-gray-300 rounded-lg focus:ring-sentra-cyan focus:border-sentra-cyan"
                    value="<?php echo htmlspecialchars($search_query); ?>">

                <button type="submit"
                    class="sentra-cyan text-white font-bold py-3 px-5 rounded-lg hover:opacity-90 transition duration-300 flex items-center shadow-md">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>
            </div>
        </form>

        <div class="bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
            <h3 class="text-xl font-bold mb-4">
                Lista Utilizatorilor
                <?php
                $user_count = count($users);

                if (!empty($search_query)):
                    echo "($user_count Rezultat" . ($user_count !== 1 ? "e" : "") . " pentru: \"" . htmlspecialchars($search_query) . "\")";
                else:
                    echo "(Afisare $user_count Utilizator" . ($user_count !== 1 ? "i" : "") . ")";
                endif;
                ?>
            </h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                            CLIENT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nume
                            Complet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Telefon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Orașul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Țara
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cod
                            Fiscal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acțiuni</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $num_columns = 8; // Numărul de coloane vizibile
                    if (empty($users)):
                        ?>
                        <tr>
                            <td colspan="<?php echo $num_columns; ?>" class="px-6 py-4 text-center text-gray-500">Niciun
                                utilizator găsit.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($user['id']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($user['city'] ?? '-'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($user['country'] ?? '-'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($user['cod_fiscal'] ?? '-'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <button
                                        onclick="showDetails(<?php echo htmlspecialchars(json_encode(get_user_details($user))); ?>)"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        Vizualizează
                                    </button>

                                    <?php if ($logged_in_user_role === 'admin'): ?>
                                        <a href="sentra_edit_user.php?id=<?php echo $user['id']; ?>"
                                            class="text-sentra-cyan hover:text-cyan-700">
                                            Modifică
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

    <div id="userDetailsModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-xl font-bold text-gray-900">Detalii Complete Client</h3>
                <button onclick="closeModal()"
                    class="text-gray-500 hover:text-gray-900 text-3xl leading-none">&times;</button>
            </div>
            <table class="min-w-full divide-y divide-gray-200" id="detailsTable">
            </table>
        </div>
    </div>

    <script>
        // Inițializarea Iconițelor Lucide
        if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        }

        const modal = document.getElementById('userDetailsModal');
        const detailsTable = document.getElementById('detailsTable');

        // Funcția de deschidere a Pop-up-ului
        function showDetails(details) {
            let tableContent = '';

            // Iterăm prin detaliile primite de la PHP
            for (const [key, value] of Object.entries(details)) {
                tableContent += `
                    <tr class="border-b">
                        <td class="py-2 pr-4 text-sm font-semibold text-gray-700">${key}:</td>
                        <td class="py-2 text-sm text-gray-500">${value}</td>
                    </tr>
                `;
            }

            detailsTable.innerHTML = `<tbody class="divide-y divide-gray-100">${tableContent}</tbody>`;
            modal.style.display = 'block';
        }

        // Funcția de închidere a Pop-up-ului
        function closeModal() {
            modal.style.display = 'none';
        }

        // Închide modalul dacă utilizatorul dă click în afara lui
        window.onclick = function (event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>