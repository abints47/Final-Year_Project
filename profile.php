<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch current user data
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email_address = ?");
    $stmt->execute([$_SESSION['user_email']]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    die("Error fetching user data: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Openly</title>
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

        #edit-modal.hidden {
            display: none;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Background Auras -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="aura bg-cyan-600 w-96 h-96 top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="aura bg-purple-600 w-96 h-96 bottom-0 right-0 translate-x-1/2 translate-y-1/2"></div>
    </div>
    <?php include 'components/navbar.php'; ?>

    <main class="flex-1 max-w-7xl mx-auto w-full px-6 pt-32 pb-20">
        <?php if (isset($_GET['status'])): ?>
            <div
                class="mb-8 p-4 rounded-2xl border <?php echo $_GET['status'] === 'success' ? 'bg-green-500/10 border-green-500/20 text-green-400' : 'bg-red-500/10 border-red-500/20 text-red-400'; ?> animate-in slide-in-from-top duration-500">
                <div class="flex items-center gap-3">
                    <?php if ($_GET['status'] === 'success'): ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-bold">Profile updated successfully!</span>
                    <?php else: ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-bold">Error:
                            <?php echo htmlspecialchars($_GET['message'] ?? 'Something went wrong.'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Sidebar -->
            <div class="glass p-8 rounded-[2.5rem] h-fit text-center">
                <div
                    class="w-32 h-32 mx-auto rounded-3xl overflow-hidden border-4 border-[#0f172a] shadow-xl mb-6 bg-white/5">
                    <?php if (!empty($user['profile_photo'])): ?>
                        <img src="<?php echo htmlspecialchars($user['profile_photo']); ?>" alt="Profile"
                            class="w-full h-full object-cover" />
                    <?php else: ?>
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?php echo urlencode($user['full_name']); ?>"
                            alt="Profile" class="w-full h-full bg-indigo-100" />
                    <?php endif; ?>
                </div>
                <h2 class="text-2xl font-black text-white mb-1"><?php echo htmlspecialchars($user['full_name']); ?>
                </h2>
                <div class="mb-4">
                    <?php
                    $role = $user['role'] ?? 'student';
                    $roleClass = 'text-cyan-400 bg-cyan-400/10';
                    if ($role === 'teacher')
                        $roleClass = 'text-emerald-400 bg-emerald-400/10';
                    if ($role === 'admin')
                        $roleClass = 'text-rose-400 bg-rose-400/10';
                    ?>
                    <span
                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest <?php echo $roleClass; ?> border border-white/5">
                        <?php echo htmlspecialchars($role); ?>
                    </span>
                </div>
                <p class="text-slate-500 text-sm mb-4"><?php echo htmlspecialchars($user['email_address']); ?></p>

                <div class="space-y-2 mb-8 text-xs font-medium text-slate-400">
                    <?php if (!empty($user['gender'])): ?>
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="capitalize"><?php echo htmlspecialchars($user['gender']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($user['address'])): ?>
                        <div class="flex items-center justify-center gap-2 px-6">
                            <svg class="w-3 h-3 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="truncate"><?php echo htmlspecialchars($user['address']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <button onclick="toggleModal()"
                    class="w-full bg-[#1e293b] hover:bg-[#334155] text-white py-3 rounded-xl font-bold text-sm transition-colors border border-white/5">
                    Edit Profile
                </button>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <div class="glass p-6 md:p-10 rounded-[2.5rem] relative z-10">
                    <h3 class="text-xl font-bold text-white mb-6">Learning Statistics</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                        <div class="bg-[#0f172a] p-4 md:p-5 rounded-2xl border border-white/5">
                            <p class="text-slate-500 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-2">XP
                                Earned</p>
                            <p class="text-2xl md:text-3xl font-black text-cyan-400">0</p>
                        </div>
                        <div class="bg-[#0f172a] p-4 md:p-5 rounded-2xl border border-white/5">
                            <p class="text-slate-500 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-2">
                                Hours</p>
                            <p class="text-2xl md:text-3xl font-black text-cyan-400">0.0</p>
                        </div>
                        <div class="bg-[#0f172a] p-4 md:p-5 rounded-2xl border border-white/5 col-span-2 md:col-span-1">
                            <p class="text-slate-500 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-2">
                                Certificates</p>
                            <p class="text-2xl md:text-3xl font-black text-cyan-400">0</p>
                        </div>
                    </div>
                </div>

                <!-- Account Details -->
                <div class="glass p-10 rounded-[2.5rem]">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-cyan-500 rounded-full"></span>
                            Personal Information
                        </h3>
                    </div>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div
                            class="p-6 bg-[#0f172a] rounded-[2rem] border border-white/5 relative group overflow-hidden">
                            <div
                                class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-12 h-12 text-cyan-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] mb-3">Gender
                                Identity</p>
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                                <p class="text-white font-bold text-lg capitalize">
                                    <?php echo htmlspecialchars($user['gender'] ?: 'Not specified'); ?></p>
                            </div>
                        </div>

                        <div
                            class="p-6 bg-[#0f172a] rounded-[2rem] border border-white/5 relative group overflow-hidden">
                            <div
                                class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] mb-3">Primary
                                Address</p>
                            <p class="text-white font-bold leading-relaxed">
                                <?php echo nl2br(htmlspecialchars($user['address'] ?: 'No address provided')); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="glass p-10 rounded-[2.5rem]">
                    <h3 class="text-xl font-bold text-white mb-6">Account Status</h3>
                    <div
                        class="flex items-center justify-between p-6 bg-[#0f172a] rounded-[2rem] border border-white/5">
                        <div class="flex items-center gap-5">
                            <div
                                class="w-14 h-14 bg-green-500/10 rounded-2xl flex items-center justify-center text-green-400 border border-green-500/20">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-black text-lg">Verified Account</p>
                                <p class="text-slate-500 text-xs font-medium tracking-wide">Standard Learning Access</p>
                            </div>
                        </div>
                        <button
                            class="px-6 py-3 bg-white/5 hover:bg-white/10 text-cyan-400 text-xs font-black uppercase tracking-widest rounded-xl transition-all border border-white/10">
                            Upgrade Pro
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Profile Modal -->
    <div id="edit-modal"
        class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6 backdrop-blur-md bg-black/50">
        <div
            class="glass max-w-lg w-full p-8 rounded-[2.5rem] relative z-10 animate-in fade-in zoom-in duration-300 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-black text-white">Edit Profile</h3>
                <button onclick="toggleModal()" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">Full
                        Name</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>"
                        class="w-full bg-[#0f172a] border border-white/5 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-cyan-500 transition-colors"
                        required>
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">Gender</label>
                    <select name="gender"
                        class="w-full bg-[#0f172a] border border-white/5 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-cyan-500 transition-colors appearance-none">
                        <option value="">Select Gender</option>
                        <option value="male" <?php echo ($user['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo ($user['gender'] === 'female') ? 'selected' : ''; ?>>Female
                        </option>
                        <option value="other" <?php echo ($user['gender'] === 'other') ? 'selected' : ''; ?>>Other
                        </option>
                    </select>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">Address</label>
                    <textarea name="address" rows="3"
                        class="w-full bg-[#0f172a] border border-white/5 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-cyan-500 transition-colors resize-none"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>

                <!-- Profile Photo -->
                <div>
                    <label class="block text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">Profile
                        Photo</label>
                    <div class="flex items-center gap-4">
                        <div id="photo-preview-container"
                            class="w-12 h-12 rounded-xl border border-white/5 overflow-hidden flex-shrink-0 bg-white/5">
                            <?php if (!empty($user['profile_photo'])): ?>
                                <img id="photo-preview" src="<?php echo htmlspecialchars($user['profile_photo']); ?>"
                                    class="w-full h-full object-cover">
                            <?php else: ?>
                                <img id="photo-preview"
                                    src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?php echo urlencode($user['full_name']); ?>"
                                    class="w-full h-full object-cover bg-indigo-100">
                            <?php endif; ?>
                        </div>
                        <input type="file" name="profile_photo" accept="image/*" onchange="previewImage(this)"
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-white/5 file:text-cyan-400 hover:file:bg-white/10 cursor-pointer">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-cyan-500 hover:bg-cyan-600 text-[#020617] py-4 rounded-xl font-black text-sm transition-all shadow-lg shadow-cyan-500/20">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>

    <script>
        function toggleModal() {
            const modal = document.getElementById('edit-modal');
            modal.classList.toggle('hidden');
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('photo-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>