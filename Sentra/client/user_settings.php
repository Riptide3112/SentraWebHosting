<?php
// user_settings.php

// 1. Sesiunea și Verificarea Autentificării
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Asigură-te că login.php există
    exit;
}

// Folosim calea absolută pentru includeri.
define('APP_ROOT', __DIR__);

// 2. Conectare la Baza de Date și Preluare Date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

$user_id = $_SESSION['user_id'];
$user_data = [];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Eroare de conectare la baza de date.");
}
$conn->set_charset("utf8mb4");

// PRELUAREA DATELOR: Includem 'role', 'postal_code' și 'reg_comert'
$stmt = $conn->prepare("
    SELECT 
        first_name, last_name, email, phone, company_name, 
        address_line_1, address_line_2, city, postal_code, country, county,
        reg_comert, cod_fiscal, role 
    FROM users 
    WHERE id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user_data = $result->fetch_assoc();
    // Extragem rolul utilizatorului
    $user_role = $user_data['role'] ?? 'client';

    // Verificăm dacă utilizatorul este Staff sau Admin
    $is_staff_or_admin = ($user_role === 'staff' || $user_role === 'admin');
    // ----------------------------------------------------

} else {
    // Dacă utilizatorul nu este găsit, distruge sesiunea și redirecționează
    session_destroy();
    header("Location: login.php");
    exit;
}

$stmt->close();
$conn->close();

