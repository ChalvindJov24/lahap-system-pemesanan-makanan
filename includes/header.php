<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lahap - <?= e($page_title ?? 'Sistem Pemesanan Makanan') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<?php if (isset($navbar) && $navbar !== false): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-danger mb-4">
    <div class="container">
        <a class="navbar-brand" href="/index.php">Lahap</a>
        <?php if (is_logged_in()): ?>
        <span class="navbar-text me-3">
            Halo, <strong><?= e(current_user()['name'] ?? '') ?></strong>
        </span>
        <a href="/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        <?php else: ?>
        <a href="/login.php" class="btn btn-outline-light btn-sm">Login</a>
        <a href="/register.php" class="btn btn-warning text-dark btn-sm">Daftar</a>
        <?php endif; ?>
    </div>
</nav>
<?php endif; ?>

<?php
$flash_msg = flash('message');
if ($flash_msg): ?>
<div class="container">
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= $flash_msg ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<div class="container">
