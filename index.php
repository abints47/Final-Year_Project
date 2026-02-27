<?php
session_start();
require_once 'includes/db.php';

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

                $_SESSION['user_id'] = $email;
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
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Openly - The Future of Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="min-h-screen flex flex-col selection:bg-indigo-500/30">
    <!-- Background Decor -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="aura bg-cyan-600 w-[500px] h-[500px] top-0 left-0 -translate-x-1/2 -translate-y-1/2 opacity-20">
        </div>
        <div class="aura bg-purple-600 w-[500px] h-[500px] bottom-0 right-0 translate-x-1/2 translate-y-1/2 opacity-20">
        </div>
    </div>

    <?php include 'components/navbar.php'; ?>

    <main
        class="flex-1 w-full max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-center px-6 py-20 gap-16 relative z-10">

        <!-- Hero Text -->
        <div class="flex-1 text-center lg:text-left pt-10 lg:pt-0">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-semibold mb-8 mx-auto lg:mx-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Transform Your Career with Expert-Led Courses
            </div>
            <h1 class="text-5xl md:text-8xl font-black text-white mb-8 leading-[1] tracking-tight">
                Master <span class="gradient-cyan">Programming</span> & <br class="hidden md:block" />
                <span class="gradient-purple">AI Skills</span>
            </h1>
            <p class="text-base md:text-lg text-slate-400 mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                Join thousands of learners mastering Python, Java, Machine Learning, and cutting-edge AI technologies.
                Learn from industry experts with hands-on projects.
            </p>

            <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                <a href="dashboard.php"
                    class="bg-gradient-to-r from-cyan-400 to-blue-600 px-8 py-4 rounded-2xl text-white font-bold text-lg shadow-xl shadow-cyan-500/20 hover:opacity-90 active:scale-[0.98] transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Explore Courses
                </a>
                <a href="#learn-more"
                    class="glass px-8 py-4 rounded-2xl text-white font-bold text-lg hover:bg-white/10 active:scale-[0.98] transition-all flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Learn More
                </a>
            </div>
        </div>

        <!-- Signup Card -->
        <div class="w-full max-w-md relative">
            <?php if ($error): ?>
                <div
                    class="absolute -top-16 left-0 right-0 p-4 bg-red-500/10 border border-red-500/20 text-red-200 text-sm rounded-2xl text-center backdrop-blur-md">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="glass p-10 rounded-[2.5rem] shadow-2xl">
                <div class="text-center mb-8">
                    <span
                        class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 mb-2 block">Registration</span>
                    <h2 id="card-title" class="text-2xl font-bold text-white">Join as a <span
                            class="text-indigo-400">Student</span></h2>
                    <p id="card-desc" class="text-slate-500 text-sm mt-1">Start your learning journey for free.</p>
                </div>

                <form action="index.php" method="POST" class="space-y-5">
                    <div class="relative group">
                        <label class="text-xs font-bold text-slate-500 mb-2 block ml-1">I am a...</label>
                        <select name="role" id="role-selector"
                            class="w-full bg-[#0f172a] border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50 appearance-none cursor-pointer">
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                        </select>
                        <div class="absolute right-5 bottom-4 pointer-events-none text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <div>
                        <input name="full_name" type="text" required placeholder="Full Name"
                            class="input-field w-full bg-[#0f172a] border border-white/10 rounded-2xl px-5 py-4 text-white placeholder:text-slate-600 focus:outline-none" />
                    </div>
                    <div>
                        <input name="email" type="email" required placeholder="Email Address"
                            class="input-field w-full bg-[#0f172a] border border-white/10 rounded-2xl px-5 py-4 text-white placeholder:text-slate-600 focus:outline-none" />
                    </div>
                    <div>
                        <input name="password" type="password" required maxlength="10" placeholder="Password"
                            class="input-field w-full bg-[#0f172a] border border-white/10 rounded-2xl px-5 py-4 text-white placeholder:text-slate-600 focus:outline-none" />
                    </div>

                    <button type="submit" id="submit-btn"
                        class="w-full bg-white hover:bg-slate-200 text-black py-4 rounded-2xl font-bold text-lg shadow-xl transition-all active:scale-[0.98] mt-2">
                        Get Started
                    </button>
                </form>

                <div class="mt-8 pt-8 border-t border-white/5 text-center">
                    <p class="text-slate-400 text-sm">
                        Have an account?
                        <a href="login.php" class="text-white font-bold hover:underline transition-colors">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>

    <script>
        const roleSelector = document.getElementById('role-selector');
        const cardTitle = document.getElementById('card-title');
        const cardDesc = document.getElementById('card-desc');
        const submitBtn = document.getElementById('submit-btn');

        roleSelector.addEventListener('change', (e) => {
            const role = e.target.value;
            if (role === 'teacher') {
                cardTitle.innerHTML = 'Join as a <span class="text-emerald-400">Teacher</span>';
                cardDesc.innerText = 'Empower students by sharing your expertise.';
                submitBtn.innerText = 'Create Teacher Account';
                submitBtn.className = 'w-full bg-emerald-500 hover:bg-emerald-600 text-white py-4 rounded-2xl font-bold text-lg shadow-xl shadow-emerald-500/20 transition-all active:scale-[0.98] mt-2';
            } else {
                cardTitle.innerHTML = 'Join as a <span class="text-indigo-400">Student</span>';
                cardDesc.innerText = 'Start your learning journey for free.';
                submitBtn.innerText = 'Get Started';
                submitBtn.className = 'w-full bg-white hover:bg-slate-200 text-black py-4 rounded-2xl font-bold text-lg shadow-xl transition-all active:scale-[0.98] mt-2';
            }
        });
    </script>
</body>

</html>