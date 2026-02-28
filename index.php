<?php
session_start();
require_once 'includes/db.php';

// Fetch stats for the showcase
$user_count = 0;
$course_count = 0;
try {
    $user_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $course_count = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
} catch (PDOException $e) {
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $role = $_POST['role'] ?? 'student';

    if (empty($full_name) || empty($email) || empty($pass)) {
        $error = "All fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT email_address FROM users WHERE email_address = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $error = "This email is already registered.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (full_name, email_address, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$full_name, $email, $pass, $role]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_name'] = $full_name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = $role;

                if ($role === 'teacher') {
                    header("Location: teacher_dashboard.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            }
        } catch (PDOException $e) {
            $error = "Registration error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Openly - Master Your Future with AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #020617;
            color: #f8fafc;
            overflow-x: hidden;
        }

        .glass {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .feature-card {
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: #22d3ee;
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
            opacity: 0.1;
            z-index: -1;
            pointer-events: none;
        }

        .input-field {
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: #22d3ee;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.1);
        }

        .hero-glow {
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(34, 211, 238, 0.03) 0%, transparent 70%);
            top: 0;
            left: 0;
            pointer-events: none;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col selection:bg-cyan-500/30">
    <!-- Static Auras -->
    <div class="aura bg-cyan-600 w-[600px] h-[600px] -top-96 -left-96"></div>
    <div class="aura bg-purple-600 w-[600px] h-[600px] bottom-0 -right-96"></div>

    <?php include 'components/navbar.php'; ?>

    <!-- Hero & Signup Area -->
    <header
        class="relative <?php echo isset($_SESSION['user_id']) ? 'pt-48 items-center' : 'pt-20 items-start'; ?> pb-32 px-6 min-h-[90vh] flex flex-col justify-center">
        <div class="hero-glow"></div>
        <div
            class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-24 w-full <?php echo isset($_SESSION['user_id']) ? 'items-center' : 'mt-12'; ?>">

            <!-- Hero Content -->
            <div
                class="flex-1 text-center lg:text-left z-10 <?php echo isset($_SESSION['user_id']) ? '' : 'lg:mt-10'; ?>">
                <div
                    class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-black mb-10 tracking-[0.2em] uppercase">
                    • Next-Gen Career AI
                </div>
                <h1 class="text-6xl lg:text-8xl font-black mb-10 leading-[0.95] tracking-tighter text-white">
                    The Future of <br>
                    <span class="gradient-text">Career Mastery</span> is Openly
                </h1>
                <p
                    class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto lg:mx-0 mb-12 leading-relaxed font-medium">
                    Unlock your professional potential with an AI-driven ecosystem designed for immersive learning,
                    career mapping, and long-term professional success.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 mb-16">
                    <button
                        class="bg-cyan-400 hover:bg-cyan-300 text-[#020617] px-10 py-5 rounded-2xl font-black text-lg transition-all shadow-xl shadow-cyan-400/20">
                        Start Your Journey Free
                    </button>
                    <button
                        class="bg-white/5 hover:bg-white/10 text-white border border-white/10 px-10 py-5 rounded-2xl font-black text-lg transition-all">
                        Watch Tech Demo
                    </button>
                </div>

                <!-- Social Proof -->
                <div class="flex items-center justify-center lg:justify-start gap-4">
                    <p class="text-slate-500 text-sm font-bold">
                        Joined by <span class="text-white"><?php echo number_format($user_count); ?>+</span> ambitious
                        professionals
                    </p>
                </div>
            </div>

            <!-- Signup Card -->
            <div class="w-full max-w-md relative z-10">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-[3.5rem] blur opacity-10">
                </div>
                <?php if ($error): ?>
                    <div
                        class="absolute -top-16 left-0 right-0 p-4 bg-red-500/10 border border-red-500/20 text-red-200 text-sm rounded-2xl text-center backdrop-blur-md z-20">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="glass p-12 rounded-[3.5rem] relative z-10 border-white/10 shadow-2xl">
                    <div class="text-center mb-10">
                        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-cyan-400 mb-3 block">Quick
                            Registration</span>
                        <h2 id="card-title" class="text-3xl font-black text-white">Create <span
                                class="text-cyan-400">Student</span> Pass</h2>
                        <p id="card-desc" class="text-slate-500 text-sm mt-3 font-medium">Unlock premium AI analytics
                            for free.</p>
                    </div>

                    <form action="index.php" method="POST" class="space-y-6">
                        <div class="relative group">
                            <label
                                class="text-[10px] font-bold text-slate-500 mb-2 block ml-1 uppercase tracking-widest">Select
                                Role</label>
                            <select name="role" id="role-selector"
                                class="w-full bg-[#030712] border border-white/5 rounded-2xl px-6 py-5 text-white font-bold focus:outline-none focus:ring-2 focus:ring-cyan-500/50 appearance-none cursor-pointer">
                                <option value="student">Student Explorer</option>
                                <option value="teacher">Expert Instructor</option>
                            </select>
                            <div class="absolute right-6 bottom-5 pointer-events-none text-slate-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <div>
                            <input name="full_name" type="text" required placeholder="Full Name"
                                class="input-field w-full bg-[#030712] border border-white/5 rounded-2xl px-6 py-5 text-white font-medium placeholder:text-slate-700 focus:outline-none" />
                        </div>
                        <div>
                            <input name="email" type="email" required placeholder="Email Address"
                                class="input-field w-full bg-[#030712] border border-white/5 rounded-2xl px-6 py-5 text-white font-medium placeholder:text-slate-700 focus:outline-none" />
                        </div>
                        <div>
                            <input name="password" type="password" required maxlength="10" placeholder="Secret Password"
                                class="input-field w-full bg-[#030712] border border-white/5 rounded-2xl px-6 py-5 text-white font-medium placeholder:text-slate-700 focus:outline-none" />
                        </div>

                        <button type="submit" id="submit-btn"
                            class="w-full bg-cyan-500 hover:bg-cyan-400 text-[#020617] py-5 rounded-2xl font-black text-xl shadow-xl shadow-cyan-500/20 active:scale-95 transition-all mt-4 uppercase tracking-tighter">
                            Begin Journey
                        </button>
                    </form>

                    <div class="mt-10 pt-10 border-t border-white/5 text-center">
                        <p class="text-slate-500 text-sm font-bold">
                            Returning Explorer?
                            <a href="login.php" class="text-white hover:text-cyan-400 transition-colors ml-1">Sign
                                In</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Detailed Feature Showcase -->
    <section id="features" class="py-40 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-32">
                <h2 class="text-4xl lg:text-6xl font-black text-white mb-8">Six Pillars of Your Evolution</h2>
                <p class="text-slate-500 text-lg max-w-3xl mx-auto font-medium">Integrated AI tools designed to cover
                    every aspect of your professional trajectory, from the first application to the corner office.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- AI Analyzer -->
                <div class="glass p-10 rounded-[3rem] feature-card group">
                    <div
                        class="w-16 h-16 bg-cyan-500/10 rounded-2xl flex items-center justify-center mb-8 text-cyan-400 transition-all group-hover:bg-cyan-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4">AI ATS Analyzer</h3>
                    <p class="text-slate-400 text-base leading-relaxed font-medium">Reverse-engineer applicant tracking
                        systems. Optimize your resume for specific job descriptions with real-time scoring.</p>
                </div>

                <!-- Interview IQ -->
                <div class="glass p-10 rounded-[3rem] feature-card group">
                    <div
                        class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center mb-8 text-amber-400 transition-all group-hover:bg-amber-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4">Interview IQ</h3>
                    <p class="text-slate-400 text-base leading-relaxed font-medium">Practice with our vocal AI. Get
                        instant feedback on your confidence, pacing, and keyword usage after every mock session.</p>
                </div>

                <!-- Career Roadmap -->
                <div class="glass p-10 rounded-[3rem] feature-card group">
                    <div
                        class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-8 text-blue-400 transition-all group-hover:bg-blue-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A2 2 0 013 15.487V6.513a2 2 0 011.553-1.943L9 2l5.447 2.724A2 2 0 0116 6.513v8.974a2 2 0 01-1.553 1.943L9 20z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20V12M12 8l-3-3-3 3" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4">Career Roadmap</h3>
                    <p class="text-slate-400 text-base leading-relaxed font-medium">Visualized paths from junior to
                        executive. See the exact roles and timeline needed to reach your long-term goals.</p>
                </div>

                <!-- Skill Gap Mapping -->
                <div class="glass p-10 rounded-[3rem] feature-card group">
                    <div
                        class="w-16 h-16 bg-rose-500/10 rounded-2xl flex items-center justify-center mb-8 text-rose-400 transition-all group-hover:bg-rose-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4">Skill Gap Mapping</h3>
                    <p class="text-slate-400 text-base leading-relaxed font-medium">Cross-reference your current skills
                        with industry demands. Identify the specific certifications you need next.</p>
                </div>

                <!-- Course Mastery -->
                <div class="glass p-10 rounded-[3rem] feature-card group">
                    <div
                        class="w-16 h-16 bg-indigo-500/10 rounded-2xl flex items-center justify-center mb-8 text-indigo-400 transition-all group-hover:bg-indigo-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4">Course Mastery Player</h3>
                    <p class="text-slate-400 text-base leading-relaxed font-medium">Immersive, AI-curated video content.
                        Learn at your own pace with smart summaries and interactive quizzes.</p>
                </div>

                <!-- Aptitude Practice -->
                <div class="glass p-10 rounded-[3rem] feature-card group">
                    <div
                        class="w-16 h-16 bg-cyan-600/10 rounded-2xl flex items-center justify-center mb-8 text-cyan-400 transition-all group-hover:bg-cyan-600/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4">Aptitude Quest</h3>
                    <p class="text-slate-400 text-base leading-relaxed font-medium">Discover your hidden strengths
                        through gamified testing. Real-world scenarios that reveal your professional DNA.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-40 px-6">
        <div class="max-w-5xl mx-auto glass p-20 rounded-[4rem] text-center relative overflow-hidden border-white/10">
            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-purple-600/10 pointer-events-none"></div>
            <h2 class="text-5xl lg:text-7xl font-black text-white mb-8 relative z-10">Ready to Master Your <span
                    class="gradient-text">Career?</span></h2>
            <p class="text-slate-400 text-xl max-w-2xl mx-auto mb-12 font-medium relative z-10">Join thousands of
                professionals using Openly to reach their full potential. The first step to your dream career is just
                one click away.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 relative z-10">
                <button
                    class="bg-cyan-400 hover:bg-cyan-300 text-[#020617] px-12 py-5 rounded-2xl font-black text-xl transition-all">Get
                    Started Now</button>
                <button
                    class="bg-white/5 hover:bg-white/10 text-white border border-white/10 px-12 py-5 rounded-2xl font-black text-xl transition-all">Schedule
                    a Consultation</button>
            </div>
        </div>
    </section>

    <?php if (isset($_SESSION['user_id'])): ?>
        <?php include 'components/footer.php'; ?>
    <?php endif; ?>

    <script>
        // Role Selector Logic
        const roleSelector = document.getElementById('role-selector');
        const cardTitle = document.getElementById('card-title');
        const cardDesc = document.getElementById('card-desc');
        const submitBtn = document.getElementById('submit-btn');

        roleSelector.addEventListener('change', (e) => {
            const role = e.target.value;
            if (role === 'teacher') {
                cardTitle.innerHTML = 'Create <span class="text-emerald-400">Teacher</span> Pass';
                cardDesc.innerText = 'Empower others by sharing your expert knowledge.';
                submitBtn.innerText = 'Launch Instructor Path';
                submitBtn.className = 'w-full bg-emerald-500 hover:bg-emerald-400 text-[#020617] py-5 rounded-2xl font-black text-xl shadow-xl shadow-emerald-500/20 active:scale-95 transition-all mt-4 uppercase tracking-tighter';
            } else {
                cardTitle.innerHTML = 'Create <span class="text-cyan-400">Student</span> Pass';
                cardDesc.innerText = 'Free access to all AI career features.';
                submitBtn.innerText = 'Begin Journey';
                submitBtn.className = 'w-full bg-cyan-500 hover:bg-cyan-400 text-[#020617] py-5 rounded-2xl font-black text-xl shadow-xl shadow-cyan-500/20 active:scale-95 transition-all mt-4 uppercase tracking-tighter';
            }
        });
    </script>
</body>

</html>