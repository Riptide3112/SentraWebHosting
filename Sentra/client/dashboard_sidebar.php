<?php
// client/dashboard_sidebar.php - Doar HTML/CSS/JavaScript (UI). Nu contine logica de baza de date.

$user_id = $_SESSION['user_id'] ?? 0;
$is_staff_or_admin = $is_staff_or_admin ?? false; 

// Determină pagina curentă pentru a marca link-ul activ
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Mobile Menu Button -->
<div class="lg:hidden fixed top-4 left-4 z-30">
    <button id="mobileMenuButton" class="p-2 rounded-lg bg-sentra-cyan text-white shadow-lg hover:bg-teal-600 transition duration-300">
        <i data-lucide="menu" class="w-6 h-6"></i>
    </button>
</div>

<!-- Mobile Overlay -->
<div id="mobileOverlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

<!-- Sidebar -->
<div class="lg:w-1/5 gradient-sidebar p-6 lg:p-8 flex-col text-white hidden lg:flex fixed left-0 top-0 h-full z-20">
    
    <!-- Logo Section -->
    <div class="flex items-center justify-between mb-8">
        <a href="../index.php" class="flex items-center cursor-pointer hover:opacity-90 transition duration-200 flex-1">
            <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight mr-2 text-white">Sentra</h1>
            <div class="w-3 h-3 rounded-full sentra-cyan shadow-xl shadow-[#00BFB2]/50"></div>
        </a>
        
        <!-- Mobile Close Button -->
        <button id="mobileCloseButton" class="lg:hidden p-1 rounded-lg hover:bg-white hover:bg-opacity-10 transition duration-200">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="space-y-2 flex-1">
        <a href="dashboard.php"
            class="sidebar-link group flex items-center px-4 py-3 rounded-xl font-medium text-base lg:text-lg transition-all duration-300 hover:bg-white hover:bg-opacity-10 hover:scale-105 <?php echo $current_page == 'dashboard.php' ? 'active bg-white bg-opacity-15 border border-white border-opacity-20' : ''; ?>"
            data-target="dashboard">
            <div class="w-8 h-8 rounded-lg <?php echo $current_page == 'dashboard.php' ? 'bg-[#00BFB2] bg-opacity-30' : 'bg-white bg-opacity-10'; ?> flex items-center justify-center mr-3 group-hover:bg-opacity-20 transition duration-300">
                <i data-lucide="layout-dashboard" class="w-4 h-4 lg:w-5 lg:h-5"></i>
            </div>
            <span>Dashboard</span>
        </a>
        
        <a href="facturare.php"
            class="sidebar-link group flex items-center px-4 py-3 rounded-xl font-medium text-base lg:text-lg transition-all duration-300 hover:bg-white hover:bg-opacity-10 hover:scale-105 <?php echo $current_page == 'facturare.php' ? 'active bg-white bg-opacity-15 border border-white border-opacity-20' : ''; ?>"
            data-target="facturare">
            <div class="w-8 h-8 rounded-lg <?php echo $current_page == 'facturare.php' ? 'bg-[#00BFB2] bg-opacity-30' : 'bg-white bg-opacity-10'; ?> flex items-center justify-center mr-3 group-hover:bg-opacity-20 transition duration-300">
                <i data-lucide="wallet" class="w-4 h-4 lg:w-5 lg:h-5"></i>
            </div>
            <span>Facturare & Plăți</span>
        </a>
        
        <a href="../client/user_services.php"
            class="sidebar-link group flex items-center px-4 py-3 rounded-xl font-medium text-base lg:text-lg transition-all duration-300 hover:bg-white hover:bg-opacity-10 hover:scale-105 <?php echo $current_page == 'user_services.php' ? 'active bg-white bg-opacity-15 border border-white border-opacity-20' : ''; ?>"
            data-target="servicii">
            <div class="w-8 h-8 rounded-lg <?php echo $current_page == 'user_services.php' ? 'bg-[#00BFB2] bg-opacity-30' : 'bg-white bg-opacity-10'; ?> flex items-center justify-center mr-3 group-hover:bg-opacity-20 transition duration-300">
                <i data-lucide="server" class="w-4 h-4 lg:w-5 lg:h-5"></i>
            </div>
            <span>Servicii Active</span>
        </a>
        
        <a href="ticket.php"
            class="group flex items-center px-4 py-3 rounded-xl font-medium text-base lg:text-lg transition-all duration-300 hover:bg-white hover:bg-opacity-10 hover:scale-105 <?php echo $current_page == 'ticket.php' ? 'active bg-white bg-opacity-15 border border-white border-opacity-20' : ''; ?>">
            <div class="w-8 h-8 rounded-lg <?php echo $current_page == 'ticket.php' ? 'bg-[#00BFB2] bg-opacity-30' : 'bg-white bg-opacity-10'; ?> flex items-center justify-center mr-3 group-hover:bg-opacity-20 transition duration-300">
                <i data-lucide="message-square" class="w-4 h-4 lg:w-5 lg:h-5"></i>
            </div>
            <span>Tichet Suport</span>
        </a>
        
        <a href="user_settings.php"
            class="group flex items-center px-4 py-3 rounded-xl font-medium text-base lg:text-lg transition-all duration-300 hover:bg-white hover:bg-opacity-10 hover:scale-105 <?php echo $current_page == 'user_settings.php' ? 'active bg-white bg-opacity-15 border border-white border-opacity-20' : ''; ?>">
            <div class="w-8 h-8 rounded-lg <?php echo $current_page == 'user_settings.php' ? 'bg-[#00BFB2] bg-opacity-30' : 'bg-white bg-opacity-10'; ?> flex items-center justify-center mr-3 group-hover:bg-opacity-20 transition duration-300">
                <i data-lucide="settings" class="w-4 h-4 lg:w-5 lg:h-5"></i>
            </div>
            <span>Setări</span>
        </a>
    </nav>

    <!-- Bottom Actions -->
    <div class="mt-auto border-t border-white border-opacity-20 pt-6 space-y-3 w-full">
        <?php if ($is_staff_or_admin): ?>
            <a href="../staff/sentrapanel.php"
                class="group flex items-center justify-center px-4 py-3 rounded-xl font-medium text-base lg:text-lg transition-all duration-300 bg-white bg-opacity-10 hover:bg-opacity-20 hover:scale-105">
                <div class="w-6 h-6 rounded-lg bg-green-500 bg-opacity-20 flex items-center justify-center mr-2">
                    <i data-lucide="layout-dashboard" class="w-3 h-3 lg:w-4 lg:h-4 text-green-300"></i>
                </div>
                <span>Sentra Panel</span>
            </a>
        <?php endif; ?>

        <!-- Notifications -->
        <div class="relative w-full">
            <button id="notifButton"
                class="group flex items-center justify-center w-full px-4 py-3 rounded-xl font-medium text-base lg:text-lg transition-all duration-300 bg-white bg-opacity-10 hover:bg-opacity-20 hover:scale-105 relative">
                <div class="w-6 h-6 rounded-lg bg-blue-500 bg-opacity-20 flex items-center justify-center mr-2">
                    <i data-lucide="bell" class="w-3 h-3 lg:w-4 lg:h-4 text-blue-300"></i>
                </div>
                <span>Notificări</span>

                <span id="notif-count-badge"
                    class="absolute top-0 right-0 transform -translate-y-1/3 translate-x-1/3 h-5 w-5 bg-transparent text-red-500 text-xs font-bold rounded-full flex items-center justify-center hidden border border-red-500">
                    0
                </span>
            </button>
        </div>

        <!-- Logout -->
        <a href="../pages/logout.php" id="logoutBtn"
            class="group flex items-center justify-center px-4 py-3 rounded-xl font-medium text-base lg:text-lg transition-all duration-300 bg-white bg-opacity-10 hover:bg-opacity-20 hover:scale-105">
            <div class="w-6 h-6 rounded-lg bg-red-500 bg-opacity-20 flex items-center justify-center mr-2">
                <i data-lucide="log-out" class="w-3 h-3 lg:w-4 lg:h-4 text-red-300"></i>
                </div>
            <span>Deconectare</span>
        </a>
    </div>