// 3. Preluare Notificări din Sesiune
$notification = $_SESSION['notification'] ?? null;
unset($_SESSION['notification']);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra - Setări Cont</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>
    <style>
    /* PĂSTRAT CSS-ul tău original */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f8f8;
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

    .custom-input {
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .custom-input:focus {
        border-color: #00BFB2;
        box-shadow: 0 0 0 3px rgba(0, 191, 178, 0.25);
    }

    .gradient-sidebar {
        background: linear-gradient(135deg, #333333 0%, #00BFB2 100%);
        min-height: 100vh;
        max-height: 100vh;
        overflow-y: auto;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 20;
    }

    .main-container {
        width: 100%;
        display: flex;
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    .content-wrapper {
        flex-grow: 1;
        padding: 2rem;
        margin-left: 20%;
        min-height: calc(100vh - 40px);
        background-color: #f8f8f8;
        display: flex;
        flex-direction: column;
    }

    .content-area {
        flex-grow: 1;
        overflow-y: auto;
        padding-bottom: 2rem;
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        padding: 2.5rem;
    }

    .client-panel-footer-alignment {
        width: 80%;
        margin-left: 20%;
        background-color: #1A1A1A;
    }

    @media (max-width: 1024px) {
        .gradient-sidebar {
            display: none;
        }

        .content-wrapper {
            margin-left: 0;
            padding-top: 2rem;
        }

        .client-panel-footer-alignment {
            width: 100%;
            margin-left: 0;
        }
    }

    /* NOILE STILURI ADAUGATE */
    .btn-modern {
        background: linear-gradient(135deg, #00BFB2 0%, #009688 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 191, 178, 0.3);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 191, 178, 0.4);
    }
    
    .btn-modern:active {
        transform: translateY(0);
    }
    
    .title-modern {
        background: linear-gradient(135deg, #00BFB2 0%, #009688 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
</head>

<body class="flex min-h-screen bg-gray-50">

    <div class="main-container">
        <?php include APP_ROOT . '/dashboard_sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="content-area p-6 sm:p-10 lg:p-12 animate__animated animate__fadeIn">

                <?php if ($notification) {
                    $type = $notification['type'];
                    $text = $notification['text'];
                    ?>
                    <div id="notification" class="fixed top-8 left-1/2 -translate-x-1/2 z-50 px-6 py-3 rounded-xl shadow-lg text-white font-semibold 
                            opacity-0 scale-95 transition-all duration-500
                            <?php echo $type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'; ?>">
                        <?php echo htmlspecialchars($text); ?>
                    </div>
                <?php } ?>

                <!-- Header modernizat -->
                <div class="text-center mb-12 animate__animated animate__fadeInDown">
                    <h1 class="text-4xl sm:text-5xl font-extrabold title-modern mb-4">
                        Setări Cont
                    </h1>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        Gestionează-ți informațiile personale și de firmă
                    </p>
                    <div class="flex justify-center items-center gap-6 mt-4 text-sm text-gray-500">
                        <span class="bg-gray-100 px-3 py-1 rounded-full">
                            ID: <?php echo htmlspecialchars($user_id); ?>
                        </span>
                    </div>
                </div>

                <form action="../api/update_settings.php" method="POST" class="space-y-8">

                    <fieldset class="border-b border-gray-200 pb-8 animate__animated animate__fadeInLeft">
                        <legend class="text-xl font-semibold text-sentra-cyan mb-4">1. Informații Personale</legend>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div class="col-span-1">
                                <label for="first_name" class="block text-sm font-medium text-gray-700">Prenume <span
                                        class="text-red-500">*</span></label>
                                <input type="text" id="first_name" name="first_name" required
                                    value="<?php echo htmlspecialchars($user_data['first_name'] ?? ''); ?>"
                                    class="custom-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none">
                            </div>
                            <div class="col-span-1">
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Nume <span
                                        class="text-red-500">*</span></label>
                                <input type="text" id="last_name" name="last_name" required
                                    value="<?php echo htmlspecialchars($user_data['last_name'] ?? ''); ?>"
                                    class="custom-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none">
                            </div>
                            <div class="col-span-1">
                                <label for="email" class="block text-sm font-medium text-gray-700">Adresă Email</label>
                                <input type="email" id="email" name="email"
                                    value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>"
                                    class="custom-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none">
                            </div>
                            <div class="col-span-1">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Număr de Telefon
                                    <span class="text-red-500">*</span></label>
                                <input type="tel" id="phone" name="phone" required
                                    value="<?php echo htmlspecialchars($user_data['phone'] ?? ''); ?>"
                                    class="custom-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border-b border-gray-200 pb-8 animate__animated animate__fadeInRight">
                        <legend class="text-xl font-semibold text-sentra-cyan mb-4">2. Adresă Facturare și Detalii Firmă
                        </legend>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div class="md:col-span-2">
                                <label for="company_name" class="block text-sm font-medium text-gray-700">Nume Companie
                                    (Opțional)</label>
                                <input type="text" id="company_name" name="company_name"
                                    value="<?php echo htmlspecialchars($user_data['company_name'] ?? ''); ?>"
                                    class="custom-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none">
                            </div>

                            <div class="md:col-span-2">
                                <label for="address_line_1" class="block text-sm font-medium text-gray-700">Adresă 1
                                    (Strada/Număr) <span class="text-red-500">*</span></label>
                                <input type="text" id="address_line_1" name="address_line_1" required
                                    value="<?php echo htmlspecialchars($user_data['address_line_1'] ?? ''); ?>"
                                    class="custom-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label for="address_line_2" class="block text-sm font-medium text-gray-700">Adresă 2
                                    (Apartament, Bloc, Scara - Opțional)</label>
                                <input type="text" id="address_line_2" name="address_line_2"
                                    value="<?php echo htmlspecialchars($user_data['address_line_2'] ?? ''); ?>"
                                    class="custom-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none">
                            </div>

                            <div class="col-span-1">
                                <label for="city" class="block text-sm font-medium text-gray-700">Oraș / Sector <span
                                        class="text-red-500">*</span></label>
                                <input type="text" id="city" name="city" required
                                    value="<?php echo htmlspecialchars($user_data['city'] ?? ''); ?>"
                                    class="custom-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none">
                            </div>

                            <div class="col-span-1">
                                <label for="postal_code" class="block text-sm font-medium text-gray-700">Cod Poștal
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="postal_code" name="postal_code" required
                                    value="<?php echo htmlspecialchars($user_data['postal_code'] ?? ''); ?>"
                                    class="custom-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none">
                            </div>

                            <div class="col-span-1">
                                <label for="country" class="block text-sm font-medium text-gray-700">Țara <span
                                        class="text-red-500">*</span></label>
                                <input type="text" id="country" name="country" required
                                    value="<?php echo htmlspecialchars($user_data['country'] ?? ''); ?>"
                                    class="custom-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none">
                            </div>

                            <div class="col-span-1">
                                <label for="county" class="block text-sm font-medium text-gray-700">Județul / Regiune
                                    <span class="text-red-500">*</span></label>
                                <input type="text" id="county" name="county" required
                                    value="<?php echo htmlspecialchars($user_data['county'] ?? ''); ?>"
                                    class="custom-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none">
                            </div>

                            <div class="col-span-1">
                                <label for="reg_comert" class="block text-sm font-medium text-gray-700">Reg. Comerțului
                                    (Opțional)</label>
                                <input type="text" id="reg_comert" name="reg_comert"
                                    value="<?php echo htmlspecialchars($user_data['reg_comert'] ?? ''); ?>"
                                    class="custom-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none">
                            </div>

                            <div class="col-span-1">
                                <label for="cod_fiscal" class="block text-sm font-medium text-gray-700">Cod Fiscal
                                    (Opțional)</label>
                                <input type="text" id="cod_fiscal" name="cod_fiscal"
                                    value="<?php echo htmlspecialchars($user_data['cod_fiscal'] ?? ''); ?>"
                                    class="custom-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm outline-none">
                            </div>
                        </div>
                    </fieldset>

                    <!-- Buton modernizat -->
                    <div class="pt-4 flex justify-center animate__animated animate__fadeInUp">
                        <button type="submit" class="btn-modern">
                            <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                            Salvează Modificările
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="client-panel-footer-alignment">
        <?php include APP_ROOT . '/footer.php'; ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const n = document.getElementById("notification");
            if (n) {
                setTimeout(() => n.classList.remove("opacity-0", "scale-95"), 50);
                setTimeout(() => n.classList.add("opacity-0", "scale-95"), 3500);
                setTimeout(() => n.remove(), 4200);
            }
            lucide.createIcons();
            
            // Adaugă animație de intrare pentru input fields
            const inputs = document.querySelectorAll('.custom-input');
            inputs.forEach((input, index) => {
                input.style.opacity = '0';
                input.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    input.style.transition = 'all 0.5s ease';
                    input.style.opacity = '1';
                    input.style.transform = 'translateY(0)';
                }, 100 + (index * 50));
            });
        });
    </script>

</body>
</html>