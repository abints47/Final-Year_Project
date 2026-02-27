<?php
require_once 'includes/db.php';

try {
    // Add video_path column if not exists
    $pdo->exec("ALTER TABLE courses ADD COLUMN IF NOT EXISTS video_path VARCHAR(255) DEFAULT NULL AFTER youtube_id");

    echo "Courses table updated successfully with video_path column.";
} catch (PDOException $e) {
    die("Error updating database: " . $e->getMessage());
}
?>