<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Chat Assistant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #1b1d23;
            --surface: #252831;
            --surface-alt: #2f333d;
            --border: #3a3f4b;
            --text-primary: #f7f7f8;
            --text-secondary: #c2c7d2;
            --accent: #10a37f;
            --radius: 16px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text-primary);
        }

        .app-layout {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 280px;
            background: #1f2129;
            border-right: 1px solid #343944;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #343944;
        }

        .new-chat-btn {
            width: 100%;
            border: none;
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
            padding: 12px 14px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.18s ease, transform 0.18s ease;
        }

        .new-chat-btn:hover {
            background: rgba(255, 255, 255, 0.09);
        }

        .history {
            flex: 1;
            overflow-y: auto;
            padding: 12px 12px 24px;
        }

        .history::-webkit-scrollbar {
            width: 4px;
        }

        .history::-webkit-scrollbar-thumb {
            background: #343843;
            border-radius: 999px;
        }

        .history-item {
            padding: 12px 14px;
            border-radius: 12px;
            color: var(--text-secondary);
            display: flex;
            flex-direction: column;
            gap: 6px;
            cursor: pointer;
            transition: background 0.18s ease, color 0.18s ease;
        }

        .history-item.active {
            background: rgba(16, 163, 127, 0.18);
            color: var(--text-primary);
        }

        .history-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-primary);
        }

        .history-item-title {
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .history-item-preview {
            font-size: 12px;
            opacity: 0.7;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .main-header {
            position: sticky;
            top: 0;
            z-index: 5;
            background: rgba(27, 29, 35, 0.94);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid #343944;
        }

        .main-header-inner {
            max-width: 960px;
            margin: 0 auto;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            display: grid;
            place-items: center;
            font-size: 20px;
        }

        .brand h1 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .brand-subtitle {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .conversation {
            flex: 1;
            overflow-y: auto;
        }

        .conversation-inner {
            max-width: 960px;
            margin: 0 auto;
            padding: 24px 24px 120px;
        }

        .suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 16px;
        }

        .chip {
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            font-size: 13px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: background 0.18s ease, border-color 0.18s ease, color 0.18s ease;
        }

        .chip:hover {
            background: rgba(16, 163, 127, 0.2);
            border-color: rgba(16, 163, 127, 0.5);
            color: var(--text-primary);
        }

        #chat-box {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .msg {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .msg-content {
            border-radius: var(--radius);
            padding: 16px 18px;
            max-width: 720px;
            line-height: 1.6;
            font-size: 15px;
            background: var(--surface);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.22);
            white-space: pre-wrap;
        }

        .msg.user {
            justify-content: flex-end;
        }

        .msg.user .msg-content {
            background: linear-gradient(162deg, #1f7c65, #10a37f);
            color: #fff;
            border-color: rgba(16, 163, 127, 0.5);
        }

        .msg .avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.14);
            display: grid;
            place-items: center;
            font-size: 16px;
            color: var(--text-secondary);
        }

        .msg.user .avatar {
            order: 2;
            background: #10a37f;
            color: #fff;
        }

        .msg.user .msg-content {
            order: 1;
        }

        .typing-dots {
            display: inline-flex;
            gap: 4px;
        }

        .typing-dots span {
            width: 6px;
            height: 6px;
            background: currentColor;
            border-radius: 50%;
            opacity: 0.4;
            animation: blink 1.2s infinite;
        }

        .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
        .typing-dots span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes blink {
            0%, 80%, 100% { opacity: 0.4; transform: translateY(0); }
            40% { opacity: 1; transform: translateY(-3px); }
        }

        .composer {
            position: sticky;
            bottom: 0;
            z-index: 5;
            background: rgba(27, 29, 35, 0.94);
            border-top: 1px solid #343944;
        }

        .composer-inner {
            max-width: 960px;
            margin: 0 auto;
            padding: 18px 24px 24px;
        }

        .input-row {
            display: flex;
            gap: 12px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: var(--radius);
            padding: 10px;
        }

        .input-row input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 15px;
            padding: 10px 12px;
            background: transparent;
            color: var(--text-primary);
        }

        .input-row input::placeholder {
            color: var(--text-secondary);
        }

        .send-btn {
            border: none;
            background: var(--accent);
            color: #ffffff;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 12px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .send-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(16, 163, 127, 0.25);
        }

        .send-btn:active {
            transform: translateY(0);
            box-shadow: none;
        }

        @media (max-width: 960px) {
            .sidebar { display: none; }
        }

        @media (max-width: 640px) {
            .conversation-inner { padding: 16px 16px 96px; }
            .composer-inner { padding: 16px 16px 20px; }
            .input-row { flex-direction: column; }
            .send-btn { justify-content: center; }
        }
    </style>
