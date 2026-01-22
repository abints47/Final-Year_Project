<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Techyarjun</title>
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

        .input-field {
            transition: all 0.3s ease;
        }

        .input-field:focus {
            box-shadow: 0 0 0 4px rgba(34, 211, 238, 0.2);
            border-color: #22d3ee;
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

        .gradient-btn {
            background: linear-gradient(to right, #22d3ee, #c084fc);
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
        <div class="text-center mb-16 relative z-10">
            <h1 class="text-5xl font-black text-white mb-4">Get in <span class="gradient-cyan">Touch</span></h1>
            <p class="text-slate-400 text-lg">We'd love to hear from you.</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12">
            <div class="space-y-8">
                <div class="glass p-8 rounded-[2.5rem]">
                    <h3 class="text-2xl font-bold text-white mb-8">Contact Info</h3>
                    <div class="space-y-6">
                        <div class="flex items-start gap-5">
                            <div
                                class="w-12 h-12 bg-cyan-600/20 rounded-2xl flex items-center justify-center text-cyan-400 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-slate-400 text-sm mb-1">Email Support</p>
                                <p class="text-white font-bold text-lg">help@techyarjun.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass p-10 rounded-[2.5rem]">
                <form class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <input type="text" placeholder="First Name"
                                class="input-field w-full bg-[#0f172a] border border-white/10 rounded-2xl px-5 py-4 text-white placeholder:text-slate-600 focus:outline-none" />
                        </div>
                        <div>
                            <input type="text" placeholder="Last Name"
                                class="input-field w-full bg-[#0f172a] border border-white/10 rounded-2xl px-5 py-4 text-white placeholder:text-slate-600 focus:outline-none" />
                        </div>
                    </div>
                    <div>
                        <input type="email" placeholder="Email"
                            class="input-field w-full bg-[#0f172a] border border-white/10 rounded-2xl px-5 py-4 text-white placeholder:text-slate-600 focus:outline-none" />
                    </div>
                    <div>
                        <textarea rows="4" placeholder="Message"
                            class="input-field w-full bg-[#0f172a] border border-white/10 rounded-2xl px-5 py-4 text-white placeholder:text-slate-600 focus:outline-none resize-none"></textarea>
                    </div>
                    <button type="button"
                        class="w-full gradient-btn text-white py-4 rounded-2xl font-bold shadow-xl transition-all active:scale-[0.98]">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>