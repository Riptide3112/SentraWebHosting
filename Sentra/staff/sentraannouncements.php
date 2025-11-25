<?php
// sentraannouncements.php - Listare și Gestiune Anunțuri (Staff/Admin)

// 1. INCLUDEM BARIERA DE SECURITATE
// Asigură-te că fișierul 'auth_check.php' există și conține logica de sesiune și verificare.
include '../includes/auth_check.php';

// 2. VERIFICĂ ACCESUL: Permite doar rolurilor 'staff' și 'admin'
check_access(['staff', 'admin'], 'dashboard.php');

// 3. Preluăm datele Staff-ului din sesiune și Rolul Utilizatorului Logat
$session_role_key = $_SESSION['role'] ?? $_SESSION['user_role'] ?? 'client';
$admin_name = $_SESSION['user_name'] ?? 'Admin';
$display_role = ucfirst($session_role_key);
// Variabila esențială pentru verificarea permisiunilor (admin/staff)
$logged_in_user_role = strtolower($session_role_key);

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

// 5. CONSTRUIREA INTEROGĂRII SQL SIMPLIFICATE (Preluăm toate anunțurile)
$sql = "SELECT 
    id, title, status, target_role, created_at, content
    FROM announcements
    ORDER BY created_at DESC LIMIT 50";

// Execuția interogării
$announcements = [];
$result = $conn->query($sql);

if ($result === false) {
    $error_message = "Eroare la interogarea bazei de date: " . $conn->error;
} else if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}

$conn->close();

/**
 * Extrage detaliile pentru pop-up, excluzând ID și Status (conform cererilor anterioare).
 */
function get_announcement_details($announcement)
{
    $details = [];
    $modal_title = '';

    foreach ($announcement as $key => $value) {
        switch ($key) {
            case 'id':
            case 'status':
                continue 2; // Exclude ID și Status din pop-up
            case 'title':
                $label = 'Titlu';
                $modal_title = htmlspecialchars($value ?? '-');
                break;
            case 'content':
                $label = 'Conținut Complet';
                break;
            case 'target_role':
                $label = 'Categorie'; // Renumește
                break;
            case 'created_at':
                $label = 'Creat La';
                break;
            case 'updated_at':
                $label = 'Ultima Modificare';
                break;
            default:
                $label = ucfirst(str_replace('_', ' ', $key));
        }

        $details[$label] = htmlspecialchars($value ?? '-');
    }

    // Ordonează detaliile
    $ordered_details = [
        'Titlu' => $details['Titlu'] ?? '',
        'Categorie' => $details['Categorie'] ?? '',
        'Creat La' => $details['Creat La'] ?? '',
        'Ultima Modificare' => $details['Ultima Modificare'] ?? '',
        'Conținut Complet' => $details['Conținut Complet'] ?? '',
    ];

    return ['details' => array_filter($ordered_details), 'modal_title' => $modal_title];
}

