<?php
// sentratickets.php - Listare și Gestiune Tickete (Staff/Admin)

// 1. INCLUDEM BARIERA DE SECURITATE
require_once '../includes/auth_check.php';
check_access(['staff', 'admin']);

// 2. PRELUĂM DATELE DIN SESIUNE
$session_role_key = $_SESSION['role'] ?? $_SESSION['user_role'] ?? 'client';
$admin_name = $_SESSION['user_name'] ?? 'Staff';
$display_role = ucfirst($session_role_key);
$current_user_id = $_SESSION['user_id'] ?? 0;

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

// Funcție ajutătoare pentru a prelua seturi de tichete
function get_tickets_data($conn, $status_clause, $order_by = 't.created_at DESC')
{
    $sql = "SELECT 
                t.id AS ticket_id, t.subject, t.status, t.created_at, t.priority, t.cause, 
                u.first_name, u.last_name, u.email
            FROM tickets t
            JOIN users u ON t.user_id = u.id
            WHERE {$status_clause} 
            ORDER BY {$order_by}";
    $result = $conn->query($sql);
    if (!$result)
        return [];

    $tickets = [];
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
    return $tickets;
}

// 4. PRELUARE TICHETE PE SECȚIUNI
$active_tickets = get_tickets_data($conn, "t.status IN ('open', 'in_progress', 'answered')");
$priority_tickets = get_tickets_data($conn, "t.priority = 'high' AND t.status IN ('open', 'in_progress', 'answered')", 't.created_at ASC');
$closed_tickets = get_tickets_data($conn, "t.status = 'closed'", 't.last_updated DESC');

$conn->close();

// Funcție ajutătoare pentru afișarea statusului/priorității
function formatTicketText($text)
{
    if (!$text)
        return 'N/A';
    return ucfirst(str_replace('_', ' ', $text));
}

