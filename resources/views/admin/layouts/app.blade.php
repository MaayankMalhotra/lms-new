<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Google Font (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

    <!-- Tailwind Config for Google Font -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        };
    </script>
</head>

<body class="text-gray-900 font-sans"
      style="font-family: 'Inter', sans-serif;
             background: url('https://img.freepik.com/free-vector/education-pattern-background-doodle-style_53876-115365.jpg') no-repeat center center fixed;
             background-size: cover;">
             
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="h-full bg-[#2c1d56] px-2 w-1/5 flex-shrink-0 ">
            @include('admin.partials.sidebar')
        </aside>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-md w-full px-6 py-2 flex justify-between items-center flex-shrink-0">
                @include('admin.partials.header')
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-auto p-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
</html>
