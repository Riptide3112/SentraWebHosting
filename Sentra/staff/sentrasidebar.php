<?php
// sentrasidebar.php - Meniul Sidebar pentru Sentra Panel
// Necesită ca $display_role și $current_page să fie definite în pagina care îl include.

// Definirea link-urilor și a iconițelor
$menu_items = [
    // Dashboard
    'sentrapanel.php' => [
        'icon' => 'layout-dashboard',
        'label' => 'Dashboard',
        'section' => 'main',
    ],

    // Client & Suport
    'sentraclients.php' => [
        'icon' => 'users',
        'label' => 'Utilizatori',
        'section' => 'client_support',
    ],
    'sentratickets.php' => [
        'icon' => 'messages-square',
        'label' => 'Tickete Deschise',
        'section' => 'client_support',
    ],

    // Comunicații & Sistem
    'sentraannouncements.php' => [
        'icon' => 'megaphone',
        'label' => 'Anunțuri',
        'section' => 'comms_system',
    ],
];

// Asigură că $current_page este setat pentru a evita erorile
$current_page = $current_page ?? 'sentrapanel.php';

// Funcție utilitară pentru a determina dacă un link este activ
function is_active($page, $current_page)
{
    return basename($page) === basename($current_page);
}
?>

<aside class="w-64 text-white flex flex-col h-full shadow-2xl"
    style="background-image: linear-gradient(to bottom, #1e293b 0%, #00BFB2 100%) !important;">

    <div class="p-6 border-b border-gray-600">
        <h1 class="text-3xl font-extrabold tracking-tight">
            <a href="index.php" class="text-white hover:opacity-90 transition duration-150">
                Sentra
            </a>
            <a href="dashboard.php" style="color: #00BFB2;" class="hover:opacity-90 transition duration-150">
                Panel
            </a>
        </h1>
        <p class="text-xs mt-1 text-gray-300">Rol: <?php echo htmlspecialchars($display_role ?? 'Client'); ?></p>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <?php
        $current_section = '';
        foreach ($menu_items as $url => $item):
            $isActive = is_active($url, $current_page);

            // Afișarea titlului de secțiune
            if ($item['section'] !== $current_section):
                $current_section = $item['section'];
                $section_title = match ($current_section) {
                    'client_support' => 'Client & Suport',
                    'comms_system' => 'Comunicații & Sistem',
                    default => '',
                };
                if (!empty($section_title)):
                    echo '<h3 class="text-xs font-semibold uppercase text-gray-300 pt-4 pb-1">' . $section_title . '</h3>';
                endif;
            endif;

            // Construirea clasei link-ului
            $link_classes = 'sidebar-link group';
            $active_style = '';

            if ($isActive) {
                // Link Activ: Fundal Sentra Cyan (Solid)
                $link_classes .= ' font-bold text-white shadow-lg';
                // Folosim o nuanță puțin mai închisă de Sentra Cyan (#00A89C) pentru fundalul activ, 
                // pentru a se distinge de gradientul principal.
                $active_style = 'style="background-color: #00A89C !important; color: white !important;"';
            } else {
                // Stilul normal de repaus: text alb, hover pe Sentra Cyan mai închis.
                $link_classes .= ' text-white hover:bg-[#007F79] hover:text-white';
            }
            ?>
            <a href="<?php echo htmlspecialchars($url); ?>" class="<?php echo $link_classes; ?>" <?php echo $active_style; ?>>
                <i data-lucide="<?php echo htmlspecialchars($item['icon']); ?>" class="w-5 h-5"></i>
                <span><?php echo htmlspecialchars($item['label']); ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="p-4 border-t border-gray-600">
        <a href="../pages/logout.php"
            class="flex items-center space-x-3 p-3 rounded-lg text-sm transition duration-150 bg-red-600 hover:bg-red-700 font-semibold">
            <i data-lucide="log-out" class="w-5 h-5"></i>
            <span>Deconectare</span>
        </a>
    </div>
</aside>