<?php
session_start();
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$analysis_result = null;
$error_message = "";
$is_latest = false;

// Handle Resume Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['resume'])) {
    $upload_dir = 'uploads/resumes/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = time() . '_' . basename($_FILES['resume']['name']);
    $target_file = $upload_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($file_type != "pdf") {
        $error_message = "Only PDF files are allowed.";
    } else {
        if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_file)) {
            // Call Python AI Bridge
            $python_path = "python";
            $script_path = "scripts/ai_processor.py";
            $abs_pdf_path = realpath($target_file);

            $command = escapeshellcmd("$python_path $script_path \"$abs_pdf_path\"");
            $output = shell_exec($command);
            $response = json_decode($output, true);

            if (isset($response['error'])) {
                $error_message = "AI Analysis Error: " . $response['error'];
            } else {
                // Store in database with new schema
                $detailed_analysis = json_encode([
                    'candidate_name' => $response['candidate_name'] ?? 'Candidate',
                    'contact_info' => $response['contact_info'] ?? [],
                    'health_checks' => $response['health_checks'] ?? [],
                    'job_fit' => $response['job_fit'] ?? [],
                    'skills_found' => $response['skills_found'] ?? [],
                    'projects' => $response['projects'] ?? []
                ]);

                $stmt = $pdo->prepare("INSERT INTO resume_analysis (user_id, resume_path, ai_summary, ats_score, detailed_analysis, recommended_jobs, interview_prep) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $user_id,
                    $target_file,
                    $response['summary'] ?? '',
                    $response['ats_compatibility'] ?? 0,
                    $detailed_analysis,
                    json_encode($response['recommended_jobs'] ?? []),
                    json_encode($response['interview_prep'] ?? [])
                ]);

                $analysis_id = $pdo->lastInsertId();
                header("Location: resume_analyzer.php?analysis_id=$analysis_id");
                exit();
            }
        } else {
            $error_message = "Error uploading file.";
        }
    }
}

