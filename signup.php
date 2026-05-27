<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/db.php';
requireGuest();

$pageTitle = 'Sign Up — MegaBlog';
$error = '';
$b = BASE;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!$name || !$email || !$password) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $result = signup($name, $email, $password);
        if ($result['success']) {
            header('Location: ' . BASE . '/index.php');
            exit;
        }
        $error = $result['message'];
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="auth-wrap">
    <div class="auth-card">
        <h2>Create Account</h2>
        <p class="subtitle">Join MegaBlog and start writing today</p>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= $b ?>/signup.php">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="John Doe">
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="john@example.com">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="At least 6 characters">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Repeat your password">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;">Create Account</button>
        </form>
        <p style="text-align:center;margin-top:20px;color:var(--text-muted);">
            Already have an account? <a href="<?= $b ?>/login.php" style="color:var(--primary);font-weight:600;">Login</a>
        </p>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
