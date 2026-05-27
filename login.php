<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/db.php';
requireGuest();

$pageTitle = 'Login — MegaBlog';
$error = '';
$b = BASE;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) {
        $error = 'Please fill in all fields.';
    } elseif (login($email, $password)) {
        header('Location: ' . BASE . '/index.php');
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="auth-wrap">
    <div class="auth-card">
        <h2>Welcome Back 👋</h2>
        <p class="subtitle">Log in to manage your blog posts</p>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= $b ?>/login.php">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="john@example.com">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Your password">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;">Login</button>
        </form>
        <p style="text-align:center;margin-top:20px;color:var(--text-muted);">
            Don't have an account? <a href="<?= $b ?>/signup.php" style="color:var(--primary);font-weight:600;">Sign Up</a>
        </p>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
