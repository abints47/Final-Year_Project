<?php
// Database connection without DB selected
$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS login");
    $pdo->exec("USE login");
    echo "Database 'login' created/selected.\n";

    // 2. Create Users Table
    $pdo->exec("DROP TABLE IF EXISTS users");
    $pdo->exec("CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email_address VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(50) DEFAULT 'student',
        profile_photo VARCHAR(255) DEFAULT 'https://api.dicebear.com/7.x/avataaars/svg?seed=default',
        gender VARCHAR(20) DEFAULT NULL,
        address TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'users' recreated.\n";

    // 3. Create Courses Table
    $pdo->exec("DROP TABLE IF EXISTS courses");
    $pdo->exec("CREATE TABLE courses (
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
        video_path VARCHAR(255),
        summary TEXT,
        full_summary TEXT,
        what_you_learn JSON,
        resources JSON,
        uploaded_by VARCHAR(255) DEFAULT 'system',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'courses' recreated.\n";

    // 4. Import Initial Course Data
    require_once __DIR__ . '/../includes/courses_data.php';
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
    echo "Imported " . count($all_courses) . " base courses.\n";

    echo "\nRESET COMPLETED SUCCESSFULLY. SITE IS READY.\n";

} catch (PDOException $e) {
    die("ERROR: " . $e->getMessage());
}
?>