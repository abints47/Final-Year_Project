<?php
session_start();
require_once 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $role = 'teacher';

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

                header("Location: teacher_dashboard.php");
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
    <title>Teacher Sign Up - Openly</title>
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

        .gradient-cyan {
            background: linear-gradient(to right, #22d3ee, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gradient-btn {
            background: linear-gradient(to right, #10b981, #3b82f6);
        }

        .aura {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.3;
            z-index: -1;
            pointer-events: none;
        }

        .input-field {
            transition: all 0.3s ease;
        }

        .input-field:focus {
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
            border-color: #10b981;
        }
    </style>

</head>

<body class="min-h-screen flex flex-col overflow-x-hidden selection:bg-emerald-500/30 bg-[#020617]">
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="aura bg-emerald-600 w-[500px] h-[500px] top-0 left-0 -translate-x-1/2 -translate-y-1/2 opacity-20">
        </div>
        <div class="aura bg-blue-600 w-[500px] h-[500px] bottom-0 right-0 translate-x-1/2 translate-y-1/2 opacity-20">
        </div>
    </div>

    <?php include 'components/navbar.php'; ?>

    <main class="flex-1 w-full max-w-7xl mx-auto flex flex-col items-center justify-center px-6 py-20">
        <div class="w-full max-w-md relative">
            <div class="text-center mb-10">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest mb-2 block">Instructional
                    Center</span>
                <h1 class="text-3xl font-black text-white mb-2 tracking-tight">Join as a <span
                        class="gradient-cyan">Teacher</span></h1>
                <p class="text-slate-400 text-sm">Empower students by sharing your expertise.</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-200 text-sm rounded-2xl text-center">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="glass p-10 rounded-[2.5rem] shadow-2xl">
                <form action="signup_teacher.php" method="POST" class="space-y-6">
                    <div>
                        <input name="full_name" type="text" required placeholder="Full Name"
                            class="input-field w-full bg-[#0f172a] border border-white/10 rounded-2xl px-5 py-4 text-white placeholder:text-slate-600 focus:outline-none" />
                    </div>
                    <div>
                        <input name="email" type="email" required placeholder="Academic Email Address"
                            class="input-field w-full bg-[#0f172a] border border-white/10 rounded-2xl px-5 py-4 text-white placeholder:text-slate-600 focus:outline-none" />
                    </div>
                    <div>
                        <input name="password" type="password" required maxlength="10" placeholder="Password"
                            class="input-field w-full bg-[#0f172a] border border-white/10 rounded-2xl px-5 py-4 text-white placeholder:text-slate-600 focus:outline-none" />
                    </div>

                    <button type="submit"
                        class="w-full gradient-btn text-white py-4 rounded-2xl font-bold text-lg shadow-xl shadow-emerald-500/20 transition-all hover:opacity-90 active:scale-[0.98]">
                        Create Teacher Account
                    </button>
                </form>

                <div class="mt-8 pt-8 border-t border-white/5 text-center">
                    <p class="text-slate-400 text-sm">
                        Prefer a different role?
                        <a href="signup_student.php"
                            class="text-indigo-400 font-bold hover:text-indigo-300 transition-colors">Student Signup</a>
                        <span class="mx-2 text-slate-600">|</span>
                        <a href="signup_admin.php"
                            class="text-indigo-400 font-bold hover:text-indigo-300 transition-colors">Admin Signup</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>