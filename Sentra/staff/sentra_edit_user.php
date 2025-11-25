<?php
// sentra_edit_user.php - Formular de Modificare a Datelor Clientului (Revizuit)

// 1. INCLUDEM BARIERA DE SECURITATE
include '../includes/auth_check.php';

// 2. VERIFICĂ ACCESUL: Permite doar rolului 'admin'
check_access(['admin'], 'dashboard.php');

// 🔑 Definirea variabilei $display_role pentru sidebar
// IMPORTANT: Această variabilă trebuie definită aici, ÎNAINTE de a include sidebar-ul
$display_role = ucfirst($_SESSION['role'] ?? 'Admin');

// 3. Conexiunea la baza de date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Eroare de conectare la baza de date: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 4. PRELUAREA ȘI VALIDAREA ID-ULUI CLIENTULUI
$client_id = $_GET['id'] ?? null;
if (isset($_POST['client_id'])) {
    $client_id = $_POST['client_id']; // Folosim ID-ul din POST la trimiterea formularului
}

if (!$client_id || !is_numeric($client_id)) {
    header("Location: sentraclients.php");
    exit;
}

$notification_message = '';
$notification_type = '';

// 5. LOGICA DE ACTUALIZARE A DATELOR (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Lista de câmpuri de actualizat (numele din DB = numele din formular)
    $fields = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company_name',
        'address_line_1',
        'address_line_2',
        'city',
        'postal_code',
        'country',
        'county',
        'reg_comert',
        'cod_fiscal',
        'role'
    ];

    $update_values = [];
    $param_types = '';
    $set_clauses = [];

    // Preluăm și pregătim datele pentru interogare
    foreach ($fields as $field) {
        // Folosim numele coloanei ca nume de variabilă în $update_values
        $update_values[] = $_POST[$field] ?? null;
        $set_clauses[] = "`{$field}` = ?";
        $param_types .= 's'; // Presupunem că majoritatea sunt string-uri
    }

    // Interogare SQL de actualizare (UPDATE)
    $sql_update = "UPDATE `users` SET " . implode(', ', $set_clauses) . " WHERE `id` = ?";

    $stmt = $conn->prepare($sql_update);

    // Adăugăm ID-ul la sfârșitul valorilor
    $update_values[] = $client_id;
    $param_types .= 'i'; // ID-ul este integer

    // Bind parameters - folosim call_user_func_array pentru a lega dinamic parametrii
    // Parametrii trebuie să fie referințe pentru bind_param, dar valorile din $update_values nu sunt.
    // Metoda cea mai simplă pentru a nu complica codul este prin listare:

    $bind_params = array_merge([$param_types], $update_values);

    // Corectie pentru bind_param: variabilele trebuie sa fie trimise prin referinta
    // $refs = [];
    // foreach($bind_params as $key => $value) {
    //     $refs[$key] = &$bind_params[$key];
    // }
    // call_user_func_array([$stmt, 'bind_param'], $refs);

    // Varianta mai simplă de listare explicită (mai puțin dinamică, dar mai sigură)
    if (count($update_values) !== 15) { // 14 câmpuri + 1 id
        $notification_message = 'Eroare internă: Număr incorect de câmpuri trimise.';
        $notification_type = 'red';
    } else {
        $stmt->bind_param(
            $param_types,
            $update_values[0],
            $update_values[1],
            $update_values[2],
            $update_values[3],
            $update_values[4],
            $update_values[5],
            $update_values[6],
            $update_values[7],
            $update_values[8],
            $update_values[9],
            $update_values[10],
            $update_values[11],
            $update_values[12],
            $update_values[13],
            $update_values[14]
        );
    }

    if ($stmt->execute()) {
        $notification_message = 'Datele clientului au fost actualizate cu succes!';
        $notification_type = 'green';
    } else {
        $notification_message = 'Eroare la actualizarea datelor: ' . $stmt->error;
        $notification_type = 'red';
    }
    $stmt->close();

    // Re-preluăm datele actualizate forțând un refresh logic
    // $client_id rămâne setat din $_POST['client_id']
}


// 6. PRELUAREA DATELOR CURENTE ALE CLIENTULUI (GET)
$sql_select = "SELECT 
    `id`, `first_name`, `last_name`, `email`, `phone`, `company_name`, 
    `address_line_1`, `address_line_2`, `city`, `postal_code`, `country`, 
    `county`, `reg_comert`, `cod_fiscal`, `role` 
    FROM `users` 
    WHERE `id` = ?";

