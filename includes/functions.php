<?php
// includes/functions.php — Helper functions

require_once __DIR__ . '/../config/database.php';

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function flash(string $key, ?string $message = null): ?string {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return $message;
    }
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function generate_order_code(): string {
    return 'ORD-' . date('Ymd-His');
}

function status_badge(string $status): string {
    $classes = [
        'pending'    => 'badge bg-warning text-dark',
        'processing' => 'badge bg-info',
        'ready'      => 'badge bg-primary',
        'completed'  => 'badge bg-success',
        'cancelled'  => 'badge bg-danger',
    ];
    $class = $classes[$status] ?? 'badge bg-secondary';
    return '<span class="' . $class . '">' . e(ucfirst($status)) . '</span>';
}

function payment_label(string $method): string {
    $labels = [
        'cash'     => 'Tunai',
        'e-wallet' => 'E-Wallet',
    ];
    return $labels[$method] ?? e($method);
}
