<?php
// includes/session.php — Session management + auth middleware

session_start();

function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function get_role(): ?string {
    return $_SESSION['role'] ?? null;
}

function require_login(string $redirect = '/login.php'): void {
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header("Location: $redirect");
        exit;
    }
}

function require_role(string $role, string $redirect = '/index.php'): void {
    require_login($redirect);
    if (get_role() !== $role) {
        header("Location: $redirect");
        exit;
    }
}

function current_user(): ?array {
    if (!is_logged_in()) return null;
    return [
        'id'    => $_SESSION['user_id'],
        'name'  => $_SESSION['user_name'],
        'email' => $_SESSION['user_email'],
        'role'  => $_SESSION['role'],
    ];
}
