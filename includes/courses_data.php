<?php
// includes/courses_data.php
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM courses ORDER BY created_at DESC");
    $all_courses = $stmt->fetchAll();

    // Decode JSON fields for each course
    foreach ($all_courses as &$course) {
        $course['what_you_learn'] = !empty($course['what_you_learn']) ? json_decode($course['what_you_learn'], true) : [];
        if (!is_array($course['what_you_learn']))
            $course['what_you_learn'] = [];

        $course['resources'] = !empty($course['resources']) ? json_decode($course['resources'], true) : [];
        if (!is_array($course['resources']))
            $course['resources'] = [];
    }
} catch (PDOException $e) {
    $all_courses = [];
    // Log error or handle as needed
}
?>