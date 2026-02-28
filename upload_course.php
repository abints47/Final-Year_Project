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

    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
        $img_tmp = $_FILES['course_image']['tmp_name'];
        $img_name = $_FILES['course_image']['name'];
        $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $allowed_img_exts = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($img_ext, $allowed_img_exts)) {
            $new_img_name = md5(time() . $img_name) . '.' . $img_ext;
            $img_upload_dir = 'uploads/thumbnails/';
            if (!is_dir($img_upload_dir))
                mkdir($img_upload_dir, 0777, true);

            $img_target_path = $img_upload_dir . $new_img_name;
            if (move_uploaded_file($img_tmp, $img_target_path)) {
                $image = $img_target_path;
            }
        }
    }

    $what_you_learn = array_filter(explode("\n", str_replace("\r", "", $_POST['what_you_learn'] ?? '')));
    $resources = [['name' => 'General Documentation', 'url' => 'https://docs.google.com/']];

    if (empty($title) || empty($summary)) {
        $error = "Title and Summary are required.";
    } elseif (!$error) {
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
<html lang="en" class="dark scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Course - Openly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #020617;
            color: #f8fafc;
        }

        .glass {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .aura {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.1;
            z-index: -1;
            pointer-events: none;
        }

        .input-field {
            width: 100%;
            background: #030712;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #22d3ee;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.1);
        }

        .input-field::placeholder {
            color: #475569;
        }
    </style>
</head>

<body class="min-h-screen pt-32 pb-20">
    <!-- Static Auras -->
    <div class="aura bg-cyan-600 w-[600px] h-[600px] -top-96 -left-96"></div>
    <div class="aura bg-purple-600 w-[600px] h-[600px] bottom-0 -right-96"></div>

    <?php include 'components/navbar.php'; ?>

    <main class="max-w-4xl mx-auto px-6">
        <div class="mb-12">
            <h1 class="text-5xl font-black text-white tracking-tight mb-4">Upload New Course</h1>
            <p class="text-slate-400 text-lg font-medium">Share your knowledge with the Openly community.</p>
        </div>

        <?php if ($error || $success): ?>
            <div
                class="mb-8 p-6 rounded-3xl text-center font-bold border <?php echo $error ? 'bg-red-500/10 border-red-500/20 text-red-200' : 'bg-emerald-500/10 border-emerald-500/20 text-emerald-200'; ?>">
                <?php echo $error ?: $success; ?>
            </div>
        <?php endif; ?>

        <div class="glass p-12 rounded-[3.5rem] shadow-2xl border-white/10">
            <form action="upload_course.php" method="POST" enctype="multipart/form-data" class="space-y-10">
                <!-- Course Title -->
                <div>
                    <label
                        class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Course
                        Title</label>
                    <input name="title" type="text" required placeholder="e.g. Master Python for Data Science"
                        class="input-field" />
                </div>

                <!-- Row: Category & Difficulty -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Category</label>
                        <select name="category" class="input-field appearance-none cursor-pointer">
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
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Difficulty
                            Level</label>
                        <select name="level" class="input-field appearance-none cursor-pointer">
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                </div>

                <!-- Row: Duration & Lessons -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Duration
                            (e.g. 4h 30m)</label>
                        <input name="duration" type="text" required placeholder="4h 30m" class="input-field" />
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Lessons
                            Count</label>
                        <input name="lessons" type="number" required placeholder="12" class="input-field" />
                    </div>
                </div>

                <!-- Thumbnail Upload -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Thumbnail URL (Optional)</label>
                        <input name="image" type="text" placeholder="https://images.unsplash.com/..." class="input-field" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">OR Upload Thumbnail Image</label>
                        <input name="course_image" type="file" accept="image/*" class="input-field cursor-pointer file:mr-4 file:py-1 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-cyan-500/20 file:text-cyan-400 hover:file:bg-cyan-500/30 transition-all" />
                    </div>
                </div>

                <!-- Row: Video Upload & YouTube -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Upload
                            Local Video (MP4/WebM)</label>
                        <div class="relative group">
                            <input name="course_video" type="file" accept="video/mp4,video/webm"
                                class="input-field cursor-pointer file:mr-4 file:py-1 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-cyan-500/20 file:text-cyan-400 hover:file:bg-cyan-500/30 transition-all" />
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">OR
                            YouTube Video ID</label>
                        <input name="youtube_id" type="text" placeholder="dQw4w9WgXcQ" class="input-field" />
                    </div>
                </div>

                <!-- Short Summary -->
                <div>
                    <label
                        class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Short
                        Summary</label>
                    <textarea name="summary" required rows="2" class="input-field"
                        placeholder="Briefly describe what this course is about..."></textarea>
                </div>

                <!-- Full Description -->
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Full
                        Description</label>
                    <textarea name="full_summary" rows="4" class="input-field"
                        placeholder="Detail the curriculum, requirements, and objectives..."></textarea>
                </div>

                <!-- What You Learn -->
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">What
                        students will learn (One per line)</label>
                    <textarea name="what_you_learn" rows="4" class="input-field"
                        placeholder="Dynamic UI components&#10;State Management&#10;API Integration"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-cyan-400 hover:bg-cyan-300 text-[#020617] py-6 rounded-2xl font-black text-xl transition-all shadow-xl shadow-cyan-400/20 active:scale-95 uppercase tracking-tighter">
                    Publish Course
                </button>
            </form>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>