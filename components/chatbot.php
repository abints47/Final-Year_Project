<?php
// components/chatbot.php
?>
<div class="fixed bottom-8 right-8 z-[100]" id="chatbot-container">
    <!-- Chat Window -->
    <div id="chat-window"
        class="hidden bg-[#0f172a] border border-white/10 w-[360px] md:w-[400px] h-[600px] rounded-[2rem] shadow-2xl flex flex-col mb-6 overflow-hidden animate-in origin-bottom-right">
        <div class="bg-indigo-600 px-6 py-5 flex items-center justify-between shadow-lg relative z-10">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-white leading-tight">Study Guide AI</h4>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                        <span
                            class="text-[10px] text-indigo-100 font-bold uppercase tracking-wider opacity-90">Online</span>
                    </div>
                </div>
            </div>
            <button id="close-chat" class="text-white/70 hover:text-white transition-colors p-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="chat-messages"
            class="flex-1 overflow-y-auto p-6 space-y-4 bg-[#020617]/50 scroll-smooth scrollbar-none">
            <div class="flex justify-start">
                <div
                    class="max-w-[85%] p-4 rounded-2xl text-sm leading-relaxed bg-[#1e293b] border border-white/5 text-slate-200 rounded-tl-none shadow-sm">
                    Hello! I am your AI learning assistant. How can I help you master a new skill today?
                </div>
            </div>
        </div>

        <div class="p-4 bg-[#0f172a] border-t border-white/5">
            <div id="suggestions" class="flex gap-2 overflow-x-auto pb-3 mb-2 scrollbar-none">
                <button onclick="sendChatMessage('How do I start React?')"
                    class="whitespace-nowrap px-3 py-1.5 bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs font-bold rounded-lg border border-white/5 transition-colors">How
                    do I start React?</button>
                <button onclick="sendChatMessage('Explain Pointers')"
                    class="whitespace-nowrap px-3 py-1.5 bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs font-bold rounded-lg border border-white/5 transition-colors">Explain
                    Pointers</button>
                <button onclick="sendChatMessage('Web Dev Roadmap')"
                    class="whitespace-nowrap px-3 py-1.5 bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white text-xs font-bold rounded-lg border border-white/5 transition-colors">Web
                    Dev Roadmap</button>
            </div>
            <div class="relative">
                <input type="text" id="chat-input" placeholder="Ask anything..."
                    class="w-full bg-[#020617] border border-white/10 rounded-2xl py-4 pl-5 pr-14 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder:text-slate-600 transition-all" />
                <button id="send-btn"
                    class="absolute right-2 top-2 p-2 text-indigo-500 hover:text-indigo-400 disabled:text-slate-700 transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Floating Button -->
    <button id="toggle-chat"
        class="bg-indigo-600 hover:bg-indigo-500 text-white w-14 h-14 md:w-16 md:h-16 rounded-2xl shadow-2xl shadow-indigo-600/30 flex items-center justify-center transition-all hover:scale-105 active:scale-95 group overflow-hidden">
        <svg id="toggle-icon" class="w-8 h-8 transition-transform duration-300 group-hover:rotate-12" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>
</div>

