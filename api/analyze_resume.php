<?php
header("Content-Type: application/json");
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Only POST requests are allowed"]);
    exit();
}

if (!isset($_FILES['resume'])) {
    echo json_encode(["error" => "No resume file uploaded"]);
    exit();
}

$upload_dir = '../uploads/resumes/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$file_name = time() . '_' . basename($_FILES['resume']['name']);
$target_file = $upload_dir . $file_name;
$file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if ($file_type != "pdf") {
    echo json_encode(["error" => "Only PDF files are allowed"]);
    exit();
}

if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_file)) {
    // Call Python AI Bridge
    $python_path = "python"; // Adjust if necessary
    $script_path = "../scripts/ai_processor.py";
    $abs_pdf_path = realpath($target_file);

    $command = escapeshellcmd("$python_path $script_path \"$abs_pdf_path\"");
    $output = shell_exec($command);

    $response = json_decode($output, true);

    if (isset($response['error'])) {
        echo json_encode(["error" => "AI Analysis Error: " . $response['error']]);
    } else {
        // Use a dummy user_id for now if not logged in, or handle auth
        $user_id = $_SESSION['user_id'] ?? 1;

        // Store in database
        try {
            $detailed_analysis = json_encode([
                'health_checks' => $response['health_checks'] ?? [],
                'job_fit' => $response['job_fit'] ?? [],
                'skills_found' => $response['skills_found'] ?? [],
                'projects' => $response['projects'] ?? []
            ]);

            $stmt = $pdo->prepare("INSERT INTO resume_analysis (user_id, resume_path, ai_summary, ats_score, detailed_analysis, recommended_jobs, interview_prep) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $user_id,
                $target_file,
                $response['summary'] ?? '',
                $response['ats_compatibility'] ?? 0,
                $detailed_analysis,
                json_encode($response['recommended_jobs'] ?? []),
                json_encode($response['interview_prep'] ?? [])
            ]);

            echo json_encode($response);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database Error: " . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(["error" => "Error uploading file"]);
}
?>