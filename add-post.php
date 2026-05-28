<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/posts.php';
require_once __DIR__ . '/config/db.php';
requireLogin();

$pageTitle = 'Add Post — MegaBlog';
$error = '';
$b = BASE;

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
            else $imagePath = $upload['filename'];
        }

        if (!$error) {
            $result = createPost($_SESSION['user_id'], $title, $slug, $content, $imagePath, $status);
            if ($result) {
                header('Location: ' . BASE . '/post.php?slug=' . urlencode($slug));
                exit;
            }
            $error = 'Failed to create post. The slug may already exist.';
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <div class="container">
        <h1>✍️ Add New Post</h1>
        <p>Share your thoughts with the world</p>
    </div>
</div>
<section class="section" style="padding-top:0;">
<div class="container" style="max-width:800px;">
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div class="table-wrap" style="padding:32px;">
        <form method="POST" action="<?= $b ?>/add-post.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Post Title *</label>
                <input type="text" id="title" name="title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" placeholder="Enter an engaging title..." oninput="autoSlug(this.value)">
            </div>
            <div class="form-group">
                <label for="slug">Slug (URL-friendly name)</label>
                <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($_POST['slug'] ?? '') ?>" placeholder="auto-generated-from-title">
                <p class="form-hint">Leave blank to auto-generate from title.</p>
            </div>
            <div class="form-group">
                <label for="content">Content *</label>
                <textarea id="content" name="content" required placeholder="Write your blog post here..."><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label for="featured_image">Featured Image</label>
                <input type="file" id="featured_image" name="featured_image" accept="image/*">
                <p class="form-hint">JPEG, PNG, GIF, WebP. Max 5MB.</p>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="active">Active (Visible)</option>
                    <option value="inactive">Inactive (Hidden)</option>
                </select>
            </div>
            <div style="display:flex;gap:12px;">
                <button type="submit" class="btn btn-primary">Publish Post 🚀</button>
                <a href="<?= $b ?>/index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
</section>
<script>
function autoSlug(title) {
    const s = document.getElementById('slug');
    if (!s.dataset.manual) s.value = title.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
}
document.getElementById('slug').addEventListener('input', function(){ this.dataset.manual='1'; });
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
