<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>🤖 Maayank Malhotra's AI Assistant – Futuristic</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Orbitron:wght@600;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg1: #0b1020;
      --bg2: #0c1a2a;
      --neon: #00ffd5;
      --neon-2: #7c4dff;
      --accent: #17c3b2;
      --text: #e6f1ff;
      --muted: #a6b2c3;
      --glass: 16px;
      --radius: 18px;
    }

    /* ------------ Base & Background ------------- */
    * { box-sizing: border-box; }
    html, body { height: 100%; }
    body {
      margin: 0;
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      color: var(--text);
      background:
        radial-gradient(1200px 1200px at 10% 10%, rgba(124,77,255,0.10), transparent 60%),
        radial-gradient(900px 900px at 90% 90%, rgba(0,255,213,0.10), transparent 60%),
        linear-gradient(180deg, var(--bg1), var(--bg2));
      overflow: hidden;
      display: grid;
      place-items: center;
    }

    /* Animated circuit grid background */
    canvas#bg-canvas {
      position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.45;
      filter: drop-shadow(0 0 2px rgba(0,255,213,0.2));
    }

    /* Floating orbs */
    .orb { position: fixed; border-radius: 50%; filter: blur(40px); opacity: .3; z-index: 0; }
    .orb.one { width: 320px; height: 320px; background: #00ffd5; top: 8%; left: 6%; animation: float 14s ease-in-out infinite; }
    .orb.two { width: 260px; height: 260px; background: #7c4dff; bottom: 10%; right: 8%; animation: float 20s ease-in-out infinite reverse; }
    @keyframes float { 0%,100% { transform: translateY(0) translateX(0);} 50% { transform: translateY(-18px) translateX(8px);} }

    /* ------------- Chat Container --------------- */
    .chat-wrap {
      position: relative; z-index: 1;
      width: min(920px, 94vw);
      height: min(86vh, 900px);
      display: grid; grid-template-rows: auto 1fr auto; gap: 0;
      border-radius: var(--radius);
      background: linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
      backdrop-filter: blur(14px);
      border: 1px solid rgba(255,255,255,0.12);
      box-shadow:
        0 0 0 1px rgba(0,255,213,0.08) inset,
        0 24px 60px rgba(0,0,0,0.55),
        0 0 40px rgba(0,255,213,0.08);
      overflow: clip;
    }

    /* Neon animated border */
    .chat-wrap::before {
      content: ""; position: absolute; inset: -1px; border-radius: calc(var(--radius) + 2px); z-index: -1;
      background: conic-gradient(from var(--a), var(--neon), transparent 20%, var(--neon-2) 50%, transparent 70%, var(--neon));
      filter: blur(10px); opacity: .45;
      animation: spin 14s linear infinite; --a: 0deg;
    }
    @keyframes spin { to { --a: 360deg; } }

    /* ------------- Header ----------------------- */
    .chat-header {
      display: flex; align-items: center; justify-content: space-between;
      padding: 14px 18px; border-bottom: 1px solid rgba(255,255,255,0.12);
      background: linear-gradient(180deg, rgba(0,0,0,0.35), rgba(0,0,0,0));
    }
    .brand {
      display: flex; align-items: center; gap: 14px;
    }
    .logo {
      width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center;
      background: radial-gradient(circle at 30% 30%, rgba(0,255,213,.35), rgba(124,77,255,.35));
      box-shadow: 0 0 18px rgba(0,255,213,.25) inset, 0 0 18px rgba(124,77,255,.25) inset;
    }
    .brand h1 { font: 800 18px/1.1 Orbitron, sans-serif; letter-spacing: 0.6px; margin: 0; }
    .status { display: flex; align-items: center; gap: 8px; color: var(--muted); font-size: 13px; }
    .status .dot { width: 8px; height: 8px; border-radius: 50%; background: #1ee8c2; box-shadow: 0 0 10px #1ee8c2; animation: pulse 1.8s ease-in-out infinite; }
    @keyframes pulse { 0%,100% { transform: scale(1); opacity: 1;} 50% { transform: scale(1.3); opacity: .6;} }

    /* ------------- Chat Box --------------------- */
    .chat-box { padding: 18px; overflow: auto; display: flex; flex-direction: column; gap: 10px; }
    .msg {
      max-width: 78%; padding: 12px 14px; border-radius: 14px;
      position: relative; word-wrap: break-word; line-height: 1.5;
      border: 1px solid rgba(255,255,255,0.12);
      box-shadow: 0 6px 18px rgba(0,0,0,.35);
      animation: rise .35s ease-out both;
    }
    @keyframes rise { from { transform: translateY(8px); opacity: 0} to { transform: translateY(0); opacity: 1} }

    .msg.user {
      align-self: flex-end; background: linear-gradient(180deg, rgba(0,255,213,0.10), rgba(0,0,0,0.25));
      border-top-right-radius: 6px; border-color: rgba(0,255,213,0.35);
      box-shadow: 0 0 18px rgba(0,255,213,0.12), 0 6px 18px rgba(0,0,0,.35);
    }
    .msg.bot {
      align-self: flex-start; background: linear-gradient(180deg, rgba(124,77,255,0.10), rgba(0,0,0,0.25));
      border-top-left-radius: 6px; border-color: rgba(124,77,255,0.35);
      box-shadow: 0 0 18px rgba(124,77,255,0.12), 0 6px 18px rgba(0,0,0,.35);
    }

    /* Message arrows */
    .msg.user::after, .msg.bot::after { content: ""; position: absolute; bottom: 0; width: 10px; height: 10px; background: inherit; }
    .msg.user::after { right: -5px; clip-path: polygon(0 100%, 100% 0, 100% 100%); filter: drop-shadow(-2px 2px 2px rgba(0,0,0,.25)); }
    .msg.bot::after  { left: -5px;  clip-path: polygon(0 0, 0 100%, 100% 100%); filter: drop-shadow(2px 2px 2px rgba(0,0,0,.25)); }

    /* Typing indicator */
    .typing { display: inline-flex; gap: 6px; align-items: center; }
    .typing .dot { width: 6px; height: 6px; background: var(--text); opacity: .6; border-radius: 50%; animation: blink 1.4s infinite; }
    .typing .dot:nth-child(2) { animation-delay: .15s; }
    .typing .dot:nth-child(3) { animation-delay: .30s; }
    @keyframes blink { 0%,80%,100%{ transform: translateY(0); opacity: .4;} 40%{ transform: translateY(-4px); opacity: 1;} }

    /* ------------- Suggestions ------------------ */
    .suggestions { display: flex; gap: 10px; flex-wrap: wrap; padding: 8px 18px 2px; }
    .chip {
      font-size: 13px; color: var(--text); opacity: .9; cursor: pointer; user-select: none;
      padding: 8px 12px; border-radius: 999px; border: 1px solid rgba(255,255,255,0.16);
      background: linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.03));
      transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }
    .chip:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(0,0,0,.35), 0 0 14px rgba(0,255,213,.2); border-color: rgba(0,255,213,.45); }

    /* ------------- Input Row -------------------- */
    .input-row {
      display: grid; grid-template-columns: 1fr auto; gap: 10px; align-items: center;
      padding: 14px; border-top: 1px solid rgba(255,255,255,0.12);
      background: linear-gradient(180deg, rgba(0,0,0,0), rgba(0,0,0,0.35));
    }

    .field {
      position: relative; border-radius: 14px; overflow: hidden;
      background: linear-gradient(180deg, rgba(255,255,255,0.07), rgba(255,255,255,0.03));
      border: 1px solid rgba(255,255,255,0.16);
      box-shadow: 0 6px 18px rgba(0,0,0,.35) inset;
    }
    .field input {
      width: 100%; background: transparent; border: 0; outline: 0; color: var(--text);
      padding: 14px 48px 14px 14px; font-size: 16px;
    }
    .field input::placeholder { color: var(--muted); }
    .field:focus-within { border-color: rgba(0,255,213,.45); box-shadow: 0 0 0 2px rgba(0,255,213,.18) inset; }

    .send-btn {
      position: relative; border: 0; border-radius: 14px; cursor: pointer; color: #001510; font-weight: 700;
      padding: 14px 22px; font-size: 15px; letter-spacing: .3px;
      background: linear-gradient(135deg, var(--neon), #82ffe9);
      box-shadow: 0 8px 24px rgba(0,255,213,.35), 0 0 28px rgba(0,255,213,.35);
      transition: transform .12s ease, filter .2s ease;
    }
    .send-btn:hover { transform: translateY(-1px); filter: brightness(1.08); }
    .send-btn:active { transform: translateY(0); }
    .send-btn .ring { position: absolute; inset: -2px; border-radius: 16px; border: 2px solid rgba(255,255,255,.65); opacity: 0; }
    .send-btn:active .ring { animation: ring .6s ease-out; }
    @keyframes ring { from { opacity: .8; transform: scale(.98);} to { opacity: 0; transform: scale(1.15);} }

    .kbd { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); opacity: .65; font-size: 12px; border: 1px solid rgba(255,255,255,.2); padding: 2px 6px; border-radius: 6px; }

    /* ------------- Scrollbar -------------------- */
    .chat-box::-webkit-scrollbar { height: 12px; width: 12px; }
    .chat-box::-webkit-scrollbar-thumb { background: linear-gradient(180deg, rgba(255,255,255,0.18), rgba(255,255,255,0.06)); border-radius: 8px; }

    /* ------------- Responsive ------------------- */
    @media (max-width: 640px) {
      .chat-wrap { height: 92vh; }
      .msg { max-width: 90%; }
      .brand h1 { font-size: 16px; }
    }
  </style>
