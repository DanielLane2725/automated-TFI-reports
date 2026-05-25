<?php
declare(strict_types=1);
require_once __DIR__.'/config.php';

function start_secure_session(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    session_name(SESSION_NAME_APP);
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function current_user(): ?array { return $_SESSION['user'] ?? null; }

function login_user(array $userRow): void {
    $_SESSION['user'] = [
        'id'    => (int)$userRow['id'],
        'email' => $userRow['email'],
        'role'  => (int)$userRow['role_id'],
    ];
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function logout_user(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
?>
