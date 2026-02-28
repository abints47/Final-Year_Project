<footer class="py-20 border-t border-white/5 bg-[#020617] mt-auto">
  <div class="max-w-7xl mx-auto px-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-20">
      <!-- Brand Area -->
      <div class="col-span-1 md:col-span-1">
        <div class="flex items-center gap-2 mb-6">
          <div class="w-8 h-8 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
            </svg>
          </div>
          <span class="text-xl font-black text-white tracking-tighter">Openly</span>
        </div>
        <p class="text-slate-500 text-sm leading-relaxed font-medium">
          Empowering the next generation of global talent with intelligent AI career tools.
        </p>
      </div>

      <!-- Platform Links -->
      <div>
        <h4 class="text-white font-bold mb-6 text-sm uppercase tracking-widest">Platform</h4>
        <ul class="space-y-4 text-slate-500 text-sm font-medium">
          <li><a href="#" class="hover:text-cyan-400 transition-colors">AI ATS</a></li>
          <li><a href="#" class="hover:text-cyan-400 transition-colors">Interview Prep</a></li>
          <li><a href="#" class="hover:text-cyan-400 transition-colors">Skill Mapping</a></li>
        </ul>
      </div>

      <!-- Company Links -->
      <div>
        <h4 class="text-white font-bold mb-6 text-sm uppercase tracking-widest">Company</h4>
        <ul class="space-y-4 text-slate-500 text-sm font-medium">
          <li><a href="#" class="hover:text-cyan-400 transition-colors">About Us</a></li>
          <li><a href="#" class="hover:text-cyan-400 transition-colors">Careers</a></li>
          <li><a href="#" class="hover:text-cyan-400 transition-colors">Success Stories</a></li>
        </ul>
      </div>

      <!-- Newsletter Area -->
      <div>
        <h4 class="text-white font-bold mb-6 text-sm uppercase tracking-widest">Stay Updated</h4>
        <div class="relative">
          <input type="email" placeholder="Your email"
            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-cyan-500/50 transition-all placeholder:text-slate-700">
          <button
            class="absolute right-1 top-1 bottom-1 bg-cyan-400 hover:bg-cyan-300 text-[#020617] p-2 rounded-lg transition-all group">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <div class="pt-10 border-t border-white/5 flex flex-col md:row items-center justify-between gap-6">
      <p class="text-slate-600 text-xs font-bold">
        &copy; <?php echo date("Y"); ?> Openly AI. All rights reserved.
      </p>
      <div class="flex gap-8 text-[10px] font-black uppercase tracking-widest text-slate-600">
        <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
        <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
      </div>
    </div>
  </div>
</footer>
<?php include 'chatbot.php'; ?>