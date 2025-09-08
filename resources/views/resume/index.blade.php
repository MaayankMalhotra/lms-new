<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maayank Malhotra Resume</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">

    <!-- Header -->
    <header class="bg-gradient-to-r from-purple-800 to-blue-600 py-10 text-center">
        <h1 class="text-4xl font-bold animate__animated animate__fadeInDown">Maayank Malhotra</h1>
        <p class="text-lg text-gray-200 animate__animated animate__fadeInUp">
            Full-Stack Developer | Laravel | React.js | VPS Deployment
        </p>
    </header>

    <main class="container mx-auto px-6 py-12">

        <!-- Experience -->
        <section class="mb-10 animate__animated animate__fadeInLeft">
            <h2 class="text-2xl font-bold mb-4">Experience</h2>
            <ul class="space-y-3 text-gray-300">
                <li>
                    <b>Founder</b> – 
                    <a href="https://maayank-malhotra.ddns.net/" target="_blank" class="text-blue-400 hover:underline">
                        maayank-malhotra.ddns.net
                    </a>  
                    Built and deployed a full-stack platform with free domain, SSL, DNS, VPS setup, Gemini API & Google Maps API integration.
                </li>
                <li>
                    <b>Freelancer</b> – 
                    <a href="https://techminimaa.com" target="_blank" class="text-blue-400 hover:underline">
                        Tech Minimaa
                    </a>  
                    Delivered end-to-end web development projects, focusing on Laravel + React stack and VPS deployment.
                </li>
                <li><b>Thinktail Global Pvt. Ltd.</b> – Full-stack apps with React.js + Node.js (Aug 2025 – Present)</li>
                <li><b>Cracode Consulting Pvt. Ltd.</b> – Laravel + React APIs (Aug 2024 – Aug 2025)</li>
                <li><b>Henry Harvin</b> – Improved CI/CD & released 5 products (Oct 2023 – Aug 2024)</li>
                <li><b>SSNTPL</b> – REST APIs for ICICI Lombard & Ninja CRM (Jan 2023 – Oct 2023)</li>
            </ul>
        </section>

        <!-- Education -->
        <section class="mb-10 animate__animated animate__fadeInRight">
            <h2 class="text-2xl font-bold mb-4">Education</h2>
            <ul class="text-gray-300 space-y-2">
                <li><b>B.Tech, Electronics</b> – YMCA University (2018–2022) | CGPA: 7.606</li>
                <li><b>Senior Secondary</b> – D.A.V. Public School (2017–2018) | 74%</li>
                <li><b>Secondary</b> – D.A.V. Public School (2015–2016) | CGPA: 8.6</li>
            </ul>
        </section>

        <!-- Projects -->
        <section class="mb-10 animate__animated animate__fadeInLeft">
            <h2 class="text-2xl font-bold mb-4">Projects</h2>
            <ul class="list-disc pl-6 text-gray-300 space-y-2">
                <li>
                    <b>Lead Generation Platform (Founder Project)</b> – 
                    <a href="https://maayank-malhotra.ddns.net/" target="_blank" class="text-blue-400 hover:underline">
                        Live
                    </a>  
                    Built on VPS with free domain, SSL, DNS, and integrated Gemini API + Google Maps API for automated lead generation.
                </li>
                <li><b>Job Portal (MERN)</b>: Job listings with filters, resume upload, admin panel. Deployed on AWS (Nginx + CI/CD).</li>
                <li><b>LMS Portal (Laravel)</b>: Student/teacher roles, assignments, grading, feedback, real-time updates with Pusher.</li>
                <li><b>CRM App (MERN)</b>: Lead tracking, pipeline management, task automation, dashboards, notifications.</li>
                <li><b>Audio/Video App (PHP + Node.js)</b>: Real-time WebRTC + socket.io with recording, chat, multi-browser support.</li>
            </ul>
        </section>

        <!-- Skills -->
        <section class="mb-10 animate__animated animate__fadeInRight">
            <h2 class="text-2xl font-bold mb-4">Skills</h2>
            <p class="text-gray-300">
                <b>Frontend:</b> HTML5, CSS3, JavaScript, TypeScript, React.js, Redux <br>
                <b>Backend:</b> Node.js, Express.js, PHP, Laravel <br>
                <b>APIs & Databases:</b> RESTful APIs, GraphQL, MySQL, MongoDB <br>
                <b>DevOps & Tools:</b> Docker, AWS, Git, Bitbucket, Linux, MacOS
            </p>
        </section>
    </main>

    <!-- Contact Popup -->
    <div id="leadForm" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center hidden">
        <div class="bg-white text-black rounded-2xl p-8 max-w-md w-full relative animate__animated animate__fadeInDown">
            <button onclick="document.getElementById('leadForm').classList.add('hidden')" 
                class="absolute top-2 right-2 text-gray-600 hover:text-black text-2xl">&times;</button>
            <h3 class="text-xl font-bold mb-4">Get in Touch</h3>
            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('resume.store') }}" class="space-y-3">
                @csrf
                <input type="text" name="name" placeholder="Your Name" 
                    class="w-full p-2 border rounded focus:ring focus:ring-blue-500" required>
                <input type="email" name="email" placeholder="Your Email" 
                    class="w-full p-2 border rounded focus:ring focus:ring-blue-500" required>
                <input type="text" name="phone" placeholder="Your Phone" 
                    class="w-full p-2 border rounded focus:ring focus:ring-blue-500">
                <textarea name="message" placeholder="Your Message"
                    class="w-full p-2 border rounded focus:ring focus:ring-blue-500"></textarea>
                <button type="submit" 
                    class="w-full py-2 bg-blue-600 hover:bg-blue-700 rounded text-white font-bold">
                    Submit
                </button>
            </form>
        </div>
    </div>

    <!-- JS to Show Popup on Page Load -->
    <script>
        window.onload = function() {
            document.getElementById('leadForm').classList.remove('hidden');
        }
    </script>
</body>
</html>
