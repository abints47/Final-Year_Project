<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/courses_data.php';

// Filtering Logic
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'All';
$level = isset($_GET['level']) ? $_GET['level'] : 'All';

$filtered_courses = array_filter($all_courses, function ($course) use ($search, $category, $level) {
    $matches_search = empty($search) || stripos($course['title'], $search) !== false || stripos($course['author'], $search) !== false;
    $matches_category = ($category === 'All') || $course['category'] === $category;
    $matches_level = ($level === 'All') || $course['level'] === $level;
    return $matches_search && $matches_category && $matches_level;
});
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Courses - ArjunTechy</title>
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

        .gradient-purple {
            background: linear-gradient(to right, #c084fc, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gradient-courses {
            background: linear-gradient(to right, #22d3ee, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .course-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .course-card:hover {
            transform: translateY(-5px);
            border-color: rgba(34, 211, 238, 0.3);
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
</head>

<body class="min-h-screen flex flex-col pt-24">
    <!-- Background Auras -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="aura bg-cyan-600 w-96 h-96 top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="aura bg-purple-600 w-96 h-96 bottom-0 right-0 translate-x-1/2 translate-y-1/2"></div>
    </div>

    <?php include 'components/navbar.php'; ?>

    <main class="max-w-7xl mx-auto px-6 py-12 w-full relative z-10">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-5xl md:text-6xl font-black text-white mb-4 tracking-tight">
                Explore Our <span class="gradient-courses">Courses</span>
            </h1>
            <p class="text-slate-400 text-lg">Discover courses that will take your skills to the next level</p>
        </div>

        <!-- Search & Filter Bar -->
        <div class="glass rounded-3xl p-8 mb-12 shadow-2xl">
            <form action="courses.php" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Search -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest pl-1">Search</label>
                    <div class="relative">
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                            placeholder="Search courses..."
                            class="w-full bg-[#020617] border border-white/10 rounded-xl px-5 py-3 text-white placeholder:text-slate-600 focus:outline-none focus:border-cyan-500/50 transition-colors" />
                    </div>
                </div>

                <!-- Category -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest pl-1">Category</label>
                    <select name="category" onchange="this.form.submit()"
                        class="w-full bg-[#020617] border border-white/10 rounded-xl px-5 py-3 text-white focus:outline-none focus:border-cyan-500/50 transition-colors appearance-none">
                        <option value="All" <?php echo $category === 'All' ? 'selected' : ''; ?>>All Categories</option>
                        <option value="Programming" <?php echo $category === 'Programming' ? 'selected' : ''; ?>>
                            Programming</option>
                        <option value="Web Dev" <?php echo $category === 'Web Dev' ? 'selected' : ''; ?>>Web Development
                        </option>
                        <option value="AI/ML" <?php echo $category === 'AI/ML' ? 'selected' : ''; ?>>AI & Machine
                            Learning</option>
                    </select>
                </div>

                <!-- Level -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest pl-1">Level</label>
                    <select name="level" onchange="this.form.submit()"
                        class="w-full bg-[#020617] border border-white/10 rounded-xl px-5 py-3 text-white focus:outline-none focus:border-cyan-500/50 transition-colors appearance-none">
                        <option value="All" <?php echo $level === 'All' ? 'selected' : ''; ?>>All Levels</option>
                        <option value="Beginner" <?php echo $level === 'Beginner' ? 'selected' : ''; ?>>Beginner</option>
                        <option value="Intermediate" <?php echo $level === 'Intermediate' ? 'selected' : ''; ?>>
                            Intermediate</option>
                        <option value="Advanced" <?php echo $level === 'Advanced' ? 'selected' : ''; ?>>Advanced</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Results Info -->
        <div class="mb-8">
            <p class="text-slate-400 font-medium">Showing <span class="text-white">
                    <?php echo count($filtered_courses); ?>
                </span> courses</p>
        </div>

        <!-- Course Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($filtered_courses)): ?>
                <div class="col-span-full py-20 text-center">
                    <h3 class="text-2xl text-slate-500 font-bold">No courses found matching your criteria.</h3>
                </div>
            <?php else: ?>
                <?php foreach ($filtered_courses as $course): ?>
                    <a href="course_detail.php?id=<?php echo $course['id']; ?>"
                        class="course-card glass rounded-[2.5rem] overflow-hidden flex flex-col group h-full">
                        <!-- Image Area -->
                        <div class="h-56 relative overflow-hidden">
                            <img src="<?php echo $course['image']; ?>" alt="<?php echo $course['title']; ?>"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[#020617] via-transparent to-transparent opacity-60">
                            </div>

                            <!-- Badges -->
                            <div class="absolute top-6 left-6 flex flex-col gap-2">
                                <span
                                    class="bg-cyan-500/90 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border border-white/10">
                                    <?php echo $course['level']; ?>
                                </span>
                            </div>
                        </div>

                        <!-- Content Area -->
                        <div class="p-8 flex-1 flex flex-col">
                            <span class="text-[10px] font-black text-cyan-400 uppercase tracking-[0.2em] mb-3 block">
                                <?php echo strtoupper($course['category']); ?>
                            </span>
                            <h3
                                class="text-2xl font-bold text-white mb-2 leading-tight group-hover:text-cyan-300 transition-colors">
                                <?php echo $course['title']; ?>
                            </h3>
                            <p class="text-slate-500 text-sm mb-6 font-medium">by <span class="text-slate-300">
                                    <?php echo $course['author']; ?>
                                </span></p>

                            <!-- Stats -->
                            <div class="flex items-center gap-6 mt-auto pt-6 border-t border-white/5">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="text-sm font-bold text-white">
                                        <?php echo $course['rating']; ?>
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span class="text-sm font-semibold text-slate-400">
                                        <?php echo $course['students']; ?>
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-semibold text-slate-400 text-nowrap">
                                        <?php echo $course['duration']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>