<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
$currentPage = basename($_SERVER['PHP_SELF']);
$loggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
$b = BASE;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'MegaBlog') ?></title>
    <link rel="stylesheet" href="<?= $b ?>/assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="container nav-inner">
        <a href="<?= $b ?>/index.php" class="logo">✍️ MegaBlog</a>
        <div class="nav-links">
            <a href="<?= $b ?>/index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">Home</a>
            <?php if ($loggedIn): ?>
                <a href="<?= $b ?>/all-posts.php" class="<?= $currentPage === 'all-posts.php' ? 'active' : '' ?>">All Posts</a>
                <a href="<?= $b ?>/add-post.php" class="<?= $currentPage === 'add-post.php' ? 'active' : '' ?>">Add Post</a>
                <span class="username">Hi, <?= htmlspecialchars($userName) ?>!</span>
                <a href="<?= $b ?>/logout.php" class="btn btn-outline">Logout</a>
            <?php else: ?>
                <a href="<?= $b ?>/login.php" class="btn btn-outline">Login</a>
                <a href="<?= $b ?>/signup.php" class="btn btn-primary">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
