<!-- SKILLOKA 3D CHATBOT WIDGET (LOKA AI ASSISTANT) -->
<div id="skillokaChatWidget" class="fixed bottom-5 right-5 sm:bottom-6 sm:right-6 z-50 font-sans select-none print:hidden">

    <!-- FLOATING WELCOME SPEECH BUBBLE (AUTO-SHOW / DISMISSIBLE) -->
    <div id="chatGreetingBubble" class="absolute bottom-24 right-2 sm:right-4 w-64 p-3.5 rounded-2xl bg-white/95 backdrop-blur-md border border-slate-200 shadow-xl shadow-brand-900/10 text-xs text-slate-700 transition-all duration-300 transform translate-y-2 opacity-0 pointer-events-none z-40">
        <div class="flex items-start justify-between gap-2">
            <div class="flex items-center gap-1.5 font-bold text-slate-900 text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Loka • AI Assistant</span>
            </div>
            <button id="closeGreetingBtn" class="text-slate-400 hover:text-slate-600 p-0.5 rounded transition" title="Tutup">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
        <p class="mt-1 text-slate-600 leading-snug">
            Ada yang bisa dibantu seputar kursus atau LPK di Indramayu? Yuk ngobrol bareng Loka! 👋
        </p>
        <div class="mt-2.5 flex items-center justify-between">
            <span class="text-[10px] text-brand-600 font-semibold cursor-pointer hover:underline" onclick="toggleSkillokaChat(true)">Buka Chat &rarr;</span>
            <span class="text-[10px] text-slate-400">Online</span>
        </div>
        <!-- Little bubble tail -->
        <div class="absolute -bottom-1.5 right-8 w-3 h-3 bg-white border-r border-b border-slate-200 transform rotate-45"></div>
    </div>

    <!-- 3D FLOATING BOT LAUNCHER BUTTON -->
    <div class="relative group cursor-pointer" onclick="toggleSkillokaChat()">
        <!-- Bot Dynamic Soft Ground Shadow -->
        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-14 h-3 bg-slate-900/30 rounded-full blur-[3px] transition-all duration-500 group-hover:w-16 group-hover:opacity-70 group-hover:blur-sm" style="animation: botShadowPulse 3.2s ease-in-out infinite;"></div>

        <!-- Golden Orbit Halo Pulse -->
        <div class="absolute inset-0 rounded-full bg-amber-400/25 blur-lg scale-90 group-hover:scale-110 group-hover:bg-amber-400/40 transition-all duration-300 pointer-events-none"></div>

        <!-- 3D Bot Character Frame -->
        <div class="relative w-20 h-20 sm:w-22 sm:h-22 flex items-center justify-center transition-transform duration-300 group-hover:scale-105 active:scale-95" style="animation: botFloat 3.4s ease-in-out infinite;">
            <img 
                src="{{ asset('assets/images/chatbot-3d-transparent.png') }}" 
                alt="Loka - 3D AI Assistant Skilloka" 
                class="w-full h-full object-contain filter drop-shadow-[0_10px_15px_rgba(15,23,42,0.25)] group-hover:drop-shadow-[0_12px_20px_rgba(245,158,11,0.35)] transition-all duration-300 pointer-events-none"
            />

            <!-- Online Status Dot Indicator -->
            <span class="absolute bottom-2 right-2 w-3.5 h-3.5 bg-emerald-500 border-2 border-white rounded-full shadow-sm z-20 flex items-center justify-center">
                <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span>
            </span>
        </div>
    </div>

    <!-- INTERACTIVE CHAT WINDOW (POPUP MODAL / DRAWER) -->
    <div id="skillokaChatWindow" class="fixed sm:absolute bottom-0 right-0 sm:bottom-24 sm:right-0 w-full sm:w-[410px] h-[90vh] sm:h-[600px] max-h-[700px] bg-white sm:rounded-3xl shadow-2xl border border-slate-200/90 flex flex-col overflow-hidden transition-all duration-300 transform translate-y-6 opacity-0 pointer-events-none z-50">
        
        <!-- HEADER -->
        <div class="p-4 bg-gradient-to-r from-[#0F172A] via-[#1E293B] to-[#0F172A] text-white flex items-center justify-between shadow-md relative">
            <div class="flex items-center gap-3">
                <!-- Mini 3D Avatar in Header -->
                <div class="relative w-11 h-11 rounded-2xl bg-slate-800/80 border border-amber-400/40 p-1 flex items-center justify-center shadow-inner">
                    <img src="{{ asset('assets/images/chatbot-3d-transparent.png') }}" alt="Loka" class="w-full h-full object-contain">
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-slate-900 rounded-full"></span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-outfit font-bold text-sm tracking-tight text-white">Loka</h2>
                        <span class="px-1.5 py-0.5 rounded-full bg-amber-400/20 text-amber-300 text-[10px] font-semibold border border-amber-400/30">AI Skilloka</span>
                    </div>
                    <p class="text-[11px] text-slate-300 leading-tight">Asisten Resmi Pelatihan & LPK</p>
                </div>
            </div>

            <div class="flex items-center gap-1.5">
                <!-- Clear History Button -->
                <button onclick="clearSkillokaChat()" class="w-8 h-8 rounded-xl bg-slate-800/70 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center transition" title="Hapus Riwayat Chat">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
                <!-- Close Button -->
                <button onclick="toggleSkillokaChat(false)" class="w-8 h-8 rounded-xl bg-slate-800/70 hover:bg-rose-600 text-slate-300 hover:text-white flex items-center justify-center transition" title="Tutup Chat">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </div>

        <!-- BANNER INFO (KAB. INDRAMAYU) -->
        <div class="px-4 py-2 bg-brand-50 border-b border-brand-100 flex items-center justify-between text-[11px] text-brand-800 font-medium">
            <span class="flex items-center gap-1.5">
                <i class="fa-solid fa-location-dot text-brand-600"></i>
                <span>Wilayah Indramayu & Standar BNSP</span>
            </span>
            <span class="text-[10px] px-2 py-0.5 rounded bg-white font-semibold text-brand-700 border border-brand-200">Resmi</span>
        </div>

        <!-- MESSAGE LIST SCROLL CONTAINER -->
        <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-3.5 bg-slate-50/60 text-sm">
            <!-- Initial Bot Welcome Bubble -->
            <div class="flex items-start gap-2.5">
                <div class="w-8 h-8 rounded-full bg-slate-900 border border-amber-400/40 p-0.5 flex-shrink-0 flex items-center justify-center">
                    <img src="{{ asset('assets/images/chatbot-3d-transparent.png') }}" alt="Loka" class="w-full h-full object-contain">
                </div>
                <div class="max-w-[82%] bg-white p-3.5 rounded-2xl rounded-tl-sm border border-slate-200/80 shadow-xs text-slate-800 text-xs sm:text-[13px] leading-relaxed">
                    <p class="font-medium text-slate-900 mb-1">Halo! Selamat datang di Skilloka 👋</p>
                    <p class="text-slate-600">
                        Saya <strong>Loka</strong>, asisten cerdas yang siap menjawab pertanyaan seputar katalog kursus, sertifikasi BNSP, pendaftaran LPK, maupun panduan booking.
                    </p>
                    <p class="text-slate-600 mt-2">
                        Silakan pilih pertanyaan cepat di bawah atau ketik langsung pertanyaan Anda ya! 😊
                    </p>
                </div>
            </div>

            <!-- QUICK SUGGESTION CHIPS -->
            <div id="chatQuickChips" class="pt-1 pb-1 space-y-1.5">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider px-1">Pertanyaan Populer:</p>
                <div class="flex flex-wrap gap-1.5">
                    <button onclick="sendQuickPrompt('Apa itu Skilloka dan bagaimana cara kerjanya?')" class="px-2.5 py-1.5 rounded-xl bg-white hover:bg-brand-50 border border-slate-200 hover:border-brand-300 text-slate-700 hover:text-brand-700 text-xs font-medium transition text-left shadow-2xs">
                        🎓 Apa itu Skilloka?
                    </button>
                    <button onclick="sendQuickPrompt('Kursus apa saja yang tersedia di Indramayu?')" class="px-2.5 py-1.5 rounded-xl bg-white hover:bg-brand-50 border border-slate-200 hover:border-brand-300 text-slate-700 hover:text-brand-700 text-xs font-medium transition text-left shadow-2xs">
                        🔍 Rekomendasi Kursus Aktif
                    </button>
                    <button onclick="sendQuickPrompt('Bagaimana cara LPK mendaftar menjadi mitra?')" class="px-2.5 py-1.5 rounded-xl bg-white hover:bg-brand-50 border border-slate-200 hover:border-brand-300 text-slate-700 hover:text-brand-700 text-xs font-medium transition text-left shadow-2xs">
                        🏢 Cara LPK Jadi Mitra
                    </button>
                    <button onclick="sendQuickPrompt('Apakah kursus di Skilloka bersertifikat BNSP?')" class="px-2.5 py-1.5 rounded-xl bg-white hover:bg-brand-50 border border-slate-200 hover:border-brand-300 text-slate-700 hover:text-brand-700 text-xs font-medium transition text-left shadow-2xs">
                        📜 Sertifikasi BNSP
                    </button>
                    <button onclick="sendQuickPrompt('Metode pembayaran apa saja yang bisa digunakan?')" class="px-2.5 py-1.5 rounded-xl bg-white hover:bg-brand-50 border border-slate-200 hover:border-brand-300 text-slate-700 hover:text-brand-700 text-xs font-medium transition text-left shadow-2xs">
                        💳 Cara Bayar (Midtrans)
                    </button>
                </div>
            </div>

            <!-- TYPING INDICATOR (HIDDEN BY DEFAULT) -->
            <div id="chatTypingIndicator" class="hidden flex items-center gap-2 text-slate-400 text-xs pt-1">
                <div class="w-7 h-7 rounded-full bg-slate-900 border border-amber-400/40 p-0.5 flex-shrink-0 flex items-center justify-center">
                    <img src="{{ asset('assets/images/chatbot-3d-transparent.png') }}" alt="Loka" class="w-full h-full object-contain">
                </div>
                <div class="bg-white px-3 py-2 rounded-2xl rounded-tl-sm border border-slate-200 shadow-xs flex items-center gap-1.5">
                    <span class="text-[11px] text-slate-500 font-medium">Loka sedang mengetik</span>
                    <span class="w-1.5 h-1.5 bg-brand-500 rounded-full animate-bounce" style="animation-delay: 0s;"></span>
                    <span class="w-1.5 h-1.5 bg-brand-500 rounded-full animate-bounce" style="animation-delay: 0.15s;"></span>
                    <span class="w-1.5 h-1.5 bg-brand-500 rounded-full animate-bounce" style="animation-delay: 0.3s;"></span>
                </div>
            </div>
        </div>

        <!-- INPUT FORM -->
        <div class="p-3 bg-white border-t border-slate-200">
            <form id="skillokaChatForm" onsubmit="handleChatSubmit(event)" class="flex items-center gap-2">
                @csrf
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        id="chatInput" 
                        placeholder="Tanya seputar kursus, LPK, sertifikat..." 
                        autocomplete="off"
                        maxlength="400"
                        class="w-full py-2.5 pl-3.5 pr-8 rounded-xl bg-slate-100/80 border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white transition"
                    />
                    <button type="button" onclick="document.getElementById('chatInput').value = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500 text-xs">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>

                <button 
                    type="submit" 
                    id="chatSendBtn"
                    class="h-10 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 active:scale-95 text-white font-semibold text-xs flex items-center justify-center gap-1.5 shadow-md shadow-brand-500/20 transition-all disabled:opacity-50 disabled:pointer-events-none"
                >
                    <span>Kirim</span>
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </form>
            <div class="mt-1.5 flex items-center justify-between text-[10px] text-slate-400 px-1">
                <span>Didukung AI Skilloka &bull; Data Resmi Indramayu</span>
                <span id="chatSourceBadge" class="font-mono text-[9px] text-slate-400">Siap</span>
            </div>
        </div>

    </div>
