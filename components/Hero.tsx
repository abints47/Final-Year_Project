import React from 'react';

const Hero: React.FC = () => {
  return (
    <section className="pt-32 pb-20 px-6 relative overflow-hidden text-center">
      <div className="max-w-5xl mx-auto relative z-10">
        <div className="animate-in" style={{ animationDelay: '0.1s' }}>
          <span className="inline-block px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[11px] font-bold uppercase tracking-widest mb-8">
            The Future of Learning
          </span>
          <h1 className="text-5xl md:text-7xl lg:text-8xl font-black text-white leading-[1.1] tracking-tight mb-8">
            Learn the Skills of <br />
            <span className="gradient-text">Tomorrow, Today.</span>
          </h1>
          <p className="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto font-medium leading-relaxed mb-10">
            Master high-demand engineering skills with our AI-powered platform. Join thousands of developers building the future.
          </p>
          <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="#explore-courses" className="w-full sm:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-600/20 transition-all active:scale-95">
              Explore Courses
            </a>
            <button className="w-full sm:w-auto px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white border border-white/5 rounded-2xl font-bold text-lg transition-all active:scale-95">
              View Syllabus
            </button>
          </div>
        </div>
      </div>
    </section>
  );
};

export default Hero;