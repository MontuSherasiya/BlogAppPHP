<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/posts.php';
require_once __DIR__ . '/config/db.php';

$pageTitle = 'MegaBlog — Modern Blogging Platform';
$posts = getAllActivePosts();
$b = BASE;

include __DIR__ . '/includes/header.php';
?>

<?php if (!isLoggedIn()): ?>
<section class="hero">
    <div class="container">
        <h1>Welcome to MegaBlog ✍️</h1>
        <p>Discover insightful articles. Share your stories. Connect with readers.</p>
        <a href="<?= $b ?>/signup.php" class="btn btn-primary" style="font-size:1rem;padding:12px 32px;">Get Started Free</a>
        &nbsp;
        <a href="<?= $b ?>/login.php" class="btn btn-outline" style="font-size:1rem;padding:12px 32px;color:#fff;border-color:#fff;">Login</a>
    </div>
</section>
<?php endif; ?>

<section class="section">
    <div class="container">
        <h2 class="section-title">📚 Latest Posts</h2>
        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <div class="icon">📝</div>
                <h3>No posts yet!</h3>
                <p>Be the first one to share a story.</p>
                <?php if (isLoggedIn()): ?>
                    <a href="<?= $b ?>/add-post.php" class="btn btn-primary">Create Post</a>
                <?php else: ?>
                    <a href="<?= $b ?>/signup.php" class="btn btn-primary">Sign Up & Write</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
        <div class="posts-grid">
            <?php foreach ($posts as $post): ?>
            <div class="post-card">
                <?php if ($post['featured_image']): ?>
                    <img src="<?= $b ?>/uploads/<?= htmlspecialchars($post['featured_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                <?php else: ?>
                    <div class="post-card-placeholder">📝</div>
                <?php endif; ?>
                <div class="post-card-body">
                    <h3><a href="<?= $b ?>/post.php?slug=<?= urlencode($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
                    <p class="post-meta">
                        By <strong><?= htmlspecialchars($post['author_name']) ?></strong>
                        · <?= date('M j, Y', strtotime($post['created_at'])) ?>
                    </p>
                    <p style="color:var(--text-muted);font-size:.9rem;flex:1;">
                        <?= htmlspecialchars(mb_strimwidth(strip_tags($post['content']), 0, 100, '...')) ?>
                    </p>
                    <div style="margin-top:12px;">
                        <a href="<?= $b ?>/post.php?slug=<?= urlencode($post['slug']) ?>" class="btn btn-outline btn-sm">Read More →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>