</div>

<!-- Notifications Panel -->
<div id="notif-panel">
    <div class="notif-header">
        <h2 class="text-xl font-bold text-gray-800 flex items-center">
            <i data-lucide="bell" class="w-5 h-5 mr-2 text-sentra-cyan"></i>
            Notificări
        </h2>
        <button id="close-notif-btn" class="p-2 rounded-lg hover:bg-gray-100 transition duration-200">
            <i data-lucide="x" class="w-5 h-5 text-gray-600"></i>
        </button>
    </div>
    
    <ul id="notif-list" class="space-y-3 overflow-y-auto max-h-[85vh] pr-2">
        <li class="p-4 bg-gray-50 rounded-lg text-gray-600 text-center">
            <i data-lucide="loader" class="w-5 h-5 animate-spin mx-auto mb-2"></i>
            Se încarcă notificările...
        </li>
    </ul>

    <div class="notif-footer mt-4 border-t pt-4 space-y-2">
        <button id="mark-all-read-btn" class="w-full text-center text-blue-600 hover:text-blue-800 transition duration-200 font-medium py-2 hidden flex items-center justify-center">
            <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
            Marchează tot ca citit
        </button>
        <button id="delete-read-btn" class="w-full text-center text-gray-600 hover:text-gray-800 transition duration-200 font-medium py-2 hidden flex items-center justify-center">
            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
            Șterge notificările citite
        </button>
    </div>
