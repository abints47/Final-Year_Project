<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/interview_data.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$category = isset($_GET['category']) ? $_GET['category'] : 'logical';
$questions_pool = isset($interview_questions[$category]) ? $interview_questions[$category] : [];

// Randomly pick 10 questions
shuffle($questions_pool);
$selected_questions = array_slice($questions_pool, 0, 10);

$category_names = [
    'logical' => 'Logical Reasoning',
    'quant' => 'Quantitative Aptitude',
    'verbal' => 'Verbal Ability'
];
$current_category_name = isset($category_names[$category]) ? $category_names[$category] : 'Quiz';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $current_category_name; ?> Quiz - Openly
    </title>
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

        .gradient-cyan {
            background: linear-gradient(to right, #22d3ee, #0ea5e9);
        }

        .option-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .option-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(34, 211, 238, 0.3);
        }

        .option-card.selected {
            background: rgba(34, 211, 238, 0.1);
            border-color: #22d3ee;
            box-shadow: 0 0 15px rgba(34, 211, 238, 0.1);
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
        <div class="aura bg-purple-600 w-[400px] h-[400px] bottom-0 right-0 translate-x-1/2 translate-y-1/2"></div>
    </div>

    <?php include 'components/navbar.php'; ?>

    <main class="flex-1 w-full max-w-4xl mx-auto px-6 py-12 relative z-10">
        <!-- Quiz Container -->
        <div id="quiz-container" class="glass p-10 rounded-[2.5rem] border border-white/5">
            <!-- Progress Bar -->
            <div class="mb-10">
                <div class="flex justify-between items-end mb-4">
                    <div>
                        <span class="text-cyan-400 font-black text-xs uppercase tracking-widest mb-1 block">Question
                            <span id="current-index">1</span> of 10</span>
                        <h2 class="text-2xl font-bold text-white">
                            <?php echo $current_category_name; ?>
                        </h2>
                    </div>
                    <div id="timer"
                        class="text-slate-400 font-mono text-sm bg-white/5 px-3 py-1 rounded-lg border border-white/5">
                        Time: 10:00
                    </div>
                </div>
                <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                    <div id="progress-bar"
                        class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 transition-all duration-500"
                        style="width: 10%"></div>
                </div>
            </div>

            <!-- Question Area -->
            <div id="question-area" class="min-h-[300px]">
                <h3 id="question-text" class="text-xl md:text-2xl font-semibold text-white mb-8 leading-relaxed">
                    <!-- Question injected here -->
                </h3>

                <div id="options-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Options injected here -->
                </div>
            </div>

            <!-- Navigation -->
            <div class="mt-12 flex justify-between items-center pt-8 border-t border-white/5">
                <button id="prev-btn"
                    class="px-6 py-3 rounded-xl bg-white/5 text-slate-400 font-bold hover:bg-white/10 transition-all disabled:opacity-20 disabled:pointer-events-none">
                    Previous
                </button>
                <div class="flex gap-2">
                    <button id="next-btn"
                        class="px-8 py-3 rounded-xl bg-gradient-to-r from-cyan-400 to-blue-500 text-white font-bold shadow-lg shadow-cyan-500/20 hover:opacity-90 transition-all">
                        Next Question
                    </button>
                    <button id="submit-btn"
                        class="hidden px-8 py-3 rounded-xl bg-green-500 text-white font-bold shadow-lg shadow-green-500/20 hover:opacity-90 transition-all">
                        Finish Quiz
                    </button>
                </div>
            </div>
        </div>

        <!-- Result Container -->
        <div id="result-container" class="hidden glass p-12 rounded-[2.5rem] border border-white/5 text-center">
            <div class="w-24 h-24 bg-cyan-500/10 rounded-full flex items-center justify-center mx-auto mb-8">
                <svg class="w-12 h-12 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-4xl font-black text-white mb-4">Quiz Completed!</h2>
            <p class="text-slate-400 mb-10">Great job completing the
                <?php echo $current_category_name; ?> assessment.
            </p>

            <div class="bg-white/5 rounded-[2rem] p-8 mb-10 max-w-sm mx-auto border border-white/5">
                <p class="text-slate-500 uppercase font-black text-xs tracking-widest mb-2">Your Score</p>
                <div class="text-6xl font-black text-white"><span id="score-text">0</span><span
                        class="text-2xl text-slate-600">/10</span></div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="location.reload()"
                    class="px-8 py-4 bg-white/10 rounded-2xl text-white font-bold hover:bg-white/20 transition-all">
                    Try Again
                </button>
                <a href="interview_prep.php"
                    class="px-8 py-4 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-2xl text-white font-bold shadow-lg shadow-cyan-500/20 hover:opacity-90 transition-all">
                    Back to Prep
                </a>
            </div>
        </div>
    </main>

    <script>
        const questions = <?php echo json_encode($selected_questions); ?>;
        let currentIndex = 0;
        let userAnswers = new Array(questions.length).fill(null);
        let timeLeft = 600; // 10 minutes

        const timerEl = document.getElementById('timer');
        const questionTextEl = document.getElementById('question-text');
        const optionsGridEl = document.getElementById('options-grid');
        const currentIndexEl = document.getElementById('current-index');
        const progressBarEl = document.getElementById('progress-bar');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');
        const quizContainer = document.getElementById('quiz-container');
        const resultContainer = document.getElementById('result-container');
        const scoreTextEl = document.getElementById('score-text');

        function startTimer() {
            const timerInterval = setInterval(() => {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerEl.textContent = `Time: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    finishQuiz();
                }
                timeLeft--;
            }, 1000);
        }

        function renderQuestion() {
            const q = questions[currentIndex];
            questionTextEl.textContent = q.question;
            optionsGridEl.innerHTML = '';

            q.options.forEach((option, index) => {
                const div = document.createElement('div');
                div.className = `option-card glass p-6 rounded-2xl border border-white/10 text-slate-300 font-medium ${userAnswers[currentIndex] === index ? 'selected' : ''}`;
                div.innerHTML = `
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-xs font-bold border border-white/10">
                            ${String.fromCharCode(65 + index)}
                        </div>
                        <span>${option}</span>
                    </div>
                `;
                div.onclick = () => selectOption(index);
                optionsGridEl.appendChild(div);
            });

            currentIndexEl.textContent = currentIndex + 1;
            progressBarEl.style.width = `${((currentIndex + 1) / questions.length) * 100}%`;

            prevBtn.disabled = currentIndex === 0;
            if (currentIndex === questions.length - 1) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
        }

        function selectOption(index) {
            userAnswers[currentIndex] = index;
            renderQuestion();
        }

        function finishQuiz() {
            let score = 0;
            questions.forEach((q, i) => {
                if (userAnswers[i] === q.answer) {
                    score++;
                }
            });

            quizContainer.classList.add('hidden');
            resultContainer.classList.remove('hidden');
            scoreTextEl.textContent = score;
        }

        prevBtn.onclick = () => {
            if (currentIndex > 0) {
                currentIndex--;
                renderQuestion();
            }
        };

        nextBtn.onclick = () => {
            if (currentIndex < questions.length - 1) {
                currentIndex++;
                renderQuestion();
            }
        };

        submitBtn.onclick = finishQuiz;

        // Initialize
        startTimer();
        renderQuestion();
    </script>

    <?php include 'components/footer.php'; ?>
</body>

</html>