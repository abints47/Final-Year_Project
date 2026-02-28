<?php
require_once __DIR__ . '/../includes/db.php';

try {
    // Create resume_analysis table
    $pdo->exec("CREATE TABLE IF NOT EXISTS resume_analysis (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        resume_path VARCHAR(255),
        ai_summary TEXT,
        technical_questions JSON,
        behavioral_questions JSON,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    echo "Created 'resume_analysis' table (if it didn't exist).\n";

    // Create uploads directory if it doesn't exist
    $resumes_dir = __DIR__ . '/../uploads/resumes';
    if (!is_dir($resumes_dir)) {
        mkdir($resumes_dir, 0777, true);
        echo "Created 'uploads/resumes' directory.\n";
    }

    echo "AI features migration completed successfully.\n";

} catch (PDOException $e) {
    die("AI features migration failed: " . $e->getMessage());
}
?>