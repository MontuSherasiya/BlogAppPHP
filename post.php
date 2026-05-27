<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/posts.php';
require_once __DIR__ . '/config/db.php';

$b = BASE;
$slug = $_GET['slug'] ?? '';
if (!$slug) { header('Location: ' . BASE . '/index.php'); exit; }

$post = getPostBySlug($slug);
if (!$post) {
    $pageTitle = 'Post Not Found — MegaBlog';
    include __DIR__ . '/includes/header.php';
    echo '<div class="container" style="padding:80px 20px;text-align:center;">
            <h2>404 — Post Not Found</h2>
            <p style="margin:12px 0;color:var(--text-muted);">This post does not exist or has been removed.</p>
            <a href="' . $b . '/index.php" class="btn btn-primary">← Back Home</a>
          </div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

if ($post['status'] === 'inactive' && !isLoggedIn()) {
    header('Location: ' . BASE . '/index.php'); exit;
}

$isOwner = isLoggedIn() && (int)$_SESSION['user_id'] === (int)$post['user_id'];
$pageTitle = htmlspecialchars($post['title']) . ' — MegaBlog';

include __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <div class="post-detail">
            <p style="margin-bottom:24px;">
                <a href="<?= $b ?>/index.php" style="color:var(--primary);text-decoration:none;">← Back to Posts</a>
            </p>
            <?php if ($post['status'] === 'inactive'): ?>
                <div class="alert alert-error" style="margin-bottom:20px;">⚠️ This post is <strong>inactive</strong> and not visible to others.</div>
            <?php endif; ?>
            <?php if ($post['featured_image']): ?>
                <img src="<?= $b ?>/uploads/<?= htmlspecialchars($post['featured_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="post-detail-img">
            <?php endif; ?>
            <h1 style="font-size:2.2rem;font-weight:900;margin-bottom:12px;"><?= htmlspecialchars($post['title']) ?></h1>
            <p class="post-meta" style="margin-bottom:32px;font-size:.95rem;">
                ✍️ By <strong><?= htmlspecialchars($post['author_name']) ?></strong>
                &nbsp;·&nbsp; 🗓️ <?= date('F j, Y', strtotime($post['created_at'])) ?>
            </p>
            <div class="post-content"><?= nl2br(htmlspecialchars($post['content'])) ?></div>
            <?php if ($isOwner): ?>
                <div style="margin-top:40px;padding-top:24px;border-top:1px solid var(--border);display:flex;gap:12px;">
                    <a href="<?= $b ?>/edit-post.php?id=<?= $post['id'] ?>" class="btn btn-primary">✏️ Edit Post</a>
                    <form method="POST" action="<?= $b ?>/delete-post.php" onsubmit="return confirm('Are you sure?');">
                        <input type="hidden" name="id" value="<?= $post['id'] ?>">
                        <button type="submit" class="btn btn-danger">🗑️ Delete Post</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
