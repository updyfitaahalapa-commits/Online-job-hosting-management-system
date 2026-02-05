<?php
// db_init.php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // 1. Connect to MySQL first
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Create Database
    echo "Creating database...<br>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS jhms_db");
    $pdo->exec("USE jhms_db");
    echo "Database 'jhms_db' ready.<br>";

    // 3. Read and execute schema
    $sql = file_get_contents('db_schema.sql');
    if ($sql === false) {
        die("Could not find db_schema.sql");
    }

    echo "Executing schema...<br>";
    $pdo->exec($sql);
    echo "<b>Tables created successfully!</b><br>";
    echo "<a href='index.php'>Go to Homepage</a>";

} catch (PDOException $e) {
    die("Error initializing database: " . $e->getMessage());
}
?>
