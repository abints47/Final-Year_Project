<?php
require_once __DIR__ . '/../includes/db.php';

try {
    // Delete courses that were added by the system (not by a teacher)
    $stmt = $pdo->prepare("DELETE FROM courses WHERE uploaded_by = 'system'");
    $stmt->execute();
    $deleted_count = $stmt->rowCount();

    echo "Successfully removed $deleted_count system-generated courses.\n";
} catch (PDOException $e) {
    die("Cleanup failed: " . $e->getMessage() . "\n");
}
?>