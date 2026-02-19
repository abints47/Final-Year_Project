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
        <span class="text-white">Open</span><span
          class="bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">ly</span>
      </span>
    </div>

    <!-- Centered Links -->
    <div class="hidden md:flex items-center gap-10 absolute left-1/2 -translate-x-1/2">
      <a href="dashboard.php" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Home</a>
      <a href="courses.php" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Courses</a>
<<<<<<< HEAD
=======
      <a href="interview_prep.php"
        class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Interview Prep</a>
>>>>>>> ea85590 (Implement Interview Prep section: quiz, landing page, and data)
      <a href="profile.php" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Profile</a>
      <a href="contact.php" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Contact</a>
    </div>

    <!-- Right Side (Auth/Profile) -->
    <div class="flex items-center gap-3">
      <?php if (isset($_SESSION['user_name'])): ?>
        <div class="flex items-center gap-4 cursor-pointer group" onclick="window.location.href='profile.php'">
          <div class="text-right hidden sm:block leading-tight">
            <p class="text-white font-bold text-sm tracking-tight"><?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </p>
            <p class="text-[#818cf8] font-black text-[10px] uppercase tracking-widest">Member</p>
          </div>
          <div
            class="w-10 h-10 rounded-xl overflow-hidden border border-white/10 bg-[#0f172a] transition-transform group-hover:scale-105">
            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?php echo urlencode($_SESSION['user_name']); ?>"
              alt="Profile" class="w-full h-full" />
          </div>
        </div>
<<<<<<< HEAD
        <div class="w-px h-8 bg-white/10 mx-2"></div>
        <a href="logout.php" class="text-sm font-bold text-slate-400 hover:text-white transition-colors">
=======
        <div class="w-px h-8 bg-white/10 mx-2 hidden md:block"></div>
        <a href="logout.php" class="text-sm font-bold text-slate-400 hover:text-white transition-colors hidden md:block">
>>>>>>> ea85590 (Implement Interview Prep section: quiz, landing page, and data)
          Logout
        </a>
      <?php else: ?>
        <a href="login.php"
          class="text-sm font-bold text-slate-300 hover:text-white transition-colors hidden sm:block">Sign In</a>
        <a href="index.php"
          class="bg-gradient-to-r from-cyan-400 to-purple-500 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all hover:opacity-90 active:scale-95 shadow-lg shadow-cyan-500/20 hidden sm:block">
          Join Now
        </a>
      <?php endif; ?>

      <!-- Mobile Menu Button -->
      <button id="mobile-menu-button"
        class="md:hidden w-10 h-10 flex flex-col items-center justify-center gap-1.5 relative z-50 transition-all active:scale-95 group">
        <span id="bar-1" class="w-6 h-0.5 bg-slate-300 rounded-full transition-all duration-300 origin-center"></span>
        <span id="bar-2" class="w-6 h-0.5 bg-slate-300 rounded-full transition-all duration-300"></span>
        <span id="bar-3" class="w-6 h-0.5 bg-slate-300 rounded-full transition-all duration-300 origin-center"></span>
      </button>
    </div>
  </div>

</nav>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu"
  class="fixed inset-0 bg-[#020617] z-[100] transition-all duration-500 opacity-0 pointer-events-none flex flex-col">

  <!-- Mobile Menu Header (Logo & Close) -->
  <div class="flex items-center justify-between px-6 py-4 border-b border-white/5 bg-[#020617]">
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-lg flex items-center justify-center">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
        </svg>
      </div>
      <span class="text-xl font-bold tracking-tight text-white">Openly</span>
    </div>
    <button id="mobile-menu-close"
      class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-white transition-colors">
      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <!-- Scrollable Content -->
  <div class="flex-1 overflow-y-auto p-8 flex flex-col gap-6">
    <div class="space-y-4">
      <a href="dashboard.php"
        class="menu-item flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5 active:bg-white/10 transition-all font-bold"
        style="--i: 1">
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-xl bg-cyan-500/10 flex items-center justify-center text-cyan-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
          </div>
          <span class="text-lg font-bold text-white tracking-tight">Home</span>
        </div>
        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </a>

      <a href="courses.php"
        class="menu-item flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5 active:bg-white/10 transition-all font-bold"
        style="--i: 2">
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <span class="text-lg font-bold text-white tracking-tight">Courses</span>
        </div>
        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </a>

      <a href="interview_prep.php"
        class="menu-item flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5 active:bg-white/10 transition-all font-bold"
        style="--i: 3">
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <span class="text-lg font-bold text-white tracking-tight">Interview Prep</span>
        </div>
        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </a>

      <a href="profile.php"
        class="menu-item flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5 active:bg-white/10 transition-all font-bold"
        style="--i: 4">
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <span class="text-lg font-bold text-white tracking-tight">Profile</span>
        </div>
        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </a>

      <a href="contact.php"
        class="menu-item flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5 active:bg-white/10 transition-all font-bold"
        style="--i: 5">
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <span class="text-lg font-bold text-white tracking-tight">Contact</span>
        </div>
        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </a>
    </div>

    <div class="mt-auto pt-6 border-t border-white/5">
      <?php if (!isset($_SESSION['user_name'])): ?>
        <div class="flex flex-col gap-4">
          <a href="login.php" class="menu-item w-full py-4 text-center text-slate-300 font-bold active:text-white"
            style="--i: 6">Sign In</a>
          <a href="index.php"
            class="menu-item w-full bg-gradient-to-r from-cyan-400 to-purple-500 text-white py-4 rounded-2xl text-center font-black text-lg shadow-xl shadow-cyan-500/20 active:scale-95 transition-all font-bold"
            style="--i: 7">
            Join Now
          </a>
        </div>
      <?php else: ?>
        <a href="logout.php"
          class="menu-item flex items-center gap-4 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 font-bold justify-center"
          style="--i: 6">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          Logout
        </a>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
  @keyframes slideInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .menu-item {
    opacity: 0;
  }

  #mobile-menu.active {
    opacity: 1;
    pointer-events: auto;
  }

  #mobile-menu.active .menu-item {
    animation: slideInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    animation-delay: calc(var(--i) * 0.1s);
  }

  /* Hamburger Animation States */
  #mobile-menu-button.open #bar-1 {
    transform: translateY(7px) rotate(45deg);
    background-color: #fff;
  }

  #mobile-menu-button.open #bar-2 {
    opacity: 0;
    transform: translateX(10px);
  }

  #mobile-menu-button.open #bar-3 {
    transform: translateY(-7px) rotate(-45deg);
    background-color: #fff;
  }
</style>

<script>
  (function () {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenu = document.getElementById('mobile-menu');

    const toggleMenu = (show) => {
      if (show) {
        mobileMenu.classList.add('active');
        mobileMenuButton.classList.add('open');
        document.body.style.overflow = 'hidden';
      } else {
        mobileMenu.classList.remove('active');
        mobileMenuButton.classList.remove('open');
        document.body.style.overflow = '';
      }
    };

    if (mobileMenuButton) mobileMenuButton.addEventListener('click', () => toggleMenu(true));
    if (mobileMenuClose) mobileMenuClose.addEventListener('click', () => toggleMenu(false));

    // Close menu when clicking a link
    if (mobileMenu) {
      mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => toggleMenu(false));
      });
    }
  })();
</script>