<?php
require_once 'includes/db.php';
try {
    $stmt = $pdo->query("DESCRIBE courses");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Courses Table:\n";
    print_r($columns);

    $stmt = $pdo->query("DESCRIBE enrollments");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "\nEnrollments Table:\n";
    print_r($columns);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>