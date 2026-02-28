<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

require_once 'includes/db.php';

$error = '';
$success = '';
$course_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch existing course
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ? AND uploaded_by = ?");
$stmt->execute([$course_id, $_SESSION['user_email']]);
$course = $stmt->fetch();

if (!$course) {
    header("Location: teacher_dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $level = trim($_POST['level']);
    $duration = trim($_POST['duration']);
    $lessons = (int) $_POST['lessons'];
    $youtube_id = trim($_POST['youtube_id']);
    $summary = trim($_POST['summary']);
    $full_summary = trim($_POST['full_summary']);
    $image = trim($_POST['image']) ?: $course['image'];
    $video_path = $course['video_path'];

    // Handle Image Upload
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

    // Handle Video Upload
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
        }
    }

    $what_you_learn = array_filter(explode("\n", str_replace("\r", "", $_POST['what_you_learn'] ?? '')));

    if (empty($title) || empty($summary)) {
        $error = "Title and Summary are required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE courses SET title = ?, category = ?, level = ?, duration = ?, lessons = ?, image = ?, youtube_id = ?, video_path = ?, summary = ?, full_summary = ?, what_you_learn = ? WHERE id = ?");
            $stmt->execute([
                $title,
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
                $course_id
            ]);
            $success = "Course updated successfully!";
            // Refresh data
            $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
            $stmt->execute([$course_id]);
            $course = $stmt->fetch();
        } catch (PDOException $e) {
            $error = "Error updating course: " . $e->getMessage();
        }
    }
}

$learn_list = "";
if ($course['what_you_learn']) {
    $items = json_decode($course['what_you_learn'], true);
    if (is_array($items)) {
        $learn_list = implode("\n", $items);
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - Openly</title>
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
    </style>
</head>

<body class="min-h-screen pt-32 pb-20">
    <div class="aura bg-emerald-600 w-[600px] h-[600px] -top-96 -left-96"></div>
    <?php include 'components/navbar.php'; ?>
    <main class="max-w-4xl mx-auto px-6">
        <div class="mb-12">
            <h1 class="text-5xl font-black text-white tracking-tight mb-4 text-emerald-400">Edit Course</h1>
            <p class="text-slate-400 text-lg font-medium">Refine your curriculum for best results.</p>
        </div>
        <?php if ($error || $success): ?>
            <div
                class="mb-8 p-6 rounded-3xl text-center font-bold border <?php echo $error ? 'bg-red-500/10 border-red-500/20 text-red-200' : 'bg-emerald-500/10 border-emerald-500/20 text-emerald-200'; ?>">
                <?php echo $error ?: $success; ?>
            </div>
        <?php endif; ?>
        <div class="glass p-12 rounded-[3.5rem] border-white/10">
            <form action="edit_course.php?id=<?php echo $course_id; ?>" method="POST" enctype="multipart/form-data"
                class="space-y-10">
                <div>
                    <label
                        class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Course
                        Title</label>
                    <input name="title" type="text" required value="<?php echo htmlspecialchars($course['title']); ?>"
                        class="input-field" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Category</label>
                        <select name="category" class="input-field appearance-none cursor-pointer">
                            <?php foreach (['Programming', 'Web Dev', 'AI/ML', 'Security', 'Cloud', 'Data Science', 'Mobile Dev'] as $cat): ?>
                                <option value="<?php echo $cat; ?>" <?php echo $course['category'] == $cat ? 'selected' : ''; ?>>
                                    <?php echo $cat; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Difficulty</label>
                        <select name="level" class="input-field appearance-none cursor-pointer">
                            <?php foreach (['Beginner', 'Intermediate', 'Advanced'] as $lvl): ?>
                                <option value="<?php echo $lvl; ?>" <?php echo $course['level'] == $lvl ? 'selected' : ''; ?>
                                    >
                                    <?php echo $lvl; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <input name="duration" type="text" required
                            value="<?php echo htmlspecialchars($course['duration']); ?>" class="input-field" />
                    </div>
                    <div>
                        <input name="lessons" type="number" required
                            value="<?php echo htmlspecialchars($course['lessons']); ?>" class="input-field" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Thumbnail
                            URL</label>
                        <input name="image" type="text" value="<?php echo htmlspecialchars($course['image']); ?>"
                            class="input-field" />
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Change
                            Thumbnail Image</label>
                        <input name="course_image" type="file" accept="image/*"
                            class="input-field cursor-pointer file:mr-4 file:py-1 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-emerald-500/20 file:text-emerald-400 hover:file:bg-emerald-500/30 transition-all" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Update
                            Local Video</label>
                        <input name="course_video" type="file" accept="video/mp4,video/webm"
                            class="input-field cursor-pointer file:mr-4 file:py-1 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-emerald-500/20 file:text-emerald-400 hover:file:bg-emerald-500/30 transition-all" />
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">OR
                            Update YouTube ID</label>
                        <input name="youtube_id" type="text"
                            value="<?php echo htmlspecialchars($course['youtube_id']); ?>" class="input-field" />
                    </div>
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Short
                        Summary</label>
                    <textarea name="summary" required rows="2"
                        class="input-field"><?php echo htmlspecialchars($course['summary']); ?></textarea>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">Full
                        Description</label>
                    <textarea name="full_summary" rows="4"
                        class="input-field"><?php echo htmlspecialchars($course['full_summary']); ?></textarea>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 ml-1">What
                        You Learn (One per line)</label>
                    <textarea name="what_you_learn" rows="4"
                        class="input-field"><?php echo htmlspecialchars($learn_list); ?></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-emerald-400 hover:bg-emerald-300 text-[#020617] py-6 rounded-2xl font-black text-xl transition-all shadow-xl shadow-emerald-400/20 active:scale-95 uppercase tracking-tighter">Save
                    Changes</button>
            </form>
        </div>
    </main>
    <?php include 'components/footer.php'; ?>
</body>

</html>