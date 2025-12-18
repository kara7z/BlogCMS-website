<?php

function isLoggedIn() {
    return isset($_SESSION['username']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}


function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}


function hasRole($role) {
    return isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
}


function hasAnyRole($roles) {
    if (!isLoggedIn()) {
        return false;
    }
    return in_array($_SESSION['user_role'], $roles);
}

function requireRole($roles) {
    requireLogin();
    
    if (!is_array($roles)) {
        $roles = [$roles];
    }
    
    if (!hasAnyRole($roles)) {
        header('Location: index.php');
        exit;
    }
}


function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}


function redirect($url, $message = null, $type = 'success') {
    if ($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header("Location: $url");
    exit;
}


function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'success';
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}


function formatDate($date) {
    if (!$date) return '';
    $timestamp = is_string($date) ? strtotime($date) : $date;
    return date('d/m/Y', $timestamp);
}


function formatDateTime($date) {
    if (!$date) return '';
    $timestamp = is_string($date) ? strtotime($date) : $date;
    return date('Y/m/d', $timestamp);
}


function truncate($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}


function getRoleBadgeClass($role) {
    $classes = [
        'admin' => 'bg-red-100 text-red-800',
        'editor' => 'bg-blue-100 text-blue-800',
        'author' => 'bg-green-100 text-green-800',
        'subscriber' => 'bg-purple-100 text-purple-800',
    ];
    return $classes[$role] ?? 'bg-gray-100 text-gray-800';
}


function getRoleNameFr($role) {
    $names = [
        'admin' => 'Administrateur',
        'editor' => 'Éditeur',
        'author' => 'Auteur',
        'subscriber' => 'Abonné',
    ];
    return $names[$role] ?? ucfirst($role);
}


function generateSlug($text) {
    // Remplacer les caractères accentués
    $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
    // Mettre en minuscules
    $text = strtolower($text);
    // Remplacer les caractères non alphanumériques par des tirets
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    // Supprimer les tirets en début et fin
    $text = trim($text, '-');
    return $text;
}


function getInitial($name) {
    if (empty($name)) return '?';
    return strtoupper(substr($name, 0, 1));
}


function getAvatarColor($name) {
    $colors = [
        'from-blue-500 to-purple-500',
        'from-green-500 to-teal-500',
        'from-yellow-500 to-orange-500',
        'from-purple-500 to-pink-500',
        'from-red-500 to-pink-500',
        'from-indigo-500 to-blue-500',
    ];
    $index = strlen($name) % count($colors);
    return $colors[$index];
}

function paginate($total, $perPage, $currentPage) {
    $totalPages = ceil($total / $perPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $perPage;
    
    return [
        'total' => $total,
        'per_page' => $perPage,
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'offset' => $offset,
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages,
    ];
}
?>
