import React from 'react';
import Navbar from './components/Navbar.tsx';
import Hero from './components/Hero.tsx';
import Features from './components/Features.tsx';
import CourseExplorer from './components/CourseExplorer.tsx';
import AIImageGenerator from './components/AIImageGenerator.tsx';
import ChatBot from './components/ChatBot.tsx';

const App: React.FC = () => {
  return (
    <div className="min-h-screen bg-[#020617] text-slate-50 relative selection:bg-indigo-500/30">
      <div className="fixed top-0 left-0 w-full h-[500px] bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none -translate-y-1/2"></div>

      <Navbar />

      <main className="relative pt-20">
        <Hero />
        <Features />
        <CourseExplorer />
        <AIImageGenerator />
      </main>

      <footer className="py-16 border-t border-white/5 bg-[#020617] mt-20 relative z-10">
        <div className="max-w-7xl mx-auto px-6 text-center">
          <div className="flex flex-col items-center gap-6">
            <div className="flex items-center gap-3">
              <div className="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <span className="text-xl font-bold tracking-tight text-white">Openly</span>
            </div>
            <div className="flex flex-wrap justify-center gap-x-8 gap-y-4 text-sm font-medium text-slate-400">
              <a href="#" className="hover:text-white transition-colors">Privacy</a>
              <a href="#" className="hover:text-white transition-colors">Terms</a>
              <a href="#" className="hover:text-white transition-colors">Contact</a>
            </div>
            <p className="text-slate-600 text-xs mt-4">
              &copy; {new Date().getFullYear()} Openly. Built for the modern engineer.
            </p>
          </div>
        </div>
      </footer>

      <ChatBot />
    </div>
  );
};

export default App;