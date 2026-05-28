<?php

require_once __DIR__ . '/../config/db.php';

function getAllActivePosts() {
    $db = getDB();
    $result = $db->query("
        SELECT p.*, u.name AS author_name
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.status = 'active'
        ORDER BY p.created_at DESC
    ");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAllPostsByUser($userId) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getPostBySlug($slug) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT p.*, u.name AS author_name
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.slug = ?
    ");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getPostById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function createPost($userId, $title, $slug, $content, $imagePath, $status) {
    $db = getDB();
    $stmt = $db->prepare("
        INSERT INTO posts (user_id, title, slug, content, featured_image, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isssss", $userId, $title, $slug, $content, $imagePath, $status);
    return $stmt->execute() ? $db->insert_id : false;
}

function updatePost($id, $title, $slug, $content, $status, $imagePath = null) {
    $db = getDB();
    if ($imagePath) {
        $stmt = $db->prepare("
            UPDATE posts SET title=?, slug=?, content=?, status=?, featured_image=?
            WHERE id=?
        ");
        $stmt->bind_param("sssssi", $title, $slug, $content, $status, $imagePath, $id);
    } else {
        $stmt = $db->prepare("
            UPDATE posts SET title=?, slug=?, content=?, status=? WHERE id=?
        ");
        $stmt->bind_param("ssssi", $title, $slug, $content, $status, $id);
    }
    return $stmt->execute();
}

function deletePost($id, $userId) {
    $db = getDB();
    $post = getPostById($id);
    $stmt = $db->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $userId);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        if ($post && $post['featured_image'] && file_exists(__DIR__ . '/../uploads/' . $post['featured_image'])) {
            unlink(__DIR__ . '/../uploads/' . $post['featured_image']);
        }
        return true;
    }
    return false;
}

function generateSlug($title) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    return $slug;
}

function uploadImage($file) {
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed)) {
        return ['success' => false, 'message' => 'Invalid image type.'];
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'Image must be under 5MB.'];
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_', true) . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
        return ['success' => true, 'filename' => $filename];
    }
    return ['success' => false, 'message' => 'Upload failed.'];
}
