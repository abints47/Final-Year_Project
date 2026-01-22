import React, { useState, useEffect } from 'react';

const Navbar: React.FC = () => {
  const [scrolled, setScrolled] = useState(false);

  useEffect(() => {
    const handleScroll = () => setScrolled(window.scrollY > 20);
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <nav className={`fixed top-0 left-0 right-0 z-[50] transition-all duration-300 border-b ${scrolled ? 'bg-[#0f172a]/80 backdrop-blur-xl border-white/5 py-3' : 'bg-transparent border-transparent py-6'}`}>
      <div className="max-w-7xl mx-auto px-6 flex items-center justify-between">
        <div className="flex items-center gap-3 cursor-pointer group" onClick={() => window.scrollTo({top: 0, behavior: 'smooth'})}>
          <div className="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110 shadow-lg shadow-indigo-600/20">
            <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <span className="text-xl font-bold tracking-tight text-white">Techyarjun</span>
        </div>

        <div className="hidden md:flex items-center gap-8">
          <a href="#explore-courses" className="text-sm font-medium text-slate-400 hover:text-white transition-colors">Courses</a>
          <a href="#features" className="text-sm font-medium text-slate-400 hover:text-white transition-colors">Features</a>
          <a href="#ai-generator" className="text-sm font-medium text-slate-400 hover:text-white transition-colors">AI Studio</a>
        </div>

        <div className="flex items-center gap-4">
          <button className="hidden sm:block text-sm font-bold text-slate-400 hover:text-white transition-colors">Log in</button>
          <button className="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-lg shadow-indigo-600/20 transition-all active:scale-95">
            Get Started
          </button>
        </div>
      </div>
    </nav>
  );
};

export default Navbar;