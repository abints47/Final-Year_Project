<nav class="fixed top-0 left-0 right-0 z-50 bg-[#020617]/80 backdrop-blur-xl border-b border-white/5 py-4">
  <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
    <!-- Logo -->
    <div class="flex items-center gap-2 cursor-pointer group" onclick="window.location.href='index.php'">
      <div
        class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-xl flex items-center justify-center transition-transform group-hover:scale-105 shadow-lg shadow-cyan-500/20">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
        </svg>
      </div>
      <span class="text-2xl font-bold tracking-tight">
        <span class="text-white">Arjun</span><span
          class="bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">Techy</span>
      </span>
    </div>

    <!-- Centered Links -->
    <div class="hidden md:flex items-center gap-10 absolute left-1/2 -translate-x-1/2">
      <a href="index.php" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Home</a>
      <a href="courses.php" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Courses</a>
      <a href="dashboard.php" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Dashboard</a>
      <a href="profile.php" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Profile</a>
      <a href="contact.php" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Contact</a>
    </div>

    <!-- Right Side (Auth/Dashboard) -->
    <div class="flex items-center gap-6">
      <?php if (isset($_SESSION['user_name'])): ?>
        <a href="dashboard.php"
          class="hidden md:flex items-center gap-2 text-sm font-medium text-slate-300 hover:text-white transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          Dashboard
        </a>
        <a href="logout.php"
          class="bg-gradient-to-r from-cyan-400 to-purple-500 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all hover:opacity-90 active:scale-95 shadow-lg shadow-cyan-500/20">
          Logout
        </a>
      <?php else: ?>
        <a href="login.php" class="text-sm font-bold text-slate-300 hover:text-white transition-colors">Sign In</a>
        <a href="index.php"
          class="bg-gradient-to-r from-cyan-400 to-purple-500 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all hover:opacity-90 active:scale-95 shadow-lg shadow-cyan-500/20">
          Join Now
        </a>
      <?php endif; ?>
    </div>
  </div>
</nav>