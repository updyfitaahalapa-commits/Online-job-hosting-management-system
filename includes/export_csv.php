<?php
// includes/export_csv.php
require_once '../config/db.php';
require_once 'functions.php';

session_start();

if (!isset($_SESSION['role'])) {
    die("Access denied.");
}

$type = $_GET['type'] ?? '';

if ($type === 'jobs' && $_SESSION['role'] === 'admin') {
    $stmt = $pdo->query("SELECT j.title, j.category, j.location, j.job_type, j.status, j.created_at, u.name as employer FROM jobs j JOIN users u ON j.employer_id = u.id");
    $data = $stmt->fetchAll();
    $filename = "all_jobs_" . date('Ymd') . ".csv";
} elseif ($type === 'applications' && $_SESSION['role'] === 'employer') {
    $employer_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT a.status, a.applied_at, j.title as job_title, u.name as seeker_name FROM applications a JOIN jobs j ON a.job_id = j.id JOIN users u ON a.seeker_id = u.id WHERE j.employer_id = ?");
    $stmt->execute([$employer_id]);
    $data = $stmt->fetchAll();
    $filename = "my_applications_" . date('Ymd') . ".csv";
} else {
    die("Invalid request.");
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
if (!empty($data)) {
    fputcsv($output, array_keys($data[0])); // Headers
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
}
fclose($output);
exit();
?>
