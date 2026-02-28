<?php
require_once __DIR__ . '/../includes/db.php';

try {
    $pdo->exec("ALTER TABLE courses MODIFY COLUMN image TEXT");
    echo "Successfully updated 'image' column to TEXT.\n";
} catch (PDOException $e) {
    die("Update failed: " . $e->getMessage() . "\n");
}
?>