</head>
<body>
  <!-- Decorative Orbs & Canvas BG -->
  <div class="orb one"></div>
  <div class="orb two"></div>
  <canvas id="bg-canvas"></canvas>

  <div class="chat-wrap">
    <!-- Header -->
    <div class="chat-header">
      <div class="brand">
        <div class="logo">🤖</div>
        <div>
          <h1>Maayank's AI Assistant</h1>
          <div class="status"><span class="dot"></span> Online • Ready to help</div>
        </div>
      </div>
      <div class="status">v1.0 • <span style="color:#82ffe9">Futuristic</span></div>
    </div>

    <!-- Suggestions -->
    <div class="suggestions" id="suggestions">
      <div class="chip" data-prompt="Show my portfolio summary">Show my portfolio summary</div>
      <div class="chip" data-prompt="What skills does Maayank have?">What skills does Maayank have?</div>
      <div class="chip" data-prompt="Share three example questions about Maayank">Example questions about Maayank</div>
      <div class="chip" data-prompt="Contact details for Maayank?">Contact details for Maayank?</div>
    </div>

    <!-- Chat Box -->
    <div class="chat-box" id="chat-box"></div>

    <!-- Input Row -->
    <div class="input-row">
      <div class="field">
        <input id="user-input" type="text" placeholder="Type your message… (Press Enter)" autocomplete="off" />
        <span class="kbd">Enter ↵</span>
      </div>
      <button class="send-btn" id="send-btn" title="Send">
        <span class="ring"></span> Send
      </button>
    </div>
  </div>

  <script>
    // ---------- Circuit Grid Canvas BG ----------
    (function gridBG(){
      const c = document.getElementById('bg-canvas');
      const ctx = c.getContext('2d');
      let w, h, t = 0;
      function resize(){ w = c.width = window.innerWidth; h = c.height = window.innerHeight; }
      window.addEventListener('resize', resize); resize();
      function draw(){
        ctx.clearRect(0,0,w,h);
        const cell = 36, speed = 0.0035; t += speed;
        ctx.globalAlpha = 0.55; ctx.strokeStyle = 'rgba(0,255,213,0.30)';
        for(let y = 0; y < h + cell; y += cell){
          for(let x = 0; x < w + cell; x += cell){
            const dx = Math.sin((x + t*200) * 0.004) * 6;
            const dy = Math.cos((y + t*160) * 0.004) * 6;
            ctx.strokeRect(x + dx, y + dy, cell, cell);
          }
        }
        // traveling pulses
        ctx.globalAlpha = 0.9; ctx.lineWidth = 2;
        ctx.strokeStyle = 'rgba(124,77,255,0.35)';
        const yPulse = (Math.sin(t*2) * 0.5 + 0.5) * h;
        ctx.beginPath(); ctx.moveTo(0, yPulse); ctx.lineTo(w, yPulse); ctx.stroke();
        requestAnimationFrame(draw);
      }
      draw();
    })();

    // ---------- Chat Logic ----------
    const chatBox = document.getElementById('chat-box');
    const input = document.getElementById('user-input');
    const sendBtn = document.getElementById('send-btn');

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function appendMessage(text, role = 'bot', asTyping = false){
      const msg = document.createElement('div');
      msg.className = `msg ${role}`;
      if(asTyping){
        msg.innerHTML = '<div class="typing"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>';
      } else {
        msg.textContent = text;
      }
      chatBox.appendChild(msg);
      chatBox.scrollTop = chatBox.scrollHeight;
      return msg;
    }

    async function typeOut(el, text, speed = 15){
      el.textContent = '';
      for(let i=0;i<text.length;i++){
        el.textContent += text[i];
        if((i % 3) === 0) await new Promise(r => setTimeout(r, speed));
        chatBox.scrollTop = chatBox.scrollHeight;
      }
    }

    function send(){
      const message = input.value.trim();
      if(!message) return;
      appendMessage(message, 'user');
      input.value = '';

      const typing = appendMessage('', 'bot', true);

      fetch('/chat-bot/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
        },
        body: JSON.stringify({ message })
      }).then(r => r.json()).then(async data => {
        typing.classList.remove('bot'); typing.classList.add('bot');
        await typeOut(typing, data?.reply || 'I could not understand that, but I\'m here to help!');
      }).catch(async () => {
        await typeOut(typing, '⚠️ Error: Could not reach chatbot. Please try again.');
      });
    }

    sendBtn.addEventListener('click', () => send());
    input.addEventListener('keydown', (e) => {
      if(e.key === 'Enter' && !e.shiftKey){ e.preventDefault(); send(); }
    });

    // Suggestion chips
    document.getElementById('suggestions').addEventListener('click', (e) => {
      const chip = e.target.closest('.chip');
      if(!chip) return;
      input.value = chip.dataset.prompt || chip.textContent.trim();
      input.focus();
    });

    // Greet
    appendMessage('Hi, I\'m Maayank\'s AI Assistant. Ask me about his skills, portfolio, or anything else!', 'bot');
  </script>
</body>
</html>