</div>

<!-- Global Notification -->
<div id="global-notification" class="fixed top-5 right-5 z-[10001] p-4 rounded-lg shadow-xl text-white opacity-0 transition-opacity duration-500 hidden">
</div>

<style>
/* Sidebar Styles */
.gradient-sidebar {
    background: linear-gradient(135deg, #1f2937 0%, #00BFB2 100%);
    min-height: 100vh;
    overflow-y: auto;
}

/* Mobile Sidebar */
@media (max-width: 1023px) {
    .gradient-sidebar {
        position: fixed;
        top: 0;
        left: -100%;
        width: 85%;
        max-width: 320px;
        transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 50;
        display: flex !important;
    }
    
    .gradient-sidebar.mobile-open {
        left: 0;
    }
    
    #mobileOverlay {
        transition: opacity 0.3s ease;
    }
}

/* Notification Panel Styles */
#notif-panel {
    position: fixed; 
    top: 0; 
    right: 0;
    width: 100%; 
    max-width: 380px; 
    height: 100%; 
    padding: 20px;
    background-color: #ffffff;
    box-shadow: -4px 0 25px rgba(0, 0, 0, 0.15);
    z-index: 10000; 
    transform: translateX(100%); 
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
}

#notif-panel.is-open {
    transform: translateX(0); 
}

.notif-header {
    display: flex; 
    justify-content: space-between; 
    align-items: center;
    border-bottom: 1px solid #e5e7eb; 
    padding-bottom: 16px; 
    margin-bottom: 16px;
}

.notif-footer {
    border-top: 1px solid #e5e7eb;
}

/* Scrollbar Styling */
#notif-list::-webkit-scrollbar {
    width: 4px;
}

#notif-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#notif-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

#notif-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Active State for Sidebar Links */
.sidebar-link.active {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.sidebar-link.active div:first-child {
    background: rgba(0, 191, 178, 0.3) !important;
}

/* Hover Effects */
.group:hover div:first-child {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

/* Notification Badge Pulse Animation */
#notif-count-badge {
    animation: pulse 2s infinite;
    background: transparent !important;
    border: 2px solid #ef4444;
    color: #ef4444;
    font-weight: bold;
}

@keyframes pulse {
    0% { transform: translate(-50%, -50%) scale(1); }
    50% { transform: translate(-50%, -50%) scale(1.1); }
    100% { transform: translate(-50%, -50%) scale(1); }
}

/* Mobile Responsive Adjustments */
@media (max-width: 640px) {
    #notif-panel {
        max-width: 100%;
        padding: 16px;
    }
    
    .gradient-sidebar {
        padding: 1.5rem;
    }
}

