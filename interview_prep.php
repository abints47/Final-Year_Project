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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['resume'])) {
    $upload_dir = 'uploads/resumes/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_error = $_FILES['resume']['error'];
    if ($file_error !== UPLOAD_ERR_OK) {
        $error_message = "Upload failed with error code: $file_error";
    } else {
        $file_name = time() . '_' . basename($_FILES['resume']['name']);
        $target_file = $upload_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($file_type != "pdf") {
            $error_message = "Only PDF files are allowed.";
        } else {
            if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_file)) {
                // Call Python processor
                $python_path = "python"; // Adjust to 'python3' if on linux/mac
                $script_path = "scripts/resume_processor.py";
                $abs_pdf_path = realpath($target_file);

                $command = "$python_path $script_path " . escapeshellarg($abs_pdf_path) . " 2>&1";
                $output = shell_exec($command);

                // Helper to extract JSON from potentially noisy output (like warnings)
                if (!function_exists('extract_json')) {
                    function extract_json($string)
                    {
                        $first_brace = strpos($string, '{');
                        $last_brace = strrpos($string, '}');
                        if ($first_brace !== false && $last_brace !== false) {
                            return substr($string, $first_brace, $last_brace - $first_brace + 1);
                        }
                        return $string;
                    }
                }

                $clean_output = extract_json($output);
                $analysis_result = json_decode($clean_output, true);

                if ($analysis_result === null || isset($analysis_result['error'])) {
                    $error_message = isset($analysis_result['error']) ? $analysis_result['error'] : "Failed to analyze resume. ";
                    // Add raw output for debugging to understand why it failed
                    if ($analysis_result === null) {
                        $error_message .= "Raw Output/JSON Error: <pre class='text-[10px] text-left mt-2 overflow-auto max-h-40'>" . htmlspecialchars($output) . "</pre>";
                    }
                }
            } else {
                $error_message = "Failed to save uploaded file.";
            }
        }
    }
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Prep - Openly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .gradient-cyan {
            background: linear-gradient(to right, #22d3ee, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gradient-btn {
            background: linear-gradient(to right, #22d3ee, #0ea5e9);
        }

        .gradient-border:hover {
            border-color: #22d3ee;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.1);
        }

        .aura {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col pt-24 relative overflow-x-hidden">
    <!-- Background Decor -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="aura bg-cyan-600 w-[500px] h-[500px] top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="aura bg-purple-600 w-[400px] h-[400px] top-1/2 right-0 translate-x-1/2 -translate-y-1/2"></div>
    </div>

    <?php include 'components/navbar.php'; ?>

    <main class="flex-1 w-full max-w-7xl mx-auto px-6 py-12 relative z-10">
        <!-- Header Section -->
        <div class="text-center mb-12 md:mb-16">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-[10px] md:text-xs font-bold mb-6">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                </svg>
                AI-Powered Career Readiness
            </div>
            <h1 class="text-4xl md:text-7xl font-black text-white mb-6 tracking-tight">
                Smart Career <span class="gradient-cyan">Accelerator</span>
            </h1>
            <p class="text-base md:text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed">
                Connect your skills with opportunities using high-impact AI resume analysis and specialized interview
                prep gems.
            </p>
        </div>

        <!-- Error Message -->
        <?php if ($error_message): ?>
            <div class="mb-10 p-6 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-center font-bold">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Smart Resume Analyzer Section -->
        <div class="mb-20">
            <?php if (!$analysis_result): ?>
                <div
                    class="glass p-12 rounded-[3.5rem] border border-white/10 bg-gradient-to-br from-cyan-500/5 to-purple-500/5 relative overflow-hidden group">
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-12">
                        <div class="flex-1 text-center md:text-left">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-black uppercase tracking-widest mb-6">
                                New AI Feature
                            </div>
                            <h2 class="text-4xl font-black text-white mb-6">Neural Resume Insights</h2>
                            <p class="text-slate-400 text-lg leading-relaxed mb-8">
                                Upload your PDF resume to unlock deep behavioral insights, skill mapping, and job fit
                                analysis powered by Gemini 1.5 Pro.
                            </p>
                        </div>
                        <div class="w-full md:w-auto">
                            <form action="" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                                <label
                                    class="w-full md:w-80 h-48 rounded-[2.5rem] border-2 border-dashed border-white/10 hover:border-cyan-500/50 transition-all flex flex-col items-center justify-center cursor-pointer group/upload bg-white/5">
                                    <input type="file" name="resume" accept=".pdf" class="hidden"
                                        onchange="this.form.submit()" required>
                                    <svg class="w-12 h-12 text-slate-500 group-hover/upload:text-cyan-400 transition-colors mb-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="text-slate-400 font-bold">Drop PDF or Click</span>
                                    <span class="text-slate-600 text-xs mt-1">Max 10MB</span>
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Results View -->
                <div class="space-y-10 animate-in fade-in duration-700">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-3xl font-black text-white">Analysis for <span
                                class="gradient-cyan"><?php echo htmlspecialchars($analysis_result['candidate_name'] ?? 'Candidate'); ?></span>
                        </h2>
                        <a href="interview_prep.php"
                            class="text-slate-500 hover:text-white transition-colors text-sm font-bold flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            New Analysis
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Left Column: Summary & Contact -->
                        <div class="md:col-span-1 space-y-8">
                            <div class="glass p-8 rounded-[2.5rem] border-white/10 relative overflow-hidden">
                                <div class="absolute -right-10 -top-10 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl opacity-50"></div>
                                <h3 class="text-xs font-black text-cyan-400 uppercase tracking-widest mb-6">Describe Me</h3>
                                <?php if (!empty($analysis_result['describe_me'])): ?>
                                    <h4 class="text-lg font-black text-white leading-tight mb-6 gradient-cyan">
                                        <?php echo htmlspecialchars($analysis_result['describe_me']); ?>
                                    </h4>
                                <?php endif; ?>
                                <p class="text-slate-400 leading-relaxed text-xs">
                                    <?php echo htmlspecialchars($analysis_result['summary'] ?? 'Summary not provided.'); ?>
                                </p>
                            </div>
                            <div class="glass p-8 rounded-[2.5rem] border-white/10">
                                <h3 class="text-xs font-black text-purple-400 uppercase tracking-widest mb-6">Contact Matrix</h3>
                                <div class="space-y-4">
                                    <?php
                                    $contacts = $analysis_result['contact_details'] ?? [];
                                    if (empty($contacts)): ?>
                                        <p class="text-xs text-slate-500 italic">No contact details extracted.</p>
                                    <?php else: ?>
                                        <?php foreach ($contacts as $key => $value): ?>
                                            <div>
                                                <p class="text-[10px] text-slate-500 uppercase font-black">
                                                    <?php echo htmlspecialchars($key); ?>
                                                </p>
                                                <p class="text-white font-bold truncate"><?php echo htmlspecialchars($value); ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Elevator Pitch Card -->
                            <div class="glass p-8 rounded-[2.5rem] border-white/10 relative overflow-hidden group">
                                <div class="absolute -right-16 -top-16 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl opacity-50 transition-all group-hover:bg-emerald-500/20"></div>
                                <h3 class="text-xs font-black text-emerald-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                    <span class="w-1.5 h-4 bg-emerald-500 rounded-full"></span>
                                    Neural Elevator Pitch
                                </h3>
                                <div class="space-y-6 relative z-10">
                                    <?php $pitch = $analysis_result['elevator_pitch'] ?? []; ?>
                                    
                                    <div>
                                        <p class="text-[9px] text-slate-500 uppercase font-black mb-2 tracking-widest">The Hook (Past)</p>
                                        <p class="text-xs text-slate-300 leading-relaxed italic">
                                            "<?php echo htmlspecialchars($pitch['hook'] ?? 'Analyzing experience...'); ?>"
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-[9px] text-slate-500 uppercase font-black mb-2 tracking-widest">The Core (Present)</p>
                                        <p class="text-xs text-slate-300 leading-relaxed">
                                            <?php echo htmlspecialchars($pitch['experience'] ?? 'Synthesizing skills...'); ?>
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-[9px] text-slate-500 uppercase font-black mb-2 tracking-widest">The Goal (Future)</p>
                                        <p class="text-xs text-emerald-400/80 font-medium leading-relaxed">
                                            <?php echo htmlspecialchars($pitch['goal'] ?? 'Projecting career path...'); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-8 pt-6 border-t border-white/5 flex items-center justify-between">
                                    <span class="text-[9px] font-bold text-slate-600 uppercase tracking-tighter">~30 Second Script</span>
                                    <button onclick="navigator.clipboard.writeText(`<?php echo addslashes(($pitch['hook']??'').' '.($pitch['experience']??'').' '.($pitch['goal']??'')); ?>`)" 
                                            class="text-[10px] font-black text-emerald-500 hover:text-emerald-400 transition-colors uppercase tracking-widest flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                        </svg>
                                        Copy Pitch
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Middle Column: Skills -->
                        <div class="md:col-span-2 space-y-8">
                            <!-- Skill Taxonomy (Detailed) -->
                            <div class="glass p-8 rounded-[2.5rem] border-white/10">
                                <h3 class="text-xs font-black text-emerald-400 uppercase tracking-widest mb-8">Detailed
                                    Skill Categorization</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                    <?php
                                    $skills = $analysis_result['skills'] ?? [];
                                    if (empty($skills)): ?>
                                        <p class="text-xs text-slate-500 italic">No skills extracted.</p>
                                    <?php else: ?>
                                        <?php foreach ($skills as $category => $skill_list): ?>
                                            <div>
                                                <h4 class="text-white font-black mb-4 flex items-center gap-2 text-sm">
                                                    <span class="w-1.5 h-4 bg-emerald-500 rounded-full"></span>
                                                    <?php echo is_string($category) ? htmlspecialchars($category) : "Skills"; ?>
                                                </h4>
                                                <div class="flex flex-wrap gap-2">
                                                    <?php
                                                    $items = is_array($skill_list) ? $skill_list : [$skill_list];
                                                    foreach ($items as $item): ?>
                                                        <span
                                                            class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/5 text-xs text-slate-300 font-medium">
                                                            <?php echo htmlspecialchars($item); ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Skill Proficiency Matrix -->
                            <div
                                class="glass p-8 rounded-[2.5rem] border-white/10 shadow-2xl relative overflow-hidden group">
                                <div
                                    class="absolute -right-20 -top-20 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl opacity-50">
                                </div>
                                <div class="flex items-center justify-between mb-8 relative z-10">
                                    <h3
                                        class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2">
                                        <span class="w-1.5 h-4 bg-cyan-400 rounded-full"></span>
                                        Skill Proficiency Matrix
                                    </h3>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Neural
                                        Radial Mapping</span>
                                </div>
                                <div class="h-[350px] w-full flex items-center justify-center relative z-10 my-8">
                                    <canvas id="skillsChart"></canvas>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-12">
                                    <!-- Mastered -->
                                    <div
                                        class="p-6 rounded-2xl bg-emerald-500/5 border border-emerald-500/10 hover:bg-emerald-500/10 transition-colors">
                                        <h4 class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-4">
                                            Mastered</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <?php
                                            $strong = $analysis_result['categorized_skills']['strong'] ?? [];
                                            if (!empty($strong)):
                                                foreach ($strong as $s): ?>
                                                    <span
                                                        class="px-2 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 text-[10px] font-bold border border-emerald-500/20"><?php echo htmlspecialchars($s); ?></span>
                                                <?php endforeach;
                                            else: ?>
                                                <span class="text-slate-600 text-[10px] font-medium italic">Analyzing...</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Progressing -->
                                    <div
                                        class="p-6 rounded-2xl bg-amber-500/5 border border-amber-500/10 hover:bg-amber-500/10 transition-colors">
                                        <h4 class="text-[10px] font-black text-amber-400 uppercase tracking-widest mb-4">
                                            Progressing</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <?php
                                            $medium = $analysis_result['categorized_skills']['medium'] ?? [];
                                            if (!empty($medium)):
                                                foreach ($medium as $s): ?>
                                                    <span
                                                        class="px-2 py-1 rounded-lg bg-amber-500/10 text-amber-400 text-[10px] font-bold border border-amber-500/20"><?php echo htmlspecialchars($s); ?></span>
                                                <?php endforeach;
                                            else: ?>
                                                <span class="text-slate-600 text-[10px] font-medium italic">Evaluating...</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Opportunity -->
                                    <div
                                        class="p-6 rounded-2xl bg-rose-500/5 border border-rose-500/10 hover:bg-rose-500/10 transition-colors">
                                        <h4 class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-4">
                                            Opportunity</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <?php
                                            $weak = $analysis_result['categorized_skills']['weak'] ?? [];
                                            if (!empty($weak)):
                                                foreach ($weak as $s): ?>
                                                    <span
                                                        class="px-2 py-1 rounded-lg bg-rose-500/10 text-rose-400 text-[10px] font-bold border border-rose-500/20"><?php echo htmlspecialchars($s); ?></span>
                                                <?php endforeach;
                                            else: ?>
                                                <span class="text-slate-600 text-[10px] font-medium italic">Optimized.</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Fit -->
                            <div class="glass p-8 rounded-[2.5rem] border-white/10">
                                <h3 class="text-xs font-black text-amber-400 uppercase tracking-widest mb-8">Neural Job
                                    Matching</h3>
                                <div class="space-y-6">
                                    <?php
                                    $jobs = $analysis_result['job_fit'] ?? [];
                                    if (empty($jobs)): ?>
                                        <p class="text-xs text-slate-500 italic">Matching data not available.</p>
                                    <?php else: ?>
                                        <?php foreach ($jobs as $job): ?>
                                            <div
                                                class="p-6 rounded-2xl bg-white/5 border border-white/5 group/job hover:border-amber-500/30 transition-all">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h4
                                                        class="text-lg font-black text-white group-hover/job:text-amber-400 transition-colors">
                                                        <?php echo htmlspecialchars($job['role'] ?? $job['title'] ?? 'N/A'); ?>
                                                    </h4>
                                                    <span
                                                        class="text-2xl font-black text-amber-500"><?php echo htmlspecialchars($job['match'] ?? $job['match_percentage'] ?? '0%'); ?></span>
                                                </div>
                                                <p class="text-sm text-slate-400 leading-relaxed italic mb-6">
                                                    "<?php echo htmlspecialchars($job['reason'] ?? $job['explanation'] ?? ''); ?>"
                                                </p>

                                                <?php
                                                $job_query = urlencode($job['role'] ?? $job['title'] ?? '');
                                                $linkedin_url = "https://www.linkedin.com/jobs/search/?keywords=" . $job_query;
                                                ?>
                                                <a href="<?php echo $linkedin_url; ?>" target="_blank"
                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-500 text-xs font-bold hover:bg-amber-500 hover:text-black transition-all group/btn">
                                                    <span>Apply on LinkedIn</span>
                                                    <svg class="w-3 h-3 transform group-hover/btn:translate-x-1 transition-transform"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Interview Question Prediction -->
                            <div class="glass p-8 rounded-[2.5rem] border-white/10 relative overflow-hidden">
                                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl opacity-50"></div>
                                <h3 class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                                    <span class="w-1.5 h-4 bg-indigo-500 rounded-full"></span>
                                    HR-Style Interview Prediction
                                </h3>
                                <div class="space-y-4 relative z-10">
                                    <?php
                                    $questions = $analysis_result['interview_questions'] ?? [];
                                    if (empty($questions)): ?>
                                        <p class="text-xs text-slate-500 italic">Questions being generated...</p>
                                    <?php else: ?>
                                        <?php foreach ($questions as $index => $q_data): 
                                            $q_text = is_array($q_data) ? ($q_data['question'] ?? 'N/A') : $q_data;
                                            $a_text = is_array($q_data) ? ($q_data['suggested_answer'] ?? $q_data['answer'] ?? null) : null;
                                        ?>
                                            <div class="p-5 rounded-2xl bg-white/5 border border-white/5 hover:border-indigo-500/30 transition-all group/q">
                                                <div class="flex gap-4 mb-3">
                                                    <span class="text-indigo-500/50 font-black text-xs mt-1">
                                                        <?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?>
                                                    </span>
                                                    <p class="text-sm text-slate-300 font-medium leading-relaxed group-hover/q:text-white transition-colors">
                                                        <?php echo htmlspecialchars($q_text); ?>
                                                    </p>
                                                </div>
                                                
                                                <?php if ($a_text): ?>
                                                    <details class="group/ans">
                                                        <summary class="list-none cursor-pointer flex items-center gap-2 text-[10px] font-black text-indigo-400/60 hover:text-indigo-400 uppercase tracking-widest transition-colors select-none">
                                                            <svg class="w-3 h-3 transform group-open/ans:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                                            </svg>
                                                            <span>Reveal Suggested STAR Answer</span>
                                                        </summary>
                                                        <div class="mt-4 p-4 rounded-xl bg-indigo-500/5 border border-indigo-500/10 text-xs text-slate-400 leading-relaxed animate-in fade-in slide-in-from-top-2 duration-300">
                                                            <div class="flex gap-2 mb-2">
                                                                <span class="px-1.5 py-0.5 rounded bg-indigo-500/20 text-[9px] font-bold text-indigo-300 border border-indigo-500/30 uppercase">STAR Model</span>
                                                            </div>
                                                            <?php echo nl2br(htmlspecialchars($a_text)); ?>
                                                        </div>
                                                    </details>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="mt-8 pt-8 border-t border-white/5">
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest text-center">
                                        Tailored specifically to your projects and experience
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>



        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
            <!-- Logical Reasoning -->
            <div
                class="glass p-8 rounded-[2.5rem] border border-white/5 flex flex-col transition-all hover:-translate-y-2 gradient-border">
                <div class="flex items-center gap-3 mb-6">
                    <span
                        class="px-3 py-1 bg-cyan-500/10 text-cyan-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-cyan-500/20">Logic</span>
                </div>
                <h2 class="text-2xl font-bold text-white mb-3">Logical Reasoning</h2>
                <p class="text-slate-400 text-sm mb-8">Puzzles, sequences and patterns</p>

                <div class="grid grid-cols-3 gap-3 mb-10">
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span class="block text-white font-bold text-sm">10</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Tasks</span>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="block text-white font-bold text-sm">10 min</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Time</span>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="block text-white font-bold text-sm">Easy</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Level</span>
                    </div>
                </div>

                <a href="interview_quiz.php?category=logical"
                    class="mt-auto block w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-white text-center py-4 rounded-2xl font-bold transition-all hover:opacity-90 active:scale-95 shadow-lg shadow-cyan-500/20 flex items-center justify-center gap-2">
                    Start Prep
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Quantitative Aptitude -->
            <div
                class="glass p-8 rounded-[2.5rem] border border-white/5 flex flex-col transition-all hover:-translate-y-2 gradient-border">
                <div class="flex items-center gap-3 mb-6">
                    <span
                        class="px-3 py-1 bg-purple-500/10 text-purple-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-purple-500/20">Math</span>
                </div>
                <h2 class="text-2xl font-bold text-white mb-3">Quantitative Aptitude</h2>
                <p class="text-slate-400 text-sm mb-8">Numbers, arithmetic and algebra</p>

                <div class="grid grid-cols-3 gap-3 mb-10">
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span class="block text-white font-bold text-sm">10</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Tasks</span>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="block text-white font-bold text-sm">10 min</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Time</span>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="block text-white font-bold text-sm">Medium</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Level</span>
                    </div>
                </div>

                <a href="interview_quiz.php?category=quant"
                    class="mt-auto block w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-white text-center py-4 rounded-2xl font-bold transition-all hover:opacity-90 active:scale-95 shadow-lg shadow-cyan-500/20 flex items-center justify-center gap-2">
                    Start Prep
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Verbal Ability -->
            <div
                class="glass p-8 rounded-[2.5rem] border border-white/5 flex flex-col transition-all hover:-translate-y-2 gradient-border">
                <div class="flex items-center gap-3 mb-6">
                    <span
                        class="px-3 py-1 bg-indigo-500/10 text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-indigo-500/20">English</span>
                </div>
                <h2 class="text-2xl font-bold text-white mb-3">Verbal Ability</h2>
                <p class="text-slate-400 text-sm mb-8">Grammar, synonyms and antonyms</p>

                <div class="grid grid-cols-3 gap-3 mb-10">
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span class="block text-white font-bold text-sm">10</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Tasks</span>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="block text-white font-bold text-sm">10 min</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Time</span>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center">
                        <svg class="w-5 h-5 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="block text-white font-bold text-sm">Easy</span>
                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Level</span>
                    </div>
                </div>

                <a href="interview_quiz.php?category=verbal"
                    class="mt-auto block w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-white text-center py-4 rounded-2xl font-bold transition-all hover:opacity-90 active:scale-95 shadow-lg shadow-cyan-500/20 flex items-center justify-center gap-2">
                    Start Prep
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Technical Mastery Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                <span class="w-1.5 h-8 bg-rose-500 rounded-full"></span>
                Technical Mastery
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- DSA Fundamentals -->
                <div
                    class="glass p-8 rounded-[2.5rem] border border-white/5 flex flex-col transition-all hover:-translate-y-2 gradient-border relative overflow-hidden group">
                    <div class="flex items-center gap-3 mb-6 relative z-10">
                        <span
                            class="px-3 py-1 bg-rose-500/10 text-rose-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-rose-500/20">Coding</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3 relative z-10">DSA Fundamentals</h3>
                    <p class="text-slate-400 text-sm mb-8 relative z-10">Algorithms, complexity and logic</p>

                    <div class="grid grid-cols-3 gap-3 mb-10 relative z-10">
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">10</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Tasks</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">15 min</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Time</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">Hard</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Level</p>
                        </div>
                    </div>

                    <a href="interview_quiz.php?category=dsa"
                        class="mt-auto block w-full bg-rose-500 hover:bg-rose-600 text-white text-center py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-rose-500/20 active:scale-95 z-10">
                        Start Coding
                    </a>

                    <!-- Subtle background light -->
                    <div
                        class="absolute -bottom-10 -right-10 w-32 h-32 bg-rose-500/10 blur-3xl rounded-full group-hover:bg-rose-500/20 transition-all pointer-events-none">
                    </div>
                </div>

                <!-- DBMS Architecture -->
                <div
                    class="glass p-8 rounded-[2.5rem] border border-white/5 flex flex-col transition-all hover:-translate-y-2 gradient-border relative overflow-hidden group">
                    <div class="flex items-center gap-3 mb-6 relative z-10">
                        <span
                            class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-emerald-500/20">Database</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3 relative z-10">DBMS Architecture</h3>
                    <p class="text-slate-400 text-sm mb-8 relative z-10">SQL, normalization and ACID</p>

                    <div class="grid grid-cols-3 gap-3 mb-10 relative z-10">
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">10</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Tasks</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">12 min</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Time</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">Medium</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Level</p>
                        </div>
                    </div>

                    <a href="interview_quiz.php?category=dbms"
                        class="mt-auto block w-full bg-emerald-500 hover:bg-emerald-600 text-white text-center py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-emerald-500/20 active:scale-95 z-10">
                        Start SQL
                    </a>

                    <div
                        class="absolute -bottom-10 -right-10 w-32 h-32 bg-emerald-500/10 blur-3xl rounded-full group-hover:bg-emerald-500/20 transition-all pointer-events-none">
                    </div>
                </div>

                <!-- Web Fundamentals -->
                <div
                    class="glass p-8 rounded-[2.5rem] border border-white/5 flex flex-col transition-all hover:-translate-y-2 gradient-border relative overflow-hidden group">
                    <div class="flex items-center gap-3 mb-6 relative z-10">
                        <span
                            class="px-3 py-1 bg-amber-500/10 text-amber-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-amber-500/20">Frontend</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3 relative z-10">Web Fundamentals</h3>
                    <p class="text-slate-400 text-sm mb-8 relative z-10">HTML, CSS and JavaScript</p>

                    <div class="grid grid-cols-3 gap-3 mb-10 relative z-10">
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">10</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Tasks</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">10 min</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Time</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 text-center">
                            <p class="text-white font-black text-sm">Easy</p>
                            <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Level</p>
                        </div>
                    </div>

                    <a href="interview_quiz.php?category=web"
                        class="mt-auto block w-full bg-amber-500 hover:bg-amber-600 text-white text-center py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-amber-500/20 active:scale-95 z-10">
                        Start Web
                    </a>

                    <div
                        class="absolute -bottom-10 -right-10 w-32 h-32 bg-amber-500/10 blur-3xl rounded-full group-hover:bg-amber-500/20 transition-all pointer-events-none">
                    </div>
                </div>
            </div>
        </div>
        <!-- Final Assessment Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                <span class="w-1.5 h-8 bg-indigo-500 rounded-full"></span>
                Final Assessment
            </h2>
            <div
                class="glass p-1 rounded-[2.5rem] bg-gradient-to-r from-indigo-500/20 to-purple-500/20 border border-white/5">
                <div
                    class="glass p-8 md:p-12 rounded-[2.3rem] border border-white/5 flex flex-col md:flex-row items-center gap-10">
                    <div class="flex-1 text-center md:text-left">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[10px] font-black uppercase tracking-widest mb-6">
                            Real Environment
                        </div>
                        <h3 class="text-3xl md:text-4xl font-black text-white mb-4">Mock Interview Test</h3>
                        <p class="text-slate-400 text-lg mb-8 leading-relaxed max-w-xl">
                            Challenge yourself with a full-length mock test. No instant feedback, no hints — just you
                            and the clock. Detailed results and corrections will be shown after submission.
                        </p>

                        <div class="flex flex-wrap gap-6 justify-center md:justify-start mb-10">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">20 Questions</p>
                                    <p class="text-xs text-slate-500">Mixed categories</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">20 Minutes</p>
                                    <p class="text-xs text-slate-500">Timed session</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">High Difficulty</p>
                                    <p class="text-xs text-slate-500">Performance results</p>
                                </div>
                            </div>
                        </div>

                        <a href="interview_quiz.php?category=all&type=mock"
                            class="inline-flex items-center gap-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-10 py-5 rounded-2xl font-black text-lg transition-all hover:scale-105 active:scale-95 shadow-2xl shadow-indigo-500/20">
                            Start Final Assessment
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    </footer>

    <?php if ($analysis_result && !isset($analysis_result['error'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const chartDom = document.getElementById('skillsChart');
                if (!chartDom) return;

                const ctx = chartDom.getContext('2d');
                const rawData = <?php echo json_encode($analysis_result['skill_levels'] ?? []); ?>;

                // Limit to top 7 categories for clarity
                const entries = Object.entries(rawData).slice(0, 7);
                const labels = entries.map(e => e[0]);
                const values = entries.map(e => e[1]);

                new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Proficiency',
                            data: values,
                            backgroundColor: 'rgba(34, 211, 238, 0.15)',
                            borderColor: '#22d3ee',
                            pointBackgroundColor: '#22d3ee',
                            pointBorderColor: '#0f172a',
                            pointHoverBackgroundColor: '#0f172a',
                            pointHoverBorderColor: '#22d3ee',
                            pointRadius: 4,
                            borderWidth: 2,
                            lineTension: 0.1
                        }]
                    },
                    options: {
                        scales: {
                            r: {
                                angleLines: { color: 'rgba(255, 255, 255, 0.05)' },
                                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                pointLabels: {
                                    color: '#94a3b8',
                                    font: {
                                        family: "'Inter', sans-serif",
                                        size: 10,
                                        weight: '900'
                                    }
                                },
                                ticks: {
                                    display: false,
                                    max: 100,
                                    min: 0,
                                    stepSize: 20
                                },
                                suggestedMin: 0,
                                suggestedMax: 100
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleFont: { size: 12, weight: 'bold' },
                                bodyFont: { size: 12 },
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    label: (context) => ` Level: ${context.raw}%`
                                }
                            }
                        },
                        maintainAspectRatio: false,
                        responsive: true
                    }
                });
            });
        </script>
    <?php endif; ?>
</body>

</html>