$stmt = $conn->prepare($sql_select);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$client_data = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$client_data) {
    header("Location: sentraclients.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra Panel - Modifică Client #<?php echo htmlspecialchars($client_id); ?></title>
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

        /* Stiluri pentru notificare */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 15px 25px;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            color: white;
            opacity: 0;
            transition: opacity 0.5s, transform 0.5s;
            transform: translateY(-20px);
        }

        .notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        .notification.green {
            background-color: #4CAF50;
        }

        .notification.red {
            background-color: #F44336;
        }
    </style>
</head>

<body class="flex h-screen">

    <?php
    $current_page = 'sentraclients.php';
    // Acum $display_role ESTE definit înainte de includere!
    include 'sentrasidebar.php';
    ?>

    <main class="flex-1 p-10 overflow-y-auto">
        <header class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Modifică Client:
                    #<?php echo htmlspecialchars($client_id); ?></h2>
                <p class="text-gray-500 mt-1">Editează datele clientului
                    <strong><?php echo htmlspecialchars($client_data['first_name'] . ' ' . $client_data['last_name']); ?></strong>.
                </p>
            </div>
            <a href="sentraclients.php"
                class="bg-gray-500 text-white font-bold py-3 px-5 rounded-lg hover:bg-gray-600 transition duration-300 flex items-center shadow-md">
                <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Înapoi la Listă
            </a>
        </header>

        <div class="bg-white p-6 rounded-xl shadow-lg">
            <form method="POST" action="sentra_edit_user.php">
                <input type="hidden" name="client_id" value="<?php echo htmlspecialchars($client_id); ?>">

                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Date Personale & Contact</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Prenume</label>
                        <input type="text" name="first_name" id="first_name" required
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg"
                            value="<?php echo htmlspecialchars($client_data['first_name'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Nume de Familie</label>
                        <input type="text" name="last_name" id="last_name" required
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg"
                            value="<?php echo htmlspecialchars($client_data['last_name'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg"
                            value="<?php echo htmlspecialchars($client_data['email'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Telefon</label>
                        <input type="text" name="phone" id="phone"
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg"
                            value="<?php echo htmlspecialchars($client_data['phone'] ?? ''); ?>">
                    </div>
                </div>

                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Date Companie & Fiscale</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700">Nume Companie</label>
                        <input type="text" name="company_name" id="company_name"
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg"
                            value="<?php echo htmlspecialchars($client_data['company_name'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="cod_fiscal" class="block text-sm font-medium text-gray-700">Cod Fiscal/CUI</label>
                        <input type="text" name="cod_fiscal" id="cod_fiscal"
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg"
                            value="<?php echo htmlspecialchars($client_data['cod_fiscal'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="reg_comert" class="block text-sm font-medium text-gray-700">Registrul
                            Comerțului</label>
                        <input type="text" name="reg_comert" id="reg_comert"
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg"
                            value="<?php echo htmlspecialchars($client_data['reg_comert'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Rol/Status
                            (IMPORTANT!)</label>
                        <select name="role" id="role" class="mt-1 block w-full p-3 border border-gray-300 rounded-lg">
                            <?php
                            $current_role = strtolower($client_data['role'] ?? 'client');
                            $roles = ['client', 'staff', 'admin'];
                            foreach ($roles as $r):
                                ?>
                                <option value="<?php echo $r; ?>" <?php echo ($current_role === $r ? 'selected' : ''); ?>>
                                    <?php echo ucfirst($r); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Adresă</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="address_line_1" class="block text-sm font-medium text-gray-700">Adresă Linia
                            1</label>
                        <input type="text" name="address_line_1" id="address_line_1"
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg"
                            value="<?php echo htmlspecialchars($client_data['address_line_1'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="address_line_2" class="block text-sm font-medium text-gray-700">Adresă Linia 2
                            (Opțional)</label>
                        <input type="text" name="address_line_2" id="address_line_2"
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg"
                            value="<?php echo htmlspecialchars($client_data['address_line_2'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Oraș</label>
                        <input type="text" name="city" id="city"
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg"
                            value="<?php echo htmlspecialchars($client_data['city'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="county" class="block text-sm font-medium text-gray-700">Județ</label>
                        <input type="text" name="county" id="county"
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg"
                            value="<?php echo htmlspecialchars($client_data['county'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700">Țara</label>
                        <input type="text" name="country" id="country"
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg"
                            value="<?php echo htmlspecialchars($client_data['country'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Cod Poștal</label>
                        <input type="text" name="postal_code" id="postal_code"
                            class="mt-1 block w-full p-3 border border-gray-300 rounded-lg"
                            value="<?php echo htmlspecialchars($client_data['postal_code'] ?? ''); ?>">
                    </div>
                </div>

                <div class="mt-8 pt-4 border-t flex justify-end">
                    <button type="submit"
                        class="sentra-cyan text-white font-bold py-3 px-8 rounded-lg hover:opacity-90 transition duration-300 flex items-center shadow-md">
                        <i data-lucide="save" class="w-5 h-5 mr-2"></i> Salvează Modificările
                    </button>
                </div>
            </form>
        </div>

    </main>

    <div id="notification" class="notification <?php echo $notification_type; ?>" style="display:none;">
        <?php echo $notification_message; ?>
    </div>

    <script>
        // Inițializarea Iconițelor Lucide
        if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        }

        // Logica de afișare a notificării după salvare
        const notification = document.getElementById('notification');
        const message = "<?php echo addslashes($notification_message); ?>"; // Folosim addslashes pentru a evita probleme cu ghilimelele
        const type = "<?php echo $notification_type; ?>";

        if (message && message !== 'Eroare la actualizarea datelor: ') { // Verificăm dacă mesajul nu este gol sau doar mesajul standard de eroare fără detalii
            notification.style.display = 'block';
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);

            // Ascunde notificarea după 4 secunde
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 500);
            }, 4000);
        }
    </script>
</body>

</html>