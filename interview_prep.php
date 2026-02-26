<?php
session_start();
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Prep - Openly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #020617;
            color: #f8fafc;
            overflow-x: hidden;
        }

        .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .gradient-cyan {
            background: linear-gradient(to right, #22d3ee, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gradient-btn {
            background: linear-gradient(to right, #22d3ee, #0ea5e9);
        }

        .gradient-border:hover {
            border-color: #22d3ee;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.1);
        }

        .aura {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col pt-24 relative overflow-x-hidden">
    <!-- Background Decor -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="aura bg-cyan-600 w-[500px] h-[500px] top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="aura bg-purple-600 w-[400px] h-[400px] top-1/2 right-0 translate-x-1/2 -translate-y-1/2"></div>
    </div>

    <?php include 'components/navbar.php'; ?>

    <main class="flex-1 w-full max-w-7xl mx-auto px-6 py-12 relative z-10">
        <!-- Header Section -->
        <div class="text-center mb-12 md:mb-16">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 text-[10px] md:text-xs font-bold mb-6">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                </svg>
                Sharpen Your Thinking
            </div>
            <h1 class="text-4xl md:text-7xl font-black text-white mb-6 tracking-tight">
                Interview <span class="gradient-cyan">Aptitude</span>
            </h1>
            <p class="text-base md:text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed">
                Master logical reasoning, quantitative analysis, and verbal skills to ace your technical interviews.
            </p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
            <!-- Logical Reasoning -->
            <div
                class="glass p-8 rounded-[2.5rem] border border-white/5 flex flex-col transition-all hover:-translate-y-2 gradient-border">
                <div class="flex items-center gap-3 mb-6">
                    <span
                        class="px-3 py-1 bg-cyan-500/10 text-cyan-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-cyan-500/20">Logic</span>
                </div>
                <h2 class="text-2xl font-bold text-white mb-3">Logical Reasoning</h2>
                <p class="text-slate-400 text-sm mb-8">Puzzles, sequences and patterns</p>

                <div class="grid grid-cols-3 gap-3 mb-10">
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span class="block text-white font-bold text-sm">10</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Tasks</span>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="block text-white font-bold text-sm">10 min</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Time</span>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="block text-white font-bold text-sm">Easy</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Level</span>
                    </div>
                </div>

                <a href="interview_quiz.php?category=logical"
                    class="mt-auto block w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-white text-center py-4 rounded-2xl font-bold transition-all hover:opacity-90 active:scale-95 shadow-lg shadow-cyan-500/20 flex items-center justify-center gap-2">
                    Start Prep
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Quantitative Aptitude -->
            <div
                class="glass p-8 rounded-[2.5rem] border border-white/5 flex flex-col transition-all hover:-translate-y-2 gradient-border">
                <div class="flex items-center gap-3 mb-6">
                    <span
                        class="px-3 py-1 bg-purple-500/10 text-purple-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-purple-500/20">Math</span>
                </div>
                <h2 class="text-2xl font-bold text-white mb-3">Quantitative Aptitude</h2>
                <p class="text-slate-400 text-sm mb-8">Numbers, arithmetic and algebra</p>

                <div class="grid grid-cols-3 gap-3 mb-10">
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span class="block text-white font-bold text-sm">10</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Tasks</span>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="block text-white font-bold text-sm">10 min</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Time</span>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="block text-white font-bold text-sm">Medium</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Level</span>
                    </div>
                </div>

                <a href="interview_quiz.php?category=quant"
                    class="mt-auto block w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-white text-center py-4 rounded-2xl font-bold transition-all hover:opacity-90 active:scale-95 shadow-lg shadow-cyan-500/20 flex items-center justify-center gap-2">
                    Start Prep
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Verbal Ability -->
            <div
                class="glass p-8 rounded-[2.5rem] border border-white/5 flex flex-col transition-all hover:-translate-y-2 gradient-border">
                <div class="flex items-center gap-3 mb-6">
                    <span
                        class="px-3 py-1 bg-indigo-500/10 text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-indigo-500/20">English</span>
                </div>
                <h2 class="text-2xl font-bold text-white mb-3">Verbal Ability</h2>
                <p class="text-slate-400 text-sm mb-8">Grammar, synonyms and antonyms</p>

                <div class="grid grid-cols-3 gap-3 mb-10">
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span class="block text-white font-bold text-sm">10</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Tasks</span>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="block text-white font-bold text-sm">10 min</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Time</span>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="block text-white font-bold text-sm">Easy</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Level</span>
                    </div>
                </div>

                <a href="interview_quiz.php?category=verbal"
                    class="mt-auto block w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-white text-center py-4 rounded-2xl font-bold transition-all hover:opacity-90 active:scale-95 shadow-lg shadow-cyan-500/20 flex items-center justify-center gap-2">
                    Start Prep
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Technical Mastery Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                <span class="w-1.5 h-8 bg-rose-500 rounded-full"></span>
                Technical Mastery
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- DSA Fundamentals -->
                <div
                    class="glass p-8 rounded-[2.5rem] border border-white/5 flex flex-col transition-all hover:-translate-y-2 gradient-border relative overflow-hidden group">
                    <div class="flex items-center gap-3 mb-6 relative z-10">
                        <span
                            class="px-3 py-1 bg-rose-500/10 text-rose-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-rose-500/20">Coding</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3 relative z-10">DSA Fundamentals</h3>
                    <p class="text-slate-400 text-sm mb-8 relative z-10">Algorithms, complexity and logic</p>

                    <div class="grid grid-cols-3 gap-3 mb-10 relative z-10">
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">10</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Tasks</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">15 min</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Time</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">Hard</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Level</p>
                        </div>
                    </div>

                    <a href="interview_quiz.php?category=dsa"
                        class="mt-auto block w-full bg-rose-500 hover:bg-rose-600 text-white text-center py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-rose-500/20 active:scale-95 z-10">
                        Start Coding
                    </a>

                    <!-- Subtle background light -->
                    <div
                        class="absolute -bottom-10 -right-10 w-32 h-32 bg-rose-500/10 blur-3xl rounded-full group-hover:bg-rose-500/20 transition-all pointer-events-none">
                    </div>
                </div>

                <!-- DBMS Architecture -->
                <div
                    class="glass p-8 rounded-[2.5rem] border border-white/5 flex flex-col transition-all hover:-translate-y-2 gradient-border relative overflow-hidden group">
                    <div class="flex items-center gap-3 mb-6 relative z-10">
                        <span
                            class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-emerald-500/20">Database</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3 relative z-10">DBMS Architecture</h3>
                    <p class="text-slate-400 text-sm mb-8 relative z-10">SQL, normalization and ACID</p>

                    <div class="grid grid-cols-3 gap-3 mb-10 relative z-10">
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">10</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Tasks</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">12 min</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Time</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">Medium</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Level</p>
                        </div>
                    </div>

                    <a href="interview_quiz.php?category=dbms"
                        class="mt-auto block w-full bg-emerald-500 hover:bg-emerald-600 text-white text-center py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-emerald-500/20 active:scale-95 z-10">
                        Start SQL
                    </a>

                    <div
                        class="absolute -bottom-10 -right-10 w-32 h-32 bg-emerald-500/10 blur-3xl rounded-full group-hover:bg-emerald-500/20 transition-all pointer-events-none">
                    </div>
                </div>

                <!-- Web Fundamentals -->
                <div
                    class="glass p-8 rounded-[2.5rem] border border-white/5 flex flex-col transition-all hover:-translate-y-2 gradient-border relative overflow-hidden group">
                    <div class="flex items-center gap-3 mb-6 relative z-10">
                        <span
                            class="px-3 py-1 bg-amber-500/10 text-amber-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-amber-500/20">Frontend</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3 relative z-10">Web Fundamentals</h3>
                    <p class="text-slate-400 text-sm mb-8 relative z-10">HTML, CSS and JavaScript</p>

                    <div class="grid grid-cols-3 gap-3 mb-10 relative z-10">
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">10</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Tasks</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">10 min</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Time</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">Easy</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Level</p>
                        </div>
                    </div>

                    <a href="interview_quiz.php?category=web"
                        class="mt-auto block w-full bg-amber-500 hover:bg-amber-600 text-white text-center py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-amber-500/20 active:scale-95 z-10">
                        Start Web
                    </a>

                    <div
                        class="absolute -bottom-10 -right-10 w-32 h-32 bg-amber-500/10 blur-3xl rounded-full group-hover:bg-amber-500/20 transition-all pointer-events-none">
                    </div>
                </div>
            </div>
        </div>
        <!-- Final Assessment Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                <span class="w-1.5 h-8 bg-indigo-500 rounded-full"></span>
                Final Assessment
            </h2>
            <div
                class="glass p-1 rounded-[2.5rem] bg-gradient-to-r from-indigo-500/20 to-purple-500/20 border border-white/5">
                <div
                    class="glass p-8 md:p-12 rounded-[2.3rem] border border-white/5 flex flex-col md:flex-row items-center gap-10">
                    <div class="flex-1 text-center md:text-left">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[10px] font-black uppercase tracking-widest mb-6">
                            Real Environment
                        </div>
                        <h3 class="text-3xl md:text-4xl font-black text-white mb-4">Mock Interview Test</h3>
                        <p class="text-slate-400 text-lg mb-8 leading-relaxed max-w-xl">
                            Challenge yourself with a full-length mock test. No instant feedback, no hints — just you
                            and the clock. Detailed results and corrections will be shown after submission.
                        </p>

                        <div class="flex flex-wrap gap-6 justify-center md:justify-start mb-10">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">20 Questions</p>
                                    <p class="text-xs text-slate-500">Mixed categories</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">20 Minutes</p>
                                    <p class="text-xs text-slate-500">Timed session</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">High Difficulty</p>
                                    <p class="text-xs text-slate-500">Performance results</p>
                                </div>
                            </div>
                        </div>

                        <a href="interview_quiz.php?category=all&type=mock"
                            class="inline-flex items-center gap-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-10 py-5 rounded-2xl font-black text-lg transition-all hover:scale-105 active:scale-95 shadow-2xl shadow-indigo-500/20">
                            Start Final Assessment
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>