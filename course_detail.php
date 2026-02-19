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

// Find the course in our data array
foreach ($all_courses as $item) {
    if ($item['id'] === $course_id) {
        $course = $item;
        break;
    }
}

// Redirect if course not found
if (!$course) {
    header('Location: courses.php');
    exit;
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
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #020617;
            color: #f8fafc;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .gradient-courses {
            background: linear-gradient(to right, #22d3ee, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .aura {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            z-index: -1;
            pointer-events: none;
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col pt-24">
    <!-- Background Auras -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="aura bg-cyan-600 w-96 h-96 top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="aura bg-purple-600 w-96 h-96 bottom-0 right-0 translate-x-1/2 translate-y-1/2"></div>
    </div>

    <?php include 'components/navbar.php'; ?>

    <main class="max-w-7xl mx-auto px-6 py-12 w-full relative z-10 flex flex-col lg:flex-row gap-8">
        <!-- Content Column -->
        <div class="flex-1 space-y-12">
            <!-- Header Section -->
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <span
                        class="bg-cyan-500/20 text-cyan-400 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border border-cyan-500/30">
                        <?php echo htmlspecialchars($course['category']); ?>
                    </span>
                    <span
                        class="bg-white/5 text-slate-400 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border border-white/10">
                        <?php echo htmlspecialchars($course['level']); ?>
                    </span>
                </div>
                <h1 class="text-3xl md:text-6xl font-black text-white leading-tight">
                    <?php echo htmlspecialchars($course['title']); ?>
                </h1>
                <p class="text-lg md:text-xl text-slate-400 leading-relaxed max-w-2xl">
                    <?php echo htmlspecialchars($course['summary']); ?>
                </p>

                <!-- Course Stats -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 p-4 md:p-6 glass rounded-2xl">
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Instructor</p>
                        <p class="text-xs md:text-sm font-semibold text-white">
                            <?php echo htmlspecialchars($course['author']); ?>
                        </p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Rating</p>
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3 md:w-4 md:h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <p class="text-xs md:text-sm font-semibold text-white">
                                <?php echo htmlspecialchars($course['rating']); ?>
                            </p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Students</p>
                        <p class="text-xs md:text-sm font-semibold text-white">
                            <?php echo htmlspecialchars($course['students']); ?>
                        </p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Modules</p>
                        <p class="text-xs md:text-sm font-semibold text-white">
                            <?php echo htmlspecialchars($course['lessons']); ?> Lessons
                        </p>
                    </div>
                </div>
            </div>

            <!-- What You'll Learn -->
            <section class="space-y-6">
                <h2 class="text-3xl font-bold text-white">What You'll Learn</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($course['what_you_learn'] as $point): ?>
                        <div class="flex items-start gap-3 p-4 glass rounded-xl">
                            <div class="mt-1">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-slate-300 font-medium">
                                <?php echo htmlspecialchars($point); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Video Section -->
            <section class="space-y-6">
                <h2 class="text-3xl font-bold text-white">Full Course Overview</h2>
                <div class="video-container">
                    <iframe src="https://www.youtube.com/embed/<?php echo $course['youtube_id']; ?>"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen></iframe>
                </div>
            </section>

            <!-- Detailed Summary -->
            <section class="space-y-6">
                <h2 class="text-2xl md:text-3xl font-bold text-white">Course Description</h2>
                <div class="prose prose-invert max-w-none text-slate-400 leading-relaxed text-base md:text-lg">
                    <?php echo nl2br(htmlspecialchars($course['full_summary'])); ?>
                </div>
            </section>

            <!-- Resources -->
            <section class="space-y-6">
                <h2 class="text-3xl font-bold text-white">Additional Resources</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($course['resources'] as $res): ?>
                        <a href="<?php echo $res['url']; ?>" target="_blank"
                            class="flex items-center justify-between p-5 glass rounded-2xl hover:border-cyan-500/30 transition-all group">
                            <div class="flex items-center gap-4">
                                <div
                                    class="p-3 bg-white/5 rounded-xl text-slate-400 group-hover:text-cyan-400 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <span class="font-bold text-white">
                                    <?php echo htmlspecialchars($res['name']); ?>
                                </span>
                            </div>
                            <svg class="w-5 h-5 text-slate-500 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <!-- Sidebar Column -->
        <div class="lg:w-96 space-y-6">
            <div class="glass p-6 md:p-8 rounded-[2.5rem] lg:sticky lg:top-32 space-y-8">
                <div class="rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                    <img src="<?php echo $course['image']; ?>" alt="Course preview" class="w-full h-auto">
                </div>

                <div class="space-y-4">
                    <button
                        class="w-full py-4 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-bold rounded-2xl shadow-lg shadow-cyan-500/25 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Watch Now
                    </button>
                    <button
                        class="w-full py-4 glass text-white font-bold rounded-2xl hover:bg-white/5 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        Add to Wishlist
                    </button>
                </div>

                <div class="space-y-6 pt-6 border-t border-white/5">
                    <h4 class="font-bold text-white">This course includes:</h4>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3 text-sm text-slate-400">
                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <?php echo $course['duration']; ?> on-demand video
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-400">
                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Coding exercises
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-400">
                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Access on mobile and TV
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-400">
                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Certificate of completion
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>