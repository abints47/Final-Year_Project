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

    // Simple arrays for what you learn and resources (can be expanded)
    $what_you_learn = array_filter(explode("\n", str_replace("\r", "", $_POST['what_you_learn'])));
    $resources = [
        ['name' => 'General Documentation', 'url' => 'https://docs.google.com/']
    ];

    if (empty($title) || empty($summary)) {
        $error = "Title and Summary are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO courses (title, author, category, level, duration, lessons, image, youtube_id, summary, full_summary, what_you_learn, resources, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $title,
                $_SESSION['user_name'],
                $category,
                $level,
                $duration,
                $lessons,
                $image,
                $youtube_id,
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
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #020617;
            color: #f8fafc;
        }

        .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .input-field {
            transition: all 0.3s ease;
            background: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1rem;
            color: white;
            width: 100%;
        }

        .input-field:focus {
            outline: none;
            border-color: #22d3ee;
            box-shadow: 0 0 0 4px rgba(34, 211, 238, 0.1);
        }

        .gradient-btn {
            background: linear-gradient(to right, #22d3ee, #c084fc);
        }
    </style>
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
            <form action="upload_course.php" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Course
                            Title</label>
                        <input name="title" type="text" required placeholder="e.g. Master React in 30 Days"
                            class="input-field" />
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Category</label>
                        <select name="category" class="input-field">
                            <option value="Programming">Programming</option>
                            <option value="Web Dev">Web Dev</option>
                            <option value="AI/ML">AI/ML</option>
                            <option value="Security">Security</option>
                            <option value="Cloud">Cloud</option>
                            <option value="Data Science">Data Science</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Level</label>
                        <select name="level" class="input-field">
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Duration</label>
                        <input name="duration" type="text" placeholder="e.g. 15 hours" class="input-field" />
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Lessons</label>
                        <input name="lessons" type="number" placeholder="50" class="input-field" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Image URL
                        (Optional)</label>
                    <input name="image" type="url" placeholder="https://unsplash.com/..." class="input-field" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">YouTube
                        Video ID</label>
                    <input name="youtube_id" type="text" placeholder="dQw4w9WgXcQ" class="input-field" />
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