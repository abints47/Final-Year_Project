<?php
require_once 'includes/db.php';
try {
    $stmt = $pdo->query("DESCRIBE courses");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Courses Table Columns:\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>