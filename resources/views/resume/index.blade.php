<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Maayank Malhotra — Resume</title>
  <meta name="description" content="Full‑Stack Developer (Laravel + React) with real VPS deployments. View projects, experience, and contact Maayank." />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    :root { --glass: rgba(255,255,255,0.06); --stroke: rgba(255,255,255,0.12); }
    * { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; }
    .glass { background: var(--glass); border: 1px solid var(--stroke); backdrop-filter: blur(8px); }
    .shine { position: relative; overflow: hidden; }
    .shine::after { content:""; position:absolute; inset:-200%; background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,.15) 50%, transparent 60%); transform: translateX(-100%); }
    .shine:hover::after { transition: transform .8s ease; transform: translateX(100%); }
    .blob { filter: blur(60px); opacity: .4; }
    .tag { border:1px solid rgba(255,255,255,.15); }
    .gradient-text { background: linear-gradient(90deg,#c084fc,#60a5fa,#34d399); -webkit-background-clip: text; background-clip:text; color:transparent; }
    .pulse { box-shadow: 0 0 0 rgba(59,130,246, 0.7); animation: pulse 2s infinite; }
    @keyframes pulse {0%{box-shadow:0 0 0 0 rgba(59,130,246,.6)}70%{box-shadow:0 0 0 18px rgba(59,130,246,0)}100%{box-shadow:0 0 0 0 rgba(59,130,246,0)}}
  </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased">
  <!-- Decorative BG blobs -->
  <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10">
    <div class="absolute top-[-10rem] left-[-6rem] w-[28rem] h-[28rem] rounded-full bg-fuchsia-600 blob animate-pulse"></div>
    <div class="absolute bottom-[-12rem] right-[-10rem] w-[35rem] h-[35rem] rounded-full bg-cyan-600 blob animate-[pulse_2.6s_ease-in-out_infinite]"></div>
    <div class="absolute top-1/3 right-1/4 w-[22rem] h-[22rem] rounded-full bg-emerald-500 blob"></div>
  </div>

  <!-- Sticky Top Nav -->
  <header class="sticky top-0 z-30 bg-slate-950/60 backdrop-blur border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
      <a href="#top" class="flex items-center gap-3">
        <div class="size-9 rounded-xl bg-gradient-to-br from-fuchsia-500 via-blue-500 to-emerald-400"></div>
        <div class="leading-tight">
          <p class="text-sm text-slate-300">Full‑Stack Developer</p>
          <p class="font-semibold tracking-tight">Maayank Malhotra</p>
        </div>
      </a>
      <nav class="hidden md:flex items-center gap-6 text-sm text-slate-300">
        <a href="#experience" class="hover:text-white">Experience</a>
        <a href="#projects" class="hover:text-white">Projects</a>
        <a href="#skills" class="hover:text-white">Skills</a>
        <a href="#contact" class="hover:text-white">Contact</a>
      </nav>
      <button id="openLeadModalBtn" class="shine pulse bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-xl font-semibold">
        Hire Me
      </button>
    </div>
  </header>

  <!-- Hero -->
  <section class="relative" id="top">
    <div class="max-w-7xl mx-auto px-4 pt-10 pb-16 grid md:grid-cols-2 gap-8 items-center">
      <div class="animate__animated animate__fadeInUp">
        <p class="inline-flex items-center gap-2 text-xs uppercase tracking-wider text-slate-300 bg-white/5 px-3 py-1 rounded-full border border-white/10">
          <span class="size-2 rounded-full bg-emerald-400"></span> Open to Full‑time & Contract
        </p>
        <h1 class="mt-5 text-4xl md:text-5xl font-extrabold leading-[1.1]"><span class="gradient-text">Laravel</span> × <span class="gradient-text">React</span> developer who ships to real servers</h1>
        <p class="mt-4 text-slate-300 max-w-prose">Pragmatic builder of fast, reliable apps with clean APIs, secure auth, and zero‑drama deployments on VPS/Cloud. I blend product thinking with rock‑solid engineering, so you get features live — not just in slides.</p>
        <div class="mt-6 flex flex-wrap gap-3">
          <span class="tag px-3 py-1 rounded-full text-sm">Laravel</span>
          <span class="tag px-3 py-1 rounded-full text-sm">React.js</span>
          <span class="tag px-3 py-1 rounded-full text-sm">MySQL</span>
          <span class="tag px-3 py-1 rounded-full text-sm">MongoDB</span>
          <span class="tag px-3 py-1 rounded-full text-sm">Docker</span>
          <span class="tag px-3 py-1 rounded-full text-sm">VPS Deployment</span>
        </div>
        <div class="mt-8 flex flex-wrap items-center gap-3">
          <button id="openLeadModalBtn2" class="shine bg-emerald-600 hover:bg-emerald-500 px-5 py-3 rounded-xl font-semibold">Let's Talk</button>
          <a href="#projects" class="px-5 py-3 rounded-xl font-semibold border border-white/15 hover:bg-white/5">View Projects</a>
        </div>
      </div>

      <!-- Inline contact card (conversion-focused) -->
      <div class="glass rounded-2xl p-6 md:p-7 shadow-2xl animate__animated animate__fadeIn">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-xl font-bold">Quick Contact</h3>
          <span class="text-xs text-slate-400">Avg. reply &lt; 24h</span>
        </div>
        <form id="resumeFormInline" method="POST" action="{{ route('resume.store') }}" class="space-y-3">
          @csrf
          <div class="grid grid-cols-2 gap-3">
            <input type="text" name="name" placeholder="Your Name" class="w-full p-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <input type="email" name="email" placeholder="Your Email" class="w-full p-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <input type="text" name="phone" placeholder="Your Phone (optional)" class="w-full p-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="interest" class="w-full p-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="Hiring">Hiring full‑time</option>
              <option value="Contract">Contract project</option>
              <option value="Freelance">Freelance / Consultation</option>
            </select>
          </div>
          <textarea name="message" placeholder="Tell me about the role or project…" class="w-full p-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[100px]"></textarea>
          <!-- Honeypot -->
          <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
          <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-500 rounded-lg font-bold shine">Submit</button>
        </form>
        <p class="text-xs text-slate-400 mt-2">Or press <kbd class="px-1.5 py-0.5 rounded bg-white/10 border border-white/10">C</kbd> to open contact.</p>
      </div>
    </div>
  </section>

  <!-- Experience -->
  <section id="experience" class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold mb-6">Experience</h2>
    <ol class="relative border-l border-white/10 space-y-8">
      <li class="ml-6">
        <span class="absolute -left-3 top-1 size-6 rounded-full bg-blue-600"></span>
        <h3 class="text-lg font-semibold">Founder — <a class="text-blue-400 underline-offset-2 hover:underline" href="https://maayank-malhotra.ddns.net/" target="_blank">maayank-malhotra.ddns.net</a></h3>
        <p class="text-slate-300 text-sm">Built & deployed a full‑stack platform with free domain, SSL, DNS, VPS setup, Gemini API & Google Maps API integration.</p>
      </li>
      <li class="ml-6">
        <span class="absolute -left-3 top-1 size-6 rounded-full bg-blue-600"></span>
        <h3 class="text-lg font-semibold">Freelancer — <a class="text-blue-400 underline-offset-2 hover:underline" href="https://techminimaa.com" target="_blank">Tech Minimaa</a></h3>
        <p class="text-slate-300 text-sm">End‑to‑end builds on Laravel + React with VPS deployments.</p>
      </li>
      <li class="ml-6">
        <span class="absolute -left-3 top-1 size-6 rounded-full bg-blue-600"></span>
        <h3 class="text-lg font-semibold">Thinktail Global Pvt. Ltd.</h3>
        <p class="text-slate-300 text-sm">Full‑stack apps with React.js + Node.js <span class="text-slate-400">(Aug 2025 – Present)</span></p>
      </li>
      <li class="ml-6">
        <span class="absolute -left-3 top-1 size-6 rounded-full bg-blue-600"></span>
        <h3 class="text-lg font-semibold">Cracode Consulting Pvt. Ltd.</h3>
        <p class="text-slate-300 text-sm">Laravel + React APIs <span class="text-slate-400">(Aug 2024 – Aug 2025)</span></p>
      </li>
      <li class="ml-6">
        <span class="absolute -left-3 top-1 size-6 rounded-full bg-blue-600"></span>
        <h3 class="text-lg font-semibold">Henry Harvin</h3>
        <p class="text-slate-300 text-sm">Improved CI/CD & released 5 products <span class="text-slate-400">(Oct 2023 – Aug 2024)</span></p>
      </li>
      <li class="ml-6">
        <span class="absolute -left-3 top-1 size-6 rounded-full bg-blue-600"></span>
        <h3 class="text-lg font-semibold">SSNTPL</h3>
        <p class="text-slate-300 text-sm">REST APIs for ICICI Lombard & Ninja CRM <span class="text-slate-400">(Jan 2023 – Oct 2023)</span></p>
      </li>
    </ol>
  </section>

  <!-- Projects (conversion‑focused cards) -->
  <section id="projects" class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold">Projects</h2>
      <a href="#contact" class="text-sm text-blue-400 hover:underline">Discuss your role →</a>
    </div>
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">

      <!-- SnoutIQ -->
      <article class="glass rounded-2xl p-5 hover:-translate-y-1 transition transform shine">
        <div class="text-xs text-slate-300 mb-2">AI Assistant • Pet Care</div>
        <h3 class="font-semibold text-lg mb-2">SnoutIQ.com — AI Pet Assistant</h3>
        <p class="text-slate-300 text-sm">Multi‑auth system, secure APIs, VPS deployment, real‑time features. Built for reliability and scale.</p>
        <div class="mt-4 flex flex-wrap gap-2 text-xs">
          <span class="tag px-2 py-1 rounded-full">Laravel</span>
          <span class="tag px-2 py-1 rounded-full">React</span>
          <span class="tag px-2 py-1 rounded-full">Socket.io</span>
          <span class="tag px-2 py-1 rounded-full">VPS</span>
        </div>
        <div class="mt-4 flex items-center gap-3">
          <a href="https://snoutiq.com" target="_blank" class="text-blue-400 hover:underline">Live ↗</a>
        </div>
      </article>

      <!-- LMS -->
      <article class="glass rounded-2xl p-5 hover:-translate-y-1 transition transform shine">
        <div class="text-xs text-slate-300 mb-2">Education • Realtime</div>
        <h3 class="font-semibold text-lg mb-2">LMS Portal (Laravel)</h3>
        <p class="text-slate-300 text-sm">Multi‑role (student/teacher), assignments, grading, dashboards, Pusher real‑time updates.</p>
        <div class="mt-4 flex flex-wrap gap-2 text-xs">
          <span class="tag px-2 py-1 rounded-full">Laravel</span>
          <span class="tag px-2 py-1 rounded-full">MySQL</span>
          <span class="tag px-2 py-1 rounded-full">Pusher</span>
        </div>
        <div class="mt-4 flex items-center gap-3">
          <a href="https://maayank-malhotra.ddns.net/" target="_blank" class="text-blue-400 hover:underline">Live ↗</a>
        </div>
      </article>

      <!-- Chatbot -->
      <article class="glass rounded-2xl p-5 hover:-translate-y-1 transition transform shine">
        <div class="text-xs text-slate-300 mb-2">AI • Support</div>
        <h3 class="font-semibold text-lg mb-2">Chatbot Assistant</h3>
        <p class="text-slate-300 text-sm">Conversational assistant with context memory and clean UI. Deployed on VPS.</p>
        <div class="mt-4 flex flex-wrap gap-2 text-xs">
          <span class="tag px-2 py-1 rounded-full">React</span>
          <span class="tag px-2 py-1 rounded-full">Laravel</span>
          <span class="tag px-2 py-1 rounded-full">REST</span>
        </div>
        <div class="mt-4 flex items-center gap-3">
          <a href="https://maayank-malhotra.ddns.net/chat-bot" target="_blank" class="text-blue-400 hover:underline">Live ↗</a>
        </div>
      </article>

      <!-- Portfolio -->
      <article class="glass rounded-2xl p-5 hover:-translate-y-1 transition transform shine">
        <div class="text-xs text-slate-300 mb-2">Personal • Brand</div>
        <h3 class="font-semibold text-lg mb-2">Interactive Portfolio</h3>
        <p class="text-slate-300 text-sm">Clean, fast, recruiter‑friendly portfolio with contact capture and analytics hooks.</p>
        <div class="mt-4 flex flex-wrap gap-2 text-xs">
          <span class="tag px-2 py-1 rounded-full">Tailwind</span>
          <span class="tag px-2 py-1 rounded-full">Blade</span>
        </div>
        <div class="mt-4 flex items-center gap-3">
          <a href="https://maayank-malhotra.ddns.net/MaayankMalhotraResume" target="_blank" class="text-blue-400 hover:underline">Live ↗</a>
        </div>
      </article>

      <!-- Job Portal (MERN) -->
      <article class="glass rounded-2xl p-5 hover:-translate-y-1 transition transform shine">
        <div class="text-xs text-slate-300 mb-2">Jobs • Platform</div>
        <h3 class="font-semibold text-lg mb-2">Job Portal (MERN)</h3>
        <p class="text-slate-300 text-sm">Listings with filters, resume uploads, admin panel. Deployed on AWS (Nginx + CI/CD).</p>
        <div class="mt-4 flex flex-wrap gap-2 text-xs">
          <span class="tag px-2 py-1 rounded-full">React</span>
          <span class="tag px-2 py-1 rounded-full">Node</span>
          <span class="tag px-2 py-1 rounded-full">MongoDB</span>
        </div>
      </article>

      <!-- CRM -->
      <article class="glass rounded-2xl p-5 hover:-translate-y-1 transition transform shine">
        <div class="text-xs text-slate-300 mb-2">Sales • Ops</div>
        <h3 class="font-semibold text-lg mb-2">CRM App (MERN)</h3>
        <p class="text-slate-300 text-sm">Leads, pipelines, tasks automation, dashboards, notifications.</p>
        <div class="mt-4 flex flex-wrap gap-2 text-xs">
          <span class="tag px-2 py-1 rounded-full">React</span>
          <span class="tag px-2 py-1 rounded-full">Node</span>
          <span class="tag px-2 py-1 rounded-full">MongoDB</span>
        </div>
      </article>

      <!-- AV App -->
      <article class="glass rounded-2xl p-5 hover:-translate-y-1 transition transform shine">
        <div class="text-xs text-slate-300 mb-2">Realtime • Comms</div>
        <h3 class="font-semibold text-lg mb-2">Audio/Video App</h3>
        <p class="text-slate-300 text-sm">WebRTC + socket.io with recording, chat, and multi‑browser support.</p>
        <div class="mt-4 flex flex-wrap gap-2 text-xs">
          <span class="tag px-2 py-1 rounded-full">WebRTC</span>
          <span class="tag px-2 py-1 rounded-full">PHP</span>
          <span class="tag px-2 py-1 rounded-full">Node</span>
        </div>
      </article>

    </div>
  </section>

  <!-- Skills -->
  <section id="skills" class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold mb-6">Skills</h2>
    <div class="grid md:grid-cols-2 gap-6">
      <div class="glass rounded-2xl p-6">
        <h3 class="font-semibold mb-3">Frontend</h3>
        <div class="flex flex-wrap gap-2 text-sm text-slate-200">
          <span class="tag px-3 py-1 rounded-full">HTML5</span>
          <span class="tag px-3 py-1 rounded-full">CSS3</span>
          <span class="tag px-3 py-1 rounded-full">JavaScript</span>
          <span class="tag px-3 py-1 rounded-full">TypeScript</span>
          <span class="tag px-3 py-1 rounded-full">React.js</span>
          <span class="tag px-3 py-1 rounded-full">Redux</span>
        </div>
      </div>
      <div class="glass rounded-2xl p-6">
        <h3 class="font-semibold mb-3">Backend</h3>
        <div class="flex flex-wrap gap-2 text-sm text-slate-200">
          <span class="tag px-3 py-1 rounded-full">PHP</span>
          <span class="tag px-3 py-1 rounded-full">Laravel</span>
          <span class="tag px-3 py-1 rounded-full">Node.js</span>
          <span class="tag px-3 py-1 rounded-full">Express.js</span>
        </div>
      </div>
      <div class="glass rounded-2xl p-6">
        <h3 class="font-semibold mb-3">APIs & Databases</h3>
        <div class="flex flex-wrap gap-2 text-sm text-slate-200">
          <span class="tag px-3 py-1 rounded-full">REST</span>
          <span class="tag px-3 py-1 rounded-full">GraphQL</span>
          <span class="tag px-3 py-1 rounded-full">MySQL</span>
          <span class="tag px-3 py-1 rounded-full">MongoDB</span>
        </div>
      </div>
      <div class="glass rounded-2xl p-6">
        <h3 class="font-semibold mb-3">DevOps & Tools</h3>
        <div class="flex flex-wrap gap-2 text-sm text-slate-200">
          <span class="tag px-3 py-1 rounded-full">Docker</span>
          <span class="tag px-3 py-1 rounded-full">AWS</span>
          <span class="tag px-3 py-1 rounded-full">Git / Bitbucket</span>
          <span class="tag px-3 py-1 rounded-full">Linux</span>
          <span class="tag px-3 py-1 rounded-full">macOS</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Conversion CTA -->
  <section id="contact" class="max-w-7xl mx-auto px-4 py-16">
    <div class="glass rounded-2xl p-8 md:p-10 grid md:grid-cols-2 gap-8 items-center">
      <div>
        <p class="text-sm uppercase tracking-wider text-slate-300">Let's build</p>
        <h3 class="text-3xl font-extrabold mt-2">Ship your next feature <span class="gradient-text">this month</span></h3>
        <p class="text-slate-300 mt-3">Share the role or project details. I'll reply within a day with next steps or a quick ETA. No spam, no fluff.</p>
        <ul class="mt-5 space-y-2 text-sm text-slate-300">
          <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-emerald-400"></span> Clean API design & auth flows</li>
          <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-emerald-400"></span> Production‑ready deployments</li>
          <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-emerald-400"></span> Clear communication & demo videos</li>
        </ul>
      </div>
      <div>
        <form id="resumeFormCTA" method="POST" action="{{ route('resume.store') }}" class="space-y-3">
          @csrf
          <div class="grid grid-cols-2 gap-3">
            <input type="text" name="name" placeholder="Your Name" class="w-full p-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <input type="email" name="email" placeholder="Your Email" class="w-full p-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>
          <input type="text" name="phone" placeholder="Your Phone (optional)" class="w-full p-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <textarea name="message" placeholder="What are you hiring for?" class="w-full p-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[110px]"></textarea>
          <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
          <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-500 rounded-lg font-bold shine">Send</button>
        </form>
        <p class="text-xs text-slate-400 mt-2">Keyboard: Press <kbd class="px-1.5 py-0.5 rounded bg-white/10 border border-white/10">C</kbd> to open the quick contact modal.</p>
      </div>
    </div>
  </section>

  <!-- Footer + Sticky CTA Bar -->
  <footer class="border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 py-8 text-sm text-slate-400 flex flex-wrap items-center justify-between gap-3">
      <p>© <span id="year"></span> Maayank Malhotra. Built with Laravel + Tailwind.</p>
      <div class="flex gap-2">
        <button id="copyEmail" class="px-3 py-1 rounded-lg border border-white/15 hover:bg-white/5">Copy email</button>
        <button id="openLeadModalBtn3" class="px-3 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white">Hire Me</button>
      </div>
    </div>
  </footer>

  <!-- Floating Contact Bubble -->
  <button id="floatingCTA" class="fixed bottom-5 right-5 z-40 rounded-full bg-blue-600 hover:bg-blue-500 text-white px-5 py-3 font-semibold shadow-2xl pulse">
    Contact Maayank
  </button>

  <!-- CONTACT MODAL -->
  <div id="leadForm" class="fixed inset-0 bg-black/70 backdrop-blur flex items-center justify-center hidden z-50">
    <div class="bg-white text-black rounded-2xl p-6 sm:p-8 max-w-md w-full relative animate__animated animate__fadeInDown">
      <button type="button" onclick="document.getElementById('leadForm').classList.add('hidden')" class="absolute top-2 right-2 text-gray-600 hover:text-black text-2xl">&times;</button>
      <h3 class="text-xl font-bold mb-4">Get in Touch</h3>
      <form id="resumeFormModal" method="POST" action="{{ route('resume.store') }}" class="space-y-3">
        @csrf
        <input type="text" name="name" placeholder="Your Name" class="w-full p-3 border rounded focus:ring focus:ring-blue-500" required>
        <input type="email" name="email" placeholder="Your Email" class="w-full p-3 border rounded focus:ring focus:ring-blue-500" required>
        <input type="text" name="phone" placeholder="Your Phone" class="w-full p-3 border rounded focus:ring focus:ring-blue-500">
        <textarea name="message" placeholder="Your Message" class="w-full p-3 border rounded focus:ring focus:ring-blue-500"></textarea>
        <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 rounded text-white font-bold">Submit</button>
      </form>
      <p class="text-xs text-slate-500 mt-2">We never share your details. Ever.</p>
    </div>
  </div>

  <script>
    // Year
    document.getElementById('year').textContent = new Date().getFullYear();

    // Open modal buttons
    const openers = ['openLeadModalBtn','openLeadModalBtn2','openLeadModalBtn3','floatingCTA'];
    openers.forEach(id => {
      const el = document.getElementById(id);
      if (el) el.addEventListener('click', () => document.getElementById('leadForm').classList.remove('hidden'));
    });

    // Keyboard shortcut: C to open contact
    document.addEventListener('keydown', (e) => {
      if ((e.key === 'c' || e.key === 'C') && !document.activeElement.matches('input, textarea')) {
        document.getElementById('leadForm').classList.remove('hidden');
      }
    });

    // Exit-intent modal (desktop)
    let modalShown = false;
    document.addEventListener('mouseout', (e) => {
      if (!modalShown && e.clientY < 10) {
        modalShown = true;
        document.getElementById('leadForm').classList.remove('hidden');
      }
    });

    // Time-delayed nudge (for assured visibility)
    setTimeout(() => {
      if (!modalShown) {
        document.getElementById('leadForm').classList.remove('hidden');
        modalShown = true;
      }
    }, 1800);

    // Copy email
    document.getElementById('copyEmail').addEventListener('click', async () => {
      const email = 'recruit@yourdomain.example'; // ← replace with your real email if desired
      try { await navigator.clipboard.writeText(email); } catch {}
      Swal.fire({
        icon: 'success', title: 'Email copied', text: email, confirmButtonColor: '#2563eb'
      });
    });

    // Basic front-end validation + honeypot check
    ['resumeFormInline','resumeFormCTA','resumeFormModal'].forEach(id => {
      const form = document.getElementById(id);
      if (!form) return;
      form.addEventListener('submit', (e) => {
        const honey = form.querySelector('input[name="website"]').value;
        if (honey) { e.preventDefault(); return; }
        const email = form.querySelector('input[type="email"]').value.trim();
        if (!email.includes('@')) {
          e.preventDefault();
          Swal.fire({icon:'error', title:'Invalid email', text:'Please enter a valid email address.', confirmButtonColor:'#2563eb'});
        }
      });
    });

    // Laravel flash success -> close modal + toast
    @if(session('success'))
      document.getElementById('leadForm').classList.add('hidden');
      Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session('success') }}', confirmButtonColor: '#2563eb' });
    @endif
  </script>
</body>
</html>
