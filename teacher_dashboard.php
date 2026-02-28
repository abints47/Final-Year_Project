<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

require_once 'includes/db.php';
require_once 'includes/courses_data.php';

// In a real app, we would fetch courses uploaded by this specific teacher
// For now, we'll show a modified version of the dashboard
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Openly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .gradient-emerald {
            background: linear-gradient(to right, #10b981, #34d399);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-[#020617] selection:bg-emerald-500/30">
    <?php include 'components/navbar.php'; ?>

    <div
        class="fixed top-0 left-0 w-full h-[600px] bg-emerald-600/10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 z-0">
    </div>

    <main class="max-w-7xl mx-auto px-6 pt-32 pb-20 relative z-10">
        <?php if (isset($_GET['success']) || isset($_GET['error'])): ?>
            <div
                class="mb-8 p-4 rounded-2xl text-center font-bold border <?php echo isset($_GET['error']) ? 'bg-red-500/10 border-red-500/20 text-red-200' : 'bg-emerald-500/10 border-emerald-500/20 text-emerald-200'; ?> animate-in">
                <?php echo htmlspecialchars($_GET['success'] ?? $_GET['error']); ?>
            </div>
        <?php endif; ?>

        <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-16 animate-in text-center md:text-left"
            style="animation-delay: 0.1s">
            <div>
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest mb-2 block">Instructor
                    Portal</span>
                <h1 class="text-3xl md:text-6xl font-black text-white tracking-tight">
                    Welcome, Coach <span class="gradient-emerald">
                        <?php echo htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]); ?>!
                    </span>
                </h1>
                <p class="text-slate-400 mt-2 font-medium text-sm md:text-base">Manage your curriculum and track student
                    engagement.</p>
            </div>
            <div class="mt-8 md:mt-0 flex gap-4">
                <a href="upload_course.php"
                    class="gradient-btn px-8 py-4 rounded-2xl text-white font-bold text-lg shadow-xl shadow-emerald-500/20 hover:opacity-90 active:scale-[0.98] transition-all flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    New Course
                </a>
            </div>
        </div>

        <!-- Smart Intelligence Layer -->
        <div class="grid grid-cols-12 gap-8 mb-24 animate-in" style="animation-delay: 0.2s">
            <!-- AI Instructor Insights -->
            <div
                class="col-span-12 lg:col-span-8 glass p-10 rounded-[3rem] relative overflow-hidden group border-white/10 hover:border-emerald-500/50 transition-all flex flex-col md:flex-row gap-10">
                <div class="flex-1 relative z-10">
                    <div
                        class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-400 mb-8 border border-emerald-500/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-black text-white mb-6">AI Instructor Insights</h3>
                    <p class="text-slate-400 text-lg leading-relaxed font-medium mb-10">
                        Your curriculum is performing <span class="text-emerald-400">18% better</span> than the
                        department average. Suggestion: Add more interactive quizzes to the "Interview IQ" module.
                    </p>
                    <div class="flex gap-4">
                        <span
                            class="px-4 py-2 bg-emerald-500/10 rounded-full text-[10px] font-black uppercase text-emerald-400 border border-emerald-500/20 tracking-widest">Market
                            Trend: AI Automation</span>
                        <span
                            class="px-4 py-2 bg-white/5 rounded-full text-[10px] font-black uppercase text-slate-400 border border-white/5 tracking-widest">High
                            Demand: Tech Interviews</span>
                    </div>
                </div>
                <!-- Decorative Visualization -->
                <div
                    class="w-full md:w-64 h-48 bg-white/5 rounded-2xl border border-white/5 relative overflow-hidden hidden md:block">
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 bg-emerald-500/10 blur-2xl rounded-full">
                    </div>
                    <div class="p-6 h-full flex flex-col justify-end">
                        <div class="flex items-end gap-2 h-20">
                            <div class="flex-1 bg-emerald-500/20 h-8 rounded-t-lg"></div>
                            <div class="flex-1 bg-emerald-500/40 h-16 rounded-t-lg"></div>
                            <div class="flex-1 bg-emerald-500/60 h-12 rounded-t-lg"></div>
                            <div class="flex-1 bg-emerald-500/80 h-20 rounded-t-lg"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Support Hub -->
            <div
                class="col-span-12 lg:col-span-4 glass p-10 rounded-[3rem] relative overflow-hidden group border-white/10 hover:border-emerald-500/50 transition-all flex flex-col justify-between">
                <div class="relative z-10">
                    <div
                        class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-400 mb-8 border border-emerald-500/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-black text-white mb-6">Support Hub</h3>
                    <div class="space-y-6">
                        <div
                            class="p-4 bg-white/5 rounded-2xl border border-white/5 group-hover:border-emerald-500/20 transition-all">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[10px] font-black uppercase text-emerald-400">Question</span>
                                <span class="text-[9px] text-slate-500 font-bold uppercase">2m ago</span>
                            </div>
                            <p class="text-white text-xs font-bold truncate">"How do I optimize my React hooks?"</p>
                        </div>
                        <div
                            class="p-4 bg-white/5 rounded-2xl border border-white/5 group-hover:border-emerald-500/20 transition-all">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[10px] font-black uppercase text-amber-400">Urgent</span>
                                <span class="text-[9px] text-slate-500 font-bold uppercase">15m ago</span>
                            </div>
                            <p class="text-white text-xs font-bold truncate">"Final project submission error..."</p>
                        </div>
                    </div>
                </div>
                <button
                    class="w-full mt-8 py-4 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                    Open Help Desk
                </button>
            </div>
        </div>

        <div class="mb-12 animate-in" style="animation-delay: 0.3s">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                Your Published Courses
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $stmt = $pdo->prepare("SELECT * FROM courses WHERE uploaded_by = ? ORDER BY id DESC");
                $stmt->execute([$_SESSION['user_email']]);
                $teacher_courses = $stmt->fetchAll();

                if (empty($teacher_courses)): ?>
                    <div class="col-span-full py-20 text-center glass rounded-[3rem] border-dashed border-2 border-white/5">
                        <p class="text-slate-500 font-medium">No courses published yet. Start by creating your first course!
                        </p>
                    </div>
                <?php else: ?>
                    <?php foreach ($teacher_courses as $course): ?>
                        <div
                            class="glass rounded-[2.5rem] overflow-hidden flex flex-col h-full border border-white/5 hover:border-emerald-500/30 transition-all">
                            <div class="h-48 relative overflow-hidden">
                                <img src="<?php echo htmlspecialchars($course['image']); ?>" class="w-full h-full object-cover">
                                <div
                                    class="absolute top-4 right-4 bg-emerald-500 text-white text-[10px] font-black px-3 py-1.5 rounded-lg shadow-lg">
                                    LIVE</div>
                            </div>
                            <div class="p-8">
                                <h3 class="text-xl font-bold text-white mb-4"><?php echo htmlspecialchars($course['title']); ?>
                                </h3>
                                <div
                                    class="flex items-center justify-between text-xs text-slate-500 font-bold uppercase tracking-widest mb-6">
                                    <span><?php echo rand(50, 200); ?> Enrolled</span>
                                    <span><?php echo htmlspecialchars($course['duration']); ?></span>
                                </div>
                                <div class="flex gap-3">
                                    <a href="edit_course.php?id=<?php echo $course['id']; ?>" class="flex-1 bg-white/5 hover:bg-white/10 text-white py-3 rounded-xl font-bold text-sm transition-all text-center">Edit Content</a>
                                    <a href="course_detail.php?id=<?php echo $course['id']; ?>" class="p-3 bg-white/5 hover:bg-white/10 text-white rounded-xl transition-all" title="View Course">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="delete_course.php?id=<?php echo $course['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this course? This action cannot be undone.')"
                                       class="p-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-xl transition-all" title="Delete Course">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>