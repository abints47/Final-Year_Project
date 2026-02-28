<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
    die("Unauthorized access.");
}

require_once 'includes/db.php';

if (isset($_GET['id'])) {
    $course_id = (int) $_GET['id'];

    // Security check: Ensure the course belongs to the logged-in teacher
    $stmt = $pdo->prepare("SELECT uploaded_by FROM courses WHERE id = ?");
    $stmt->execute([$course_id]);
    $course = $stmt->fetch();

    if ($course && $course['uploaded_by'] === $_SESSION['user_email']) {
        try {
            $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
            $stmt->execute([$course_id]);
            header("Location: teacher_dashboard.php?success=Course deleted successfully");
            exit();
        } catch (PDOException $e) {
            header("Location: teacher_dashboard.php?error=Error deleting course");
            exit();
        }
    } else {
        header("Location: teacher_dashboard.php?error=Unauthorized deletion attempt");
        exit();
    }
} else {
    header("Location: teacher_dashboard.php");
    exit();
}
?>