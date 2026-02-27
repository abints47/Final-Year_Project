<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'includes/db.php';
require_once 'includes/courses_data.php';

$error = '';
$success = '';

// Handle deletion (example administrative action)
if (isset($_GET['delete_course'])) {
    $course_id = (int) $_GET['delete_course'];
    try {
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$course_id]);
        $success = "Course deleted successfully.";
        // Refresh local data
        header("Location: admin_panel.php?success=" . urlencode($success));
        exit();
    } catch (PDOException $e) {
        $error = "Failed to delete course: " . $e->getMessage();
    }
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$all_users = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Openly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .table-row {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: background 0.3s ease;
        }

        .table-row:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-student {
            background: rgba(34, 211, 238, 0.1);
            color: #22d3ee;
        }

        .badge-teacher {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .badge-admin {
            background: rgba(244, 63, 94, 0.1);
            color: #f43f5e;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col pt-24 pb-12">
    <?php include 'components/navbar.php'; ?>

    <main class="max-w-7xl mx-auto px-6 w-full">
        <div class="mb-12">
            <h1 class="text-5xl font-black text-white tracking-tight">Admin <span
                    class="bg-gradient-to-r from-rose-400 to-orange-500 bg-clip-text text-transparent">Panel</span></h1>
            <p class="text-slate-400 mt-2">Manage users, courses, and platform settings.</p>
        </div>

        <?php if (isset($_GET['success'])):
            $success = $_GET['success']; ?>
            <div
                class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-200 text-sm rounded-2xl text-center">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- User Management -->
            <div class="lg:col-span-2 glass p-8 rounded-[2.5rem] shadow-2xl overflow-hidden">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    User Management
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-slate-500 text-[10px] font-black uppercase tracking-widest border-b border-white/10">
                                <th class="pb-4 px-4">User</th>
                                <th class="pb-4 px-4">Role</th>
                                <th class="pb-4 px-4">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php foreach ($all_users as $user): ?>
                                <tr class="table-row">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-white font-bold text-xs">
                                                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <p class="text-white font-bold">
                                                    <?php echo htmlspecialchars($user['full_name']); ?>
                                                </p>
                                                <p class="text-slate-500 text-xs">
                                                    <?php echo htmlspecialchars($user['email_address']); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="badge badge-<?php echo $user['role']; ?>">
                                            <?php echo $user['role']; ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-400 text-xs">
                                        <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Course Control -->
            <div class="glass p-8 rounded-[2.5rem] shadow-2xl">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Recent Courses
                </h2>
                <div class="space-y-4">
                    <?php foreach ($all_courses as $course): ?>
                        <div
                            class="p-4 rounded-2xl bg-white/5 border border-white/5 flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg overflow-hidden border border-white/10">
                                    <img src="<?php echo $course['image']; ?>" class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <p class="text-white font-bold text-xs truncate w-32">
                                        <?php echo htmlspecialchars($course['title']); ?>
                                    </p>
                                    <p class="text-slate-500 text-[10px] uppercase font-bold">
                                        <?php echo $course['category']; ?>
                                    </p>
                                </div>
                            </div>
                            <a href="admin_panel.php?delete_course=<?php echo $course['id']; ?>"
                                onclick="return confirm('Are you sure you want to delete this course?')"
                                class="text-rose-500 hover:text-rose-400 transition-colors opacity-0 group-hover:opacity-100 p-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>