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
    <style>
        .admin-shell {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .admin-sidebar {
            width: 20%;
            min-width: 260px;
            max-width: 320px;
            flex-shrink: 0;
        }
    </style>
</head>

<body class="text-gray-900 font-sans"
      style="font-family: 'Inter', sans-serif;
             background: url('https://img.freepik.com/free-vector/education-pattern-background-doodle-style_53876-115365.jpg') no-repeat center center fixed;
             background-size: cover;">
             
    <div class="admin-shell">
        <!-- Sidebar -->
        <aside class="admin-sidebar h-full bg-[#2c1d56] px-2 flex flex-col overflow-hidden">
            @include('admin.partials.sidebar')
        </aside>

        <!-- Main Content Area -->
        <div class="admin-main flex-1 min-w-0 flex flex-col h-full overflow-hidden">
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
@stack('scripts')
</body>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    (function () {
        try {
            const stored = localStorage.getItem('adminPanelBg');
            if (!stored) {
                return;
            }

            const parsed = JSON.parse(stored);
            if (!parsed || typeof parsed !== 'object') {
                return;
            }

            const {
                image,
                size = 'cover',
                repeat = 'no-repeat',
                position = 'center center',
                color = ''
            } = parsed;

            if (!image) {
                return;
            }

            const body = document.body;
            body.style.backgroundImage = `url('${image}')`;
            body.style.backgroundSize = size;
            body.style.backgroundRepeat = repeat;
            body.style.backgroundPosition = position;
            if (color) {
                body.style.backgroundColor = color;
            }
        } catch (error) {
            console.warn('Unable to apply stored admin background', error);
        }
    })();
</script>
</html>
