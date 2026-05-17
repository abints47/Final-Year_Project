<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$course_id = isset($data['course_id']) ? intval($data['course_id']) : null;
$user_id = $_SESSION['user_id'];

if (!$course_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid course ID']);
    exit();
}

try {
    // Update progress to 100% and set status to completed
    $stmt = $pdo->prepare("UPDATE enrollments SET progress = 100, status = 'completed' WHERE user_id = ? AND course_id = ?");
    $result = $stmt->execute([$user_id, $course_id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>