</div>

<!-- STYLES & ANIMATIONS FOR 3D BOT -->
<style>
@keyframes botFloat {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-10px) rotate(2deg);
    }
}

@keyframes botShadowPulse {
    0%, 100% {
        transform: translateX(-50%) scale(1);
        opacity: 0.35;
    }
    50% {
        transform: translateX(-50%) scale(0.82);
        opacity: 0.18;
    }
}

/* Chat bubble markdown-like formatting */
.chat-reply-bubble strong {
    font-weight: 700;
    color: #0f172a;
}
.chat-reply-bubble a {
    color: #2563eb;
    text-decoration: underline;
    font-weight: 600;
}
.chat-reply-bubble a:hover {
    color: #1d4ed8;
}
.chat-reply-bubble ul {
    margin-top: 6px;
    margin-bottom: 6px;
    padding-left: 18px;
    list-style-type: disc;
}
</style>

<!-- CHATBOT CLIENT SCRIPT -->
<script>
(function() {
    let chatHistory = [];
    const chatModal = document.getElementById('skillokaChatWindow');
    const greetingBubble = document.getElementById('chatGreetingBubble');
    const messagesContainer = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const chatSendBtn = document.getElementById('chatSendBtn');
    const typingIndicator = document.getElementById('chatTypingIndicator');
    const chatSourceBadge = document.getElementById('chatSourceBadge');
    let isOpen = false;

    // Load history from session
    try {
        const saved = sessionStorage.getItem('skilloka_chat_history');
        if (saved) {
            chatHistory = JSON.parse(saved);
            chatHistory.forEach(item => {
                appendMessageToUI(item.sender, item.text, false);
            });
        }
    } catch(e) {}

    // Show greeting bubble after 3.5s if not already opened
    setTimeout(() => {
        if (!isOpen && greetingBubble && !sessionStorage.getItem('skilloka_bubble_dismissed')) {
            greetingBubble.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
            greetingBubble.classList.add('opacity-100', 'translate-y-0');
        }
    }, 3500);

    const closeGreetingBtn = document.getElementById('closeGreetingBtn');
    if (closeGreetingBtn) {
        closeGreetingBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dismissGreetingBubble();
        });
    }

    function dismissGreetingBubble() {
        if (greetingBubble) {
            greetingBubble.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
            greetingBubble.classList.remove('opacity-100', 'translate-y-0');
            sessionStorage.setItem('skilloka_bubble_dismissed', 'true');
        }
    }

    // Toggle Chat Window
    window.toggleSkillokaChat = function(forceState) {
        if (typeof forceState === 'boolean') {
            isOpen = forceState;
        } else {
            isOpen = !isOpen;
        }

        if (isOpen) {
            dismissGreetingBubble();
            chatModal.classList.remove('opacity-0', 'translate-y-6', 'pointer-events-none');
            chatModal.classList.add('opacity-100', 'translate-y-0');
            setTimeout(() => {
                chatInput.focus();
                scrollToBottom();
            }, 100);
        } else {
            chatModal.classList.add('opacity-0', 'translate-y-6', 'pointer-events-none');
            chatModal.classList.remove('opacity-100', 'translate-y-0');
        }
    };

    window.clearSkillokaChat = function() {
        if (!confirm('Hapus riwayat obrolan dengan Loka?')) return;
        chatHistory = [];
        sessionStorage.removeItem('skilloka_chat_history');
        
        // Remove all user and bot replies, keep initial greeting
        const bubbles = messagesContainer.querySelectorAll('.dynamic-chat-bubble');
        bubbles.forEach(b => b.remove());
        const chips = document.getElementById('chatQuickChips');
        if (chips) chips.style.display = 'block';
        chatSourceBadge.textContent = 'Siap';
    };

    window.sendQuickPrompt = function(promptText) {
        chatInput.value = promptText;
        handleChatSubmit(new Event('submit'));
    };

    window.handleChatSubmit = async function(e) {
        if (e) e.preventDefault();
        const text = chatInput.value.trim();
        if (!text) return;

        // Hide quick chips once chatting
        const chips = document.getElementById('chatQuickChips');
        if (chips) chips.style.display = 'none';

        // Add user message
        appendMessageToUI('user', text);
        chatHistory.push({ sender: 'user', text: text });
        saveHistory();

        chatInput.value = '';
        chatSendBtn.disabled = true;
        showTyping(true);

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                || document.querySelector('input[name="_token"]')?.value;

            const res = await fetch("{{ route('chatbot.message') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token || ''
                },
                body: JSON.stringify({
                    message: text,
                    history: chatHistory.slice(-4)
                })
            });

            const data = await res.json();
            showTyping(false);

            if (data && data.reply) {
                appendMessageToUI('bot', data.reply);
                chatHistory.push({ sender: 'bot', text: data.reply });
                saveHistory();
                if (chatSourceBadge) {
                    chatSourceBadge.textContent = data.source === 'gemini' ? 'Gemini 1.5' : 'Smart Engine';
                }
            } else {
                appendMessageToUI('bot', 'Maaf, Loka sedang mengalami kendala jaringan sejenak. Silakan coba lagi ya.');
            }
        } catch (err) {
            showTyping(false);
            console.error('Chatbot error:', err);
            appendMessageToUI('bot', 'Maaf, terjadi gangguan saat menghubungi server. Silakan coba sesaat lagi.');
        } finally {
            chatSendBtn.disabled = false;
            chatInput.focus();
        }
    };

    function appendMessageToUI(sender, text, scroll = true) {
        const wrap = document.createElement('div');
        wrap.className = 'dynamic-chat-bubble flex items-start gap-2.5 ' + (sender === 'user' ? 'justify-end' : '');

        const formattedHtml = formatChatText(text);

        if (sender === 'user') {
            wrap.innerHTML = `
                <div class="max-w-[85%] bg-gradient-to-r from-brand-600 to-indigo-600 text-white p-3 rounded-2xl rounded-tr-sm shadow-xs text-xs sm:text-[13px] leading-relaxed">
                    <p class="whitespace-pre-wrap">${escapeHtml(text)}</p>
                </div>
            `;
        } else {
            wrap.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-slate-900 border border-amber-400/40 p-0.5 flex-shrink-0 flex items-center justify-center">
                    <img src="{{ asset('assets/images/chatbot-3d-transparent.png') }}" alt="Loka" class="w-full h-full object-contain">
                </div>
                <div class="max-w-[85%] bg-white p-3.5 rounded-2xl rounded-tl-sm border border-slate-200/80 shadow-xs text-slate-800 text-xs sm:text-[13px] leading-relaxed chat-reply-bubble">
                    ${formattedHtml}
                </div>
            `;
        }

        // Insert before typing indicator
        messagesContainer.insertBefore(wrap, typingIndicator);

        if (scroll) {
            scrollToBottom();
        }
    }

    function showTyping(show) {
        if (show) {
            typingIndicator.classList.remove('hidden');
            scrollToBottom();
        } else {
            typingIndicator.classList.add('hidden');
        }
    }

    function scrollToBottom() {
        setTimeout(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 50);
    }

    function saveHistory() {
        try {
            sessionStorage.setItem('skilloka_chat_history', JSON.stringify(chatHistory.slice(-20)));
        } catch(e) {}
    }

    function escapeHtml(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function formatChatText(str) {
        let escaped = escapeHtml(str);

        // Convert bold: **text**
        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

        // Convert links: [text](url)
        escaped = escaped.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2">$1</a>');

        // Convert newlines to <br>
        escaped = escaped.replace(/\n/g, '<br>');

        return escaped;
    }
})();
</script>
