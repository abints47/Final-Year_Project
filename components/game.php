<section id="code-racer" class="py-12 animate-in" style="animation-delay: 0.2s;">
    <div class="glass rounded-[3rem] p-10 md:p-16 border border-white/5 relative overflow-hidden text-center">
        
        <!-- Background Decor -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-amber-500/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 max-w-4xl mx-auto">
            <div class="mb-10">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-400">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight">Code Racer</h2>
                </div>
                <p class="text-slate-400 text-lg">Type the code snippets as fast as you can to boost your WPM!</p>
            </div>

            <div class="bg-[#020617]/50 backdrop-blur-md rounded-[2.5rem] border border-white/10 p-8 md:p-12 relative overflow-hidden shadow-2xl">
                <!-- Stats HUD -->
                <div class="flex justify-between items-center mb-12 px-4">
                    <div class="bg-[#0f172a] border border-white/5 px-6 py-3 rounded-xl text-center min-w-[120px]">
                        <span class="block text-[10px] uppercase font-bold text-slate-500 tracking-widest">Score</span>
                        <span id="score" class="text-2xl font-black text-white">0</span>
                    </div>
                    <div class="bg-[#0f172a] border border-white/5 px-6 py-3 rounded-xl text-center min-w-[120px]">
                        <span class="block text-[10px] uppercase font-bold text-slate-500 tracking-widest">Time</span>
                        <span class="text-2xl font-black text-amber-400"><span id="timer">30</span>s</span>
                    </div>
                </div>

                <!-- Start Screen -->
                <div id="startScreen" class="py-10">
                    <button onclick="window.startGame()" class="group relative px-10 py-5 bg-amber-500 hover:bg-amber-400 text-black font-black text-xl rounded-2xl transition-all hover:scale-105 active:scale-95 shadow-xl shadow-amber-500/20">
                        Start Game
                        <div class="absolute inset-0 rounded-2xl ring-2 ring-white/20 group-hover:ring-white/40 transition-all"></div>
                    </button>
                </div>

                <!-- Gameplay -->
                <div id="gamePlay" class="hidden w-full max-w-lg mx-auto text-center">
                    <div class="mb-8">
                        <span id="targetWord" class="font-mono text-4xl md:text-5xl text-white font-bold tracking-tight select-none">function()</span>
                    </div>
                    <input type="text" id="playerInput" autocomplete="off" class="w-full bg-[#0f172a] border border-white/10 rounded-2xl px-6 py-5 text-xl text-center text-white placeholder:text-slate-700 focus:outline-none focus:border-amber-500/50 focus:ring-4 focus:ring-amber-500/10 transition-all font-mono" placeholder="Type here...">
                </div>
            </div>

            <!-- Leaderboard & Achievements -->
            <div class="grid md:grid-cols-2 gap-6 mt-12">
                <div class="bg-[#0f172a]/50 border border-white/5 p-8 rounded-[2rem] hover:border-emerald-500/20 transition-colors group">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="text-left">
                            <h3 class="text-white font-bold text-lg">Your Rank</h3>
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">#1 &bull; 5,400 XP</p>
                        </div>
                    </div>
                    <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full w-[85%]"></div>
                    </div>
                </div>

                <div class="bg-[#0f172a]/50 border border-white/5 p-8 rounded-[2rem] hover:border-indigo-500/20 transition-colors group">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        </div>
                        <div class="text-left">
                            <h3 class="text-white font-bold text-lg">Achievements</h3>
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">5 Badges Unlocked</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <div class="w-8 h-8 rounded-full bg-indigo-600/20 border border-indigo-500/30"></div>
                        <div class="w-8 h-8 rounded-full bg-pink-600/20 border border-pink-500/30"></div>
                        <div class="w-8 h-8 rounded-full bg-amber-600/20 border border-amber-500/30"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>