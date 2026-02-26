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
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .gradient-btn {
            background: linear-gradient(to right, #10b981, #3b82f6);
        }

        .gradient-emerald {
            background: linear-gradient(to right, #10b981, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-[#020617] selection:bg-emerald-500/30">
    <?php include 'components/navbar.php'; ?>

    <div
        class="fixed top-0 left-0 w-full h-[600px] bg-emerald-600/10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 z-0">
    </div>

    <main class="max-w-7xl mx-auto px-6 pt-32 pb-20 relative z-10">
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

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16 animate-in" style="animation-delay: 0.2s">
            <div class="glass p-8 rounded-[2rem]">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-2">Total Students</p>
                <h3 class="text-4xl font-black text-white">1,284</h3>
                <p class="text-emerald-400 text-xs font-bold mt-2">↑ 12% this month</p>
            </div>
            <div class="glass p-8 rounded-[2rem]">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-2">Course Rating</p>
                <h3 class="text-4xl font-black text-white">4.9/5.0</h3>
                <p class="text-emerald-400 text-xs font-bold mt-2">from 450 reviews</p>
            </div>
            <div class="glass p-8 rounded-[2rem]">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-2">Platform Earned</p>
                <h3 class="text-4xl font-black text-white">$12,450</h3>
                <p class="text-slate-400 text-xs font-bold mt-2">Lifetime revenue</p>
            </div>
        </div>

        <div class="mb-12 animate-in" style="animation-delay: 0.3s">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                Your Published Courses
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach (array_slice($all_courses, 0, 3) as $index => $course): ?>
                    <div
                        class="glass rounded-[2.5rem] overflow-hidden flex flex-col h-full border border-white/5 hover:border-emerald-500/30 transition-all">
                        <div class="h-48 relative overflow-hidden">
                            <img src="<?php echo $course['image']; ?>" class="w-full h-full object-cover">
                            <div
                                class="absolute top-4 right-4 bg-emerald-500 text-white text-[10px] font-black px-3 py-1.5 rounded-lg shadow-lg">
                                LIVE</div>
                        </div>
                        <div class="p-8">
                            <h3 class="text-xl font-bold text-white mb-4">
                                <?php echo $course['title']; ?>
                            </h3>
                            <div
                                class="flex items-center justify-between text-xs text-slate-500 font-bold uppercase tracking-widest mb-6">
                                <span>
                                    <?php echo rand(50, 200); ?> Enrolled
                                </span>
                                <span>
                                    <?php echo $course['duration']; ?>
                                </span>
                            </div>
                            <div class="flex gap-3">
                                <button
                                    class="flex-1 bg-white/5 hover:bg-white/10 text-white py-3 rounded-xl font-bold text-sm transition-all">Edit
                                    Content</button>
                                <button class="p-3 bg-white/5 hover:bg-white/10 text-white rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>