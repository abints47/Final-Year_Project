<?php
require_once __DIR__ . '/../includes/db.php';

try {
    // Check if column exists first
    $stmt = $pdo->query("SHOW COLUMNS FROM courses LIKE 'playlist_videos'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE courses ADD COLUMN playlist_videos TEXT DEFAULT NULL");
        echo "Successfully added 'playlist_videos' column to 'courses' table.\n";
    } else {
        echo "'playlist_videos' column already exists.\n";
    }
} catch (PDOException $e) {
    die("Error adding column: " . $e->getMessage());
}
?>