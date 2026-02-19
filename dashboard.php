<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/db.php';
require_once 'includes/courses_data.php';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Openly</title>
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

        .course-card {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.5);
            border-color: rgba(34, 211, 238, 0.3);
        }

        .gradient-btn {
            background: linear-gradient(to right, #22d3ee, #c084fc);
        }

        .gradient-cyan {
            background: linear-gradient(to right, #22d3ee, #0ea5e9);
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

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #020617;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }

        /* Features Section Styles */
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(34, 211, 238, 0.1);
            border: 1px solid rgba(34, 211, 238, 0.2);
            border-radius: 9999px;
            color: #22d3ee;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 900;
            color: white;
            margin-bottom: 1rem;
            letter-spacing: -0.025em;
        }

        .gradient-text {
            background: linear-gradient(to right, #22d3ee, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .section-description {
            color: #94a3b8;
            max-width: 32rem;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .features-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .features-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .feature-icon {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, rgba(34, 211, 238, 0.1), rgba(192, 132, 252, 0.1));
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #22d3ee;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(34, 211, 238, 0.2);
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.75rem;
        }

        .feature-description {
            color: #94a3b8;
            font-size: 0.875rem;
            line-height: 1.6;
        }

        .feature-card {
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 2rem;
            border-radius: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(34, 211, 238, 0.4);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }
    </style>


</head>

<body class="min-h-screen flex flex-col bg-[#020617] selection:bg-indigo-500/30">
    <?php include 'components/navbar.php'; ?>

    <div
        class="fixed top-0 left-0 w-full h-[600px] bg-cyan-600/10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2 z-0">
    </div>
    <div
        class="fixed bottom-0 right-0 w-full h-[600px] bg-purple-600/10 blur-[120px] rounded-full pointer-events-none translate-y-1/2 z-0">
    </div>

    <main class="max-w-7xl mx-auto px-6 pt-32 pb-20 relative z-10">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-16 animate-in text-center md:text-left"
            style="animation-delay: 0.1s">
            <div>
                <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest mb-2 block">Overview</span>
<<<<<<< HEAD
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight">
=======
                <h1 class="text-3xl md:text-6xl font-black text-white tracking-tight">
>>>>>>> ea85590 (Implement Interview Prep section: quiz, landing page, and data)
                    Welcome back, <span class="gradient-cyan">
                        <?php echo htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]); ?>!
                    </span>
                </h1>
<<<<<<< HEAD
                <p class="text-slate-400 mt-2 font-medium">Continue your learning journey</p>
=======
                <p class="text-slate-400 mt-2 font-medium text-sm md:text-base">Continue your learning journey</p>
>>>>>>> ea85590 (Implement Interview Prep section: quiz, landing page, and data)
            </div>
            <div class="mt-8 md:mt-0 glass px-6 py-4 rounded-2xl flex items-center gap-4 mx-auto md:mx-0">
                <div class="w-10 h-10 bg-green-500/10 rounded-full flex items-center justify-center text-green-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Current Streak</p>
                    <p class="text-xl font-black text-white">12 Days</p>
                </div>
            </div>
        </div>

        <!-- Hero Section -->
        <section class="py-20 animate-in text-center" style="animation-delay: 0.3s">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-semibold mb-8">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Transform Your Career with Expert-Led Courses
            </div>
            <h1 class="text-4xl md:text-7xl font-black text-white mb-8 leading-[1.1] tracking-tight">
                Master <span class="gradient-cyan">Programming</span> & <br class="hidden md:block" />
                <span class="gradient-purple">AI Skills</span>
            </h1>
            <p class="text-base md:text-lg text-slate-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                Join thousands of learners mastering Python, Java, Machine Learning, and cutting-edge AI technologies.
                Learn from industry experts with hands-on projects.
            </p>

            <div class="flex flex-col sm:flex-row items-center gap-4 justify-center">
                <a href="courses.php"
                    class="gradient-btn px-8 py-4 rounded-2xl text-white font-bold text-lg shadow-xl shadow-cyan-500/20 hover:opacity-90 active:scale-[0.98] transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Explore Courses
                </a>
                <a href="#features"
                    class="glass px-8 py-4 rounded-2xl text-white font-bold text-lg hover:bg-white/10 active:scale-[0.98] transition-all flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Learn More
                </a>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="features py-20 animate-in" id="features" style="animation-delay: 0.5s">
            <div class="section-header">
                <div class="section-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        data-lucide="layers" class="lucide lucide-layers">
                        <path
                            d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z">
                        </path>
                        <path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12"></path>
                        <path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17"></path>
                    </svg>
                    Why Choose Us
                </div>
                <h2 class="section-title">Everything You Need to <span class="gradient-text">Succeed</span></h2>
                <p class="section-description">Our platform offers comprehensive learning tools designed to help you
                    master new skills effectively.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card glass animate-in" style="animation-delay: 0.2s">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            data-lucide="play-circle" class="lucide lucide-play-circle">
                            <path
                                d="M9 9.003a1 1 0 0 1 1.517-.859l4.997 2.997a1 1 0 0 1 0 1.718l-4.997 2.997A1 1 0 0 1 9 14.996z">
                            </path>
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                    </div>
                    <h3 class="feature-title">HD Video Lessons</h3>
                    <p class="feature-description">Crystal clear video content with lifetime access</p>
                </div>
                <div class="feature-card glass animate-in" style="animation-delay: 0.3s">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            data-lucide="clipboard-check" class="lucide lucide-clipboard-check">
                            <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <path d="m9 14 2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="feature-title">Interactive Quizzes</h3>
                    <p class="feature-description">Test your knowledge with engaging assessments</p>
                </div>
                <div class="feature-card glass animate-in" style="animation-delay: 0.4s">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            data-lucide="flame" class="lucide lucide-flame">
                            <path
                                d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="feature-title">Learning Streaks</h3>
                    <p class="feature-description">Stay motivated with daily learning goals</p>
                </div>
                <div class="feature-card glass animate-in" style="animation-delay: 0.5s">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            data-lucide="trophy" class="lucide lucide-trophy">
                            <path d="M10 14.66v1.626a2 2 0 0 1-.976 1.696A5 5 0 0 0 7 21.978"></path>
                            <path d="M14 14.66v1.626a2 2 0 0 0 .976 1.696A5 5 0 0 1 17 21.978"></path>
                            <path d="M18 9h1.5a1 1 0 0 0 0-5H18"></path>
                            <path d="M4 22h16"></path>
                            <path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"></path>
                            <path d="M6 9H4.5a1 1 0 0 1 0-5H6"></path>
                        </svg>
                    </div>
                    <h3 class="feature-title">Earn Badges</h3>
                    <p class="feature-description">Unlock achievements as you progress</p>
                </div>
                <div class="feature-card glass animate-in" style="animation-delay: 0.6s">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            data-lucide="award" class="lucide lucide-award">
                            <path
                                d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526">
                            </path>
                            <circle cx="12" cy="8" r="6"></circle>
                        </svg>
                    </div>
                    <h3 class="feature-title">Certificates</h3>
                    <p class="feature-description">Get certified upon course completion</p>
                </div>
                <div class="feature-card glass animate-in" style="animation-delay: 0.7s">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            data-lucide="bar-chart" class="lucide lucide-bar-chart">
                            <path d="M5 21v-6"></path>
                            <path d="M12 21V9"></path>
                            <path d="M19 21V3"></path>
                        </svg>
                    </div>
                    <h3 class="feature-title">Progress Tracking</h3>
                    <p class="feature-description">Monitor your learning journey in real-time</p>
                </div>
            </div>
        </section> <!-- Course Grid -->
        <div class="mb-12 animate-in pt-10 pb-20" style="animation-delay: 0.7s">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                <span class="w-2 h-8 bg-cyan-500 rounded-full"></span>
                Active Courses
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($all_courses as $index => $course):
                    $badgeColor = 'bg-emerald-500';
                    if ($course['level'] == 'Intermediate' || $course['level'] == 'Medium')
                        $badgeColor = 'bg-amber-500';
                    if ($course['level'] == 'Advanced' || $course['level'] == 'Hard')
                        $badgeColor = 'bg-rose-500';
                    ?>
                    <a href="course_detail.php?id=<?php echo $course['id']; ?>"
                        class="course-card bg-[#0f172a] border border-white/5 rounded-[2rem] overflow-hidden flex flex-col h-full animate-in group"
                        style="animation-delay: <?php echo $index * 0.05; ?>s">
                        <div class="h-44 relative overflow-hidden">
                            <img src="<?php echo $course['image']; ?>" alt="<?php echo $course['title']; ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors"></div>
                            <div
                                class="absolute top-4 left-4 bg-white/10 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border border-white/10">
                                <?php echo $course['category']; ?>
                            </div>
                            <div
                                class="absolute top-4 right-4 <?php echo $badgeColor; ?> text-white text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-lg">
                                <?php echo $course['level']; ?>
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <h3
                                class="text-lg font-bold text-white mb-3 leading-tight group-hover:text-cyan-400 transition-colors">
                                <?php echo $course['title']; ?>
                            </h3>
                            <p class="text-slate-400 text-xs mb-6 flex-1 leading-relaxed line-clamp-3">
                                <?php echo $course['summary']; ?>
                            </p>

                            <div
                                class="flex items-center justify-between text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-6 border-t border-white/5 pt-4">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <?php echo $course['duration']; ?>
                                </span>
                                <span>Video Course</span>
                            </div>

                            <div class="flex gap-2">
                                <span
                                    class="flex-1 gradient-btn text-white py-3 rounded-xl font-bold text-center text-xs transition-all hover:opacity-90 active:scale-95 shadow-lg shadow-cyan-500/20">
                                    Start Learning
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- Code Racer Game -->
        <?php include 'components/game.php'; ?>


    </main>

    <?php include 'components/footer.php'; ?>


    <script type="module" src="assets/js/main.js"></script>
    <script src="assets/js/game.js"></script>
</body>

</html>