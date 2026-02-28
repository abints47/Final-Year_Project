<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/db.php';
require_once 'includes/courses_data.php';

// Fetch user's enrollments
$enrolled_courses = [];
try {
    $stmt = $pdo->prepare("SELECT c.*, e.status, e.progress, e.enrolled_at 
                           FROM enrollments e 
                           JOIN courses c ON e.course_id = c.id 
                           WHERE e.user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $enrolled_courses = $stmt->fetchAll();
} catch (PDOException $e) {
    $enrolled_courses = [];
}

$not_enrolled_courses = array_filter($all_courses, function ($course) use ($enrolled_courses) {
    foreach ($enrolled_courses as $enrolled) {
        if ($enrolled['id'] == $course['id'])
            return false;
    }
    return true;
});
?>
<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Openly</title>
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

        .gradient-text {
            background: linear-gradient(135deg, #22d3ee 0%, #a855f7 100%);
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

        .course-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .course-card:hover {
            transform: translateY(-8px);
            border-color: #22d3ee;
            box-shadow: 0 20px 40px -20px rgba(34, 211, 238, 0.3);
        }

        /* Floating Chat Button */
        .chat-float {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 3.5rem;
            height: 3.5rem;
            background: #6366f1;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 50;
        }

        .chat-float:hover {
            transform: scale(1.1) rotate(5deg);
        }
    </style>
</head>

<body class="min-h-screen pt-24 pb-20 overflow-x-hidden">
    <!-- Dynamic Auras -->
    <div class="aura bg-cyan-600 w-[600px] h-[600px] -top-96 -left-96"></div>
    <div class="aura bg-purple-600 w-[600px] h-[600px] bottom-0 -right-96"></div>

    <?php include 'components/navbar.php'; ?>

    <main class="max-w-7xl mx-auto px-6">

        <!-- Welcome Row (Top Header) -->
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8 mb-20 animate-in">
            <div class="text-center lg:text-left">
                <span class="text-[10px] font-black uppercase text-cyan-400 tracking-widest mb-3 block">Overview</span>
                <h1 class="text-5xl lg:text-7xl font-black text-white tracking-tight leading-tight">
                    Welcome back, <span
                        class="text-cyan-400"><?php echo htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]); ?>!</span>
                </h1>
                <p class="text-slate-400 text-lg mt-4 font-medium">Continue your learning journey</p>
            </div>

            <!-- Dashboard Quick Stats Card -->
            <div class="glass p-8 rounded-[2.5rem] flex items-center gap-6 min-w-[300px] border-white/10 shadow-xl">
                <div
                    class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-400 border border-emerald-500/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest mb-1">Courses In Progress
                    </p>
                    <p class="text-4xl font-black text-white leading-none"><?php echo count($enrolled_courses); ?></p>
                </div>
            </div>
        </div>

        <!-- Master Hero Section (Center Hub) -->
        <section class="py-24 text-center relative reveal mb-12">
            <div
                class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-black mb-10 tracking-widest uppercase">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Transform Your Career with Expert-Led Courses
            </div>
            <h1 class="text-6xl lg:text-8xl font-black text-white mb-10 tracking-tighter leading-[0.9]">
                Master <span class="text-cyan-400">Programming</span> & <br />
                <span class="gradient-text">AI Skills</span>
            </h1>
            <p class="text-slate-400 text-lg md:text-xl max-w-3xl mx-auto leading-relaxed font-medium mb-12">
                Join thousands of learners mastering Python, Java, Machine Learning, and cutting-edge AI technologies.
                Learn from industry experts with hands-on projects.
            </p>

            <div class="flex flex-col sm:flex-row items-center gap-4 justify-center">
                <a href="courses.php"
                    class="bg-cyan-500 text-[#020617] px-8 py-4 rounded-2xl font-black text-lg shadow-xl shadow-cyan-500/20 hover:opacity-90 active:scale-95 transition-all flex items-center gap-2">
                    Explore Courses
                </a>
                <a href="resume_analyzer.php"
                    class="glass px-8 py-4 rounded-2xl text-white font-bold text-lg hover:bg-white/10 active:scale-95 transition-all flex items-center gap-2 border-white/10">
                    AI Career Analysis
                </a>
            </div>
        </section>

        <!-- Precision Tools Section -->
        <section class="mb-24 reveal">
            <div class="mb-12">
                <h2 class="text-4xl font-black text-white mb-4 tracking-tight">Precision Tools</h2>
                <p class="text-slate-500 font-medium">Architected for clarity, speed, and result-driven outcomes.</p>
            </div>

            <div class="grid grid-cols-12 gap-8">
                <!-- Large Card: ATS Analyzer -->
                <div
                    class="col-span-12 lg:col-span-8 glass p-10 rounded-[3rem] relative overflow-hidden group border-white/10 hover:border-cyan-500/50 transition-all flex flex-col md:flex-row gap-10">
                    <div class="flex-1 relative z-10">
                        <div
                            class="w-14 h-14 bg-cyan-500/10 rounded-2xl flex items-center justify-center text-cyan-400 mb-8 border border-cyan-500/20">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black text-white mb-6">AI ATS Analyzer</h3>
                        <p class="text-slate-400 text-lg leading-relaxed font-medium mb-10 max-w-md">
                            Proprietary neural matching that reverse-engineers hiring algorithms to position your
                            profile at the absolute top of the stack.
                        </p>
                        <a href="resume_analyzer.php"
                            class="inline-flex items-center gap-2 text-cyan-400 font-black uppercase text-xs tracking-widest group-hover:gap-4 transition-all">
                            Explore Analyzer
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                    <!-- Decorative Element -->
                    <div
                        class="w-full md:w-64 h-48 bg-white/5 rounded-2xl border border-white/5 relative overflow-hidden hidden md:block">
                        <div class="absolute top-4 left-4 right-4 h-2 bg-white/10 rounded-full"></div>
                        <div class="absolute top-10 left-4 right-10 h-2 bg-white/5 rounded-full"></div>
                        <div class="absolute bottom-4 right-4 w-4 h-4 rounded-full bg-cyan-500/20"></div>
                    </div>
                </div>

                <!-- Medium Card: Interview IQ -->
                <div
                    class="col-span-12 lg:col-span-4 glass p-10 rounded-[3rem] relative overflow-hidden group border-white/10 hover:border-purple-500/50 transition-all flex flex-col justify-between">
                    <div class="relative z-10">
                        <div
                            class="w-14 h-14 bg-purple-500/10 rounded-2xl flex items-center justify-center text-purple-400 mb-8 border border-purple-500/20">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black text-white mb-6">Interview IQ</h3>
                        <p class="text-slate-400 text-base leading-relaxed font-medium mb-8">
                            Real-time biometric and semantic feedback during mock sessions to refine your executive
                            presence.
                        </p>
                        <a href="interview_prep.php"
                            class="text-white text-sm font-bold opacity-60 group-hover:opacity-100 transition-opacity">Launch
                            Studio</a>
                    </div>
                </div>

                <!-- Smaller Tools Row -->
                <div
                    class="col-span-12 lg:col-span-4 glass p-10 rounded-[3rem] border-white/10 hover:border-amber-500/50 transition-all group">
                    <div
                        class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center text-white mb-8 group-hover:bg-amber-500/10 transition-all group-hover:text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A2 2 0 013 15.487V6.513a2 2 0 011.553-1.943L9 2l5.447 2.724A2 2 0 0116 6.513v8.974a2 2 0 01-1.553 1.943L9 20z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20V12" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-black text-white mb-3">Career Roadmap</h4>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">Long-range forecasting for senior
                        leadership trajectories.</p>
                </div>

                <div
                    class="col-span-12 lg:col-span-4 glass p-10 rounded-[3rem] border-white/10 hover:border-emerald-500/50 transition-all group">
                    <div
                        class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center text-white mb-8 group-hover:bg-emerald-500/10 transition-all group-hover:text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-black text-white mb-3">Skill Gap Mapping</h4>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">Automated identification of critical
                        certification pathways.</p>
                </div>

                <div
                    class="col-span-12 lg:col-span-4 glass p-10 rounded-[3rem] border-white/10 hover:border-blue-500/50 transition-all group">
                    <div
                        class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center text-white mb-8 group-hover:bg-blue-500/10 transition-all group-hover:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-black text-white mb-3">Course Player</h4>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">Immersive learning with AI-generated
                        dynamic transcripts.</p>
                </div>
            </div>
        </section>

        <!-- Course Grid Content -->
        <div class="space-y-24">

            <!-- Enrolled Section -->
            <?php if (!empty($enrolled_courses)): ?>
                <section>
                    <h2 class="text-2xl font-black text-white mb-8 flex items-center gap-4">
                        <span class="w-2 h-8 bg-cyan-500 rounded-full"></span>
                        Your Learning Path
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php foreach ($enrolled_courses as $course): ?>
                            <a href="course_player.php?id=<?php echo $course['id']; ?>"
                                class="course-card glass rounded-[2.5rem] overflow-hidden flex flex-col h-full group">
                                <div class="h-48 relative overflow-hidden">
                                    <img src="<?php echo $course['image']; ?>"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-all"></div>
                                    <div
                                        class="absolute top-4 left-4 glass px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest border-white/20">
                                        <?php echo $course['category']; ?>
                                    </div>
                                </div>
                                <div class="p-8 flex-1 flex flex-col">
                                    <h3
                                        class="text-xl font-black text-white mb-4 group-hover:text-cyan-400 transition-colors leading-tight">
                                        <?php echo $course['title']; ?>
                                    </h3>
                                    <div class="mt-auto">
                                        <div
                                            class="flex justify-between text-[10px] font-black text-slate-500 mb-2 uppercase tracking-widest">
                                            <span>Progress</span>
                                            <span class="text-cyan-400"><?php echo $course['progress']; ?>%</span>
                                        </div>
                                        <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                                            <div class="h-full bg-cyan-500" style="width: <?php echo $course['progress']; ?>%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Recommended Section -->
            <section>
                <h2 class="text-2xl font-black text-white mb-8 flex items-center gap-4">
                    <span class="w-2 h-8 bg-purple-500 rounded-full"></span>
                    Recommended For You
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php
                    $rec_courses = !empty($enrolled_courses) ? array_slice($not_enrolled_courses, 0, 4) : array_slice($all_courses, 0, 4);
                    foreach ($rec_courses as $course): ?>
                        <a href="course_detail.php?id=<?php echo $course['id']; ?>"
                            class="course-card glass rounded-[2.5rem] overflow-hidden flex flex-col h-full group">
                            <div class="h-48 relative overflow-hidden">
                                <img src="<?php echo $course['image']; ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-all"></div>
                                <div
                                    class="absolute top-4 left-4 glass px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest border-white/20">
                                    <?php echo $course['category']; ?>
                                </div>
                            </div>
                            <div class="p-8 flex-1 flex flex-col">
                                <h3
                                    class="text-xl font-black text-white mb-4 group-hover:text-purple-400 transition-colors leading-tight">
                                    <?php echo $course['title']; ?>
                                </h3>
                                <div
                                    class="mt-auto pt-6 border-t border-white/5 flex items-center justify-between text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <?php echo $course['duration']; ?>
                                    </span>
                                    <span class="text-purple-400">View Details</span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>

    <!-- Floating Chat Button -->
    <div class="chat-float">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </div>

    <?php include 'components/footer.php'; ?>
</body>

</html>