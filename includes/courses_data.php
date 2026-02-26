<?php
// includes/courses_data.php
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM courses ORDER BY created_at DESC");
    $all_courses = $stmt->fetchAll();

    // Decode JSON fields for each course
    foreach ($all_courses as &$course) {
        if (isset($course['what_you_learn'])) {
            $course['what_you_learn'] = json_decode($course['what_you_learn'], true);
        }
        if (isset($course['resources'])) {
            $course['resources'] = json_decode($course['resources'], true);
        }
    }
} catch (PDOException $e) {
    $all_courses = [];
    // Log error or handle as needed
}
?>