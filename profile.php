<?php
session_start();
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
    <title>Profile - Techyarjun</title>
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
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .gradient-cyan {
            background: linear-gradient(to right, #22d3ee, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .aura {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.2;
            z-index: -1;
            pointer-events: none;
        }
    </style>
    <script type="importmap">
    {
      "imports": {
        "@google/genai": "https://esm.sh/@google/genai@1.3.1"
      }
    }
    </script>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Background Auras -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="aura bg-cyan-600 w-96 h-96 top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="aura bg-purple-600 w-96 h-96 bottom-0 right-0 translate-x-1/2 translate-y-1/2"></div>
    </div>
    <?php include 'components/navbar.php'; ?>

    <main class="flex-1 max-w-7xl mx-auto w-full px-6 pt-32 pb-20">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Sidebar -->
            <div class="glass p-8 rounded-[2.5rem] h-fit text-center">
                <div class="w-32 h-32 mx-auto rounded-3xl overflow-hidden border-4 border-[#0f172a] shadow-xl mb-6">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?php echo urlencode($_SESSION['user_name']); ?>"
                        alt="Profile" class="w-full h-full bg-indigo-100" />
                </div>
                <h2 class="text-2xl font-black text-white mb-1"><?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </h2>
                <p class="text-slate-500 text-sm mb-8"><?php echo htmlspecialchars($_SESSION['user_email']); ?></p>

                <button
                    class="w-full bg-[#1e293b] hover:bg-[#334155] text-white py-3 rounded-xl font-bold text-sm transition-colors border border-white/5">
                    Edit Settings
                </button>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <div class="glass p-10 rounded-[2.5rem] relative z-10">
                    <h3 class="text-xl font-bold text-white mb-6">Learning Statistics</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                        <div class="bg-[#0f172a] p-5 rounded-2xl border border-white/5">
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2">XP Earned</p>
                            <p class="text-3xl font-black text-cyan-400">0</p>
                        </div>
                        <div class="bg-[#0f172a] p-5 rounded-2xl border border-white/5">
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2">Hours</p>
                            <p class="text-3xl font-black text-cyan-400">0.0</p>
                        </div>
                        <div class="bg-[#0f172a] p-5 rounded-2xl border border-white/5">
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2">Certificates</p>
                            <p class="text-3xl font-black text-cyan-400">0</p>
                        </div>
                    </div>
                </div>

                <div class="glass p-10 rounded-[2.5rem]">
                    <h3 class="text-xl font-bold text-white mb-6">Account Status</h3>
                    <div class="flex items-center justify-between p-5 bg-[#0f172a] rounded-2xl border border-white/5">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 bg-green-500/10 rounded-xl flex items-center justify-center text-green-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-bold">Active Member</p>
                                <p class="text-slate-500 text-xs">Free Tier</p>
                            </div>
                        </div>
                        <button
                            class="text-cyan-400 text-xs font-bold uppercase tracking-widest hover:text-white transition-colors">
                            Upgrade
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>