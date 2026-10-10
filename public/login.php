<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

// If already logged in, redirect to appropriate dashboard
if (is_logged_in()) {
    $redirect = get_role() === 'admin' ? '/admin/dashboard.php' : '/customer/menu.php';
    header("Location: $redirect");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email']= $user['email'];
            $_SESSION['role']      = $user['role'];

            $redirect = $user['role'] === 'admin' ? '/admin/dashboard.php' : '/customer/menu.php';
            $after = $_SESSION['redirect_after_login'] ?? $redirect;
            header("Location: $after");
            exit;
        } else {
            $error = 'Email atau password salah.';
        }
    }
}
$page_title = 'Login';
$navbar = false;
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white text-center">
                <h4 class="mb-0">Lahap - Login</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="/login.php" data-bs-theme="light">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger">Login</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <small>Belum punya akun? <a href="/register.php">Daftar di sini</a></small>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
