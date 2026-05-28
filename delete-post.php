<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/posts.php';
require_once __DIR__ . '/config/db.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE . '/all-posts.php'); exit;
}

$postId = (int)($_POST['id'] ?? 0);
deletePost($postId, (int)$_SESSION['user_id']);
header('Location: ' . BASE . '/all-posts.php?deleted=1');
exit;
