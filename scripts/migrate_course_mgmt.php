<?php
require_once __DIR__ . '/../includes/db.php';

try {
    // 1. Create enrollments table
    $pdo->exec("CREATE TABLE IF NOT EXISTS enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        course_id INT NOT NULL,
        enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('active', 'completed') DEFAULT 'active',
        progress INT DEFAULT 0,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
        UNIQUE KEY user_course (user_id, course_id)
    )");
    echo "Created 'enrollments' table (if it didn't exist).\n";

    // 2. Create course_progress table (for granular lesson tracking)
    $pdo->exec("CREATE TABLE IF NOT EXISTS course_progress (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        course_id INT NOT NULL,
        lesson_id INT NOT NULL,
        completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
        UNIQUE KEY user_course_lesson (user_id, course_id, lesson_id)
    )");
    echo "Created 'course_progress' table (if it didn't exist).\n";

    echo "Course Management migration completed successfully.\n";

} catch (PDOException $e) {
    die("Migration failed: " . $e->getMessage());
}
?>