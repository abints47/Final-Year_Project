// Words for the game
const words = [
    'function', 'return', 'const', 'let', 'var', 'async', 'await',
    'import', 'export', 'class', 'interface', 'console.log',
    '<div>', '</span>', 'padding', 'margin', 'border',
    'background', 'color', 'display', 'flex', 'grid',
    'mysql', 'php', 'echo', '$variable', 'public',
    'private', 'protected', 'namespace', 'composer',
    'laravel', 'react', 'vue', 'angular', 'node'
];

let time = 30;
let score = 0;
let isPlaying = false;
let timeInterval;
let currentWord = '';

// DOM Elements
const startScreen = document.getElementById('startScreen');
const gamePlay = document.getElementById('gamePlay');
const wordDisplay = document.getElementById('targetWord');
const wordInput = document.getElementById('playerInput');
const timeDisplay = document.getElementById('timer');
const scoreDisplay = document.getElementById('score');

// Initialize Game
window.startGame = function() {
    if (isPlaying) return;
    
    // Reset state
    score = 0;
    time = 30;
    isPlaying = true;
    
    // Update UI
    scoreDisplay.innerText = score;
    timeDisplay.innerText = time;
    startScreen.classList.add('hidden');
    gamePlay.classList.remove('hidden');
    
    // Setup Input
    wordInput.value = '';
    wordInput.focus();
    
    // Get first word
    showNewWord();
    
    // Start Timer
    timeInterval = setInterval(countdown, 1000);
}

function showNewWord() {
    const randIndex = Math.floor(Math.random() * words.length);
    currentWord = words[randIndex];
    wordDisplay.innerText = currentWord;
}

function countdown() {
    if (time > 0) {
        time--;
        timeDisplay.innerText = time;
    } else if (time === 0) {
        endGame();
    }
}

function endGame() {
    isPlaying = false;
    clearInterval(timeInterval);
    
    // Update UI
    gamePlay.classList.add('hidden');
    startScreen.classList.remove('hidden');
    
    // Change Button Text to Show Score
    const btn = startScreen.querySelector('button');
    btn.innerHTML = `Game Over! Score: ${score}<br><span class="text-sm font-normal opacity-80">Play Again</span>`;
}

// Input Listener
if (wordInput) {
    wordInput.addEventListener('input', () => {
        if (!isPlaying) return;
        
        if (wordInput.value === currentWord) {
            score += 10; // Increment score
            scoreDisplay.innerText = score;
            wordInput.value = ''; // Clear input
            
            // Add slight animation to word display
            wordDisplay.classList.add('text-emerald-400');
            setTimeout(() => wordDisplay.classList.remove('text-emerald-400'), 200);
            
            showNewWord();
        }
    });
}