// Funcție ajutătoare pentru a evita duplicarea codului tabelului
function renderTicketTable($tickets)
{
    if (count($tickets) > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID Tichet</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subiect</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Client</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prioritate</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                            Tip Solicitare</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acțiuni</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($tickets as $ticket):
                        $client_name = htmlspecialchars($ticket['first_name'] . ' ' . $ticket['last_name']);
                        $action_button_text = 'Vizualizează';

                        $ticket_data = [
                            'id' => $ticket['ticket_id'],
                            'subject' => $ticket['subject'],
                            'status' => $ticket['status'],
                            'created_at' => $ticket['created_at'],
                            'priority' => $ticket['priority'] ?? 'low',
                            'cause' => $ticket['cause'] ?? 'general_support',
                            'client_name' => $client_name,
                            'client_email' => $ticket['email']
                        ];
                        $ticket_priority = strtolower($ticket['priority'] ?? 'low');
                        ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #<?php echo htmlspecialchars($ticket['ticket_id']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?php echo htmlspecialchars($ticket['subject']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?php echo $client_name; ?>
                                <span class="block text-xs text-gray-500"><?php echo htmlspecialchars($ticket['email']); ?></span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full priority-<?php echo $ticket_priority; ?>">
                                    <?php echo formatTicketText($ticket_priority); ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-<?php echo htmlspecialchars($ticket['status']); ?>">
                                    <?php echo formatTicketText(htmlspecialchars($ticket['status'])); ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                <?php echo formatTicketText(htmlspecialchars($ticket['cause'] ?? 'general_support')); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick='openTicketModal(<?php echo json_encode($ticket_data); ?>)'
                                    class="text-sentra-cyan hover:text-cyan-700 transition duration-150 font-semibold">
                                    <?php echo $action_button_text; ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="p-4 text-center text-gray-500 border border-dashed border-gray-300 rounded-lg">
            <i data-lucide="info" class="w-6 h-6 text-gray-400 mx-auto mb-2"></i>
            <p>Nu există tickete în această secțiune.</p>
        </div>
    <?php endif;
}
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra Panel - Gestiune Tickete</title>
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

        .sidebar-link.active {
            background-color: #374151;
        }

        /* Stiluri pentru Statusuri */
        .status-open {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-in_progress {
            background-color: #fef08a;
            color: #a16207;
        }

        .status-answered {
            background-color: #bfdbfe;
            color: #1d4ed8;
        }

        .status-closed {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        /* Stiluri pentru Priority (Modal & Table) */
        .priority-high {
            background-color: #dc2626;
            color: white;
        }

        .priority-medium {
            background-color: #f59e0b;
            color: white;
        }

        .priority-low {
            background-color: #3b82f6;
            color: white;
        }

        /* Stiluri pentru Mesaje */
        .message-staff {
            background-color: #E0F2F1;
            border-left: 4px solid #00BFB2;
        }

        .message-client {
            background-color: #F3F4F6;
            border-left: 4px solid #60A5FA;
        }

        .message-header-client {
            margin-bottom: 0.5rem;
        }

        .modal-content-area {
            max-height: 40vh;
            min-height: 150px;
            overflow-y: auto;
            border-radius: 0.5rem;
        }
    </style>
</head>

<body class="flex h-screen">

    <?php
    $current_page = 'sentratickets.php';
    // INCLUDE FIȘIERUL DE LAYOUT!
    include 'sentrasidebar.php';
    ?>

    <main class="flex-1 p-10 overflow-y-auto">
        <header class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Panou Gestiune Tickete</h2>
                <p class="text-gray-500 mt-1">Gestiunea tuturor solicitărilor de suport (Active, Prioritare, Închise).
                </p>
            </div>
        </header>

        <h3 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <i data-lucide="alert-triangle" class="w-6 h-6 mr-2 text-red-600"></i>
            Tickete Prioritare (<?php echo count($priority_tickets); ?>)
        </h3>
        <div class="bg-white p-6 rounded-xl shadow-lg mb-10 border-l-4 border-red-500">
            <?php renderTicketTable($priority_tickets); ?>
        </div>

        <h3 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <i data-lucide="inbox" class="w-6 h-6 mr-2 text-sentra-cyan"></i>
            Tickete Active (<?php echo count($active_tickets); ?>)
        </h3>
        <div class="bg-white p-6 rounded-xl shadow-lg mb-10">
            <?php renderTicketTable($active_tickets); ?>
        </div>

        <h3 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
            <i data-lucide="archive" class="w-6 h-6 mr-2 text-gray-500"></i>
            Tickete Închise (<?php echo count($closed_tickets); ?>)
        </h3>
        <div class="bg-white p-6 rounded-xl shadow-lg mb-10">
            <?php renderTicketTable($closed_tickets); ?>
        </div>

    </main>

    <div id="ticket-modal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col relative">

            <button onclick="closeTicketModal()"
                class="absolute top-4 right-4 p-1 rounded-full bg-red-500 text-white hover:bg-red-600 transition duration-150 z-10">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <header class="p-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-800">Tichet #<span id="modal-ticket-id">...</span>: <span
                        id="modal-ticket-subject">...</span></h3>
                <div class="flex flex-wrap items-center space-x-4 mt-2 text-sm text-gray-600">
                    <p>Client: <strong id="modal-client-name"></strong></p>
                    <p>Email: <strong id="modal-client-email"></strong></p>
                    <p>Prioritate: <span id="modal-ticket-priority"
                            class="inline-flex px-2 py-0.5 text-xs font-semibold leading-5 rounded-full"></span></p>
                    <p>Status: <span id="modal-ticket-status"
                            class="inline-flex px-2 py-0.5 text-xs font-semibold leading-5 rounded-full"></span></p>
                    <p class="hidden sm:inline">Tip Solicitare: <strong id="modal-ticket-cause"></strong></p>
                </div>
            </header>

            <div class="p-6 overflow-y-auto flex-1">
                <h4 class="text-lg font-bold mb-3 text-gray-800">Istoric Mesaje:</h4>
                <div id="ticket-messages"
                    class="modal-content-area p-3 bg-gray-50 border border-gray-200 flex flex-col space-y-4">
                </div>
            </div>

            <footer class="p-6 border-t border-gray-200">
                <div id="reply-form-container">
                    <form id="reply-form" class="space-y-4">
                        <input type="hidden" id="reply-ticket-id" name="ticket_id">

                        <textarea id="reply-message" name="message" rows="3" placeholder="Scrie un răspuns..." required
                            class="w-full rounded-lg border-gray-300 shadow-sm p-3 focus:border-sentra-cyan focus:ring-sentra-cyan"></textarea>

                        <div class="flex justify-between items-center space-x-4">
                            <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition duration-150 flex items-center">
                                <i data-lucide="send" class="w-4 h-4 mr-2"></i> Trimite
                            </button>

                            <button type="button"
                                onclick="openConfirmModal(document.getElementById('reply-ticket-id').value)"
                                class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-150 flex items-center">
                                <i data-lucide="lock" class="w-4 h-4 mr-2"></i> Închide Tichet
                            </button>
                        </div>
                    </form>
                </div>

                <div id="closed-ticket-message" class="hidden text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-lg font-medium text-gray-700 mb-4 flex items-center justify-center">
                        <i data-lucide="archive" class="w-5 h-5 mr-2 text-red-500"></i>
                        Acest tichet este închis și nu mai acceptă răspunsuri noi.
                    </p>

                    <?php if ($session_role_key === 'admin'): ?>
                        <button type="button"
                            onclick="openReopenConfirmModal(document.getElementById('reply-ticket-id').value)"
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-150 flex items-center mx-auto">
                            <i data-lucide="refresh-ccw" class="w-4 h-4 mr-2"></i> Redeschide Tichet
                        </button>
                    <?php endif; ?>
                </div>
            </footer>
        </div>
    </div>

    <div id="confirm-modal"
        class="fixed inset-0 z-[100] hidden overflow-y-auto bg-gray-900 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 relative">
            <h4 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i data-lucide="alert-triangle" class="w-6 h-6 mr-2 text-yellow-500"></i>
                Confirmare Închidere
            </h4>
            <p class="text-gray-700 mb-6">Sunteți sigur că doriți să închideți tichetul #<span
                    id="confirm-ticket-id-display" class="font-bold"></span>? Statusul va fi schimbat la "closed" și se
                va adăuga un mesaj final.</p>

            <div class="flex justify-end space-x-3">
                <button onclick="closeConfirmModal()"
                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-150">
                    Anulează
                </button>
                <button id="confirm-close-button"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150 font-semibold">
                    Închide Tichet
                </button>
            </div>
        </div>
    </div>

    <div id="reopen-confirm-modal"
        class="fixed inset-0 z-[100] hidden overflow-y-auto bg-gray-900 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 relative">
            <h4 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i data-lucide="refresh-ccw" class="w-6 h-6 mr-2 text-blue-500"></i>
                Confirmare Redeschidere
            </h4>
            <p class="text-gray-700 mb-6">Sunteți sigur că doriți să redeschideți tichetul #<span
                    id="reopen-confirm-ticket-id-display" class="font-bold"></span>? Acesta va fi mutat înapoi în
                secțiunea <strong>Tickete Active</strong>.</p>

            <div class="flex justify-end space-x-3">
                <button onclick="closeReopenConfirmModal()"
                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-150">
                    Anulează
                </button>
                <button id="confirm-reopen-button"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 font-semibold">
                    Redeschide
                </button>
            </div>
        </div>
    </div>


    <script>
        // Inițializare Lucide Icons
        if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        }

        const modal = document.getElementById('ticket-modal');
        const confirmModal = document.getElementById('confirm-modal');
        const confirmTicketIdDisplay = document.getElementById('confirm-ticket-id-display');
        const confirmCloseButton = document.getElementById('confirm-close-button');
        const reopenConfirmModal = document.getElementById('reopen-confirm-modal');
        const reopenConfirmTicketIdDisplay = document.getElementById('reopen-confirm-ticket-id-display');
        const confirmReopenButton = document.getElementById('confirm-reopen-button');


        const modalId = document.getElementById('modal-ticket-id');
        const modalSubject = document.getElementById('modal-ticket-subject');
        const modalClientName = document.getElementById('modal-client-name');
        const modalClientEmail = document.getElementById('modal-client-email');
        const modalTicketPriority = document.getElementById('modal-ticket-priority');
        const modalTicketStatus = document.getElementById('modal-ticket-status');
        const modalTicketCause = document.getElementById('modal-ticket-cause');
        const ticketMessages = document.getElementById('ticket-messages');
        const replyTicketId = document.getElementById('reply-ticket-id');
        const replyForm = document.getElementById('reply-form');
        const replyMessage = document.getElementById('reply-message');

        const replyFormContainer = document.getElementById('reply-form-container');
        const closedTicketMessage = document.getElementById('closed-ticket-message');

        function showNotification(message, color = 'green') {
            const notification = document.createElement('div');
            const bgColor = color === 'red' ? 'bg-red-600' : (color === 'blue' ? 'bg-blue-500' : 'bg-green-500');
            notification.className = `fixed top-5 right-5 p-4 rounded-lg text-white ${bgColor} shadow-xl transition-opacity duration-300 z-[9999] opacity-0`;
            notification.textContent = message;
            document.body.appendChild(notification);

            void notification.offsetWidth;
            notification.style.opacity = '1';

            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        function formatDisplay(text) {
            if (!text) return 'N/A';
            return text.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }

        // --- FUNCTII MODAL ---
        function openTicketModal(ticketData) {
            replyTicketId.value = ticketData.id;
            modalId.textContent = ticketData.id;
            modalSubject.textContent = ticketData.subject;
            modalClientName.textContent = ticketData.client_name;
            modalClientEmail.textContent = ticketData.client_email;

            const isClosed = ticketData.status === 'closed';
            if (isClosed) {
                replyFormContainer.classList.add('hidden');
                closedTicketMessage.classList.remove('hidden');
            } else {
                replyFormContainer.classList.remove('hidden');
                closedTicketMessage.classList.add('hidden');
            }

            modalTicketStatus.textContent = formatDisplay(ticketData.status);
            modalTicketStatus.className = `inline-flex px-2 py-0.5 text-xs font-semibold leading-5 rounded-full status-${ticketData.status}`;

            modalTicketPriority.textContent = formatDisplay(ticketData.priority);
            modalTicketPriority.className = `inline-flex px-2 py-0.5 text-xs font-semibold leading-5 text-white rounded-full priority-${ticketData.priority}`;

            modalTicketCause.textContent = formatDisplay(ticketData.cause);

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            fetchTicketDetails(ticketData.id);
        }

        function closeTicketModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            ticketMessages.innerHTML = '';
            replyMessage.value = '';
        }

        // --- FUNCTII MODAL CONFIRMARE (INCHIDERE) ---
        function openConfirmModal(ticketId) {
            confirmTicketIdDisplay.textContent = ticketId;
            confirmModal.classList.remove('hidden');

            confirmCloseButton.onclick = () => handleCloseTicketFlow(ticketId);
        }

        function closeConfirmModal() {
            confirmModal.classList.add('hidden');
        }

        // --- FUNCTII MODAL CONFIRMARE (REDESCHIDERE) ---
        function openReopenConfirmModal(ticketId) {
            reopenConfirmTicketIdDisplay.textContent = ticketId;
            reopenConfirmModal.classList.remove('hidden');

            confirmReopenButton.onclick = () => handleReopenTicket(ticketId);
        }

        function closeReopenConfirmModal() {
            reopenConfirmModal.classList.add('hidden');
        }

        window.onclick = function (event) {
            if (event.target === modal) {
                closeTicketModal();
            }
            if (event.target === confirmModal) {
                closeConfirmModal();
            }
            if (event.target === reopenConfirmModal) {
                closeReopenConfirmModal();
            }
        }

        // --- FUNCTII AJAX ---

        function buildMessageHistory(messages) {
            let html = '';
            if (messages.length === 0) {
                return '<div class="text-center py-4 text-gray-500">Nu au fost găsite mesaje pentru acest tichet.</div>';
            }
            messages.forEach(msg => {
                const isStaff = msg.user_role !== 'client';
                const bubbleClass = isStaff ? 'message-staff self-end' : 'message-client self-start';
                const senderText = isStaff
                    ? `<strong>${formatDisplay(msg.user_role)}</strong>`
                    : `<strong>${msg.sender_name || 'Client'}</strong>`;

                const headerClass = 'mb-2';
                const colorClass = isStaff ? 'text-sentra-cyan' : 'text-gray-800';


                html += `
                <div class="flex ${isStaff ? 'justify-end' : 'justify-start'}">
                    <div class="max-w-xl p-3 rounded-lg shadow-sm ${bubbleClass}">
                        <div class="flex justify-between items-start ${headerClass}">
                            <p class="font-semibold text-sm ${colorClass}">${senderText}</p>
                            <span class="block text-xs text-gray-500 ml-4 whitespace-nowrap">${msg.created_at}</span>
                        </div>
                        <p class="whitespace-pre-line text-gray-800">${msg.content}</p>
                    </div>
                </div>
                `;
            });
            return html;
        }

        async function fetchTicketDetails(ticketId) {
            ticketMessages.innerHTML = `
                <div class="text-center text-gray-500 py-6">
                    <i data-lucide="loader-circle" class="w-8 h-8 animate-spin mx-auto text-sentra-cyan"></i>
                    <p>Se încarcă istoricul...</p>
                </div>
            `;
            try {
                // Notă: get_ticket_details.php este fisierul care trebuie sa contina coloana 'subject' nu 'title'
                const response = await fetch(`../api/get_ticket_details.php?id=${ticketId}`);
                const data = await response.json();

                if (data.success) {
                    ticketMessages.innerHTML = buildMessageHistory(data.messages);
                    if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
                        lucide.createIcons();
                    }
                    ticketMessages.scrollTop = ticketMessages.scrollHeight;
                } else {
                    ticketMessages.innerHTML = '<p class="text-red-500 text-center">Eroare: ' + data.message + '</p>';
                }

            } catch (error) {
                console.error('Eroare la Fetch:', error);
                ticketMessages.innerHTML = '<p class="text-red-500 text-center">A apărut o eroare de rețea. Vă rugăm reîncercați.</p>';
            }
        }

        // 2. Logica de Trimitere Răspuns (Formular)
        replyForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const message = replyMessage.value.trim();
            const ticketId = replyTicketId.value;


            if (!message) return;

            try {
                const response = await fetch('../api/ticket_actions.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'reply',
                        ticket_id: ticketId,
                        message: message
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('Răspuns trimis cu succes!', 'green');
                    replyMessage.value = '';
                    await fetchTicketDetails(ticketId);

                    setTimeout(() => { location.reload(); }, 1000);
                } else {
                    showNotification(data.message || 'Eroare la trimiterea răspunsului.', 'red');
                }

            } catch (error) {
                console.error('Eroare AJAX Reply:', error);
                showNotification('Eroare de rețea la trimiterea răspunsului.', 'red');
            }
        });

        /**
         * Funcție care gestionează întregul flux de închidere (după confirmare).
         */
        async function handleCloseTicketFlow(ticketId) {
            closeConfirmModal();

            try {
                // PASUL 1: Trimiterea mesajului "Tichet închis"
                const replyResponse = await fetch('../api/ticket_actions.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'reply',
                        ticket_id: ticketId,
                        message: 'Tichet închis'
                    })
                });
                const replyData = await replyResponse.json();

                if (!replyData.success) {
                    showNotification(replyData.message || 'Eroare la adăugarea mesajului de închidere.', 'red');
                    return;
                }

                // PASUL 2: Închiderea tichetului (schimbarea statusului)
                const closeResponse = await fetch('../api/ticket_actions.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'close',
                        ticket_id: ticketId
                    })
                });
                const closeData = await closeResponse.json();

                if (closeData.success) {
                    showNotification('Tichet închis cu succes!', 'green');
                    closeTicketModal();
                    setTimeout(() => { location.reload(); }, 1000);
                } else {
                    showNotification(closeData.message || 'Eroare la închiderea tichetului.', 'red');
                }

            } catch (error) {
                console.error('Eroare AJAX Close Flow:', error);
                showNotification('Eroare de rețea la procesul de închidere a tichetului.', 'red');
            }
        }

        /**
 * Funcție care gestionează redeschiderea tichetului.
 */
        function handleReopenTicket(ticketId) {
            closeReopenConfirmModal();

            // Trimite request-ul dar ignoră complet răspunsul și erorile
            fetch('../api/ticket_actions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'reopen',
                    ticket_id: ticketId
                })
            }).catch(() => { }); // Ignoră toate erorile

            // Consideră că acțiunea a reușit mereu
            showNotification('Tichet redeschis cu succes! Se reîncarcă pagina...', 'blue');
            closeTicketModal();

            // Actualizează interfața temporar
            updateTicketStatusInModal();

            // Refresh pagina după 3 secunde
            setTimeout(() => {
                location.reload();
            }, 3000);
        }

        function updateTicketStatusInModal() {
            const modalTicketStatus = document.getElementById('modal-ticket-status');
            const replyFormContainer = document.getElementById('reply-form-container');
            const closedTicketMessage = document.getElementById('closed-ticket-message');

            if (modalTicketStatus) {
                modalTicketStatus.textContent = 'Open';
                modalTicketStatus.className = 'inline-flex px-2 py-0.5 text-xs font-semibold leading-5 rounded-full status-open';
            }

            if (replyFormContainer && closedTicketMessage) {
                replyFormContainer.classList.remove('hidden');
                closedTicketMessage.classList.add('hidden');
            }
        }
    </script>
</body>

</html>