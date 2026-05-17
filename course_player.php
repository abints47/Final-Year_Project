<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'includes/db.php';
require_once 'includes/courses_data.php';

$course_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$user_id = $_SESSION['user_id'];

// Check if user is enrolled
try {
    $stmt = $pdo->prepare("SELECT e.*, c.title, c.youtube_id, c.video_path, c.image, c.lessons, c.what_you_learn, c.resources, c.full_summary, c.playlist_videos 
                           FROM enrollments e 
                           JOIN courses c ON e.course_id = c.id 
                           WHERE e.user_id = ? AND e.course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    $enrollment = $stmt->fetch();

    if (!$enrollment) {
        header("Location: course_detail.php?id=$course_id");
        exit();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Decode JSON
if (is_string($enrollment['what_you_learn'])) {
    $enrollment['what_you_learn'] = json_decode($enrollment['what_you_learn'], true) ?: [];
}

// Robust YouTube ID extraction
function get_yt_id($url)
{
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    return $url;
}

// Decode playlist
$playlist_raw = json_decode($enrollment['playlist_videos'] ?? '[]', true) ?: [];
$playlist_ids = [];
if (!empty($playlist_raw)) {
    foreach ($playlist_raw as $url_or_id) {
        $ytid = get_yt_id($url_or_id);
        if ($ytid)
            $playlist_ids[] = $ytid;
    }
} else {
    // Fallback to single ID
    if (!empty($enrollment['youtube_id'])) {
        $playlist_ids[] = get_yt_id($enrollment['youtube_id']);
    }
}
$yt_id = !empty($playlist_ids) ? $playlist_ids[0] : '';

// Decode resources
if (is_string($enrollment['resources'])) {
    $resources_list = json_decode($enrollment['resources'], true) ?: [];
} else {
    $resources_list = $enrollment['resources'] ?: [];
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning:
        <?php echo htmlspecialchars($enrollment['title']); ?> - Openly
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-[#020617] text-white min-h-screen flex flex-col pt-20">
    <?php include 'components/navbar.php'; ?>

    <main class="flex-1 flex flex-col lg:flex-row h-full overflow-hidden">
        <!-- Main Content (Video) -->
        <div class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="max-w-5xl mx-auto space-y-8">
                <!-- Video Player Container -->
                <div
                    class="bg-black/50 rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl aspect-video relative">
                    <?php if (!empty($enrollment['video_path'])): ?>
                        <video class="w-full h-full object-cover" controls>
                            <source src="<?php echo htmlspecialchars($enrollment['video_path']); ?>" type="video/mp4">
                        </video>
                    <?php elseif (!empty($yt_id)): ?>
                        <iframe id="main-video-player"
                            src="https://www.youtube.com/embed/<?php echo $yt_id; ?>?rel=0&modestbranding=1"
                            class="absolute inset-0 w-full h-full border-0" allowfullscreen allow="autoplay">
                        </iframe>
                    <?php endif; ?>
                </div>

                <!-- Info Header -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <h1 class="text-3xl md:text-4xl font-black tracking-tight">
                        <?php echo htmlspecialchars($enrollment['title']); ?>: Getting Started
                    </h1>
                    <button id="mark-done-btn"
                        class="shrink-0 px-6 py-3 bg-cyan-500 hover:bg-cyan-400 text-black font-black rounded-xl transition-all active:scale-95 flex items-center gap-2 <?php echo $enrollment['progress'] >= 100 ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                        <?php echo $enrollment['progress'] >= 100 ? 'disabled' : ''; ?>>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        <span><?php echo $enrollment['progress'] >= 100 ? 'Completed' : 'Mark as Done'; ?></span>
                    </button>
                </div>
            </div>

            <!-- Tabs (About, Resources, Notes) -->
            <div class="border-b border-white/10 flex gap-8" id="tabs-header">
                <button data-tab="overview"
                    class="tab-btn pb-4 text-white font-bold border-b-2 border-cyan-500 text-sm md:text-base">Overview</button>
                <button data-tab="resources"
                    class="tab-btn pb-4 text-slate-500 font-bold text-sm md:text-base hover:text-white transition-colors">Resources</button>
                <button data-tab="notes"
                    class="tab-btn pb-4 text-slate-500 font-bold text-sm md:text-base hover:text-white transition-colors">Notes</button>
            </div>

            <div id="tab-content" class="py-4">
                <!-- Overview Tab -->
                <div id="overview-content" class="tab-pane space-y-6">
                    <h3 class="text-xl font-bold">What you'll learn in this module</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($enrollment['what_you_learn'] as $point): ?>
                            <div class="flex gap-3 items-start p-4 bg-white/5 rounded-2xl border border-white/5">
                                <svg class="w-5 h-5 text-cyan-400 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-slate-300 font-medium text-sm">
                                    <?php echo htmlspecialchars($point); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Resources Tab -->
                <div id="resources-content" class="tab-pane hidden space-y-6">
                    <h3 class="text-xl font-bold text-white">Course Resources</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <?php if (empty($resources_list)): ?>
                            <p class="text-slate-500">No resources available for this course.</p>
                        <?php else: ?>
                            <?php foreach ($resources_list as $res): ?>
                                <a href="<?php echo htmlspecialchars($res['url'] ?? '#'); ?>" target="_blank"
                                    class="flex items-center justify-between p-6 bg-white/5 rounded-2xl border border-white/5 hover:border-cyan-500/50 hover:bg-white/10 transition-all group">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-cyan-500/10 flex items-center justify-center text-cyan-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">
                                                <?php echo htmlspecialchars($res['name'] ?? 'Resource'); ?>
                                            </p>
                                            <p class="text-xs text-slate-500">Click to open or download</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-600 group-hover:text-cyan-400 transition-colors" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Notes Tab -->
                <div id="notes-content" class="tab-pane hidden space-y-6">
                    <h3 class="text-xl font-bold text-white">Curriculum & Notes</h3>
                    <div
                        class="prose prose-invert max-w-none bg-white/5 p-8 rounded-3xl border border-white/5 text-slate-300 leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($enrollment['full_summary'] ?: 'No detailed notes available yet.')); ?>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Sidebar (Lesson Navigation) -->
        <div class="w-full lg:w-96 bg-[#0f172a] border-l border-white/5 flex flex-col h-full lg:h-[calc(100vh-5rem)]">
            <div class="p-6 border-b border-white/5">
                <h3 class="font-black text-white uppercase tracking-widest text-sm">Course Content</h3>
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex-1 h-1.5 bg-white/5 rounded-full overflow-hidden mr-4">
                        <div class="h-full bg-cyan-500" style="width: <?php echo $enrollment['progress']; ?>%"></div>
                    </div>
                    <span class="text-xs font-black text-cyan-400">
                        <?php echo $enrollment['progress']; ?>%
                    </span>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto">
                <?php
                $lessons_count = count($playlist_ids) > 0 ? count($playlist_ids) : (intval($enrollment['lessons']) ?: 1);
                for ($i = 0; $i < $lessons_count; $i++):
                    $is_first = ($i == 0);
                    $current_id = $playlist_ids[$i] ?? '';
                    ?>
                    <div class="lesson-item p-6 flex items-center gap-4 hover:bg-white/5 cursor-pointer border-b border-white/[0.05] group <?php echo $is_first ? 'bg-white/5 active-lesson' : ''; ?>"
                        data-yt-id="<?php echo htmlspecialchars($current_id); ?>">
                        <div
                            class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center shrink-0 group-hover:bg-cyan-500/10 group-hover:border-cyan-500/20 transition-all icon-container">
                            <?php if ($is_first): ?>
                                <svg class="w-5 h-5 text-cyan-400 play-icon" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                            <?php else: ?>
                                <span
                                    class="text-xs font-black text-slate-500 group-hover:text-cyan-400 number-icon"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Part
                                <?php echo $i + 1; ?></span>
                            <span
                                class="text-sm font-bold title-text <?php echo $is_first ? 'text-white' : 'text-slate-400'; ?> group-hover:text-white transition-colors">
                                Video Lesson Section
                            </span>
                        </div>
                        <div class="ml-auto status-badge <?php echo $is_first ? '' : 'hidden'; ?>">
                            <span
                                class="px-2 py-0.5 bg-cyan-500/10 text-cyan-400 text-[8px] font-black uppercase rounded">Playing</span>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <script>
            // Tab Switching Logic
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tabName = btn.getAttribute('data-tab');

                    // Update header
                    document.querySelectorAll('.tab-btn').forEach(b => {
                        b.classList.remove('text-white', 'border-b-2', 'border-cyan-500');
                        b.classList.add('text-slate-500');
                    });
                    btn.classList.add('text-white', 'border-b-2', 'border-cyan-500');
                    btn.classList.remove('text-slate-500');

                    // Update content
                    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));
                    document.getElementById(`${tabName}-content`).classList.remove('hidden');
                });
            });

            // Lesson Video Switching Logic
            const lessonItems = document.querySelectorAll('.lesson-item');
            const videoIframe = document.getElementById('main-video-player');

            if (lessonItems.length > 0 && videoIframe) {
                lessonItems.forEach((item, index) => {
                    item.addEventListener('click', () => {
                        const ytId = item.getAttribute('data-yt-id');
                        if (ytId) {
                            videoIframe.src = `https://www.youtube.com/embed/${ytId}?rel=0&modestbranding=1&autoplay=1`;

                            // Visual updates
                            lessonItems.forEach((btn, idx) => {
                                btn.classList.remove('bg-white/5', 'active-lesson');
                                const numStr = String(idx + 1).padStart(2, '0');
                                btn.querySelector('.icon-container').innerHTML = `<span class="text-xs font-black text-slate-500 group-hover:text-cyan-400 number-icon">${numStr}</span>`;
                                btn.querySelector('.title-text').classList.remove('text-white');
                                btn.querySelector('.title-text').classList.add('text-slate-400');
                                btn.querySelector('.status-badge').classList.add('hidden');
                            });

                            item.classList.add('bg-white/5', 'active-lesson');
                            item.querySelector('.icon-container').innerHTML = `<svg class="w-5 h-5 text-cyan-400 play-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>`;
                            item.querySelector('.title-text').classList.remove('text-slate-400');
                            item.querySelector('.title-text').classList.add('text-white');
                            item.querySelector('.status-badge').classList.remove('hidden');
                        }
                    });
                });
            }

            // Mark as Done Logic
            const markDoneBtn = document.getElementById('mark-done-btn');
            if (markDoneBtn) {
                markDoneBtn.addEventListener('click', async () => {
                    const courseId = new URLSearchParams(window.location.search).get('id');

                    markDoneBtn.disabled = true;
                    markDoneBtn.querySelector('span').innerText = 'Updating...';

                    try {
                        const response = await fetch('api/update_progress.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ course_id: courseId })
                        });

                        const result = await response.json();
                        if (result.success) {
                            markDoneBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            markDoneBtn.querySelector('span').innerText = 'Completed';
                            // Update sidebar progress bar if needed
                            const progressBar = document.querySelector('.bg-cyan-500');
                            const progressText = document.querySelector('.text-cyan-400.text-xs');
                            if (progressBar) progressBar.style.width = '100%';
                            if (progressText) progressText.innerText = '100%';
                        } else {
                            alert('Error updating progress: ' + result.message);
                            markDoneBtn.disabled = false;
                            markDoneBtn.querySelector('span').innerText = 'Mark as Done';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        markDoneBtn.disabled = false;
                        markDoneBtn.querySelector('span').innerText = 'Mark as Done';
                    }
                });
            }
        </script>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>