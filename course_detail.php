<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'includes/db.php';
require_once 'includes/courses_data.php';

// Get course ID from URL
$course_id = isset($_GET['id']) ? $_GET['id'] : null;
$course = null;

// Get course from database directly for robustness
try {
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$course_id]);
    $course = $stmt->fetch();
} catch (PDOException $e) {
    $course = null;
}

// Redirect if course not found
if (!$course) {
    header('Location: courses.php');
    exit;
}

// Decode JSON fields if they are still strings
if (is_string($course['what_you_learn'])) {
    $course['what_you_learn'] = json_decode($course['what_you_learn'], true) ?: [];
}
if (is_string($course['resources'])) {
    $course['resources'] = json_decode($course['resources'], true) ?: [];
}

// Image fallback
if (empty($course['image'])) {
    $course['image'] = 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80&w=800';
}

// Robust YouTube ID extraction
function get_yt_id($url)
{
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    return $url; // Return as-is if no match (already an ID)
}
$yt_id = get_yt_id($course['youtube_id']);

// Check enrollment status
$is_enrolled = false;
try {
    $stmt = $pdo->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$_SESSION['user_id'], $course_id]);
    $is_enrolled = (bool) $stmt->fetch();
} catch (PDOException $e) {
    $is_enrolled = false;
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Openly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Specific page styles */
        .glass-bright {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .gradient-border {
            position: relative;
            background: rgba(15, 23, 42, 0.6);
            border-radius: 2rem;
        }

        .gradient-border::before {
            content: "";
            position: absolute;
            inset: -1px;
            border-radius: 2rem;
            padding: 1px;
            background: linear-gradient(135deg, rgba(34, 211, 238, 0.3) 0%, rgba(192, 132, 252, 0.3) 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .glow {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(34, 211, 238, 0.15) 0%, transparent 70%);
            filter: blur(60px);
            pointer-events: none;
            z-index: -1;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col pt-24 bg-[#020617] selection:bg-cyan-500/30">
    <div
        class="fixed top-0 left-0 w-full h-[800px] bg-gradient-to-b from-cyan-600/5 to-transparent pointer-events-none z-0">
    </div>
    <div class="glow top-20 -left-20"></div>
    <div class="glow bottom-0 -right-20 group-hover:opacity-100 transition-opacity"
        style="background: radial-gradient(circle, rgba(192, 132, 252, 0.1) 0%, transparent 70%);"></div>

    <?php include 'components/navbar.php'; ?>

    <main class="max-w-7xl mx-auto px-6 py-12 relative z-10">
        <!-- Hero Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-20">
            <!-- Left Info -->
            <div class="lg:col-span-7 space-y-8">
                <div class="flex flex-wrap items-center gap-4">
                    <span
                        class="px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-black uppercase tracking-widest">
                        <?php echo htmlspecialchars($course['category']); ?>
                    </span>
                    <span
                        class="px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-slate-400 text-[10px] font-black uppercase tracking-widest">
                        <?php echo htmlspecialchars($course['level']); ?>
                    </span>
                    <div class="flex items-center gap-1.5 ml-auto md:ml-0">
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-sm font-black text-white"><?php echo $course['rating']; ?></span>
                        <span class="text-xs text-slate-500 font-medium">(<?php echo $course['students']; ?>+
                            Students)</span>
                    </div>
                </div>

                <h1 class="text-4xl md:text-7xl font-black text-white leading-[1.1] tracking-tighter">
                    <?php echo htmlspecialchars($course['title']); ?>
                </h1>

                <p class="text-lg md:text-xl text-slate-400 leading-relaxed max-w-2xl font-medium">
                    <?php echo htmlspecialchars($course['summary']); ?>
                </p>

                <div class="flex items-center gap-6 pt-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-500 p-[1px]">
                            <div class="w-full h-full rounded-2xl bg-[#020617] overflow-hidden">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?php echo urlencode($course['author']); ?>"
                                    class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest leading-none mb-1">
                                Instructor</p>
                            <p class="text-sm font-bold text-white"><?php echo htmlspecialchars($course['author']); ?>
                            </p>
                        </div>
                    </div>
                    <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
                    <div class="hidden sm:block">
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest leading-none mb-1">
                            Duration</p>
                        <p class="text-sm font-bold text-white"><?php echo $course['duration']; ?> total</p>
                    </div>
                </div>
            </div>

            <!-- Right Preview Card -->
            <div class="lg:col-span-5">
                <div class="gradient-border p-[1px] shadow-2xl overflow-hidden group">
                    <div class="bg-[#0f172a] rounded-[2rem] overflow-hidden">
                        <div class="relative overflow-hidden aspect-video">
                            <img src="<?php echo $course['image']; ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                <div
                                    class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-2xl shadow-white/20 transition-transform group-hover:scale-110">
                                    <svg class="w-8 h-8 text-black ml-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <p class="text-white font-black text-sm uppercase tracking-widest text-center">Preview
                                    Course Content</p>
                            </div>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-black text-white">Free</span>
                                <span class="text-slate-500 line-through text-lg">$99.99</span>
                            </div>

                            <?php if ($is_enrolled): ?>
                                <button onclick="window.location.href='course_player.php?id=<?php echo $course_id; ?>'"
                                    class="w-full py-4 bg-gradient-to-r from-emerald-400 to-teal-500 text-white font-black rounded-2xl text-lg transition-all active:scale-[98%] shadow-xl shadow-emerald-500/20">
                                    Continue Learning
                                </button>
                            <?php else: ?>
                                <button id="enrollBtn" onclick="enrollCourse(<?php echo $course_id; ?>)"
                                    class="w-full py-4 bg-white text-black font-black rounded-2xl text-lg transition-all hover:bg-slate-200 active:scale-[98%] shadow-xl shadow-cyan-500/10">
                                    Enroll Now
                                </button>
                            <?php endif; ?>

                            <p class="text-[10px] text-center text-slate-500 font-bold uppercase tracking-widest">30-Day
                                Money Back Guarantee</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video & Summary Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-24">
            <div class="lg:col-span-8">
                <div class="gradient-border p-[1px] shadow-2xl overflow-hidden group">
                    <div class="aspect-video w-full rounded-[2rem] overflow-hidden bg-black/50 relative">
                        <?php if (!empty($course['video_path'])): ?>
                            <video class="absolute inset-0 w-full h-full object-cover" controls
                                poster="<?php echo $course['image']; ?>">
                                <source src="<?php echo htmlspecialchars($course['video_path']); ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php elseif (!empty($yt_id)): ?>
                            <iframe
                                src="https://www.youtube.com/embed/<?php echo $yt_id; ?>?autoplay=0&rel=0&modestbranding=1"
                                title="Course Overview" class="absolute inset-0 w-full h-full border-0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        <?php else: ?>
                            <div class="absolute inset-0 flex items-center justify-center text-slate-500 font-bold italic">
                                No preview video available for this course.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-4 flex flex-col justify-center">
                <div class="glass p-8 rounded-[2.5rem] border border-white/10 space-y-6">
                    <h3 class="text-2xl font-black text-white">Course Preview</h3>
                    <p class="text-slate-400 font-medium leading-relaxed">
                        <?php echo htmlspecialchars($course['summary']); ?>
                    </p>
                    <div class="pt-6 border-t border-white/5 space-y-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center text-cyan-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-white"><?php echo $course['duration']; ?> of
                                content</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-white">Full Lifetime Access</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Summary & Highlights Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 mb-24">
            <div class="lg:col-span-2 space-y-16">
                <!-- What you'll learn (Premium List) -->
                <section class="space-y-8">
                    <h3 class="text-3xl font-black text-white flex items-center gap-3">
                        <span class="w-2 h-10 bg-cyan-500 rounded-full"></span>
                        Summary & Learning Outcomes
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($course['what_you_learn'] as $point): ?>
                            <div class="glass p-6 rounded-3xl flex items-start gap-4 hover:bg-white/5 transition-all">
                                <div
                                    class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="text-slate-300 font-semibold leading-relaxed">
                                    <?php echo htmlspecialchars($point); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Curriculum / Lesson List -->
                <section class="space-y-8">
                    <h3 class="text-3xl font-black text-white flex items-center gap-3">
                        <span class="w-2 h-10 bg-purple-500 rounded-full"></span>
                        Course Curriculum
                    </h3>
                    <div class="space-y-4">
                        <?php
                        // Simulated curriculum based on lessons count
                        $modules = ['Core Concepts', 'Deep Dive', 'Project Implementation', 'Advanced Optimization'];
                        for ($i = 0; $i < count($modules); $i++):
                            ?>
                            <div class="glass rounded-3xl overflow-hidden">
                                <button class="w-full px-8 py-6 flex items-center justify-between text-left group">
                                    <div class="flex items-center gap-6">
                                        <span class="text-2xl font-black text-slate-700">0<?php echo $i + 1; ?></span>
                                        <div>
                                            <h4
                                                class="text-lg font-bold text-white group-hover:text-cyan-400 transition-colors">
                                                <?php echo $modules[$i]; ?>
                                            </h4>
                                            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">
                                                <?php echo rand(2, 5); ?> Lessons • <?php echo rand(45, 120); ?>m
                                            </p>
                                        </div>
                                    </div>
                                    <svg class="w-6 h-6 text-slate-600 transition-transform group-hover:rotate-180"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        <?php endfor; ?>
                    </div>
                </section>

                <!-- Course Description -->
                <section class="space-y-8">
                    <h3 class="text-3xl font-black text-white flex items-center gap-3">
                        <span class="w-2 h-10 bg-emerald-500 rounded-full"></span>
                        About this Course
                    </h3>
                    <div class="glass p-8 rounded-[2rem] border border-white/5">
                        <div class="prose prose-invert max-w-none text-slate-400 leading-relaxed text-lg font-medium">
                            <?php echo nl2br(htmlspecialchars($course['full_summary'])); ?>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-8">
                <div class="glass p-8 rounded-[2.5rem] sticky top-32 border border-white/10 shadow-3xl">
                    <h4 class="text-xl font-bold text-white mb-8">Course Highlights</h4>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 group">
                            <div
                                class="w-10 h-10 rounded-xl bg-cyan-400/10 flex items-center justify-center text-cyan-400 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Accessibility</p>
                                <p class="text-sm font-bold text-white">Mobile & TV Access</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <div
                                class="w-10 h-10 rounded-xl bg-purple-400/10 flex items-center justify-center text-purple-400 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Skill Level</p>
                                <p class="text-sm font-bold text-white"><?php echo $course['level']; ?> Concepts</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-400/10 flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Certification</p>
                                <p class="text-sm font-bold text-white">Certificate of Completion</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 mt-8 border-t border-white/5">
                        <h5 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-4">Resources Included
                        </h5>
                        <div class="space-y-4">
                            <?php foreach ($course['resources'] as $res): ?>
                                <a href="<?php echo $res['url']; ?>" target="_blank"
                                    class="block p-4 glass-bright rounded-2xl border border-white/5 hover:border-cyan-500/30 transition-all">
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="text-xs font-bold text-white truncate max-w-[150px]"><?php echo htmlspecialchars($res['name']); ?></span>
                                        <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 00-2 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>

    <script>
        async function enrollCourse(courseId) {
            const btn = document.getElementById('enrollBtn');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<div class="w-5 h-5 border-2 border-black/30 border-t-black rounded-full animate-spin mx-auto"></div>';

            try {
                const formData = new FormData();
                formData.append('course_id', courseId);

                const response = await fetch('api/enroll.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Refresh to show "Continue Learning"
                    window.location.reload();
                } else {
                    alert(data.error || 'Enrollment failed');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred. Please try again.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    </script>
</body>

</html>