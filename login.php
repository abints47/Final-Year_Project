<?php
session_start();
require_once 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if (empty($email) || empty($pass)) {
        $error = "Please fill in all fields.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT full_name, email_address FROM users WHERE email_address = ? AND password = ?");
            $stmt->execute([$email, $pass]);
            $user = $stmt->fetch();

            if ($user) {
                $_SESSION['user_id'] = $user['email_address'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email_address'];

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid credentials.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Techyarjun</title>
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

        .input-field {
            transition: all 0.3s ease;
        }

        .input-field:focus {
            box-shadow: 0 0 0 4px rgba(34, 211, 238, 0.2);
            border-color: #22d3ee;
        }

        .gradient-btn {
            background: linear-gradient(to right, #22d3ee, #c084fc);
        }
    </style>

</head>

<body class="min-h-screen flex flex-col relative">
    <!-- Navbar simplified for login -->
    <nav class="absolute top-0 left-0 right-0 z-50 px-6 py-6">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2 cursor-pointer group" onclick="window.location.href='index.php'">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-xl flex items-center justify-center transition-transform group-hover:scale-105 shadow-lg shadow-cyan-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                </div>
                <span class="text-2xl font-bold tracking-tight">
                    <span class="text-white">Arjun</span><span
                        class="bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">Techy</span>
                </span>
            </div>
        </div>
    </nav>

    <main class="flex-1 flex items-center justify-center p-6 pt-24">
        <div class="glass w-full max-w-md p-10 rounded-[2.5rem] shadow-2xl relative">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-black text-white mb-2 tracking-tight">Welcome Back</h1>
                <p class="text-slate-400 text-sm">Enter your credentials to continue.</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-200 text-sm rounded-2xl text-center">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-6">
                <div>
                    <input name="email" type="email" required placeholder="name@example.com"
                        class="input-field w-full bg-[#0f172a] border border-white/10 rounded-2xl px-5 py-4 text-white placeholder:text-slate-600 focus:outline-none" />
                </div>
                <div>
                    <input name="password" type="password" required placeholder="••••••••"
                        class="input-field w-full bg-[#0f172a] border border-white/10 rounded-2xl px-5 py-4 text-white placeholder:text-slate-600 focus:outline-none" />
                </div>

                <button type="submit"
                    class="w-full gradient-btn text-white py-4 rounded-2xl font-bold shadow-lg shadow-cyan-500/20 transition-all hover:opacity-90 active:scale-[0.98]">
                    Sign In
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-white/5 text-center">
                <p class="text-slate-400 text-sm">
                    Don't have an account?
                    <a href="index.php" class="text-indigo-400 font-bold hover:text-indigo-300 transition-colors">Create
                        one</a>
                </p>
            </div>
        </div>
    </main>

    <div
        class="absolute bottom-0 w-full h-1/2 bg-gradient-to-t from-indigo-900/10 to-transparent pointer-events-none z-[-1]">
    </div>

    <?php include 'components/footer.php'; ?>

    <script type="module" src="assets/js/main.js"></script>
</body>

</html>