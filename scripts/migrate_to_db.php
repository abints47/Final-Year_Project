<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/courses_data.php';

try {
    // 1. Add role column to users table
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'student' AFTER password");
        echo "Added 'role' column to 'users' table.\n";
    } else {
        echo "'role' column already exists in 'users' table.\n";
    }

    // 2. Create courses table
    $pdo->exec("CREATE TABLE IF NOT EXISTS courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255),
        category VARCHAR(100),
        level VARCHAR(50),
        rating DECIMAL(3,1) DEFAULT 0.0,
        students VARCHAR(50) DEFAULT '0',
        duration VARCHAR(100),
        lessons INT DEFAULT 0,
        image VARCHAR(255),
        youtube_id VARCHAR(50),
        summary TEXT,
        full_summary TEXT,
        what_you_learn JSON,
        resources JSON,
        uploaded_by VARCHAR(255) DEFAULT 'system',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Created 'courses' table (if it didn't exist).\n";

    // 3. Migrate data if table is empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM courses");
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO courses (title, author, category, level, rating, students, duration, lessons, image, youtube_id, summary, full_summary, what_you_learn, resources) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($all_courses as $course) {
            $stmt->execute([
                $course['title'],
                $course['author'],
                $course['category'],
                $course['level'],
                $course['rating'],
                $course['students'],
                $course['duration'],
                $course['lessons'],
                $course['image'],
                $course['youtube_id'],
                $course['summary'],
                $course['full_summary'],
                json_encode($course['what_you_learn']),
                json_encode($course['resources'])
            ]);
        }
        echo "Migrated " . count($all_courses) . " courses to the database.\n";
    } else {
        echo "Courses table already has data. Skipping migration.\n";
    }

    echo "Migration completed successfully.\n";

} catch (PDOException $e) {
    die("Migration failed: " . $e->getMessage());
}
?>