<style>
    #chat-window.animate-in {
        animation: chatSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes chatSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    const botDataset = [
        { keywords: ["course", "courses", "learn", "teach", "what do you offer"], answer: "We offer a wide range of courses including HTML Foundations, Modern CSS, JavaScript Mastery, React 19, C Programming, Java Enterprise, Go Microservices, and MongoDB. You can find them all on our Courses page!" },
        { keywords: ["certificate", "certification", "degree", "diploma"], answer: "Yes! Upon successful completion of any course on Openly, you will receive a digital certificate that you can add to your LinkedIn profile or resume." },
        { keywords: ["price", "cost", "free", "payment", "money"], answer: "Many of our introductory courses are completely free! For premium specialized tracks, we offer competitive pricing and occasional discounts for students." },
        { keywords: ["login", "signin", "account", "signup", "register"], answer: "You can create an account by clicking the 'Sign Up' button in the navigation bar. If you already have an account, just use the 'Login' option." },
        { keywords: ["who are you", "what is techyarjun", "what is openly", "about", "creator"], answer: "Openly is an AI-powered learning platform designed to help students and professionals master modern tech skills through hands-on projects and expert-led content." },
        { keywords: ["contact", "support", "help", "email", "reach out"], answer: "You can reach our support team via the Contact page or email us directly at support@openly.com. We're here to help!" },
        { keywords: ["react", "frontend", "framework"], answer: "Our React 19 Framework course covers everything from component architecture to the latest Server Components features. It's perfect for advanced frontend developers!" },
        { keywords: ["python", "ai", "machine learning", "ml"], answer: "We have extensive resources for Python and AI. Check out our 'Master a Language' section on the homepage for deep dives into AI-related technologies." },
        { keywords: ["java", "backend", "enterprise"], answer: "Our Java Enterprise course teaches you OOP principles and how to build robust, scalable server-side applications used by top companies." },
        { keywords: ["track", "progress", "streak"], answer: "You can track your learning progress and daily streaks directly on your Dashboard. Keep learning every day to maintain your streak!" }
    ];

    const toggleBtn = document.getElementById('toggle-chat');
    const closeBtn = document.getElementById('close-chat');
    const chatWindow = document.getElementById('chat-window');
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const sendBtn = document.getElementById('send-btn');
    const toggleIcon = document.getElementById('toggle-icon');

    const iconPathChat = "M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z";
    const iconPathClose = "M6 18L18 6M6 6l12 12";

    toggleBtn.addEventListener('click', () => {
        const isHidden = chatWindow.classList.contains('hidden');
        if (isHidden) {
            chatWindow.classList.remove('hidden');
            toggleIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="${iconPathClose}" />`;
            toggleIcon.classList.add('rotate-90');
        } else {
            chatWindow.classList.add('hidden');
            toggleIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="${iconPathChat}" />`;
            toggleIcon.classList.remove('rotate-90');
        }
    });

    closeBtn.addEventListener('click', () => {
        chatWindow.classList.add('hidden');
        toggleIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="${iconPathChat}" />`;
        toggleIcon.classList.remove('rotate-90');
    });

    async function sendChatMessage(text) {
        const message = text || chatInput.value.trim();
        if (!message) return;

        // User Message
        addMessage(message, 'user');
        chatInput.value = '';

        // Loading State
        const loadingId = addLoading();

        // Check Dataset
        const lowercaseText = message.toLowerCase();
        const datasetMatch = botDataset.find(entry =>
            entry.keywords.some(keyword => lowercaseText.includes(keyword))
        );

        if (datasetMatch) {
            await new Promise(r => setTimeout(r, 600));
            removeLoading(loadingId);
            addMessage(datasetMatch.answer, 'model');
        } else {
            // Fallback or simple AI simulate for now as we don't have direct access to Gemini service easily here without bundling
            await new Promise(r => setTimeout(r, 1500));
            removeLoading(loadingId);
            addMessage("I am a learning assistant. I can answer questions about Openly's courses, certificates, and more. For complex queries, please visit our documentation or contact support.", 'model');
        }
    }

    function addMessage(text, role) {
        const div = document.createElement('div');
        div.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'}`;
        div.innerHTML = `
        <div class="max-w-[85%] p-4 rounded-2xl text-sm leading-relaxed ${role === 'user' ? 'bg-indigo-600 text-white rounded-tr-none shadow-md' : 'bg-[#1e293b] border border-white/5 text-slate-200 rounded-tl-none shadow-sm'
            }">
            ${text}
        </div>
    `;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function addLoading() {
        const id = 'loading-' + Date.now();
        const div = document.createElement('div');
        div.id = id;
        div.className = 'flex justify-start';
        div.innerHTML = `
        <div class="bg-[#1e293b] p-4 rounded-2xl rounded-tl-none border border-white/5 flex gap-1.5">
            <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce"></div>
            <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce [animation-delay:0.2s]"></div>
            <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce [animation-delay:0.4s]"></div>
        </div>
    `;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        return id;
    }

    function removeLoading(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    chatInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') sendChatMessage();
    });

    sendBtn.addEventListener('click', () => sendChatMessage());
</script>