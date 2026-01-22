import React, { useState } from 'react';
import { generateAIImage, checkAndRequestKey } from '../services/geminiService.ts';
import { ImageSize, GeneratedImage } from '../types.ts';

const AIImageGenerator: React.FC = () => {
  const [prompt, setPrompt] = useState('');
  const [loading, setLoading] = useState(false);
  const [images, setImages] = useState<GeneratedImage[]>([]);
  const [error, setError] = useState<string | null>(null);

  const handleGenerate = async () => {
    if (!prompt.trim()) return;
    setLoading(true);
    setError(null);
    try {
      const imageUrl = await generateAIImage(prompt, ImageSize.SIZE_1K);
      setImages(prev => [{ url: imageUrl, prompt, timestamp: Date.now() }, ...prev]);
      setPrompt('');
    } catch (err: any) {
      setError(err.message || "An unexpected error occurred.");
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const onOpenKeyDialog = async () => {
    const win = window as any;
    if (win.aistudio) await win.aistudio.openSelectKey();
  };

  return (
    <section id="ai-generator" className="py-20 px-6 animate-in" style={{ animationDelay: '0.4s' }}>
      <div className="max-w-5xl mx-auto bg-[#0f172a] rounded-[3rem] p-10 md:p-16 relative overflow-hidden border border-white/5 shadow-2xl">
        <div className="text-center mb-12 relative z-10">
          <div className="inline-flex items-center gap-2 px-3 py-1 bg-indigo-500/10 rounded-full border border-indigo-500/20 mb-6">
            <span className="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
            <span className="text-[10px] font-black uppercase tracking-widest text-indigo-300">Gemini 3 Pro Vision</span>
          </div>
          <h2 className="text-4xl md:text-5xl font-black text-white mb-4 tracking-tight">Creative Studio</h2>
          <p className="text-slate-400 text-lg max-w-xl mx-auto font-medium">
            Describe your vision and let Gemini bring it to life. 
          </p>
        </div>

        <div className="max-w-2xl mx-auto relative z-10">
          <div className="bg-[#020617] rounded-[2rem] p-3 shadow-xl border border-white/10 flex flex-col sm:flex-row gap-2">
            <input 
              type="text"
              value={prompt}
              onChange={(e) => setPrompt(e.target.value)}
              onKeyDown={(e) => e.key === 'Enter' && handleGenerate()}
              placeholder="Describe what you want to create..."
              className="flex-1 bg-transparent border-none px-6 py-4 text-base text-white focus:outline-none placeholder:text-slate-600"
            />
            <button 
              onClick={handleGenerate}
              disabled={loading || !prompt.trim()}
              className="bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-4 rounded-[1.5rem] font-bold text-sm disabled:bg-slate-800 disabled:text-slate-500 transition-all active:scale-95"
            >
              {loading ? (
                <div className="flex items-center gap-2">
                  <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                  <span>Creating</span>
                </div>
              ) : 'Generate'}
            </button>
          </div>
          
          <div className="mt-6 flex justify-center gap-6">
            <button 
              onClick={onOpenKeyDialog}
              className="text-[10px] font-bold text-indigo-400 uppercase tracking-widest hover:text-white transition-colors"
            >
              Switch API Key
            </button>
          </div>

          {error && (
            <div className="mt-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-200 text-sm font-medium text-center">
              {error}
            </div>
          )}
        </div>

        {images.length > 0 && (
          <div className="grid grid-cols-2 md:grid-cols-3 gap-6 mt-16">
            {images.map((img) => (
              <div key={img.timestamp} className="card-hover bg-[#020617] rounded-2xl overflow-hidden border border-white/5 group">
                <img src={img.url} alt={img.prompt} className="w-full aspect-square object-cover" />
                <div className="p-4">
                  <p className="text-[10px] text-slate-500 font-mono truncate">"{img.prompt}"</p>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </section>
  );
};

export default AIImageGenerator;