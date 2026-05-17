<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_token = $_POST['id_token'] ?? '';

    if (empty($id_token)) {
        echo json_encode(['success' => false, 'message' => 'No token provided']);
        exit();
    }

    // Verify the Google ID Token using cURL (More reliable than file_get_contents)
    $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $id_token;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local XAMPP environments where SSL bundles might be missing

    $response = curl_exec($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        echo json_encode(['success' => false, 'message' => 'Connection to Google failed: ' . $curl_error]);
        exit();
    }

    $payload = json_decode($response, true);

    if (isset($payload['email'])) {
        $email = $payload['email'];
        $name = $payload['name'] ?? 'Google User';
        $google_id = $payload['sub'];
        $picture = $payload['picture'] ?? '';

        try {
            $stmt = $pdo->prepare("SELECT id, role, full_name, status FROM users WHERE email_address = ? OR google_id = ?");
            $stmt->execute([$email, $google_id]);
            $user = $stmt->fetch();

            if ($user) {
                // Update google_id if it's not set
                $stmt = $pdo->prepare("UPDATE users SET google_id = ? WHERE id = ?");
                $stmt->execute([$google_id, $user['id']]);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_photo'] = $picture;

                echo json_encode([
                    'success' => true,
                    'redirect' => ($user['role'] === 'admin' ? 'admin_panel.php' : ($user['role'] === 'teacher' ? 'teacher_dashboard.php' : 'dashboard.php'))
                ]);
            } else {
                // Register new student user
                $stmt = $pdo->prepare("INSERT INTO users (full_name, email_address, role, status, google_id, profile_photo) VALUES (?, ?, 'student', 'approved', ?, ?)");
                $stmt->execute([$name, $email, $google_id, $picture]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = 'student';
                $_SESSION['user_photo'] = $picture;

                echo json_encode(['success' => true, 'redirect' => 'dashboard.php']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        $error_msg = $payload['error_description'] ?? 'Invalid Google Token';
        echo json_encode(['success' => false, 'message' => $error_msg]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
