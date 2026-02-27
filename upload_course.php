<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

require_once 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $level = trim($_POST['level']);
    $duration = trim($_POST['duration']);
    $lessons = (int) $_POST['lessons'];
    $youtube_id = trim($_POST['youtube_id']);
    $summary = trim($_POST['summary']);
    $full_summary = trim($_POST['full_summary']);
    $image = trim($_POST['image']) ?: 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80&w=800';
    $video_path = null;

    // Handle Local Video Upload
    if (isset($_FILES['course_video']) && $_FILES['course_video']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['course_video']['tmp_name'];
        $file_name = $_FILES['course_video']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_exts = ['mp4', 'webm', 'ogg'];
        if (in_array($file_ext, $allowed_exts)) {
            $new_file_name = md5(time() . $file_name) . '.' . $file_ext;
            $upload_dir = 'uploads/course_videos/';
            if (!is_dir($upload_dir))
                mkdir($upload_dir, 0777, true);

            $target_path = $upload_dir . $new_file_name;
            if (move_uploaded_file($file_tmp, $target_path)) {
                $video_path = $target_path;
            }
        } else {
            $error = "Invalid video format. Allowed: mp4, webm, ogg.";
        }
    }

    // Simple arrays for what you learn and resources
    $what_you_learn = array_filter(explode("\n", str_replace("\r", "", $_POST['what_you_learn'])));
    $resources = [
        ['name' => 'General Documentation', 'url' => 'https://docs.google.com/']
    ];

    if (empty($title) || empty($summary)) {
        $error = "Title and Summary are required.";
    } elseif (!$error) { // Only proceed if no upload error
        try {
            $stmt = $pdo->prepare("INSERT INTO courses (title, author, category, level, duration, lessons, image, youtube_id, video_path, summary, full_summary, what_you_learn, resources, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $title,
                $_SESSION['user_name'],
                $category,
                $level,
                $duration,
                $lessons,
                $image,
                $youtube_id,
                $video_path,
                $summary,
                $full_summary,
                json_encode($what_you_learn),
                json_encode($resources),
                $_SESSION['user_email']
            ]);
            $success = "Course uploaded successfully!";
        } catch (PDOException $e) {
            $error = "Error uploading course: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Course - Openly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="min-h-screen flex flex-col pt-24 pb-12">
    <?php include 'components/navbar.php'; ?>

    <main class="max-w-3xl mx-auto px-6 w-full">
        <div class="mb-8">
            <h1 class="text-4xl font-black text-white tracking-tight">Upload New Course</h1>
            <p class="text-slate-400 mt-2">Share your knowledge with the Openly community.</p>
        </div>

        <?php if ($error): ?>
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-200 text-sm rounded-2xl text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div
                class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-200 text-sm rounded-2xl text-center">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <div class="glass p-8 rounded-[2rem] shadow-2xl">
            <form action="upload_course.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Course
                            Title</label>
                        <input name="title" type="text" required placeholder="e.g. Master Python for Data Science"
                            class="input-field" />
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Category</label>
                        <select name="category" class="input-field">
                            <option value="Programming">Programming</option>
                            <option value="Web Dev">Web Development</option>
                            <option value="AI/ML">AI & Machine Learning</option>
                            <option value="Security">Cyber Security</option>
                            <option value="Cloud">Cloud Computing</option>
                            <option value="Data Science">Data Science</option>
                            <option value="Mobile Dev">Mobile Development</option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Difficulty
                            Level</label>
                        <select name="level" class="input-field">
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Duration
                            (e.g. 4h 30m)</label>
                        <input name="duration" type="text" required placeholder="4h 30m" class="input-field" />
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Lessons
                            Count</label>
                        <input name="lessons" type="number" required placeholder="12" class="input-field" />
                    </div>

                    <div class="md:col-span-2">
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Thumbnail
                            Image URL (Optional)</label>
                        <input name="image" type="text" placeholder="https://images.unsplash.com/..."
                            class="input-field" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Upload
                            Local Video (MP4/WebM)</label>
                        <input name="course_video" type="file" accept="video/mp4,video/webm"
                            class="input-field cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-white/5 file:text-cyan-400 hover:file:bg-white/10" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">OR
                            YouTube Video ID</label>
                        <input name="youtube_id" type="text" placeholder="dQw4w9WgXcQ" class="input-field" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Short
                        Summary</label>
                    <textarea name="summary" required rows="2" class="input-field"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Full
                        Description</label>
                    <textarea name="full_summary" rows="4" class="input-field"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">What
                        students will learn (One per line)</label>
                    <textarea name="what_you_learn" rows="4" class="input-field"
                        placeholder="Dynamic UI components&#10;State Management&#10;API Integration"></textarea>
                </div>

                <button type="submit"
                    class="w-full gradient-btn text-white py-4 rounded-2xl font-bold transition-all hover:opacity-90 active:scale-95 shadow-xl shadow-cyan-500/20">
                    Publish Course
                </button>
            </form>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>