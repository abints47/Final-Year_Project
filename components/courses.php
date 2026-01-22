<section id="explore-courses" class="py-20 px-6 max-w-7xl mx-auto">
  <div class="flex items-end justify-between mb-12">
    <div>
      <h2 class="text-3xl font-bold text-white mb-2">Featured Courses</h2>
      <p class="text-slate-400">Master new tech stacks with structured learning paths.</p>
    </div>
    <button class="text-indigo-400 font-bold hover:text-indigo-300 transition-all active:scale-95 hover:translate-x-1">View All Courses →</button>
  </div>
  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php foreach ($courses as $course): 
      $levelColor = 'bg-green-500';
      if($course['level'] == 'Medium') $levelColor = 'bg-amber-500';
      if($course['level'] == 'Hard') $levelColor = 'bg-rose-500';
    ?>
      <div class="bg-slate-900 border border-white/5 rounded-3xl overflow-hidden hover:border-indigo-500/50 transition-all group hover:shadow-2xl hover:shadow-indigo-500/10">
        <div class="aspect-video relative overflow-hidden">
          <img src="<?php echo $course['image']; ?>" alt="<?php echo $course['title']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
          <div class="absolute top-4 left-4 bg-indigo-600/90 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full shadow-lg">
            <?php echo $course['category']; ?>
          </div>
          <div class="absolute top-4 right-4 <?php echo $levelColor; ?> text-white text-[10px] font-black uppercase px-3 py-1.5 rounded-full shadow-lg">
            <?php echo $course['level']; ?>
          </div>
          <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
            <a href="https://www.youtube.com/watch?v=<?php echo $course['yt_id']; ?>" target="_blank" class="bg-white text-black p-4 rounded-full active:scale-90 transition-transform">
               <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
            </a>
          </div>
        </div>
        <div class="p-8">
          <div class="flex items-center gap-4 text-xs font-bold text-slate-500 mb-4">
            <span class="flex items-center gap-1.5">
              <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              <?php echo $course['duration']; ?>
            </span>
            <span class="flex items-center gap-1.5">
              <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
              Level: <?php echo $course['level']; ?>
            </span>
          </div>
          <h3 class="text-xl font-bold text-white mb-3 leading-snug group-hover:text-indigo-400 transition-colors"><?php echo $course['title']; ?></h3>
          <p class="text-slate-400 text-sm mb-6 leading-relaxed line-clamp-2">
            <?php echo $course['desc']; ?>
          </p>
          <div class="flex gap-3">
             <button class="flex-1 bg-slate-800 hover:bg-slate-700 text-white py-3 rounded-2xl font-bold transition-all active:scale-95 border border-white/5 hover:border-indigo-500/30">
               Learn More
             </button>
             <a href="https://www.youtube.com/watch?v=<?php echo $course['yt_id']; ?>" target="_blank" class="bg-indigo-600/10 hover:bg-indigo-600/20 text-indigo-400 px-4 py-3 rounded-2xl border border-indigo-500/20 active:scale-95 transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 15l5.19-3L10 9v6zM21.56 7.17c-.17-.67-.7-1.2-1.37-1.37C18.98 5.5 12 5.5 12 5.5s-6.98 0-8.19.3c-.67.17-1.2.7-1.37 1.37C2.11 8.38 2.11 12 2.11 12s0 3.62.3 4.83c.17.67.7 1.2 1.37 1.37 1.21.3 8.19.3 8.19.3s6.98 0 8.19-.3c.67-.17 1.2-.7 1.37-1.37.3-1.21.3-4.83.3-4.83s0-3.62-.3-4.83z"/></svg>
             </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>