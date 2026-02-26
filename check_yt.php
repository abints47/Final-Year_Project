<?php
require_once 'includes/db.php';
$stmt = $pdo->query("SELECT id, title, youtube_id FROM courses");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($courses as $c) {
    echo "ID: " . $c['id'] . " | Title: " . $c['title'] . " | YT: " . $c['youtube_id'] . "\n";
}
?>