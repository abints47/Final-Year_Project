<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'includes/db.php';
require_once 'includes/courses_data.php';

$course_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$user_id = $_SESSION['user_id'];

// Check if user is enrolled
try {
    $stmt = $pdo->prepare("SELECT e.*, c.title, c.youtube_id, c.video_path, c.image, c.lessons, c.what_you_learn 
                           FROM enrollments e 
                           JOIN courses c ON e.course_id = c.id 
                           WHERE e.user_id = ? AND e.course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    $enrollment = $stmt->fetch();

    if (!$enrollment) {
        header("Location: course_detail.php?id=$course_id");
        exit();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Decode JSON
if (is_string($enrollment['what_you_learn'])) {
    $enrollment['what_you_learn'] = json_decode($enrollment['what_you_learn'], true) ?: [];
}

// Robust YouTube ID extraction
function get_yt_id($url)
{
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    return $url;
}
$yt_id = get_yt_id($enrollment['youtube_id']);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning:
        <?php echo htmlspecialchars($enrollment['title']); ?> - Openly
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-[#020617] text-white min-h-screen flex flex-col pt-20">
    <?php include 'components/navbar.php'; ?>

    <main class="flex-1 flex flex-col lg:flex-row h-full overflow-hidden">
        <!-- Main Content (Video) -->
        <div class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="max-w-5xl mx-auto space-y-8">
                <!-- Video Player Container -->
                <div
                    class="bg-black/50 rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl aspect-video relative">
                    <?php if (!empty($enrollment['video_path'])): ?>
                        <video class="w-full h-full object-cover" controls>
                            <source src="<?php echo htmlspecialchars($enrollment['video_path']); ?>" type="video/mp4">
                        </video>
                    <?php elseif (!empty($yt_id)): ?>
                        <iframe src="https://www.youtube.com/embed/<?php echo $yt_id; ?>?rel=0&modestbranding=1"
                            class="absolute inset-0 w-full h-full border-0" allowfullscreen>
                        </iframe>
                    <?php endif; ?>
                </div>

                <!-- Info Header -->
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <span
                            class="px-3 py-1 bg-cyan-500/10 text-cyan-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-cyan-500/20">
                            Introduction
                        </span>
                        <span class="text-slate-500 text-sm font-medium">• 12:45 Duration</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-black tracking-tight">
                        <?php echo htmlspecialchars($enrollment['title']); ?>: Getting Started
                    </h1>
                </div>

                <!-- Tabs (About, Resources, Notes) -->
                <div class="border-b border-white/10 flex gap-8">
                    <button
                        class="pb-4 text-white font-bold border-b-2 border-cyan-500 text-sm md:text-base">Overview</button>
                    <button
                        class="pb-4 text-slate-500 font-bold text-sm md:text-base hover:text-white transition-colors">Resources</button>
                    <button
                        class="pb-4 text-slate-500 font-bold text-sm md:text-base hover:text-white transition-colors">Notes</button>
                </div>

                <div class="py-4 space-y-6">
                    <h3 class="text-xl font-bold">What you'll learn in this module</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($enrollment['what_you_learn'] as $point): ?>
                            <div class="flex gap-3 items-start p-4 bg-white/5 rounded-2xl border border-white/5">
                                <svg class="w-5 h-5 text-cyan-400 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-slate-300 font-medium text-sm">
                                    <?php echo htmlspecialchars($point); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar (Lesson Navigation) -->
        <div class="w-full lg:w-96 bg-[#0f172a] border-l border-white/5 flex flex-col h-full lg:h-[calc(100vh-5rem)]">
            <div class="p-6 border-b border-white/5">
                <h3 class="font-black text-white uppercase tracking-widest text-sm">Course Content</h3>
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex-1 h-1.5 bg-white/5 rounded-full overflow-hidden mr-4">
                        <div class="h-full bg-cyan-500" style="width: <?php echo $enrollment['progress']; ?>%"></div>
                    </div>
                    <span class="text-xs font-black text-cyan-400">
                        <?php echo $enrollment['progress']; ?>%
                    </span>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto">
                <?php
                $modules = ['Introduction', 'Core Architecture', 'Implementation', 'Final Project'];
                foreach ($modules as $mIndex => $module):
                    ?>
                    <div class="border-b border-white/5">
                        <button
                            class="w-full p-6 flex items-center justify-between hover:bg-white/5 transition-colors group">
                            <div class="flex flex-col text-left">
                                <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-1">Module 0
                                    <?php echo $mIndex + 1; ?>
                                </span>
                                <span class="text-sm font-bold text-slate-300 group-hover:text-white">
                                    <?php echo $module; ?>
                                </span>
                            </div>
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <!-- Lessons List (Simplified for now) -->
                        <div class="bg-black/20">
                            <?php for ($l = 1; $l <= 3; $l++): ?>
                                <div
                                    class="p-6 pl-10 flex items-center gap-4 hover:bg-white/5 cursor-pointer border-t border-white/[0.02]">
                                    <div
                                        class="w-5 h-5 rounded-full border-2 border-slate-700 flex items-center justify-center shrink-0">
                                        <?php if ($mIndex == 0 && $l == 1): ?>
                                            <div class="w-2.5 h-2.5 bg-cyan-500 rounded-full"></div>
                                        <?php endif; ?>
                                    </div>
                                    <span
                                        class="text-xs font-medium <?php echo ($mIndex == 0 && $l == 1) ? 'text-white' : 'text-slate-500'; ?>">
                                        Lesson
                                        <?php echo $l; ?>: Concepts & Overview
                                    </span>
                                    <span class="ml-auto text-[10px] text-slate-600 font-bold">12:00</span>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>