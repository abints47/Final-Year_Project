<?php
header("Content-Type: application/json");
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Please login to enroll in courses"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : null;

if (!$course_id) {
    echo json_encode(["error" => "Course ID is required"]);
    exit();
}

try {
    // Check if already enrolled
    $stmt = $pdo->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);

    if ($stmt->fetch()) {
        echo json_encode(["success" => true, "message" => "Already enrolled"]);
        exit();
    }

    // Insert new enrollment
    $stmt = $pdo->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $course_id]);

    echo json_encode(["success" => true, "message" => "Enrolled successfully"]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>