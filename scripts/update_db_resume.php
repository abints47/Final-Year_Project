<?php
require_once 'includes/db.php';

try {
    // Add full_analysis column if it doesn't exist
    $pdo->exec("ALTER TABLE resume_analysis ADD COLUMN IF NOT EXISTS full_analysis LONGTEXT");
    echo "Database updated successfully or column already exists.\n";
} catch (PDOException $e) {
    // Some older versions of MySQL/MariaDB don't support ADD COLUMN IF NOT EXISTS
    try {
        $pdo->exec("ALTER TABLE resume_analysis ADD COLUMN full_analysis LONGTEXT");
        echo "Column added successfully.\n";
    } catch (PDOException $e2) {
        if (strpos($e2->getMessage(), 'Duplicate column') !== false) {
            echo "Column already exists.\n";
        } else {
            echo "Error: " . $e2->getMessage() . "\n";
        }
    }
}
?>