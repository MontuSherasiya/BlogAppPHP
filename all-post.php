<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/posts.php';
require_once __DIR__ . '/config/db.php';
requireLogin();

$pageTitle = 'My Posts — MegaBlog';
$posts = getAllPostsByUser($_SESSION['user_id']);
$deleted = isset($_GET['deleted']);
$b = BASE;

include __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <div class="container" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div><h1>📚 My Posts</h1><p>Manage all your blog posts</p></div>
        <a href="<?= $b ?>/add-post.php" class="btn btn-primary">+ New Post</a>
    </div>
</div>
<section class="section" style="padding-top:0;">
<div class="container">
    <?php if ($deleted): ?>
        <div class="alert alert-success">Post deleted successfully.</div>
    <?php endif; ?>
    <?php if (empty($posts)): ?>
        <div class="empty-state">
            <div class="icon">📝</div>
            <h3>No posts yet</h3>
            <p>Start writing and share your thoughts.</p>
            <a href="<?= $b ?>/add-post.php" class="btn btn-primary">Write First Post</a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Title</th><th>Slug</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($post['title']) ?></strong></td>
                        <td style="color:var(--text-muted);font-size:.85rem;"><?= htmlspecialchars($post['slug']) ?></td>
                        <td><span class="badge <?= $post['status']==='active'?'badge-active':'badge-inactive' ?>"><?= ucfirst($post['status']) ?></span></td>
                        <td style="font-size:.85rem;color:var(--text-muted);"><?= date('M j, Y', strtotime($post['created_at'])) ?></td>
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <a href="<?= $b ?>/post.php?slug=<?= urlencode($post['slug']) ?>" class="btn btn-outline btn-sm">View</a>
                                <a href="<?= $b ?>/edit-post.php?id=<?= $post['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <form method="POST" action="<?= $b ?>/delete-post.php" onsubmit="return confirm('Delete this post permanently?');" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
