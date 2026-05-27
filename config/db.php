<?php
// - Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'megablog');

// Base path - change this if your folder name is different
// e.g. if accessing via http://localhost:7080/megablog  → '/megablog'
// e.g. if accessing via http://localhost:7080/          → ''
define('BASE', '/megablog');

function getDB() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }
        $conn->set_charset("utf8mb4");
    }
    return $conn;
}