<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/posts.php';
require_once __DIR__ . '/config/db.php';
requireLogin();

$postId = (int)($_GET['id'] ?? 0);
$post = getPostById($postId);
$b = BASE;

if (!$post || $post['user_id'] !== (int)$_SESSION['user_id']) {
    header('Location: ' . BASE . '/all-posts.php'); exit;
}

$pageTitle = 'Edit Post — MegaBlog';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $slug    = trim($_POST['slug'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status  = $_POST['status'] ?? 'active';

    if (!$title || !$content) {
        $error = 'Title and content are required.';
    } else {
        if (!$slug) $slug = generateSlug($title);
        else $slug = generateSlug($slug);

        $imagePath = null;
        if (!empty($_FILES['featured_image']['name'])) {
            $upload = uploadImage($_FILES['featured_image']);
            if (!$upload['success']) $error = $upload['message'];
            else {
                if ($post['featured_image'] && file_exists(__DIR__ . '/uploads/' . $post['featured_image']))
                    unlink(__DIR__ . '/uploads/' . $post['featured_image']);
                $imagePath = $upload['filename'];
            }
        }

        if (!$error) {
            if (updatePost($postId, $title, $slug, $content, $status, $imagePath)) {
                header('Location: ' . BASE . '/post.php?slug=' . urlencode($slug)); exit;
            }
            $error = 'Update failed. The slug may conflict with another post.';
        }
    }
}

$title   = $_POST['title'] ?? $post['title'];
$slug    = $_POST['slug'] ?? $post['slug'];
$content = $_POST['content'] ?? $post['content'];
$status  = $_POST['status'] ?? $post['status'];

include __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <div class="container"><h1>✏️ Edit Post</h1><p>Update your blog post</p></div>
</div>
<section class="section" style="padding-top:0;">
<div class="container" style="max-width:800px;">
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div class="table-wrap" style="padding:32px;">
        <form method="POST" action="<?= $b ?>/edit-post.php?id=<?= $postId ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label>Post Title *</label>
                <input type="text" name="title" required value="<?= htmlspecialchars($title) ?>">
            </div>
            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" value="<?= htmlspecialchars($slug) ?>">
            </div>
            <div class="form-group">
                <label>Content *</label>
                <textarea name="content" required><?= htmlspecialchars($content) ?></textarea>
            </div>
            <div class="form-group">
                <label>Current Featured Image</label>
                <?php if ($post['featured_image']): ?>
                    <img src="<?= $b ?>/uploads/<?= htmlspecialchars($post['featured_image']) ?>" style="max-width:200px;border-radius:8px;display:block;margin-bottom:8px;">
                <?php else: ?>
                    <p style="color:var(--text-muted);">No image uploaded.</p>
                <?php endif; ?>
                <label style="margin-top:8px;">Replace Image</label>
                <input type="file" name="featured_image" accept="image/*">
                <p class="form-hint">Leave blank to keep existing image.</p>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active" <?= $status==='active'?'selected':'' ?>>Active (Visible)</option>
                    <option value="inactive" <?= $status==='inactive'?'selected':'' ?>>Inactive (Hidden)</option>
                </select>
            </div>
            <div style="display:flex;gap:12px;">
                <button type="submit" class="btn btn-primary">Update Post</button>
                <a href="<?= $b ?>/post.php?slug=<?= urlencode($post['slug']) ?>" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
