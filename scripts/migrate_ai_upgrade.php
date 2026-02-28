<?php
require_once __DIR__ . '/../includes/db.php';

try {
    // We will alter the resume_analysis table to support the new JSON structure
    // and keep it flexible.

    // Add new columns to resume_analysis
    $pdo->exec("ALTER TABLE resume_analysis 
        ADD COLUMN IF NOT EXISTS ats_score INT DEFAULT 0,
        ADD COLUMN IF NOT EXISTS detailed_analysis JSON,
        ADD COLUMN IF NOT EXISTS recommended_jobs JSON,
        MODIFY COLUMN technical_questions JSON NULL,
        MODIFY COLUMN behavioral_questions JSON NULL
    ");

    // Since the new AI output is a single 'interview_prep' array, 
    // let's add a column for that as well.
    $pdo->exec("ALTER TABLE resume_analysis 
        ADD COLUMN IF NOT EXISTS interview_prep JSON
    ");

    echo "Resume Analysis table upgraded for Phase 6 successfully.\n";

} catch (PDOException $e) {
    die("Migration failed: " . $e->getMessage());
}
?>