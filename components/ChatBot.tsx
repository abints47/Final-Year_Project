import React, { useState, useRef, useEffect } from 'react';
import { startAIChat } from '../services/geminiService.ts';
import { ChatMessage } from '../types.ts';
import { botDataset } from '../services/botDataset.ts';

const ChatBot: React.FC = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [messages, setMessages] = useState<ChatMessage[]>([
    { role: 'model', text: 'Hello! I am your AI learning assistant. How can I help you master a new skill today?' }
  ]);
  const [input, setInput] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const scrollRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (scrollRef.current) {
      scrollRef.current.scrollTop = scrollRef.current.scrollHeight;
    }
  }, [messages, isLoading, isOpen]);

  const handleSend = async (textOverride?: string) => {
    const text = textOverride || input;
    if (!text.trim() || isLoading) return;

    const userMsg: ChatMessage = { role: 'user', text };
    setMessages(prev => [...prev, userMsg]);
    setInput('');
    setIsLoading(true);

    try {
      // 1. Check local dataset first
      const lowercaseText = text.toLowerCase();
      const datasetMatch = botDataset.find(entry =>
        entry.keywords.some(keyword => lowercaseText.includes(keyword))
      );

      if (datasetMatch) {
        // Add a slight artificial delay for natural feel
        await new Promise(resolve => setTimeout(resolve, 500));
        setMessages(prev => [...prev, { role: 'model', text: datasetMatch.answer }]);
        setIsLoading(false);
        return;
      }

      // 2. Fallback to AI
      const history = messages.map(m => ({ role: m.role, text: m.text }));
      const response = await startAIChat(history, text);
      setMessages(prev => [...prev, { role: 'model', text: response || 'I did not get a response.' }]);
    } catch (err) {
      setMessages(prev => [...prev, { role: 'model', text: 'Connection error. Please check your API key.' }]);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="fixed bottom-8 right-8 z-[100]">
      {isOpen && (
        <div className="bg-[#0f172a] border border-white/10 w-[360px] md:w-[400px] h-[600px] rounded-[2rem] shadow-2xl flex flex-col mb-6 overflow-hidden animate-in origin-bottom-right">
          <div className="bg-indigo-600 px-6 py-5 flex items-center justify-between shadow-lg relative z-10">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white backdrop-blur-sm">
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
              </div>
              <div>
                <h4 className="font-bold text-white leading-tight">Study Guide AI</h4>
                <div className="flex items-center gap-1.5 mt-0.5">
                  <span className="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                  <span className="text-[10px] text-indigo-100 font-bold uppercase tracking-wider opacity-90">Online</span>
                </div>
              </div>
            </div>
            <button onClick={() => setIsOpen(false)} className="text-white/70 hover:text-white transition-colors p-1">
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
          </div>

          <div ref={scrollRef} className="flex-1 overflow-y-auto p-6 space-y-4 bg-[#020617]/50 scroll-smooth scrollbar-none">
            {messages.map((msg, idx) => (
              <div key={idx} className={`flex ${msg.role === 'user' ? 'justify-end' : 'justify-start'}`}>
                <div className={`max-w-[85%] p-4 rounded-2xl text-sm leading-relaxed ${msg.role === 'user' ? 'bg-indigo-600 text-white rounded-tr-none shadow-md' : 'bg-[#1e293b] border border-white/5 text-slate-200 rounded-tl-none shadow-sm'
                  }`}>
                  {msg.text}
                </div>
              </div>
            ))}
            {isLoading && (
              <div className="flex justify-start">
                <div className="bg-[#1e293b] p-4 rounded-2xl rounded-tl-none border border-white/5 flex gap-1.5">
                  <div className="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce"></div>
                  <div className="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce [animation-delay:0.2s]"></div>
                  <div className="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce [animation-delay:0.4s]"></div>
                </div>
              </div>
            )}
          </div>

          <div className="p-4 bg-[#0f172a] border-t border-white/5">
            {messages.length < 3 && (
              <div className="flex gap-2 overflow-x-auto pb-3 scrollbar-none">
                {['How do I start React?', 'Explain Pointers', 'Web Dev Roadmap'].map(suggestion => (
                  <button
                    key={suggestion}
                    onClick={() => handleSend(suggestion)}
                    className="whitespace-nowrap px-3 py-1.5 bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs font-bold rounded-lg border border-white/5 transition-colors"
                  >
                    {suggestion}
                  </button>
                ))}
              </div>
            )}
            <div className="relative">
              <input
                type="text"
                value={input}
                onChange={(e) => setInput(e.target.value)}
                onKeyDown={(e) => e.key === 'Enter' && handleSend()}
                placeholder="Ask anything..."
                className="w-full bg-[#020617] border border-white/10 rounded-2xl py-4 pl-5 pr-14 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder:text-slate-600 transition-all"
              />
              <button
                onClick={() => handleSend()}
                disabled={isLoading || !input.trim()}
                className="absolute right-2 top-2 p-2 text-indigo-500 hover:text-indigo-400 disabled:text-slate-700 transition-colors"
              >
                <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" /></svg>
              </button>
            </div>
          </div>
        </div>
      )}

      <button
        onClick={() => setIsOpen(!isOpen)}
        className="bg-indigo-600 hover:bg-indigo-500 text-white w-14 h-14 md:w-16 md:h-16 rounded-2xl shadow-2xl shadow-indigo-600/30 flex items-center justify-center transition-all hover:scale-105 active:scale-95 group overflow-hidden"
      >
        <svg className={`w-8 h-8 transition-transform duration-300 ${isOpen ? 'rotate-90' : 'group-hover:rotate-12'}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
          {isOpen ? (
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2.5} d="M6 18L18 6M6 6l12 12" />
          ) : (
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2.5} d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
          )}
        </svg>
      </button>
    </div>
  );
};

export default ChatBot;