// Fetch existing analysis IF we aren't asking for a "new" upload
if (!isset($_GET['new'])) {
    if (isset($_GET['analysis_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM resume_analysis WHERE id = ? AND user_id = ?");
        $stmt->execute([$_GET['analysis_id'], $user_id]);
        $analysis_result = $stmt->fetch();
    } else {
        // Fetch latest
        $stmt = $pdo->prepare("SELECT * FROM resume_analysis WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$user_id]);
        $analysis_result = $stmt->fetch();
        $is_latest = true;
    }
}

// Parse rich data
$data = [
    'candidate_name' => 'Candidate',
    'contact_info' => [],
    'ats_score' => 0,
    'summary' => '',
    'health_checks' => [],
    'job_fit' => [],
    'skills_found' => [],
    'projects' => [],
    'recommended_jobs' => [],
    'interview_prep' => []
];

if ($analysis_result) {
    $data['ats_score'] = $analysis_result['ats_score'] ?? 0;
    $data['summary'] = $analysis_result['ai_summary'] ?? '';

    if (!empty($analysis_result['detailed_analysis'])) {
        $detailed = json_decode($analysis_result['detailed_analysis'], true) ?: [];
        $data['candidate_name'] = $detailed['candidate_name'] ?? 'Candidate';
        $data['contact_info'] = $detailed['contact_info'] ?? [];
        $data['health_checks'] = $detailed['health_checks'] ?? [];
        $data['job_fit'] = $detailed['job_fit'] ?? [];
        $data['skills_found'] = $detailed['skills_found'] ?? [];
        $data['projects'] = $detailed['projects'] ?? [];
    }

    if (!empty($analysis_result['recommended_jobs'])) {
        $data['recommended_jobs'] = json_decode($analysis_result['recommended_jobs'], true) ?: [];
    }

    if (!empty($analysis_result['interview_prep'])) {
        $data['interview_prep'] = json_decode($analysis_result['interview_prep'], true) ?: [];
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Dashboard - Openly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #020617;
            color: #f8fafc;
        }

        .dashboard-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 2rem;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            border-color: #22d3ee;
        }

        .gradient-cyan {
            background: linear-gradient(to right, #22d3ee, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
    </style>
</head>

<body class="min-h-screen pt-24 pb-12">
    <?php include 'components/navbar.php'; ?>

    <main class="max-w-[1400px] mx-auto px-6">
        <?php if (!$analysis_result): ?>
            <!-- Initial Upload State -->
            <div class="max-w-xl mx-auto mt-20">
                <div class="dashboard-card p-12 text-center">
                    <div class="w-24 h-24 bg-cyan-500/10 rounded-3xl flex items-center justify-center mx-auto mb-8">
                        <svg class="w-12 h-12 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-black mb-4">Launch AI Analysis</h2>
                    <p class="text-slate-500 mb-10">Upload your PDF resume to generate your professional career dashboard.
                    </p>

                    <?php if ($error_message): ?>
                        <div class="bg-rose-500/10 border border-rose-500/20 text-rose-500 p-4 rounded-xl mb-8">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <form action="resume_analyzer.php" method="POST" enctype="multipart/form-data">
                        <label class="block cursor-pointer mb-6">
                            <div
                                class="border-2 border-dashed border-slate-800 rounded-3xl p-10 hover:border-cyan-500 transition-all">
                                <span class="text-slate-400 font-bold" id="file-name">Select PDF Resume</span>
                                <input type="file" name="resume" class="hidden" accept=".pdf" required
                                    onchange="document.getElementById('file-name').innerText = this.files[0].name">
                            </div>
                        </label>
                        <button type="submit"
                            class="w-full py-5 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-[1.5rem] font-black shadow-xl shadow-cyan-500/20 active:scale-95 transition-all">
                            Analyze Now
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- Dashboard State -->
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between mb-12 gap-8">
                <div>
                    <h1 class="text-4xl font-extrabold flex items-center gap-3 mb-2">
                        Career <span class="gradient-cyan">Analytics</span>
                    </h1>
                    <p class="text-slate-500 font-medium">Detailed professional assessment for <span class="text-white font-bold"><?php echo htmlspecialchars($data['candidate_name']); ?></span></p>
                </div>
                <div class="flex flex-wrap gap-4">
                    <?php if (!empty($data['contact_info']['email'])): ?>
                        <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-xl border border-white/5">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="text-xs font-bold text-slate-300"><?php echo htmlspecialchars($data['contact_info']['email']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($data['contact_info']['phone'])): ?>
                        <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-xl border border-white/5">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span class="text-xs font-bold text-slate-300"><?php echo htmlspecialchars($data['contact_info']['phone']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($data['contact_info']['location'])): ?>
                        <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-xl border border-white/5">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-xs font-bold text-slate-300"><?php echo htmlspecialchars($data['contact_info']['location']); ?></span>
                        </div>
                    <?php endif; ?>
                    <a href="resume_analyzer.php?new=1"
                        class="px-6 py-3 bg-white/5 rounded-xl text-sm font-bold hover:bg-white/10 transition-all border border-white/5">Re-upload</a>
                    <a href="interview_prep.php"
                        class="px-6 py-3 bg-cyan-500 text-[#020617] rounded-xl text-sm font-bold hover:opacity-90 transition-all">Back</a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left: Main Stats -->
                <div class="lg:col-span-12 grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <!-- ATS Score -->
                    <div class="dashboard-card p-8 flex flex-col items-center justify-center text-center">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-6">ATS Compatibility</h3>
                        <div
                            class="relative w-28 h-28 flex items-center justify-center rounded-full border-4 border-cyan-500/20">
                            <span class="text-3xl font-black text-white"><?php echo $data['ats_score']; ?>%</span>
                        </div>
                    </div>

                    <!-- Health Check Grid -->
                    <div class="md:col-span-3 dashboard-card p-8">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-6">Category Health</h3>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <?php foreach ($data['health_checks'] as $cat => $val):
                                $color = $val === 'Strong' ? 'text-emerald-400' : ($val === 'Moderate' ? 'text-amber-400' : 'text-rose-400');
                                ?>
                                <div class="bg-white/5 p-4 rounded-2xl text-center">
                                    <p class="text-[9px] font-bold text-slate-500 uppercase mb-2"><?php echo $cat; ?></p>
                                    <span class="<?php echo $color; ?> text-xs font-black"><?php echo $val; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Middle: Summary & Questions -->
                <div class="lg:col-span-8 space-y-8">
                    <!-- Summary -->
                    <div class="dashboard-card p-10 bg-gradient-to-br from-cyan-500/5 to-transparent">
                        <h3 class="text-sm font-black uppercase tracking-widest text-cyan-400 mb-6">Executive Summary</h3>
                        <p class="text-xl text-slate-200 leading-relaxed italic font-medium">
                            "<?php echo htmlspecialchars($data['summary']); ?>"</p>
                    </div>

                    <!-- Interview Prep -->
                    <div class="dashboard-card p-10">
                        <h3 class="text-xl font-bold mb-8">Advanced Interview Preparation</h3>
                        <div class="space-y-6 max-h-[600px] overflow-y-auto pr-4 custom-scrollbar">
                            <?php foreach ($data['interview_prep'] as $index => $item): ?>
                                <div class="border-b border-white/5 pb-6 last:border-0 group">
                                    <div class="flex gap-4 mb-4">
                                        <span
                                            class="text-2xl font-black opacity-10 italic"><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></span>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span
                                                    class="text-[10px] font-black uppercase text-cyan-500"><?php echo htmlspecialchars($item['type'] ?? 'General'); ?></span>
                                            </div>
                                            <p class="text-white font-bold group-hover:text-cyan-400 transition-colors">
                                                <?php echo htmlspecialchars($item['question']); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <details class="ml-10">
                                        <summary
                                            class="text-xs font-bold text-slate-500 cursor-pointer hover:text-white uppercase tracking-widest outline-none">
                                            View Answer Guide</summary>
                                        <div
                                            class="mt-4 p-5 bg-white/5 rounded-2xl text-sm text-slate-400 leading-relaxed border border-white/5">
                                            <?php echo nl2br(htmlspecialchars($item['answer'])); ?>
                                        </div>
                                    </details>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Smart Job Matches -->
                    <?php if (!empty($data['recommended_jobs'])): ?>
                        <div class="dashboard-card p-10 animate-fade-in" style="animation-delay: 0.5s">
                            <h3 class="text-lg font-bold mb-8 flex items-center gap-2">🚀 Smart Job Matches</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <?php foreach ($data['recommended_jobs'] as $job): ?>
                                    <div
                                        class="p-6 rounded-2xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 group hover:border-cyan-500 transition-all">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="font-bold text-lg text-white">
                                                <?php echo htmlspecialchars($job['title']); ?>
                                            </h4>
                                            <span
                                                class="px-3 py-1 bg-cyan-500/10 text-cyan-400 text-[10px] font-black rounded-lg border border-cyan-500/10">
                                                <?php echo $job['match_score']; ?>% Match
                                            </span>
                                        </div>
                                        <p class="text-slate-500 text-sm mb-6 leading-relaxed">
                                            <?php echo htmlspecialchars($job['why_fit']); ?>
                                        </p>
                                        <div class="flex gap-3">
                                            <a href="https://www.linkedin.com/jobs/search/?keywords=<?php echo urlencode($job['title']); ?>"
                                                target="_blank"
                                                class="flex-1 py-3 bg-white text-black text-center rounded-xl font-bold text-xs hover:bg-slate-200 transition-all">Search
                                                LinkedIn</a>
                                            <a href="https://www.indeed.com/jobs?q=<?php echo urlencode($job['title']); ?>"
                                                target="_blank"
                                                class="flex-1 py-3 bg-slate-800 text-white text-center rounded-xl font-bold text-xs hover:bg-slate-700 transition-all">Search
                                                Indeed</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right Column -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Job Fit -->
                    <?php if (!empty($data['job_fit'])): ?>
                        <div class="dashboard-card p-8 animate-fade-in" style="animation-delay: 0.5s">
                            <h3 class="text-lg font-bold mb-8 flex items-center gap-2">🎯 Job Fit</h3>
                            <div class="space-y-8">
                                <?php foreach ($data['job_fit'] as $role => $fit): ?>
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center text-xs font-bold">
                                            <span class="text-slate-600 dark:text-slate-400">
                                                <?php echo $role; ?>
                                            </span>
                                            <span class="text-slate-800 dark:text-white">
                                                <?php echo $fit; ?>%
                                            </span>
                                        </div>
                                        <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all duration-1000"
                                                style="width: <?php echo $fit; ?>%"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Skills Found -->
                    <?php if (!empty($data['skills_found'])): ?>
                        <div class="dashboard-card p-8 animate-fade-in" style="animation-delay: 0.6s">
                            <h3 class="text-lg font-bold mb-8 flex items-center gap-2">🔍 Skills Found</h3>
                            <div class="space-y-6">
                                <?php foreach ($data['skills_found'] as $category => $skills): ?>
                                    <div class="space-y-3">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                            <?php echo $category; ?>
                                        </p>
                                        <div class="flex flex-wrap gap-2">
                                            <?php foreach ($skills as $skill): ?>
                                                <span
                                                    class="px-3 py-1.5 bg-indigo-500/10 text-indigo-400 text-xs font-bold rounded-lg border border-indigo-500/10">
                                                    <?php echo htmlspecialchars($skill); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'components/footer.php'; ?>

    <script>
        function toggleAnswer(id) {
            const answer = document.getElementById(`answer-${id}`);
            answer.classList.toggle('hidden');
        }

        window.addEventListener('load', () => {
            const progress = document.querySelector('.circular-progress');
            if (progress) {
                const percent = progress.style.getPropertyValue('--percent');
                progress.offsetHeight;
            }
        });
    </script>
</body>

</html>