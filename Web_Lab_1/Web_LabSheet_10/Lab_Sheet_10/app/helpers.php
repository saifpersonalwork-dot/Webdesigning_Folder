<?php
declare(strict_types=1);

function e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function redirect(string $route): never {
    header('Location: index.php?route=' . urlencode($route));
    exit;
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}
function verify_csrf(): void {
    $sent = $_POST['_csrf'] ?? '';
    if (!is_string($sent) || !hash_equals($_SESSION['csrf'] ?? '', $sent)) {
        http_response_code(419);
        exit('Invalid or expired form token. Please go back and try again.');
    }
}
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void {
    if (!current_user()) redirect('login');
}
function json_response(array $data, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}
