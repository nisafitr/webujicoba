<?php
session_start();

const ADMIN_USER = 'admin';
const ADMIN_PASS = 'admin123';

function adminIsLoggedIn(): bool {
    return !empty($_SESSION['admin_logged_in']);
}

function requireAdmin(): void {
    if (!adminIsLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function attemptLogin(string $username, string $password): bool {
    if ($username === ADMIN_USER && $password === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        return true;
    }
    return false;
}

function logoutAdmin(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}
