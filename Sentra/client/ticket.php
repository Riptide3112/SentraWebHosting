<?php
// client/ticket.php

session_start();

// 1. VERIFICAREA AUTENTIFICĂRII
if (!isset($_SESSION['user_id'])) {
    $_SESSION['notification'] = ['type' => 'error', 'text' => 'Te rugăm să te autentifici pentru a accesa această pagină.'];
    header("Location: ../pages/login.php");
    exit;
}

// FIX IMPORTANT PENTRU SIDEBAR: Definirea rolului utilizatorului
$user_role = $_SESSION['user_role'] ?? 'client'; // Valoare implicită
$is_staff_or_admin = ($user_role === 'staff' || $user_role === 'admin');

// Date de conectare
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";

// Preluăm ID-ul și Numele utilizatorului
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Utilizator';

// 🔴 NOU: VERIFICARE PENTRU AUTO-DESCHIDERE TICHET
$auto_open_ticket_id = null;
if (isset($_GET['open_ticket']) && is_numeric($_GET['open_ticket'])) {
    $auto_open_ticket_id = (int) $_GET['open_ticket'];

    // Verifică dacă tichetul aparține utilizatorului (pentru securitate)
    $conn_check = new mysqli($servername, $username, $password, $dbname);
    if (!$conn_check->connect_error) {
        $conn_check->set_charset("utf8mb4");
        $stmt = $conn_check->prepare("SELECT id FROM tickets WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $auto_open_ticket_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // Tichetul nu aparține utilizatorului sau nu există
            $auto_open_ticket_id = null;
            $_SESSION['notification'] = ['type' => 'error', 'text' => 'Tichetul nu a fost găsit sau nu ai acces la el.'];
        }
        $stmt->close();
        $conn_check->close();
    }
}

// 2. PROCESAREA FORMULARULUI (POST) - CREARE TIChet
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_ticket'])) {

    // Conectare la DB
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $_SESSION['notification'] = ['type' => 'error', 'text' => 'Eroare de conectare la baza de date. Vă rugăm să reîncercați.'];
        header("Location: ticket.php");
        exit;
    }
    $conn->set_charset("utf8mb4");

    // Preluare și curățare date
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $priority = $_POST['priority'];
    $cause = $_POST['cause'];

    // Definirea statusului inițial
    $status = 'open';

    // Interogarea de inserare în tabela tickets
    $stmt = $conn->prepare("INSERT INTO tickets (user_id, subject, content, status, priority, cause) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $subject, $message, $status, $priority, $cause);

    if ($stmt->execute()) {
        $ticket_id = $stmt->insert_id; // Preluăm ID-ul tichetului nou inserat

        // ** CORECȚIE CRITICĂ AICI: Folosim sender_id și sender_role **
        $stmt_msg = $conn->prepare("INSERT INTO ticket_messages (ticket_id, sender_id, sender_role, content) VALUES (?, ?, ?, ?)");
        $user_role_for_message = $user_role; // Rolul clientului care deschide

        // Conținutul primului mesaj este descrierea tichetului
        $stmt_msg->bind_param("iiss", $ticket_id, $user_id, $user_role_for_message, $message);
        $stmt_msg->execute();
        $stmt_msg->close();

        $_SESSION['notification'] = ['type' => 'success', 'text' => 'Tichetul a fost deschis cu succes!'];
    } else {
        $_SESSION['notification'] = ['type' => 'error', 'text' => 'Eroare la salvarea tichetului: ' . $stmt->error];
    }

    $stmt->close();
    $conn->close();

    // Redirecționează (PRG Pattern)
    header("Location: ticket.php");
    exit;
}

// 2.1 PRELUAREA TICHETELOR PENTRU UTILIZATORUL CURENT
// Deschidem conexiunea pentru preluarea datelor
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Eroare de conectare la baza de date: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Funcție ajutătoare pentru a prelua tichetele după status
function get_user_tickets($conn, $user_id, $status_type)
{
    if ($status_type === 'open') {
        $status_condition = "status != 'closed' AND status != 'resolved'";
    } else {
        $status_condition = "status = 'closed' OR status = 'resolved'";
    }

    $sql = "SELECT id, subject, content, priority, cause, created_at, status FROM tickets WHERE user_id = ? AND ({$status_condition}) ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tickets = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $tickets;
}