/* Smooth Transitions */
* {
    transition-property: color, background-color, border-color, transform, opacity;
    transition-duration: 200ms;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Functionality
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileCloseButton = document.getElementById('mobileCloseButton');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const sidebar = document.querySelector('.gradient-sidebar');

    function openMobileMenu() {
        sidebar.classList.add('mobile-open');
        mobileOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        sidebar.classList.remove('mobile-open');
        mobileOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', openMobileMenu);
    }

    if (mobileCloseButton) {
        mobileCloseButton.addEventListener('click', closeMobileMenu);
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', closeMobileMenu);
    }

    // Close mobile menu when clicking on navigation links
    document.querySelectorAll('.gradient-sidebar a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                closeMobileMenu();
            }
        });
    });

    // Notification System
    const API_URL = '../api/notificari.php';
    const FETCH_INTERVAL = 8000;
    const NOTIFICATION_DURATION = 2000;
    
    const notifBtn = document.getElementById('notifButton'); 
    const notifPanel = document.getElementById('notif-panel');
    const closeBtn = document.getElementById('close-notif-btn');
    const notifList = document.getElementById('notif-list');
    const notifCountBadge = document.getElementById('notif-count-badge');
    const markAllReadBtn = document.getElementById('mark-all-read-btn');
    const deleteReadBtn = document.getElementById('delete-read-btn'); 
    const logoutBtn = document.getElementById('logoutBtn');
    const globalNotif = document.getElementById('global-notification');

    if (!notifBtn || !notifPanel || !closeBtn) {
        console.error("Eroare: Elementele HTML/JS esențiale pentru notificări nu au fost găsite.");
        return;
    }

    // Utility Functions
    function showNotification(message, color) {
        let bgColor = color === 'red' ? 'bg-red-600' : color === 'green' ? 'bg-green-600' : 'bg-gray-700';
        globalNotif.textContent = message;
        globalNotif.className = `fixed top-5 right-5 z-[10001] p-4 rounded-lg shadow-xl text-white opacity-0 transition-opacity duration-500 ${bgColor}`;
        globalNotif.classList.remove('hidden');
        setTimeout(() => globalNotif.classList.add('opacity-100'), 10); 

        setTimeout(() => {
            globalNotif.classList.remove('opacity-100');
            setTimeout(() => globalNotif.classList.add('hidden'), 500);
        }, NOTIFICATION_DURATION);
    }
    
    function adaugaNotificareInLista(notif) {
        const liClass = notif.is_read == 0 
            ? 'p-4 bg-blue-50 hover:bg-blue-100 transition duration-200 rounded-lg border-l-4 border-blue-500 cursor-pointer group' 
            : 'p-4 bg-gray-50 hover:bg-gray-100 transition duration-200 rounded-lg border-l-4 border-gray-300 cursor-pointer group';

        const icon = notif.is_read == 0 ? 'bell' : 'bell-off';
        
        return `
            <li class="${liClass}" data-notif-id="${notif.id}" data-is-read="${notif.is_read}">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <i data-lucide="${icon}" class="w-4 h-4 ${notif.is_read == 0 ? 'text-blue-500' : 'text-gray-400'}"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <a href="${notif.url || '#'}" class="block">
                            <p class="text-sm font-medium text-gray-900 group-hover:text-gray-600">${notif.mesaj}</p>
                            <p class="text-xs text-gray-500 mt-1">${notif.data_creare}</p>
                        </a>
                    </div>
                </div>
            </li>
        `;
    }

    function actualizeazaEcuson(count) {
        if (count > 0) {
            notifCountBadge.textContent = count > 9 ? '9+' : count;
            notifCountBadge.classList.remove('hidden');
        } else {
            notifCountBadge.classList.add('hidden');
            notifCountBadge.textContent = '0';
        }
    }

    // AJAX Functions
    function markNotificationAsRead(notifId) {
        fetch(API_URL, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'mark_read', notif_id: notifId })
        })
        .then(response => {
             if (response.ok) {
                 const currentCount = parseInt(notifCountBadge.textContent || '0');
                 if (currentCount > 0) {
                      actualizeazaEcuson(currentCount - 1);
                 }
             }
             return response.json(); 
        })
        .catch(error => console.error('Eroare la marcarea ca citit:', error));
    }
    
    function markAllAsRead() {
        fetch(API_URL, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'mark_all_read' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                actualizeazaEcuson(0);
                incarcaNotificari(true);
                showNotification('Toate notificările au fost marcate ca citite.', 'green');
            }
        })
        .catch(error => console.error('Eroare la marcarea tuturor ca citite:', error));
    }
    
    function deleteReadNotifications() {
        fetch(API_URL, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'delete_read' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.count > 0) {
                    showNotification(`Au fost șterse ${data.count} notificări citite.`, 'green');
                } else {
                    showNotification('Nu au fost găsite notificări citite de șters.', 'gray');
                }
                incarcaNotificari(true);
            }
        })
        .catch(error => {
            console.error('Eroare la ștergerea notificărilor citite:', error);
            showNotification('Eroare la ștergerea notificărilor.', 'red');
        });
    }

    // Main Loading Function
    function incarcaNotificari(isPanelOpen = false) {
        if (isPanelOpen) {
            notifList.innerHTML = `
                <li class="p-4 text-center text-gray-500">
                    <i data-lucide="loader" class="w-5 h-5 animate-spin mx-auto mb-2"></i>
                    <p>Se încarcă notificările...</p>
                </li>
            `;
        }
        
        fetch(API_URL)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Eroare HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(notificari => {
                let html = '';
                let unreadCount = 0;
                let readCount = 0; 
                
                if (Array.isArray(notificari) && notificari.length > 0) {
                    notificari.forEach(notif => {
                        html += adaugaNotificareInLista(notif);
                        if (notif.is_read == 0) {
                            unreadCount++;
                        } else {
                            readCount++;
                        }
                    });
                } else if (Array.isArray(notificari)) {
                    html = `
                        <li class="p-8 text-center text-gray-500">
                            <i data-lucide="bell-off" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="font-medium">Nu ai notificări noi</p>
                            <p class="text-sm">Vei fi notificat când apar actualizări</p>
                        </li>
                    `;
                }
                
                if (notifPanel.classList.contains('is-open')) {
                    notifList.innerHTML = html;
                    
                    if (readCount > 0) {
                        deleteReadBtn.classList.remove('hidden');
                    } else {
                        deleteReadBtn.classList.add('hidden');
                    }
                    
                    if (unreadCount > 0) {
                        markAllReadBtn.classList.remove('hidden');
                    } else {
                        markAllReadBtn.classList.add('hidden');
                    }

                    // Refresh icons
                    lucide.createIcons();
                }

                actualizeazaEcuson(unreadCount);
            })
            .catch(error => {
                console.error('Eroare AJAX la notif:', error);
                if (notifPanel.classList.contains('is-open')) {
                    notifList.innerHTML = `
                        <li class="p-4 text-center text-red-500">
                            <i data-lucide="alert-circle" class="w-5 h-5 mx-auto mb-2"></i>
                            <p>Eroare la încărcarea notificărilor</p>
                            <p class="text-xs">${error.message}</p>
                        </li>
                    `;
                    deleteReadBtn.classList.add('hidden');
                    markAllReadBtn.classList.add('hidden');
                }
                actualizeazaEcuson(0);
            });
    }

    // Click Outside Handler
    function handleClickOutside(event) {
        if (!notifPanel.classList.contains('is-open')) {
            return;
        }
        
        const isClickInsidePanel = notifPanel.contains(event.target);
        const isClickOnNotifButton = notifBtn.contains(event.target);
        
        if (!isClickInsidePanel && !isClickOnNotifButton) {
            notifPanel.classList.remove('is-open');
        }
    }

    // Event Listeners
    if (logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            showNotification('Vei fi deconectat. Redirecționare în 2 secunde...', 'red');
            setTimeout(() => { window.location.href = '../pages/logout.php'; }, NOTIFICATION_DURATION);
        });
    }

    notifBtn.addEventListener('click', (event) => {
        event.stopPropagation();
        notifPanel.classList.toggle('is-open'); 
        if (notifPanel.classList.contains('is-open')) {
            incarcaNotificari(true);
        }
    });
    
    closeBtn.addEventListener('click', () => notifPanel.classList.remove('is-open'));
    
    markAllReadBtn.addEventListener('click', markAllAsRead);
    deleteReadBtn.addEventListener('click', deleteReadNotifications);

    notifList.addEventListener('click', function(e) {
        const li = e.target.closest('li[data-notif-id]');
        if (!li) return;

        const isUnread = li.dataset.isRead == 0;
        
        if (isUnread) { 
            const notifId = li.dataset.notifId;
            markNotificationAsRead(notifId);
            
            li.dataset.isRead = 1; 
            li.classList.remove('bg-blue-50', 'border-blue-500');
            li.classList.add('bg-gray-50', 'border-gray-300');
            
            incarcaNotificari(true); 
        }
        
        const link = li.querySelector('a');
        if (link && link.href) {
            setTimeout(() => window.location.href = link.href, 50); 
        }
    });

    document.addEventListener('click', handleClickOutside);
    notifPanel.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    // Initialize
    incarcaNotificari(false); 
    setInterval(() => incarcaNotificari(false), FETCH_INTERVAL); 
    
    if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
    }

    // Close notifications panel on mobile when navigating
    window.addEventListener('resize', function() {
        if (window.innerWidth < 1024 && notifPanel.classList.contains('is-open')) {
            notifPanel.classList.remove('is-open');
        }
    });
});
</script>