</head>
<body>
<div class="app-layout">
    <aside class="sidebar">
        <div class="sidebar-header">
            <button class="new-chat-btn" id="new-chat-btn">➕ New chat</button>
        </div>
        <div class="history" id="history"></div>
    </aside>

    <div class="main">
        <header class="main-header">
            <div class="main-header-inner">
                <div class="brand">
                    <div class="brand-icon">🤖</div>
                    <div>
                        <h1>Chat Assistant</h1>
                        <div class="brand-subtitle">Ask Maayank's assistant anything</div>
                    </div>
                </div>
            </div>
        </header>

        <main class="conversation">
            <div class="conversation-inner">
                <div class="suggestions" id="suggestions">
                    <div class="chip" data-prompt="Show me Maayank's portfolio summary">Show portfolio summary</div>
                    <div class="chip" data-prompt="List Maayank's primary skills">List primary skills</div>
                    <div class="chip" data-prompt="How can I contact Maayank?">How to contact Maayank?</div>
                    <div class="chip" data-prompt="What recent projects has Maayank worked on?">Recent projects</div>
                </div>
                <div id="chat-box"></div>
            </div>
        </main>

        <footer class="composer">
            <div class="composer-inner">
                <div class="input-row">
                    <input id="user-input" type="text" placeholder="Message Maayank's assistant…" autocomplete="off" />
                    <button class="send-btn" id="send-btn" type="button">Send ➤</button>
                </div>
            </div>
        </footer>
    </div>
</div>