$open_tickets = get_user_tickets($conn, $user_id, 'open');
$closed_tickets = get_user_tickets($conn, $user_id, 'closed');

$conn->close();

// 3. AFIȘAREA NOTIFICĂRILOR (După redirecționare)
$notification_data = null;
if (isset($_SESSION['notification'])) {
    $notification_data = $_SESSION['notification'];
    unset($_SESSION['notification']);
}

// Funcție ajutătoare pentru a formata Tipul Solicitării (cause)
function formatCause($cause)
{
    return ucwords(str_replace('_', ' ', $cause));
}

// NOU: Funcție pentru a genera textul statusului (pentru afișarea în tabel)
function getStatusDisplayText($status_key)
{
    switch ($status_key) {
        case 'open':
            return 'Deschis';
        case 'in_progress':
            return 'În Curs';
        case 'awaiting_client':
            return 'Așteptă Răspuns';
        case 'closed':
            return 'Închis';
        case 'resolved':
            return 'Rezolvat';
        default:
            return ucfirst($status_key);
    }
}

// NOU: Funcție pentru a genera clasa CSS pentru status
function getStatusCssClass($status_key)
{
    switch ($status_key) {
        case 'open':
            return 'status-open';
        case 'in_progress':
            return 'status-in-progress';
        case 'awaiting_client':
            return 'status-awaiting';
        case 'closed':
            return 'status-closed';
        case 'resolved':
            return 'status-resolved';
        default:
            return 'status-closed';
    }
}
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra - Centru de Suport</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

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

        /* Stil pentru butonul de Trimitere (Verde) */
        .submit-button-green {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            transition: all 0.3s ease;
        }

        .submit-button-green:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        /* Gradientul Meniului Lateral (Sidebar Fix) */
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
            padding: 2rem;
            margin-left: 20%;
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
            padding: 3rem;
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

        /* Input fields moderne */
        .modern-input {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 191, 178, 0.2);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .modern-input:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: #00BFB2;
            box-shadow: 0 0 0 3px rgba(0, 191, 178, 0.1);
        }

        /* Tabele moderne */
        .modern-table {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .modern-table thead {
            background: linear-gradient(135deg, #00BFB2 0%, #009688 100%);
        }

        .modern-table th {
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .modern-table tbody tr:hover {
            background: rgba(0, 191, 178, 0.05);
            transform: scale(1.01);
        }

        /* Badge-uri moderne */
        .modern-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .modern-badge:hover {
            transform: scale(1.05);
        }

        /* Stiluri pentru Priority/Status */
        .priority-high {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: white;
        }

        .priority-medium {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: white;
        }

        .priority-low {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
            color: white;
        }

        /* Stiluri pentru Statusuri */
        .status-open {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
        }

        .status-in-progress {
            background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
            color: white;
        }

        .status-awaiting {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
            color: white;
        }

        .status-closed {
            background: linear-gradient(135deg, #6B7280 0%, #4B5563 100%);
            color: white;
        }

        .status-resolved {
            background: linear-gradient(135deg, #047857 0%, #065F46 100%);
            color: white;
        }

        /* Stiluri pentru MODAL - IMPEDĂ SCROLL ÎN FUNDAL */
        .modal-open {
            overflow: hidden;
        }

        .modern-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            animation: fadeIn 0.3s ease;
            overflow-y: auto; /* Permite scroll doar în modal */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .modern-modal-content {
            position: relative;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            margin: 5% auto;
            padding: 2.5rem;
            border-radius: 1.5rem;
            max-width: 900px;
            max-height: 90vh; /* Înălțime maximă pentru modal */
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideIn 0.3s ease;
            overflow: hidden; /* Ascunde overflow-ul din containerul principal */
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modern-close-btn {
            color: white;
            float: right;
            font-size: 1.5rem;
            font-weight: bold;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .modern-close-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }

        /* Container pentru conversație cu scroll propriu */
        #modalTicketConversation {
            max-height: 400px; /* Înălțime fixă pentru conversație */
            overflow-y: auto; /* Scroll doar în interiorul conversației */
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 1rem;
            border: 1px solid rgba(0, 191, 178, 0.1);
        }

        /* Personalizare scrollbar pentru conversație */
        #modalTicketConversation::-webkit-scrollbar {
            width: 6px;
        }

        #modalTicketConversation::-webkit-scrollbar-track {
            background: rgba(0, 191, 178, 0.1);
            border-radius: 3px;
        }

        #modalTicketConversation::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #00BFB2 0%, #009688 100%);
            border-radius: 3px;
        }

        #modalTicketConversation::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #009688 0%, #00897B 100%);
        }

        /* Pentru Firefox */
        #modalTicketConversation {
            scrollbar-width: thin;
            scrollbar-color: #00BFB2 rgba(0, 191, 178, 0.1);
        }

        /* Stiluri pentru Mesaje */
        .message-staff {
            background: linear-gradient(135deg, #E0F2F1 0%, #B2DFDB 100%);
            border-left: 4px solid #00BFB2;
            padding: 1.5rem;
            border-radius: 1rem;
            margin: 1rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            animation: messageSlideIn 0.4s ease;
        }

        .message-client {
            background: linear-gradient(135deg, #F3F4F6 0%, #E5E7EB 100%);
            border-left: 4px solid #3B82F6;
            padding: 1.5rem;
            border-radius: 1rem;
            margin: 1rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            animation: messageSlideIn 0.4s ease;
        }

        @keyframes messageSlideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Butoane moderne */
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

        /* Animatie pentru încărcare */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        /* Efect de hover pentru card-uri */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
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
            width: 80%;
            margin-left: 20%;
            background: linear-gradient(135deg, #1A1A1A 0%, #2D2D2D 100%);
        }

        /* Reglaje pentru rezoluții mici (mobile) */
        @media (max-width: 1024px) {
            .content-wrapper {
                margin-left: 0;
                padding-top: 2rem;
            }

            .client-panel-footer-alignment {
                width: 100%;
                margin-left: 0;
            }

            .modern-modal-content {
                margin: 10% 5%;
                padding: 1.5rem;
                max-height: 85vh; /* Mai mult spațiu pe mobile */
            }
            
            #modalTicketConversation {
                max-height: 300px; /* Înălțime mai mică pe mobile */
            }
        }

        @media (max-width: 768px) {
            .modern-modal-content {
                margin: 10% 3%;
                padding: 1.5rem;
            }
            
            #modalTicketConversation {
                max-height: 300px;
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .modern-modal-content {
                margin: 5% 3%;
                padding: 1rem;
                max-height: 90vh;
            }
            
            #modalTicketConversation {
                max-height: 250px;
                padding: 1rem;
            }
        }
    </style>
    <script src="https://unpkg.com/lucide@0.306.0/dist/umd/lucide.min.js"></script>