// Funcție utilitară pentru a afișa badge-ul de status
function display_status_badge($status)
{
    $status = strtolower($status);
    $class = 'bg-gray-400';
    $text = ucfirst($status);

    switch ($status) {
        case 'active':
            $class = 'bg-green-100 text-green-800';
            $text = 'Activ';
            break;
        case 'draft':
            $class = 'bg-yellow-100 text-yellow-800';
            $text = 'Ciornă';
            break;
        case 'archived':
            $class = 'bg-red-100 text-red-800';
            $text = 'Arhivat';
            break;
    }

    return "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full $class'>$text</span>";
}
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentra Panel - Anunțuri</title>
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

        /* Modal Detalii */
        #announcementDetailsModal .modal-content {
            background-color: #fefefe;
            margin: 8% auto;
            padding: 25px;
            border-radius: 12px;
            width: 95%;
            max-width: 750px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* Punctul 1: Modal Adaugă Anunț - Lățime mai mare și modernizare */
        #addAnnouncementModal .modal-content {
            background-color: #ffffff;
            margin: 8% auto;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border-top: 5px solid #00BFB2;
            /* Bandă Sentra Cyan */
        }

        /* Stiluri de hover */
        .announcement-row {
            position: relative;
            cursor: pointer;
            /* Punctul 2 & 3: Indicator vizual pentru click */
        }

        .announcement-preview {
            max-height: 0;
            opacity: 0;
            padding: 0 1.5rem;
            transition: max-height 0.4s ease-out, opacity 0.4s ease-out;
            background-color: #f7fafc;
        }

        .announcement-row:hover .announcement-preview {
            max-height: 200px;
            opacity: 1;
            padding: 1rem 1.5rem 1.5rem 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .announcement-preview-content {
            filter: blur(0.5px);
            color: #4a5568;
            font-size: 0.9rem;
            line-height: 1.4;
            max-height: 70px;
            overflow: hidden;
        }
    </style>
</head>

<body class="flex h-screen">

    <?php
    $current_page = 'sentraannouncements.php';
    include 'sentrasidebar.php';
    ?>

    <main class="flex-1 p-10 overflow-y-auto">
        <header class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Gestiunea Anunțurilor</h2>
                <p class="text-gray-500 mt-1">Vizualizați toate anunțurile din sistem.</p>
            </div>

            <?php
            if ($logged_in_user_role === 'admin'): ?>
                <button onclick="openAddModal()"
                    class="sentra-cyan text-white font-bold py-3 px-5 rounded-lg hover:opacity-90 transition duration-300 flex items-center shadow-md">
                    <i data-lucide="megaphone" class="w-5 h-5 mr-2"></i> Adaugă Anunț Nou
                </button>
            <?php endif; ?>
        </header>

        <div class="bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
            <?php if (isset($error_message)): ?>
                <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg">
                    <strong>Eroare:</strong> <?php echo htmlspecialchars($error_message); ?>. Vă rugăm să verificați dacă
                    ați creat tabela `announcements` sau ați modificat coloana `target_role`.
                </div>
            <?php endif; ?>

            <h3 class="text-xl font-bold mb-4">
                Lista Anunțurilor
                <?php
                $announcement_count = count($announcements);
                echo "(Afisare $announcement_count Anunț" . ($announcement_count !== 1 ? "uri" : "") . ")";
                ?>
            </h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">
                            Categorie</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[5%]">
                            ID</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[50%]">
                            Titlu Anunț</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[25%]">
                            Dată Creare</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $num_columns = 4; // Numărul nou de coloane
                    if (empty($announcements)):
                        ?>
                        <tr>
                            <td colspan="<?php echo $num_columns; ?>" class="px-6 py-4 text-center text-gray-500">Niciun
                                anunț găsit.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($announcements as $announcement): ?>
                            <tr class="announcement-row cursor-pointer" onclick="showDetails(<?php
                            // Preluăm detaliile pentru afișare
                            $details_data = get_announcement_details($announcement);
                            // Adăugăm ID-ul și rolul pentru a permite butonul "Șterge" în pop-up (Punctul 4)
                            $details_data['id'] = $announcement['id'];
                            $details_data['is_admin'] = ($logged_in_user_role === 'admin');
                            echo htmlspecialchars(json_encode($details_data));
                            ?>)">
                                <td colspan="<?php echo $num_columns; ?>" class="p-0 border-b border-gray-200">
                                    <div class="flex items-center hover:bg-gray-50 transition duration-150">
                                        <div class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-[20%]">
                                            <?php echo htmlspecialchars(ucfirst($announcement['target_role'])); ?></div>
                                        <div class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-[5%]">
                                            <?php echo htmlspecialchars($announcement['id']); ?></div>
                                        <div class="px-6 py-4 whitespace-nowrap font-bold text-base text-gray-900 w-[50%]">
                                            <?php echo htmlspecialchars($announcement['title']); ?></div>
                                        <div class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-[25%]">
                                            <?php echo date('d M Y', strtotime($announcement['created_at'])); ?></div>
                                    </div>

                                    <div class="announcement-preview">
                                        <div class="announcement-preview-content">
                                            <p class="font-semibold text-gray-600 mb-1">Previzualizare Subiect:</p>
                                            <p><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

    <div id="announcementDetailsModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-xl font-bold text-gray-900" id="detailsModalHeader"></h3>
                <button onclick="closeModal()"
                    class="text-gray-500 hover:text-gray-900 text-3xl leading-none">&times;</button>
            </div>
            <div id="detailsTableContainer" class="grid grid-cols-2 gap-x-8 gap-y-4">
            </div>

            <div class="mt-6 pt-4 border-t flex justify-end" id="deleteButtonContainer">
            </div>

        </div>
    </div>

    <div id="addAnnouncementModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-xl font-bold text-gray-900">Adaugă Anunț Nou</h3>
                <button onclick="closeAddModal()"
                    class="text-gray-500 hover:text-gray-900 text-3xl leading-none">&times;</button>
            </div>

            <form id="addAnnouncementForm" class="space-y-4">
                <div>
                    <label for="announcement_title" class="block text-sm font-medium text-gray-700">Titlu *</label>
                    <input type="text" id="announcement_title" name="title" required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-sentra-cyan focus:border-sentra-cyan">
                </div>

                <div>
                    <label for="announcement_content" class="block text-sm font-medium text-gray-700">Conținut *</label>
                    <textarea id="announcement_content" name="content" rows="4" required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-sentra-cyan focus:border-sentra-cyan"></textarea>
                </div>

                <div>
                    <label for="target_role" class="block text-sm font-medium text-gray-700">Categorie</label>
                    <select id="target_role" name="target_role"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                        <option value="Servicii">Servicii</option>
                        <option value="Regulament">Regulament</option>
                        <option value="Clienti">Clienți</option>
                        <option value="Altele">Altele</option>
                    </select>
                </div>

                <div id="addAnnouncementMessage" class="text-sm font-medium pt-2"></div>

                <button type="submit"
                    class="sentra-cyan text-white font-bold py-2 px-4 rounded-lg hover:opacity-90 transition duration-300 shadow-md w-full">
                    Postează
                </button>
            </form>
        </div>
    </div>

    <script>
        // Inițializarea Iconițelor Lucide
        if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        }

        // === LOGICA MODAL VIZUALIZARE (Detalii Complete) ===
        const detailsModal = document.getElementById('announcementDetailsModal');
        const detailsModalHeader = document.getElementById('detailsModalHeader');
        const detailsTableContainer = document.getElementById('detailsTableContainer');

        /**
         * Populează și afișează modalul de detalii.
         * @param {object} data - Obiectul care include details, modal_title, id și is_admin.
         */
        function showDetails(data) {
            const details = data.details;
            const announcementId = data.id;
            const isAdmin = data.is_admin;

            // Setează Titlul dinamic ca antet al pop-up-ului
            detailsModalHeader.textContent = data.modal_title || 'Detalii Anunț';

            let detailsContent = '';

            // Generează conținutul detaliilor
            for (const [key, value] of Object.entries(details)) {
                let displayValue = value;
                let colClass = 'col-span-1';

                if (key === 'Conținut Complet') {
                    // Câmpul de conținut se întinde pe ambele coloane (col-span-2)
                    displayValue = `<div class="whitespace-pre-wrap py-2 max-h-60 overflow-y-auto border p-3 rounded-md bg-gray-50 text-base text-gray-700">${value}</div>`;
                    colClass = 'col-span-2';
                    detailsContent += `
                        <div class="${colClass} pt-4">
                            <p class="text-sm font-semibold text-gray-800 mb-1">${key}:</p>
                            ${displayValue}
                        </div>
                    `;
                } else {
                    // Detalii pe două coloane
                    detailsContent += `
                        <div class="${colClass} pt-2">
                            <p class="text-sm font-semibold text-gray-700">${key}:</p>
                            <p class="text-base text-gray-500">${displayValue}</p>
                        </div>
                    `;
                }
            }

            detailsTableContainer.innerHTML = detailsContent;

            // NOU: Adaugă butonul Șterge dacă este Admin (Punctul 4)
            const deleteButtonContainer = document.getElementById('deleteButtonContainer');
            if (isAdmin) {
                deleteButtonContainer.innerHTML = `
                    <button onclick="deleteAnnouncement(${announcementId})" 
                            class="bg-red-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-600 transition duration-300 shadow-md flex items-center">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Șterge Anunțul
                    </button>
                `;
                // Re-creează iconițele Lucide după ce adaugi butonul
                if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
                    lucide.createIcons();
                }
            } else {
                deleteButtonContainer.innerHTML = '';
            }

            detailsModal.style.display = 'block';
        }

        function closeModal() {
            detailsModal.style.display = 'none';
        }

        // === LOGICA MODAL ADĂUGARE (Creare Nouă) ===
        const addModal = document.getElementById('addAnnouncementModal');
        const addForm = document.getElementById('addAnnouncementForm');
        const addMessage = document.getElementById('addAnnouncementMessage');

        function openAddModal() {
            addForm.reset();
            addMessage.textContent = '';
            addModal.style.display = 'block';
        }

        function closeAddModal() {
            addModal.style.display = 'none';
        }

        // Atasăm evenimentul de submit la formularul de adăugare (AJAX)
        addForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            addMessage.textContent = 'Se postează...';
            addMessage.className = 'text-sm font-medium pt-2 text-blue-600';

            const formData = new FormData(this);

            try {
                // Folosește fișierul API unificat
                const response = await fetch('api/create_announcement.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    addMessage.textContent = result.message;
                    addMessage.className = 'text-sm font-medium pt-2 text-green-600';
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    addMessage.textContent = 'Eroare: ' + (result.message || 'Eroare la procesarea cererii.');
                    addMessage.className = 'text-sm font-medium pt-2 text-red-600';
                }

            } catch (error) {
                console.error('AJAX Error:', error);
                addMessage.textContent = 'Eroare de rețea. Vă rugăm verificați conexiunea.';
                addMessage.className = 'text-sm font-medium pt-2 text-red-600';
            }
        });

        // === LOGICA ȘTERGERE (Folosește fișierul unificat) ===
        function deleteAnnouncement(id) {
            if (confirm(`Sunteți sigur că doriți să ștergeți anunțul cu ID-ul ${id}? Această acțiune este ireversibilă.`)) {
                // Închide modalul de vizualizare înainte de reîncărcare
                closeModal();

                // Folosește api/create_announcement.php
                fetch('api/create_announcement.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Anunț șters cu succes!');
                            window.location.reload();
                        } else {
                            alert('Eroare la ștergere: ' + (data.message || 'Eroare necunoscută.'));
                        }
                    })
                    .catch(error => {
                        console.error('Eroare rețea/AJAX:', error);
                        alert('Eroare de rețea la ștergere.');
                    });
            }
        }


        // Închide ambele modaluri dacă utilizatorul dă click în afara lor
        window.onclick = function (event) {
            if (event.target == detailsModal) {
                closeModal();
            } else if (event.target == addModal) {
                closeAddModal();
            }
        }
    </script>
</body>

</html>