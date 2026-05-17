<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Racer - Openly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #020617;
            color: #f8fafc;
        }

        .font-mono-code {
            font-family: 'Fira Code', monospace;
        }

        .glass {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .aura {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            z-index: -1;
            pointer-events: none;
        }

        .char {
            border-bottom: 2px solid transparent;
            transition: all 0.1s ease;
        }

        .char.correct {
            color: #22d3ee;
        }

        .char.incorrect {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            border-bottom-color: #ef4444;
        }

        .char.current {
            border-bottom-color: #22d3ee;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            50% {
                border-bottom-color: transparent;
            }
        }

        .game-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="min-h-screen pt-24 pb-20 overflow-x-hidden">
    <!-- Dynamic Auras -->
    <div class="aura bg-cyan-600 w-[600px] h-[600px] -top-96 -left-96"></div>
    <div class="aura bg-purple-600 w-[600px] h-[600px] bottom-0 -right-96"></div>

    <?php include 'components/navbar.php'; ?>

    <main class="max-w-4xl mx-auto px-6">
        <div class="text-center mb-12">
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-black mb-6 tracking-widest uppercase">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Typing Speed Calculator
            </div>
            <h1 class="text-5xl font-black text-white mb-4 tracking-tight">Code <span class="text-cyan-400">Racer</span>
            </h1>
            <p class="text-slate-400 font-medium">Type the code snippets as fast as you can to boost your WPM!</p>
        </div>

        <div class="glass rounded-[3rem] p-12 border-white/10 relative overflow-hidden">
            <!-- Stats Bar -->
            <div class="flex justify-between mb-12">
                <div class="flex gap-8">
                    <div class="stat-card px-8 py-4 rounded-2xl text-center">
                        <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest mb-1">Time</p>
                        <p id="timer" class="text-3xl font-black text-white leading-none">60s</p>
                    </div>
                    <div class="stat-card px-8 py-4 rounded-2xl text-center">
                        <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest mb-1">WPM</p>
                        <p id="wpm" class="text-3xl font-black text-cyan-400 leading-none">0</p>
                    </div>
                    <div class="stat-card px-8 py-4 rounded-2xl text-center">
                        <p id="accuracy-label"
                            class="text-[10px] text-slate-500 uppercase font-black tracking-widest mb-1">Accuracy</p>
                        <p id="accuracy" class="text-3xl font-black text-purple-400 leading-none">0%</p>
                    </div>
                </div>
                <div>
                    <button id="reset-btn"
                        class="w-14 h-14 glass rounded-2xl flex items-center justify-center text-slate-400 hover:text-white hover:border-white/20 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Typing Area -->
            <div id="game-container" class="relative group">
                <div id="snippet-display"
                    class="font-mono-code text-2xl leading-relaxed text-slate-500 break-words mb-8 select-none">
                    <!-- Characters will be dynamic -->
                    <div class="flex items-center justify-center p-20">
                        <button id="start-btn"
                            class="bg-cyan-500 text-[#020617] px-10 py-5 rounded-2xl font-black text-xl shadow-2xl shadow-cyan-500/20 hover:scale-105 active:scale-95 transition-all">
                            Start Game
                        </button>
                    </div>
                </div>
                <textarea id="typing-input"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-default resize-none pointer-events-none"
                    autofocus disabled></textarea>
            </div>

            <!-- Result Overlay -->
            <div id="result-overlay"
                class="absolute inset-0 glass flex flex-col items-center justify-center hidden z-20">
                <h2 class="text-4xl font-black text-white mb-2">Race Complete!</h2>
                <div class="flex gap-12 my-10">
                    <div class="text-center">
                        <p class="text-sm text-slate-500 uppercase font-bold tracking-widest mb-1">Final WPM</p>
                        <p id="final-wpm" class="text-6xl font-black text-cyan-400">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-slate-500 uppercase font-bold tracking-widest mb-1">Accuracy</p>
                        <p id="final-accuracy" class="text-6xl font-black text-purple-400">0%</p>
                    </div>
                </div>
                <button id="play-again-btn"
                    class="bg-white text-black px-8 py-3 rounded-xl font-bold hover:bg-cyan-500 hover:text-[#020617] transition-all">Play
                    Again</button>
            </div>
        </div>
    </main>

    <script>
        const snippets = [
            "function", "variable", "constant", "interface", "abstract", "document", "listener", "callback", "promise", "async",
            "await", "return", "export", "import", "package", "namespace", "boolean", "integer", "string", "double",
            "private", "public", "protected", "static", "final", "extends", "implements", "foreach", "while", "break",
            "continue", "switch", "default", "try", "catch", "finally", "throw", "instanceof", "yield", "generator"
        ];

        let timeLeft = 60;
        let timerId = null;
        let currentSnippet = "";
        let isPlaying = false;
        let charIndex = 0;
        let mistakes = 0;
        let totalTypedChars = 0;
        let totalMistakes = 0;

        const timerEl = document.getElementById('timer');
        const wpmEl = document.getElementById('wpm');
        const accuracyEl = document.getElementById('accuracy');
        const snippetEl = document.getElementById('snippet-display');
        const inputEl = document.getElementById('typing-input');
        const startBtn = document.getElementById('start-btn');
        const resetBtn = document.getElementById('reset-btn');
        const resultOverlay = document.getElementById('result-overlay');
        const finalWpmEl = document.getElementById('final-wpm');
        const finalAccuracyEl = document.getElementById('final-accuracy');
        const playAgainBtn = document.getElementById('play-again-btn');

        function loadSnippet() {
            const index = Math.floor(Math.random() * snippets.length);
            currentSnippet = snippets[index];
            snippetEl.innerHTML = "";
            currentSnippet.split("").forEach(char => {
                let span = document.createElement("span");
                span.classList.add("char");
                span.innerText = char;
                snippetEl.appendChild(span);
            });
            snippetEl.querySelectorAll(".char")[0].classList.add("current");
            charIndex = 0;
            inputEl.value = "";
        }

        function updateStats() {
            const timeElapsed = (60 - timeLeft) / 60;
            if (timeElapsed <= 0) return;

            const wpm = Math.round((totalTypedChars / 5) / timeElapsed);
            wpmEl.innerText = isNaN(wpm) || wpm === Infinity ? 0 : wpm;

            const accuracy = totalTypedChars > 0 
                ? Math.round(((totalTypedChars - totalMistakes) / totalTypedChars) * 100) 
                : 100;
            accuracyEl.innerText = (accuracy < 0 ? 0 : accuracy) + "%";
        }

        function startGame() {
            if (isPlaying) return;
            isPlaying = true;
            timeLeft = 60;
            totalTypedChars = 0;
            totalMistakes = 0;
            loadSnippet();
            inputEl.disabled = false;
            inputEl.classList.remove('pointer-events-none');
            inputEl.focus();
            resultOverlay.classList.add('hidden');
            wpmEl.innerText = "0";
            accuracyEl.innerText = "0%";

            timerId = setInterval(() => {
                if (timeLeft > 0) {
                    timeLeft--;
                    timerEl.innerText = timeLeft + "s";
                } else {
                    endGame();
                }
            }, 1000);
        }

        function endGame() {
            clearInterval(timerId);
            isPlaying = false;
            inputEl.disabled = true;
            inputEl.classList.add('pointer-events-none');
            resultOverlay.classList.remove('hidden');
            finalWpmEl.innerText = wpmEl.innerText;
            finalAccuracyEl.innerText = accuracyEl.innerText;
        }

        function resetGame() {
            clearInterval(timerId);
            isPlaying = false;
            timeLeft = 60;
            timerEl.innerText = "60s";
            wpmEl.innerText = "0";
            accuracyEl.innerText = "0%";
            inputEl.disabled = true;
            inputEl.classList.add('pointer-events-none');
            snippetEl.innerHTML = `<div class="flex items-center justify-center p-20"><button id="start-btn" class="bg-cyan-500 text-[#020617] px-10 py-5 rounded-2xl font-black text-xl shadow-2xl shadow-cyan-500/20 hover:scale-105 active:scale-95 transition-all">Start Game</button></div>`;
            document.getElementById('start-btn').addEventListener('click', startGame);
            resultOverlay.classList.add('hidden');
        }

        inputEl.addEventListener('input', (e) => {
            if (!isPlaying) return;

            const typedValue = inputEl.value;
            const characters = snippetEl.querySelectorAll(".char");
            
            if (e.inputType === "insertText" || e.inputType === "insertFromPaste") {
                totalTypedChars++;
                const lastIdx = typedValue.length - 1;
                if (typedValue[lastIdx] !== currentSnippet[lastIdx]) {
                    totalMistakes++;
                }
            }

            characters.forEach((span, index) => {
                const typedChar = typedValue[index];
                span.classList.remove("correct", "incorrect", "current");

                if (typedChar == null) {
                    if (index === typedValue.length) span.classList.add("current");
                } else {
                    if (typedChar === currentSnippet[index]) {
                        span.classList.add("correct");
                    } else {
                        span.classList.add("incorrect");
                    }
                }
            });

            if (typedValue === currentSnippet) {
                loadSnippet();
            }

            updateStats();
        });

            startBtn.addEventListener('click', startGame);
            resetBtn.addEventListener('click', resetGame);
            playAgainBtn.addEventListener('click', startGame);

            // Keep input focused
            document.addEventListener('keydown', () => {
                if (isPlaying) inputEl.focus();
            });
    </script>

    <?php include 'components/footer.php'; ?>
</body>

</html>