<script>
    const chatBox = document.getElementById('chat-box');
    const input = document.getElementById('user-input');
    const sendBtn = document.getElementById('send-btn');
    const newChatBtn = document.getElementById('new-chat-btn');
    const historyEl = document.getElementById('history');
    const suggestionsEl = document.getElementById('suggestions');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const SEND_URL = "{{ url('/chat-bot/send') }}";

    const STORAGE_KEY = 'chatbot-conversations-v1';

    let conversations = loadConversations();
    let activeConversationId = conversations.length ? conversations[0].id : null;

    if (!activeConversationId) {
        const bootstrapConversation = {
            id: Date.now().toString(),
            title: 'New chat',
            messages: []
        };
        conversations.unshift(bootstrapConversation);
        activeConversationId = bootstrapConversation.id;
        saveConversations();
    }

    renderHistory();
    renderConversation();

    function loadConversations() {
        try {
            const raw = window.localStorage.getItem(STORAGE_KEY);
            if (!raw) return [];
            const parsed = JSON.parse(raw);
            if (!Array.isArray(parsed)) return [];
            return parsed.map(conv => ({
                id: conv.id || Date.now().toString(),
                title: conv.title || 'New chat',
                messages: Array.isArray(conv.messages) ? conv.messages : []
            }));
        } catch (err) {
            console.warn('Failed to load conversations', err);
            return [];
        }
    }

    function saveConversations() {
        window.localStorage.setItem(STORAGE_KEY, JSON.stringify(conversations));
    }

    function createConversation(title = 'New chat') {
        const conversation = {
            id: Date.now().toString(),
            title,
            messages: []
        };
        conversations.unshift(conversation);
        activeConversationId = conversation.id;
        saveConversations();
        renderHistory();
        renderConversation();
        return conversation;
    }

    function getActiveConversation() {
        return conversations.find(conv => conv.id === activeConversationId) || null;
    }

    function setActiveConversation(id) {
        if (activeConversationId === id) return;
        activeConversationId = id;
        conversations.sort((a, b) => a.id === id ? -1 : b.id === id ? 1 : 0);
        saveConversations();
        renderHistory();
        renderConversation();
    }

    function renderHistory() {
        historyEl.innerHTML = '';
        if (!conversations.length) {
            const empty = document.createElement('div');
            empty.className = 'history-item';
            empty.textContent = 'No previous conversations';
            empty.style.opacity = 0.6;
            historyEl.appendChild(empty);
            return;
        }

        conversations.forEach(conv => {
            const item = document.createElement('div');
            item.className = 'history-item' + (conv.id === activeConversationId ? ' active' : '');
            const title = document.createElement('div');
            title.className = 'history-item-title';
            title.textContent = conv.title || 'New chat';
            const preview = document.createElement('div');
            preview.className = 'history-item-preview';
            const lastMessage = conv.messages[conv.messages.length - 1];
            preview.textContent = lastMessage ? lastMessage.text : 'No messages yet';
            item.appendChild(title);
            item.appendChild(preview);
            item.addEventListener('click', () => setActiveConversation(conv.id));
            historyEl.appendChild(item);
        });
    }

    function renderConversation() {
        chatBox.innerHTML = '';
        const conversation = getActiveConversation();
        if (!conversation) {
            createConversation();
            return;
        }

        if (!conversation.messages.length) {
            createMessage('Hi, I\'m Maayank\'s assistant. Ask me about his skills, projects, or anything else you need to know.', 'bot');
            return;
        }

        conversation.messages.forEach(msg => {
            createMessage(msg.text, msg.role);
        });
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function createMessage(text, role = 'bot', typing = false) {
        const row = document.createElement('div');
        row.className = `msg ${role}`;

        const avatar = document.createElement('div');
        avatar.className = 'avatar';
        avatar.textContent = role === 'user' ? 'You' : 'AI';

        const bubble = document.createElement('div');
        bubble.className = 'msg-content';

        if (typing) {
            bubble.innerHTML = '<span class="typing-dots"><span></span><span></span><span></span></span>';
        } else {
            bubble.textContent = text;
        }

        if (role === 'user') {
            row.appendChild(bubble);
            row.appendChild(avatar);
        } else {
            row.appendChild(avatar);
            row.appendChild(bubble);
        }

        chatBox.appendChild(row);
        chatBox.scrollTop = chatBox.scrollHeight;
        return bubble;
    }

    async function revealText(node, text, speed = 12) {
        node.textContent = '';
        for (let i = 0; i < text.length; i++) {
            node.textContent += text[i];
            if (i % 3 === 0) {
                await new Promise(res => setTimeout(res, speed));
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }
    }

    function updateConversationTitle(conversation) {
        if (!conversation.messages.length) return;
        const firstUserMessage = conversation.messages.find(m => m.role === 'user');
        if (!firstUserMessage) return;
        const trimmed = firstUserMessage.text.trim();
        if (!trimmed) return;
        conversation.title = trimmed.length > 42 ? trimmed.slice(0, 39) + '…' : trimmed;
    }

    function saveMessageToConversation(role, text) {
        const conversation = getActiveConversation();
        if (!conversation) return;
        conversation.messages.push({ role, text });
        updateConversationTitle(conversation);
        saveConversations();
        renderHistory();
    }

    function handleSend() {
        const message = input.value.trim();
        if (!message) return;
        input.value = '';

        saveMessageToConversation('user', message);
        createMessage(message, 'user');

        const typingBubble = createMessage('', 'bot', true);
        saveConversations();

        fetch(SEND_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
            },
            body: JSON.stringify({ message, _token: csrf })
        })
            .then(async response => {
                if (!response.ok) {
                    const text = await response.text();
                    throw new Error(text || `Request failed with ${response.status}`);
                }
                return response.json();
            })
            .then(async data => {
                const reply = data?.reply || 'I could not understand that, but I\'m here to help.';
                await revealText(typingBubble, reply);
                saveMessageToConversation('bot', reply);
            })
            .catch(async (error) => {
                console.error('Chat request failed:', error);
                const errorText = '⚠️ Sorry, I could not connect. Please try again.';
                await revealText(typingBubble, errorText);
                saveMessageToConversation('bot', errorText);
            });
    }

    sendBtn.addEventListener('click', handleSend);
    input.addEventListener('keydown', event => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            handleSend();
        }
    });

    newChatBtn.addEventListener('click', () => {
        createConversation();
        setTimeout(() => input.focus(), 0);
    });

    suggestionsEl.addEventListener('click', event => {
        const chip = event.target.closest('.chip');
        if (!chip) return;
        input.value = chip.dataset.prompt || chip.textContent.trim();
        input.focus();
    });
</script>
</body>
</html>
