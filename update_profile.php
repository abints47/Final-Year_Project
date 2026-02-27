<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $gender = $_POST['gender'];
    $address = trim($_POST['address']);
    $user_email = $_SESSION['user_email'];

    try {
        // Start building the update query
        $update_fields = ["full_name = ?", "gender = ?", "address = ?"];
        $params = [$full_name, $gender, $address];

        // Handle Profile Photo Upload
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['profile_photo']['tmp_name'];
            $file_name = $_FILES['profile_photo']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Validate extension
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($file_ext, $allowed_exts)) {
                $new_file_name = md5(time() . $file_name) . '.' . $file_ext;
                $upload_dir = __DIR__ . '/uploads/profile_photos/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $upload_path = $upload_dir . $new_file_name;
                $db_save_path = 'uploads/profile_photos/' . $new_file_name;

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $update_fields[] = "profile_photo = ?";
                    $params[] = $db_save_path;
                    $_SESSION['user_photo'] = $db_save_path;
                } else {
                    throw new Exception("Failed to move uploaded file.");
                }
            } else {
                throw new Exception("Invalid file extension. Allowed: jpg, jpeg, png, webp.");
            }
        }

        $params[] = $user_email;
        $sql = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE email_address = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Update Session Variables
        $_SESSION['user_name'] = $full_name;

        header("Location: profile.php?status=success");
        exit();
    } catch (PDOException $e) {
        header("Location: profile.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: profile.php");
    exit();
}
?>