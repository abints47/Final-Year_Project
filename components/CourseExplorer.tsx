import React from 'react';

interface Course {
  id: string;
  title: string;
  category: string;
  level: string;
  duration: string;
  image: string;
  summary: string;
  ytId: string;
}

const courses: Course[] = [
  {
    id: '1',
    title: 'HTML Foundations',
    category: 'Web',
    level: 'Beginner',
    duration: '8h',
    image: 'https://images.unsplash.com/photo-1542831371-29b0f74f9713?auto=format&fit=crop&q=80&w=800',
    summary: 'Master the architectural structure of the modern web. Semantic tags and SEO excellence.',
    ytId: 'ok-plXXHlWw'
  },
  {
    id: '2',
    title: 'Modern CSS Layouts',
    category: 'Design',
    level: 'Beginner',
    duration: '12h',
    image: 'https://images.unsplash.com/photo-1523437113738-bbd3cc89fb19?auto=format&fit=crop&q=80&w=800',
    summary: 'Style with precision. Deep dive into Flexbox, Grid, and high-end responsiveness.',
    ytId: '1Rs2ND1RYYc'
  },
  {
    id: '3',
    title: 'JS Logic Engine',
    category: 'Code',
    level: 'Medium',
    duration: '35h',
    image: 'https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a?auto=format&fit=crop&q=80&w=800',
    summary: 'From basic loops to advanced asynchronous patterns and browser engines.',
    ytId: 'W6NZfCO5SIk'
  },
  {
    id: '4',
    title: 'React 19 Core',
    category: 'Frontend',
    level: 'Advanced',
    duration: '40h',
    image: 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?auto=format&fit=crop&q=80&w=800',
    summary: 'Build massive interfaces with React Server Components and state management.',
    ytId: 'SqcY0GlvtPk'
  },
  {
    id: '5',
    title: 'C Programming Core',
    category: 'Systems',
    level: 'Hard',
    duration: '50h',
    image: 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&q=80&w=800',
    summary: 'Understand memory, pointers, and the foundation of all computing power.',
    ytId: 'KJgsSFOSQv0'
  },
  {
    id: '6',
    title: 'Java Enterprise',
    category: 'Backend',
    level: 'Medium',
    duration: '45h',
    image: 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&q=80&w=800',
    summary: 'Scalable backend logic. Master OOP, multithreading, and Spring Boot.',
    ytId: 'grEKMHGYyns'
  },
  {
    id: '7',
    title: 'Go (Golang) Cloud',
    category: 'Backend',
    level: 'Medium',
    duration: '22h',
    image: 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&q=80&w=800',
    summary: 'Modern infrastructure. Concurrency, goroutines, and ultra-fast microservices.',
    ytId: 'YS4e4q9oBaU'
  },
  {
    id: '8',
    title: 'MongoDB Mastery',
    category: 'Database',
    level: 'Medium',
    duration: '18h',
    image: 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?auto=format&fit=crop&q=80&w=800',
    summary: 'Flexible modeling. Document databases, scaling, and high-speed queries.',
    ytId: 'vE0Z9pL0Wos'
  }
];

const CourseExplorer: React.FC = () => {
  return (
    <section id="explore-courses" className="py-20 animate-in px-6" style={{ animationDelay: '0.3s' }}>
      <div className="max-w-7xl mx-auto">
        <div className="flex flex-col md:flex-row items-end justify-between mb-12 gap-6">
          <div>
            <h2 className="text-3xl font-black text-white mb-2">Featured Courses</h2>
            <p className="text-slate-400 font-medium">Master new tech stacks with structured learning paths.</p>
          </div>
          <button className="text-indigo-400 font-bold hover:text-indigo-300 transition-all hover:translate-x-1">
            View All Courses →
          </button>
        </div>
        
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          {courses.map((course) => {
             let badgeColor = 'bg-emerald-500';
             if (course.level === 'Medium') badgeColor = 'bg-amber-500';
             if (course.level === 'Hard' || course.level === 'Advanced') badgeColor = 'bg-rose-500';

             return (
              <div key={course.id} className="bg-[#0f172a] border border-white/5 rounded-[2rem] overflow-hidden card-hover group flex flex-col h-full">
                <div className="h-44 overflow-hidden relative">
                  <img src={course.image} alt={course.title} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                  <div className="absolute top-4 left-4 bg-indigo-600/90 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-lg">
                    {course.category}
                  </div>
                  <div className={`absolute top-4 right-4 ${badgeColor} text-white text-[10px] font-black uppercase px-3 py-1.5 rounded-lg shadow-lg`}>
                    {course.level}
                  </div>
                </div>
                <div className="p-6 flex-1 flex flex-col">
                  <div className="flex items-center gap-3 text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-3">
                    <span className="flex items-center gap-1">
                      <svg className="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                      {course.duration}
                    </span>
                  </div>
                  <h3 className="text-lg font-bold text-white mb-2 leading-tight group-hover:text-indigo-400 transition-colors">{course.title}</h3>
                  <p className="text-slate-400 text-xs mb-6 line-clamp-3 leading-relaxed">
                    {course.summary}
                  </p>
                  <div className="mt-auto flex gap-3">
                    <button className="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white py-3 rounded-xl font-bold text-sm transition-all active:scale-95 shadow-lg shadow-indigo-600/10">
                      Start
                    </button>
                    <a 
                      href={`https://www.youtube.com/watch?v=${course.ytId}`} 
                      target="_blank" 
                      className="w-12 bg-slate-800 hover:bg-slate-700 hover:text-red-400 text-slate-400 flex items-center justify-center rounded-xl transition-all"
                    >
                      <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 15l5.19-3L10 9v6zM21.56 7.17c-.17-.67-.7-1.2-1.37-1.37C18.98 5.5 12 5.5 12 5.5s-6.98 0-8.19.3c-.67.17-1.2.7-1.37 1.37C2.11 8.38 2.11 12 2.11 12s0 3.62.3 4.83c.17.67.7 1.2 1.37 1.37 1.21.3 8.19.3 8.19.3s6.98 0 8.19-.3c.67-.17 1.2-.7 1.37-1.37.3-1.21.3-4.83.3-4.83s0-3.62-.3-4.83z"/></svg>
                    </a>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
};

export default CourseExplorer;