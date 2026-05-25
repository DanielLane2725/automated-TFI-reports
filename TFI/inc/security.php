<?php
declare(strict_types=1);
require_once __DIR__.'/session.php';

function require_login(): void {
    start_secure_session();
    if (!current_user()) {
        http_response_code(401);
        echo json_encode(['error'=>'not_authenticated']);
        exit;
    }
}

function require_role(int $minRole): void {
    require_login();
    $role = current_user()['role'] ?? 0;
    if ($role < $minRole) {
        http_response_code(403);
        echo json_encode(['error'=>'forbidden']);
        exit;
    }
}

function check_csrf(): void {
    $hdr = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $hdr)) {
        http_response_code(419);
        echo json_encode(['error'=>'bad_csrf']);
        exit;
    }
}

function json_input(): array {
    $raw = file_get_contents('php://input');
    $d = json_decode($raw, true);
    return is_array($d) ? $d : [];
}

function json_out($payload, int $code=200): void {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($code);
    echo json_encode($payload);
    exit;
}
?>