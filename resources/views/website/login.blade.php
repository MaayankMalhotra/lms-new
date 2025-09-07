<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edutech Platform - Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
    input {
      transition: border-color 0.3s ease;
    }
    button {
      transition: background-color 0.3s ease, transform 0.2s ease;
    }
    button:hover {
      transform: scale(1.05);
    }
    .bg-circle {
      animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
    }
    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      border: 0;
    }
    .home-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      z-index: 20;
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen relative overflow-hidden">

  <!-- Background circles -->
  <div class="absolute w-[300px] h-[300px] bg-gradient-to-br from-indigo-300 to-purple-300 rounded-full top-[-100px] left-[-100px] opacity-90 bg-circle"></div>
  <div class="absolute w-[150px] h-[150px] bg-gradient-to-br from-pink-300 to-red-300 rounded-full top-10 left-48 opacity-90 bg-circle" style="animation-delay: -2s;"></div>
  <div class="absolute w-[250px] h-[250px] bg-gradient-to-br from-cyan-300 to-blue-300 rounded-full bottom-[-50px] right-[-50px] opacity-90 bg-circle" style="animation-delay: -4s;"></div>

  <!-- Background logos -->
  <img src="https://upload.wikimedia.org/wikipedia/commons/c/c3/Python-logo-notext.svg" loading="lazy" class="absolute h-20 w-20 top-40 left-20 opacity-80" alt="Python logo">
  <img src="https://img.icons8.com/?size=100&id=54087&format=png&color=000000" loading="lazy" class="absolute h-20 w-20 bottom-12 left-56 opacity-80" alt="Node.js logo">
  <img src="https://upload.wikimedia.org/wikipedia/commons/d/d7/Android_robot.svg" loading="lazy" class="absolute h-20 w-20 top-8 left-1/2 transform -translate-x-1/2 opacity-80" alt="Android logo">
  <img src="https://upload.wikimedia.org/wikipedia/en/3/30/Java_programming_language_logo.svg" loading="lazy" class="absolute h-20 w-20 top-24 right-20 opacity-80" alt="Java logo">
  <img src="https://upload.wikimedia.org/wikipedia/commons/a/a7/React-icon.svg" loading="lazy" class="absolute h-20 w-20 top-80 right-12 opacity-80" alt="React logo">

  <!-- Login Card -->
  <main class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg w-[450px] z-10 overflow-hidden card">
    <div class="h-2 bg-gradient-to-r from-[#2c0b57] to-[#0c3c7c]"></div>
    <div class="p-10">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome Back</h2>
        <a href="{{ route('home-page') }}" title="Go to Home Page" class="text-indigo-500 hover:text-indigo-700 dark:hover:text-indigo-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
        </a>
      </div>
      <!-- Success Message -->
      @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
          {{ session('success') }}
        </div>
      @endif
      <!-- Error Messages -->
      @if (session('error'))
        <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
          {{ session('error') }}
        </div>
      @endif
      @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
          <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Login Form -->
      <form id="login-form" action="{{ route('logincheck') }}" method="get">
        @csrf
        <div class="mb-6">
          <label for="email" class="sr-only">Email</label>
          <input 
            id="email"
            type="email" 
            name="email"
            placeholder="Email" 
            required
            value="{{ old('email') }}"
            class="w-full border-0 border-b-2 border-gray-200 focus:border-indigo-500 p-3 bg-gray-50 dark:bg-gray-700 rounded-md focus:outline-none text-gray-700 dark:text-gray-200" 
          />
        </div>
        
        <div class="relative mb-6">
          <label for="password" class="sr-only">Password</label>
          <input 
            id="password"
            type="password" 
            name="password"
            placeholder="Password" 
            required
            minlength="4"
            class="w-full border-0 border-b-2 border-gray-200 focus:border-indigo-500 p-3 bg-gray-50 dark:bg-gray-700 rounded-md pr-10 focus:outline-none text-gray-700 dark:text-gray-200" 
          />
          <button type="button" aria-label="Toggle password visibility" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10z" />
              <path d="M10 8a2 2 0 100 4 2 2 0 000-4z" />
            </svg>
          </button>
        </div>

        <div class="flex justify-between items-center mb-8">
          <div class="flex items-center">
            <input id="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
            <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Remember me</label>
          </div>
          <a href="" class="text-sm text-gray-500 dark:text-gray-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors">Forgot Password?</a>
        </div>

        <div class="flex gap-4">
          <a 
            href="{{ route('website-register-page') }}"
            class="w-1/2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold py-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 text-center transition-colors"
          >
            Register
          </a>
          <button 
            type="submit" 
            class="w-1/2 bg-indigo-500 text-white font-semibold py-3 rounded-lg hover:bg-indigo-600 flex justify-center items-center transition-colors"
          >
            <span class="hidden loading">Loading...</span>
            <span class="default">Sign In</span>
          </button>
        </div>
      </form>

    
    </div>
  </main>

  <script>
    // Password toggle
    const passwordInput = document.getElementById('password');
    const toggleButton = document.querySelector('button[aria-label="Toggle password visibility"]');
    toggleButton.addEventListener('click', () => {
      const type = passwordInput.type === 'password' ? 'text' : 'password';
      passwordInput.type = type;
    });

    // Form submission with loading state
    document.getElementById('login-form').addEventListener('submit', (e) => {
      const submitButton = document.querySelector('button[type="submit"]');
      submitButton.querySelector('.default').classList.add('hidden');
      submitButton.querySelector('.loading').classList.remove('hidden');
    });
  </script>
</body>
</html>