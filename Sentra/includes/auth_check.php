<?php
// includes/auth_check.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifică dacă utilizatorul este autentificat
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

/**
 * Verifică dacă utilizatorul are un anumit rol
 */
function has_role($required_role) {
    if (!is_logged_in()) {
        return false;
    }
    
    $user_role = $_SESSION['user_role'] ?? '';
    return in_array($user_role, (array)$required_role);
}

/**
 * Verifică accesul și redirectează dacă nu este autorizat
 */
function check_access($required_role = null) {
    // Dacă nu este logat, redirect către login
    if (!is_logged_in()) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'text' => 'Trebuie să fii autentificat pentru a accesa această pagină.'
        ];
        header("Location: ../sentra/pages/login.php");
        exit;
    }
    
    // Dacă se specifică un rol, verifică dacă are acces
    if ($required_role !== null && !has_role($required_role)) {
        $_SESSION['notification'] = [
            'type' => 'error', 
            'text' => 'Nu ai permisiunea de a accesa această pagină.'
        ];
        header("Location: ../sentra/client/dashboard.php");
        exit;
    }
    
    return true;
}

/**
 * Obține rolul utilizatorului curent
 */
function get_user_role() {
    return $_SESSION['user_role'] ?? 'client';
}

/**
 * Obține numele utilizatorului curent
 */
function get_user_name() {
    return $_SESSION['user_name'] ?? 'Utilizator';
}
?>