</head>

<body>

    <div class="main-container">

        <?php
        include 'dashboard_sidebar.php';
        ?>

        <div class="content-wrapper">
            <div id="content-area" class="content-area">

                <!-- Section Header cu animație -->
                <div class="text-center mb-12 animate__animated animate__fadeInDown">
                    <h1 class="text-4xl sm:text-5xl font-extrabold gradient-text mb-4">
                        Centrul de Suport Sentra.
                    </h1>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        Echipa noastră de suport este aici să vă ajute. Deschideți un tichet nou sau verificați statusul
                        celor existente.
                    </p>
                </div>

                <!-- Formular creare tichet -->
                <div id="ticket-creation-form" class="modern-card p-8 mb-12 animate__animated animate__fadeInUp">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-sentra-cyan to-teal-500 rounded-xl flex items-center justify-center mr-4">
                            <i data-lucide="plus" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Deschide Tichet Nou</h2>
                            <p class="text-gray-600">Completează formularul de mai jos pentru a deschide un nou tichet
                                de suport</p>
                        </div>
                    </div>

                    <form id="ticket-form" method="POST" action="ticket.php" class="grid grid-cols-1 gap-6">
                        <div class="col-span-1">
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subiect
                                Tichet</label>
                            <input type="text" id="subject" name="subject" required
                                placeholder="Ex: Eroare la baza de date, Reînnoire domeniu"
                                class="modern-input w-full rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-sentra-cyan transition-all duration-300">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Nivel
                                    Prioritate</label>
                                <select id="priority" name="priority"
                                    class="modern-input w-full rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-sentra-cyan transition-all duration-300">
                                    <option value="low">Scăzută (Răspuns în 48h)</option>
                                    <option value="medium" selected>Medie (Răspuns în 24h)</option>
                                    <option value="high" class="text-red-600">Urgentă (Răspuns în 1h)</option>
                                </select>
                            </div>

                            <div>
                                <label for="cause" class="block text-sm font-medium text-gray-700 mb-2">Tip
                                    Solicitare</label>
                                <select id="cause" name="cause"
                                    class="modern-input w-full rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-sentra-cyan transition-all duration-300">
                                    <option value="technical_hosting">Probleme Tehnice (Găzduire/Server)</option>
                                    <option value="technical_domain">Probleme Tehnice (Domeniu)</option>
                                    <option value="billing_invoice">Facturare / Confirmare Plată</option>
                                    <option value="billing_delay">Amânare Plată / Reînnoire</option>
                                    <option value="account_data">Probleme Cont / Schimbare Date</option>
                                    <option value="migrare">Migrare</option>
                                    <option value="other">Altele</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Descriere
                                Detaliată</label>
                            <textarea id="message" name="message" rows="8" required
                                placeholder="Descrie problema sau solicitarea ta în detaliu. Cu cât oferi mai multe informații, cu atât vom putea rezolva mai rapid..."
                                class="modern-input w-full rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-sentra-cyan transition-all duration-300 resize-none"></textarea>
                        </div>

                        <div class="col-span-1 pt-4">
                            <?php if ($_SESSION['user_role'] === 'client'): ?>
                                <button type="submit" id="submit-ticket-button" name="submit_ticket"
                                    class="group w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-xl font-bold text-lg flex items-center justify-center transition-all duration-300 hover:bg-white hover:bg-opacity-10 hover:scale-105 shadow-lg">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-white bg-opacity-10 flex items-center justify-center mr-3 group-hover:bg-opacity-20 transition duration-300">
                                        <i data-lucide="send" class="w-5 h-5"></i>
                                    </div>
                                    <span>Deschide Tichet de Suport</span>
                                </button>
                            <?php else: ?>
                                <div
                                    class="group w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white px-8 py-4 rounded-xl font-bold text-lg flex items-center justify-center cursor-not-allowed opacity-80">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-white bg-opacity-10 flex items-center justify-center mr-3">
                                        <i data-lucide="lock" class="w-5 h-5"></i>
                                    </div>
                                    <span>Doar clienții pot deschide tichete</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Tichete Deschise -->
                <div id="open-tickets-section" class="modern-card p-8 mb-12 animate__animated animate__fadeInUp">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                <i data-lucide="message-square-text" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">Tichete Active</h2>
                                <p class="text-gray-600">Tichetele tale deschise în așteptarea unui răspuns</p>
                            </div>
                        </div>
                        <span class="modern-badge bg-gradient-to-r from-green-500 to-emerald-600 text-white">
                            <?php echo count($open_tickets); ?> Active
                        </span>
                    </div>

                    <?php if (count($open_tickets) > 0): ?>
                        <div class="modern-table overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-white">ID & Subiect</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-white hidden md:table-cell">
                                            Prioritate</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-white hidden lg:table-cell">
                                            Data</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-white">Status</th>
                                        <th class="px-6 py-4 text-right text-sm font-semibold text-white">Acțiuni</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($open_tickets as $ticket): ?>
                                        <tr class="hover-lift" data-ticket='<?php echo json_encode($ticket); ?>'>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-sentra-cyan to-teal-500 rounded-lg flex items-center justify-center mr-4">
                                                        <i data-lucide="message-circle" class="w-5 h-5 text-white"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            #<?php echo htmlspecialchars($ticket['id']); ?> -
                                                            <?php echo htmlspecialchars($ticket['subject']); ?>
                                                        </div>
                                                        <div class="text-sm text-gray-500 md:hidden">
                                                            <?php echo formatCause(htmlspecialchars($ticket['cause'])); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 hidden md:table-cell">
                                                <span
                                                    class="modern-badge priority-<?php echo strtolower(htmlspecialchars($ticket['priority'])); ?>">
                                                    <?php echo ucfirst(htmlspecialchars($ticket['priority'])); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 hidden lg:table-cell">
                                                <?php echo date('d.m.Y H:i', strtotime($ticket['created_at'])); ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="modern-badge <?php echo getStatusCssClass(htmlspecialchars($ticket['status'])); ?>">
                                                    <?php echo getStatusDisplayText(htmlspecialchars($ticket['status'])); ?>
                                                </span>
                                            <td class="px-6 py-4 text-right">
                                                <button onclick="openTicketModal(this)"
                                                    class="modern-button bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-2 rounded-lg font-semibold hover:from-green-600 hover:to-emerald-700 transition-all duration-300 flex items-center shadow-lg hover:shadow-xl">
                                                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                                    Vizualizează
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <div
                                class="w-24 h-24 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="check-check" class="w-12 h-12 text-green-500"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Niciun tichet activ</h3>
                            <p class="text-gray-600 max-w-md mx-auto">
                                Felicitări! Toate solicitările tale au fost rezolvate. Poți deschide un nou tichet dacă ai
                                nevoie de asistență.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tichete Închise -->
                <div id="closed-tickets-section" class="modern-card p-8 mb-12 animate__animated animate__fadeInUp">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-gray-400 to-gray-600 rounded-xl flex items-center justify-center mr-4">
                                <i data-lucide="archive" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">Arhivă Tichete</h2>
                                <p class="text-gray-600">Tichetele finalizate și închise anterior</p>
                            </div>
                        </div>
                        <span class="modern-badge bg-gradient-to-r from-gray-400 to-gray-600 text-white">
                            <?php echo count($closed_tickets); ?> În arhivă
                        </span>
                    </div>

                    <?php if (count($closed_tickets) > 0): ?>
                        <div class="modern-table overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-white">ID & Subiect</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-white hidden md:table-cell">
                                            Prioritate</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-white hidden lg:table-cell">
                                            Data Închiderii</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-white">Status Final</th>
                                        <th class="px-6 py-4 text-right text-sm font-semibold text-white">Acțiuni</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($closed_tickets as $ticket): ?>
                                        <tr class="hover-lift opacity-80 hover:opacity-100 transition-opacity duration-300"
                                            data-ticket='<?php echo json_encode($ticket); ?>'>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-gray-400 to-gray-500 rounded-lg flex items-center justify-center mr-4">
                                                        <i data-lucide="archive" class="w-5 h-5 text-white"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            #<?php echo htmlspecialchars($ticket['id']); ?> -
                                                            <?php echo htmlspecialchars($ticket['subject']); ?>
                                                        </div>
                                                        <div class="text-sm text-gray-500 md:hidden">
                                                            <?php echo formatCause(htmlspecialchars($ticket['cause'])); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 hidden md:table-cell">
                                                <span
                                                    class="modern-badge priority-<?php echo strtolower(htmlspecialchars($ticket['priority'])); ?>">
                                                    <?php echo ucfirst(htmlspecialchars($ticket['priority'])); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 hidden lg:table-cell">
                                                <?php echo date('d.m.Y H:i', strtotime($ticket['created_at'])); ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="modern-badge <?php echo getStatusCssClass(htmlspecialchars($ticket['status'])); ?>">
                                                    <?php echo getStatusDisplayText(htmlspecialchars($ticket['status'])); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <button onclick="openTicketModal(this)"
                                                    class="modern-button bg-gradient-to-r from-gray-400 to-gray-500 text-white px-6 py-2 rounded-lg font-semibold hover:from-gray-500 hover:to-gray-600 transition-all duration-300 flex items-center">
                                                    <i data-lucide="history" class="w-4 h-4 mr-2"></i>
                                                    Istoric
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <div
                                class="w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="folder-open" class="w-12 h-12 text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Arhivă goală</h3>
                            <p class="text-gray-600 max-w-md mx-auto">
                                Momentan nu ai niciun tichet închis în arhivă. Toate tichetele active sunt afișate mai sus.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <div class="client-panel-footer-alignment">
        <?php
        include '../pages/footer.php';
        ?>
    </div>

    <!-- Modal Modernizat pentru Tichete -->
    <div id="ticketModal" class="modern-modal">
        <div class="modern-modal-content">
            <span class="modern-close-btn" onclick="closeTicketModal()">&times;</span>

            <div class="flex items-center mb-6">
                <div
                    class="w-14 h-14 bg-gradient-to-r from-sentra-cyan to-teal-500 rounded-2xl flex items-center justify-center mr-4">
                    <i data-lucide="message-circle" class="w-6 h-6 text-white"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-extrabold text-gray-800 mb-1" id="modalTicketSubject"></h3>
                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i data-lucide="hash" class="w-4 h-4 mr-1"></i>
                            <strong id="modalTicketId"></strong>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                            <span id="modalTicketPriority" class="modern-badge"></span>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                            <span id="modalTicketStatus" class="modern-badge"></span>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                            <strong id="modalTicketDate"></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-sentra-cyan to-teal-500 h-1 w-20 rounded-full mb-6"></div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="modern-card p-4 text-center">
                    <i data-lucide="folder" class="w-8 h-8 text-sentra-cyan mx-auto mb-2"></i>
                    <p class="text-sm text-gray-600">Tip Solicitare</p>
                    <p class="font-semibold text-gray-800" id="modalTicketCause"></p>
                </div>
                <div class="modern-card p-4 text-center">
                    <i data-lucide="user" class="w-8 h-8 text-sentra-cyan mx-auto mb-2"></i>
                    <p class="text-sm text-gray-600">Deschis de</p>
                    <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($user_name); ?></p>
                </div>
                <div class="modern-card p-4 text-center">
                    <i data-lucide="message-square" class="w-8 h-8 text-sentra-cyan mx-auto mb-2"></i>
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="font-semibold"><span id="modalTicketStatusText"></span></p>
                </div>
            </div>

            <h4 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                <i data-lucide="messages-square" class="w-5 h-5 mr-2 text-sentra-cyan"></i>
                Conversație Tichet
            </h4>

            <div id="modalTicketConversation"
                class="space-y-4 p-6 bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-2xl flex flex-col mb-6">
                <div class="text-gray-500 text-center py-8" id="conversation-loader">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-sentra-cyan mx-auto mb-4"></div>
                    <p>Se încarcă conversația...</p>
                </div>
            </div>

            <div id="reply-block" class="modern-card p-6">
                <div id="active-conversation" class="space-y-4">
                    <label for="replyMessageContent" class="block text-sm font-medium text-gray-700 mb-2">
                        <i data-lucide="edit" class="w-4 h-4 inline mr-1"></i>
                        Adaugă un răspuns
                    </label>
                    <textarea id="replyMessageContent"
                        class="modern-input w-full rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-sentra-cyan transition-all duration-300 resize-none"
                        rows="4" placeholder="Scrie mesajul tău aici..."></textarea>
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                            Răspunsul tău va fi vizibil echipei de suport
                        </div>
                        <button id="sendReplyButton" onclick="sendReply()"
                            class="modern-button submit-button-green text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 flex items-center">
                            <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                            Trimite Răspuns
                        </button>
                    </div>
                </div>

                <div id="closed-message-container" class="hidden text-center py-8">
                    <div
                        class="w-16 h-16 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="lock" class="w-8 h-8 text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-700 mb-2">Tichet Închis</h4>
                    <p class="text-gray-600 max-w-md mx-auto">
                        Acest tichet a fost închis. Nu mai poți adăuga răspunsuri noi.
                        Dacă ai nevoie de asistență suplimentară, te rugăm să deschizi un tichet nou.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variabila globală pentru a stoca ID-ul tichetului
        let currentTicketId = null;
        let isStaff = <?php echo json_encode($is_staff_or_admin); ?>;

        // Animatie pentru notificări
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            const bgColor = type === 'error' ? 'bg-gradient-to-r from-red-500 to-red-600' : 'bg-gradient-to-r from-green-500 to-emerald-600';

            notification.className = `fixed top-5 right-5 z-50 p-4 rounded-xl text-white font-semibold shadow-2xl transform transition-all duration-500 ${bgColor}`;
            notification.innerHTML = `
            <div class="flex items-center">
                <i data-lucide="${type === 'error' ? 'x-circle' : 'check-circle'}" class="w-5 h-5 mr-2"></i>
                ${message}
            </div>
        `;

            document.body.appendChild(notification);
            lucide.createIcons();

            // Animație intrare
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 10);

            // Ascunde după 5 secunde
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 500);
            }, 5000);
        }

        // Funcție pentru deschiderea automată a modalului
        function autoOpenTicketModal(ticketId) {
            const rows = document.querySelectorAll('tr[data-ticket]');
            let foundRow = null;

            rows.forEach(row => {
                const ticketData = JSON.parse(row.dataset.ticket);
                if (ticketData.id === ticketId) {
                    foundRow = row;
                }
            });

            if (foundRow) {
                const openButton = foundRow.querySelector('button[onclick="openTicketModal(this)"]');
                if (openButton) {
                    openTicketModal(openButton);

                    // Animatie scroll
                    const ticketSection = foundRow.closest('.content-section');
                    if (ticketSection) {
                        ticketSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }
            }
        }

        // Funcție pentru preluarea și afișarea mesajelor
        function fetchAndDisplayMessages(ticketId) {
            const conversationContainer = document.getElementById('modalTicketConversation');
            conversationContainer.innerHTML = `
            <div class="text-gray-500 text-center py-8" id="conversation-loader">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-sentra-cyan mx-auto mb-4"></div>
                <p>Se încarcă conversația...</p>
            </div>
        `;

            fetch(`../api/get_ticket_messages.php?ticket_id=${ticketId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") !== -1) {
                        return response.json();
                    } else {
                        throw new Error("Received non-JSON response from API");
                    }
                })
                .then(data => {
                    if (data.error) {
                        conversationContainer.innerHTML = `
                        <div class="modern-card p-6 text-center text-red-500 bg-red-50 border border-red-200 rounded-xl">
                            <i data-lucide="alert-triangle" class="w-8 h-8 mx-auto mb-2"></i>
                            Eroare: ${data.error}
                        </div>
                    `;
                    } else {
                        conversationContainer.innerHTML = buildMessageHtml(data.messages);
                    }
                    conversationContainer.scrollTop = conversationContainer.scrollHeight;
                    lucide.createIcons();
                })
                .catch(error => {
                    console.error('Eroare la preluarea mesajelor:', error);
                    conversationContainer.innerHTML = `
                    <div class="modern-card p-6 text-center text-red-500 bg-red-50 border border-red-200 rounded-xl">
                        <i data-lucide="wifi-off" class="w-8 h-8 mx-auto mb-2"></i>
                        Eroare de conexiune. Vă rugăm să reîncercați.
                    </div>
                `;
                });
        }

        // Funcție pentru trimiterea răspunsurilor
        function sendReply() {
            const messageContent = document.getElementById('replyMessageContent').value.trim();
            const sendButton = document.getElementById('sendReplyButton');

            if (!currentTicketId) {
                showNotification('Eroare: Nu s-a putut identifica ID-ul tichetului.', 'error');
                return;
            }

            if (messageContent === '') {
                showNotification('Te rugăm să scrii un mesaj înainte de a trimite.', 'error');
                return;
            }

            // Animatie buton
            sendButton.disabled = true;
            sendButton.innerHTML = `
            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
            Se trimite...
        `;

            const postData = new URLSearchParams();
            postData.append('ticket_id', currentTicketId);
            postData.append('content', messageContent);

            fetch('../api/reply_to_ticket.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: postData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('replyMessageContent').value = '';
                        showNotification('Răspunsul a fost trimis cu succes!');
                        fetchAndDisplayMessages(currentTicketId);
                    } else {
                        showNotification('Eroare la trimitere: ' + (data.error || 'Eroare necunoscută'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Eroare la trimitere:', error);
                    showNotification('Eroare de rețea. Vă rugăm să reîncercați.', 'error');
                })
                .finally(() => {
                    sendButton.disabled = false;
                    sendButton.innerHTML = `
                    <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                    Trimite Răspuns
                `;
                    lucide.createIcons();
                });
        }

        // Funcții utilitare
        function getStatusDisplay(statusKey) {
            const statusMap = {
                'open': 'Deschis',
                'in_progress': 'În Curs',
                'awaiting_client': 'Așteptă Răspuns',
                'closed': 'Închis',
                'resolved': 'Rezolvat'
            };
            return statusMap[statusKey] || statusKey;
        }

        function getStatusCssClassJS(statusKey) {
            const classMap = {
                'open': 'status-open',
                'in_progress': 'status-in-progress',
                'awaiting_client': 'status-awaiting',
                'closed': 'status-closed',
                'resolved': 'status-resolved'
            };
            return classMap[statusKey] || 'status-closed';
        }

        function formatTicketDate(dateString) {
            return new Date(dateString).toLocaleDateString('ro-RO', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function formatCauseDisplay(cause) {
            if (!cause) return 'N/A';
            return cause.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }

        function buildMessageHtml(messages) {
            if (messages.length === 0) {
                return `
                <div class="text-center py-8 text-gray-500">
                    <i data-lucide="message-square" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                    <p>Nicio conversație găsită.</p>
                </div>
            `;
            }

            let html = '';
            messages.forEach((msg, index) => {
                const isStaffReply = msg.user_role === 'staff' || msg.user_role === 'admin';
                const messageClass = isStaffReply ? 'message-staff' : 'message-client';
                const sender = isStaffReply ? 'Echipa Sentra' : 'Tu';
                const senderIcon = isStaffReply ? 'user-check' : 'user';

                html += `
                <div class="${messageClass} animate__animated animate__fadeInUp" style="animation-delay: ${index * 0.1}s">
                    <div class="flex items-center mb-2">
                        <i data-lucide="${senderIcon}" class="w-4 h-4 mr-2 ${isStaffReply ? 'text-sentra-cyan' : 'text-blue-500'}"></i>
                        <span class="font-semibold text-sm ${isStaffReply ? 'text-sentra-cyan' : 'text-blue-600'}">${sender}</span>
                        <span class="text-xs text-gray-500 ml-auto">
                            ${new Date(msg.created_at).toLocaleDateString('ro-RO', {
                    hour: '2-digit',
                    minute: '2-digit',
                    day: 'numeric',
                    month: 'short'
                })}
                        </span>
                    </div>
                    <p class="whitespace-pre-line text-gray-700 leading-relaxed">${msg.content}</p>
                </div>
            `;
            });
            return html;
        }

        // Funcții pentru modal
        function openTicketModal(button) {
            const row = button.closest('tr');
            const ticketData = JSON.parse(row.dataset.ticket);
            const modal = document.getElementById('ticketModal');

            currentTicketId = ticketData.id;

            // Blochează scroll-ul în fundal
            document.body.classList.add('modal-open');

            // Actualizează conținutul modalului
            document.getElementById('modalTicketId').textContent = '#' + ticketData.id;
            document.getElementById('modalTicketSubject').textContent = ticketData.subject;
            document.getElementById('modalTicketDate').textContent = formatTicketDate(ticketData.created_at);
            document.getElementById('modalTicketCause').textContent = formatCauseDisplay(ticketData.cause);
            document.getElementById('modalTicketStatusText').textContent = getStatusDisplay(ticketData.status);

            const statusSpan = document.getElementById('modalTicketStatus');
            statusSpan.textContent = getStatusDisplay(ticketData.status);
            statusSpan.className = 'modern-badge ' + getStatusCssClassJS(ticketData.status);

            const prioritySpan = document.getElementById('modalTicketPriority');
            prioritySpan.textContent = ticketData.priority.charAt(0).toUpperCase() + ticketData.priority.slice(1);
            prioritySpan.className = 'modern-badge priority-' + ticketData.priority.toLowerCase();

            // Gestionează starea pentru răspunsuri
            const isClosed = ticketData.status === 'closed' || ticketData.status === 'resolved';
            const activeConversation = document.getElementById('active-conversation');
            const closedMessage = document.getElementById('closed-message-container');

            if (isClosed) {
                activeConversation.style.display = 'none';
                closedMessage.classList.remove('hidden');
            } else {
                activeConversation.style.display = 'block';
                closedMessage.classList.add('hidden');
            }

            // Deschide modalul
            modal.style.display = 'block';
            fetchAndDisplayMessages(ticketData.id);
        }

        function closeTicketModal() {
            const modal = document.getElementById('ticketModal');
            modal.style.display = 'none';
            
            // Reactivează scroll-ul în fundal
            document.body.classList.remove('modal-open');
        }

        // Inițializare
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            // Închide modal la click în afara
            const modal = document.getElementById('ticketModal');
            window.onclick = function (event) {
                if (event.target === modal) {
                    closeTicketModal();
                }
            }

            // Auto-deschidere tichet dacă este specificat
            <?php if (isset($auto_open_ticket_id) && $auto_open_ticket_id): ?>
                setTimeout(() => {
                    autoOpenTicketModal(<?php echo $auto_open_ticket_id; ?>);
                }, 800);
            <?php endif; ?>

            // Animații pentru elemente
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

            // Aplică animații pentru card-uri
            document.querySelectorAll('.modern-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
        });
    </script>

    <?php if ($notification_data): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showNotification('<?php echo htmlspecialchars($notification_data['text']); ?>', '<?php echo $notification_data['type']; ?>');
            });
        </script>
    <?php endif; ?>

</body>

</html>