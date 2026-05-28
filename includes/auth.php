<?php
require_once __DIR__ . '/../config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: '.BASE.'/login.php');
        exit;
    }
}

function requireGuest() {
    if (isLoggedIn()) {
        header('Location: '.BASE.'/index.php');
        exit;
    }
}

function currentUser() {
    if (!isLoggedIn()) return null;
    require_once __DIR__ . '/../config/db.php';
    $db = getDB();
    $id = (int)$_SESSION['user_id'];
    $stmt = $db->prepare("SELECT id, name, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function login($email, $password) {
    require_once __DIR__ . '/../config/db.php';
    $db = getDB();
    $stmt = $db->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        return true;
    }
    return false;
}

function logout() {
    session_destroy();
    header('Location: '.BASE.'/login.php');
    exit;
}

function signup($name, $email, $password) {
    require_once __DIR__ . '/../config/db.php';
    $db = getDB();
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        return ['success' => false, 'message' => 'Email already registered.'];
    }
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hash);
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $db->insert_id;
        $_SESSION['user_name'] = $name;
        return ['success' => true];
    }
    return ['success' => false, 'message' => 'Registration failed. Try again.'];
}
