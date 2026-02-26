<?php
require_once 'includes/db.php';

// Fix ID 10
$pdo->prepare("UPDATE courses SET youtube_id = ? WHERE id = 10")->execute(['VL0qiKhWsUI']);

// Provide a default for ID 9 if empty (using the HTML Foundations ID as a placeholder or a common one)
$pdo->prepare("UPDATE courses SET youtube_id = ? WHERE id = 9 AND (youtube_id IS NULL OR youtube_id = '')")->execute(['ok-plXXHlWw']);

echo "Database IDs cleaned up.\n";
?>