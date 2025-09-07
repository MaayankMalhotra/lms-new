<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You for Your Presence</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-700 to-blue-800 text-white">

    <div class="bg-white p-10 rounded-lg shadow-xl max-w-lg w-full text-center">
        <img src="http://16.16.64.105/images/THINK%20CHAMP%20logo2.png" alt="Think Champ Logo" class="w-40 mx-auto mb-6">

        <h1 class="text-2xl font-bold text-gray-800 mb-2">Thank You, {{ $name }}!</h1>
        <p class="text-gray-700 text-md mb-4">
            Your presence has been successfully confirmed for the webinar:<br>
            <strong>{{ $webinar_title }}</strong>
        </p>

        <p class="text-sm text-gray-500 mb-6">We appreciate your participation.</p>

        <!-- Return to website button -->
        <a href="{{ route('home-page') }}"
           class="inline-block bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800 transition duration-300">
            Return to Our Website
        </a>
    </div>

</body>
</html>
