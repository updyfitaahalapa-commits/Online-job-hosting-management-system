<?php
// config/db.php
$host = 'localhost';
$dbname = 'jhms_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Define Base URL - Detects if project is in a subfolder or root
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];

// Get the physical path to the project root (one level up from this config folder)
$project_root = str_replace('\\', '/', dirname(__DIR__));
// Get the document root
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
// Calculate the web path
$web_path = str_replace($doc_root, '', $project_root);
// Ensure it starts and ends with a slash correctly
$web_path = '/' . trim($web_path, '/') . '/';
if ($web_path === '//') $web_path = '/';

define('BASE_URL', $protocol . '://' . $host . $web_path);
?>
