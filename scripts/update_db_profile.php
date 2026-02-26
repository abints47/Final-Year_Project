<?php
require_once 'includes/db.php';

try {
    // Add gender column if not exists
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS gender VARCHAR(20) DEFAULT NULL");

    // Add address column if not exists
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS address TEXT DEFAULT NULL");

    // Add profile_photo column if not exists
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_photo VARCHAR(255) DEFAULT NULL");

    echo "Database schema updated successfully.";
} catch (PDOException $e) {
    die("Error updating database: " . $e->getMessage());
}
?>