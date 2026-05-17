<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/utils.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if (empty($email) || empty($pass)) {
        $error = "Please fill in all fields.";
    } elseif (!is_email_valid($email)) {
        $error = "Please enter a valid email address.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, full_name, email_address, role, profile_photo, status FROM users WHERE email_address = ? AND password = ?");
            $stmt->execute([$email, $pass]);
            $user = $stmt->fetch();

            if ($user) {
                if ($user['role'] === 'teacher' && $user['status'] !== 'approved') {
                    $error = "Your account is currently " . $user['status'] . ". Please wait for admin approval.";
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['full_name'];
                    $_SESSION['user_email'] = $user['email_address'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_photo'] = $user['profile_photo'];

                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        header("Location: admin_panel.php");
                    } elseif ($user['role'] === 'teacher') {
                        header("Location: teacher_dashboard.php");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit();
                }
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
    <title>Login - Openly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">

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
                    <span class="text-white">Open</span><span
                        class="bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">ly</span>
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

            <?php if (isset($_GET['msg'])): ?>
                <div
                    class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-200 text-sm rounded-2xl text-center">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

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

            <div class="mt-6 flex flex-col gap-4">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-[#0f172a] px-4 text-slate-500 font-bold tracking-widest">Or continue with</span>
                    </div>
                </div>

                <!-- Google Sign-In Button Container -->
                <div id="g_id_onload"
                    data-client_id="862597476920-ntmq8rsak24e5ar8v2ig3ljgdklviskl.apps.googleusercontent.com"
                    data-context="signin" data-ux_mode="popup" data-callback="handleCredentialResponse"
                    data-auto_prompt="false">
                </div>

                <div class="g_id_signin w-full" data-type="standard" data-shape="rectangular" data-theme="outline"
                    data-text="signin_with" data-size="large" data-logo_alignment="left" data-width="400">
                </div>
            </div>

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


    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        function handleCredentialResponse(response) {
            // Send the ID token to your server for verification
            const id_token = response.credential;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'api/google_auth.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        window.location.href = res.redirect;
                    } else {
                        alert('Authentication failed: ' + res.message);
                    }
                } catch (e) {
                    console.error('Server error response:', xhr.responseText);
                    alert('A server error occurred during verification. Check the console for details.');
                }
            };
            xhr.onerror = function() {
                alert('Connection error occurred while verifying the login.');
            };
            xhr.send('id_token=' + id_token);
        }
    </script>
    <script type="module" src="assets/js/main.js"></script>
</body>

</html>