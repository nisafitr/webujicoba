<?php
require_once __DIR__ . '/jwt.php';

/**
 * Ambil user dari JWT token di header Authorization.
 * @return array|null
 */
function getAuthUser(): ?array {
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
    } else {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    }

    // Jika tidak ditemukan di Authorization header, coba ambil dari cookie (compatibilitas)
    if (empty($authHeader)) {
        $cookieToken = $_COOKIE['jwtToken'] ?? $_COOKIE['jwt_token'] ?? '';
        if (!empty($cookieToken)) {
            $authHeader = 'Bearer ' . $cookieToken;
        }
    }

    if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
        return null;
    }

    $token = substr($authHeader, 7);
    $user = JWT::decode($token);
    return $user ?: null;
}

/**
 * Untuk proteksi halaman HTML (redirect ke login jika bukan admin)
 * @return array
 */
function requireAdminPage(): array {
    $user = getAuthUser();
    if (!$user || (($user['role'] ?? '') !== 'admin')) {
        header('Location: login.php');
        exit;
    }
    return $user;
}

/**
 * Middleware umum untuk memeriksa JWT token.
 * @return array
 */
function requireAuth(): array {
    $user = getAuthUser();
    if (!$user) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized. Token tidak valid atau kadaluarsa.']);
        exit;
    }
    return $user;
}

/**
 * Middleware khusus untuk admin.
 * @return array
 */
function isAdmin(): array {
    $user = requireAuth();
    if (($user['role'] ?? '') !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden. Hanya admin yang dapat mengakses resource ini.']);
        exit;
    }
    return $user;
}

/**
 * Alias untuk kompatibilitas kode lama.
 */
function requireAdmin(): array {
    return isAdmin();
}
