<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shop Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100">
    <header class="bg-[#131921] text-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <a href="{{ route('shop.index') }}" class="text-xl font-semibold">Aromea Admin</a>
            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ route('admin.shop.products.index') }}" class="hover:text-[#febd69]">Products</a>
                <a href="{{ route('shop.index') }}" class="hover:text-[#febd69]">View Store</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto mt-8 max-w-6xl px-4">
        @if(session('status'))
            <div class="mb-6 rounded-md bg-emerald-100 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>
