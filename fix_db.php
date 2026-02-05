<?php
// fix_db.php
require_once 'config/db.php';

try {
    // Check if profile_pic column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM profiles LIKE 'profile_pic'");
    if (!$stmt->fetch()) {
        echo "Adding 'profile_pic' column to 'profiles' table...<br>";
        $pdo->exec("ALTER TABLE profiles ADD COLUMN profile_pic VARCHAR(255) AFTER logo_path");
        echo "Column added!<br>";
    } else {
        echo "Column 'profile_pic' already exists.<br>";
    }

    // Also check for logo_path if it was missing
    $stmt = $pdo->query("SHOW COLUMNS FROM profiles LIKE 'logo_path'");
    if (!$stmt->fetch()) {
        echo "Adding 'logo_path' column to 'profiles' table...<br>";
        $pdo->exec("ALTER TABLE profiles ADD COLUMN logo_path VARCHAR(255) AFTER company_description");
        echo "Column added!<br>";
    }

    echo "<b>Database fix complete!</b><br>";
    echo "<a href='seeker/profile.php'>Back to Profile</a>";
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
