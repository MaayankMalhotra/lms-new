<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edutech Platform - Register</title>
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
  </style>
</head>
<body class="flex items-center justify-center min-h-screen relative overflow-hidden ">

  <!-- Background circles -->
  <div class="absolute w-[300px] h-[300px] bg-gradient-to-br from-pink-400 to-red-400 rounded-full top-[-100px] left-[-100px] opacity-70 bg-circle"></div>
  <div class="absolute w-[150px] h-[150px] bg-gradient-to-br from-purple-400 to-indigo-400 rounded-full top-10 right-48 opacity-70 bg-circle" style="animation-delay: -2s;"></div>
  <div class="absolute w-[250px] h-[250px] bg-gradient-to-br from-cyan-400 to-teal-400 rounded-full bottom-[-50px] left-[-50px] opacity-70 bg-circle" style="animation-delay: -4s;"></div>

  <!-- Background logos -->
  <img src="https://upload.wikimedia.org/wikipedia/commons/c/c3/Python-logo-notext.svg" loading="lazy" class="absolute h-20 w-20 top-40 left-20 opacity-70" alt="Python logo">
  <img src="https://img.icons8.com/?size=100&id=54087&format=png&color=000000" loading="lazy" class="absolute h-20 w-20 bottom-12 left-56 opacity-70" alt="Node.js logo">
  <img src="https://upload.wikimedia.org/wikipedia/en/3/30/Java_programming_language_logo.svg" loading="lazy" class="absolute h-20 w-20 top-24 right-20 opacity-70" alt="Java logo">
  <img src="https://upload.wikimedia.org/wikipedia/commons/a/a7/React-icon.svg" loading="lazy" class="absolute h-20 w-20 top-80 right-12 opacity-70" alt="React logo">

  <!-- Register Card -->
  <main class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg w-[500px] h-[600px] border  z-10 overflow-y-scroll scrollbar-hidden card">
    <div class="p-8">
      <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Teacher Registeration</h2>

      <!-- Register Form -->
      <form id="register-form" action="{{ route('register.submit.teacher') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
          <label for="profile_image" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Profile Image</label>
          <input 
            id="profile_image"
            type="file" 
            name="profile_image"
            accept="image/*"
            class="w-full border border-gray-300 rounded-md p-2 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700 dark:text-gray-200"
          />
          <img id="image_preview" src="#" alt="Profile Preview" class="mt-2 w-20 h-20 object-cover rounded-full hidden">
        </div>

        <div class="mb-4">
          <label for="name" class="sr-only">Name</label>
          <input 
            id="name"
            type="text" 
            name="name"
            placeholder="Name" 
            required
            class="w-full border border-gray-300 rounded-md p-3 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500" 
          />
        </div>

        <div class="mb-4">
          <label for="qualification" class="sr-only">Qualification</label>
          <input 
            id="qualification"
            type="text" 
            name="qualification"
            placeholder="Qualification" 
            required
            class="w-full border border-gray-300 rounded-md p-3 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500" 
          />
        </div>

        <div class="mb-4">
          <label for="college_company" class="sr-only">College/Company</label>
          <input 
            id="college_company"
            type="text" 
            name="college_company"
            placeholder="College/Company" 
            required
            class="w-full border border-gray-300 rounded-md p-3 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500" 
          />
        </div>

        <div class="mb-4">
          <label for="email" class="sr-only">Email</label>
          <input 
            id="email"
            type="email" 
            name="email"
            placeholder="Email" 
            required
            class="w-full border border-gray-300 rounded-md p-3 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500" 
          />
        </div>

        <div class="mb-4">
          <label for="phone" class="sr-only">Phone Number</label>
          <input 
            id="phone"
            type="tel" 
            name="phone"
            placeholder="Phone Number" 
            required
            pattern="[0-9]{10}"
            class="w-full border border-gray-300 rounded-md p-3 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500" 
          />
        </div>

        <div class="mb-4 relative">
          <label for="password" class="sr-only">Password</label>
          <input 
            id="password"
            type="password" 
            name="password"
            placeholder="Password" 
            required
            minlength="5"
            class="w-full border border-gray-300 rounded-md p-3 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 pr-10" 
          />
          <button type="button" aria-label="Toggle password visibility" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10z" />
              <path d="M10 8a2 2 0 100 4 2 2 0 000-4z" />
            </svg>
          </button>
        </div>

        <div class="mb-6 relative">
          <label for="confirm_password" class="sr-only">Confirm Password</label>
          <input 
            id="confirm_password"
            type="password" 
            name="confirm_password"
            placeholder="Confirm Password" 
            required
            minlength="5"
            class="w-full border border-gray-300 rounded-md p-3 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 pr-10" 
          />
          <button type="button" aria-label="Toggle confirm password visibility" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10z" />
              <path d="M10 8a2 2 0 100 4 2 2 0 000-4z" />
            </svg>
          </button>
        </div>

        <div class="flex gap-4">
        <button 
            type="submit" 
            class="w-1/2 bg-orange-500 text-white font-semibold py-3 rounded-lg hover:bg-orange-600 flex justify-center items-center"
          >
            <span class="hidden loading">Loading...</span>
            <span class="default">Register</span>
          </button>
          <a 
            href="/login"
            class="w-1/2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold py-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 text-center"
          >
            Sign In
          </a>
        </div>
      </form>
    </div>
  </main>

  <script>
    // Password toggle for both password fields
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    const toggleButtons = document.querySelectorAll('button[aria-label*="Toggle"]');
    toggleButtons.forEach((btn, index) => {
      btn.addEventListener('click', () => {
        const type = passwordInputs[index].type === 'password' ? 'text' : 'password';
        passwordInputs[index].type = type;
      });
    });

    // Image preview
    const profileImageInput = document.getElementById('profile_image');
    const imagePreview = document.getElementById('image_preview');
    profileImageInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          imagePreview.src = e.target.result;
          imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
      } else {
        imagePreview.classList.add('hidden');
      }
    });

    // Form submission with loading state
    document.getElementById('register-form').addEventListener('submit', (e) => {
      const submitButton = document.querySelector('button[type="submit"]');
      submitButton.querySelector('.default').classList.add('hidden');
      submitButton.querySelector('.loading').classList.remove('hidden');
    });
  </script>
</body>
</html>
