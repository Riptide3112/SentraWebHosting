<?php
// api_fetch_data.php
// script php care foloseste ajax pentru a actualiza automat datele fara a reincarca pagina
session_start();
if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit(json_encode(['error' => 'Neautorizat']));
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sentra_db";
$user_id = $_SESSION['user_id'];

header('Content-Type: application/json');

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Eroare de conectare la baza de date.");
    }
    $conn->set_charset("utf8mb4");

    $response = [];

    // Verifică ce date sunt solicitate
    $data_type = $_GET['type'] ?? 'all';

    if ($data_type === 'invoices' || $data_type === 'all') {
        // Preluare facturi actualizate
        $invoices = [];
        $stmt = $conn->prepare("
            SELECT id, invoice_number, amount, due_date, status, created_at 
            FROM invoices 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $invoices[] = $row;
        }
        $stmt->close();

        $response['invoices'] = $invoices;
        
        // Statistici facturi
        $pending_count = 0;
        $paid_count = 0;
        $total_amount = 0;
        
        foreach ($invoices as $invoice) {
            if ($invoice['status'] === 'pending') {
                $pending_count++;
                $total_amount += $invoice['amount'];
            } elseif ($invoice['status'] === 'paid') {
                $paid_count++;
            }
        }
        
        $response['invoice_stats'] = [
            'total' => count($invoices),
            'pending' => $pending_count,
            'paid' => $paid_count,
            'total_amount' => $total_amount
        ];
    }

    if ($data_type === 'services' || $data_type === 'all') {
        // Preluare servicii actualizate
        $services = [];
        $stmt = $conn->prepare("
            SELECT id, service_name, service_type, status, price, created_at, expires_at 
            FROM services 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        $stmt->close();

        $response['services'] = $services;
        
        // Statistici servicii
        $active_count = 0;
        $expiring_count = 0;
        $expired_count = 0;
        $today = date('Y-m-d');
        $next_week = date('Y-m-d', strtotime('+7 days'));
        
        foreach ($services as $service) {
            if ($service['status'] === 'active') {
                $active_count++;
                
                if ($service['expires_at']) {
                    if ($service['expires_at'] < $today) {
                        $expired_count++;
                    } elseif ($service['expires_at'] <= $next_week) {
                        $expiring_count++;
                    }
                }
            }
        }
        
        $response['service_stats'] = [
            'total' => count($services),
            'active' => $active_count,
            'expiring' => $expiring_count,
            'expired' => $expired_count
        ];
    }

    $response['timestamp'] = date('Y-m-d H:i:s');
    $response['success'] = true;

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>