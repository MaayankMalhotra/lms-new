<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Your Presence</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script> {{-- Use this if not using Vite --}}
</head>
<body class="min-h-screen bg-gradient-to-r from-purple-800 to-blue-800 flex items-center justify-center p-4">

    <div class="bg-white shadow-xl rounded-lg w-full max-w-md p-8 space-y-6">
        <div class="text-center">
            <img src="http://16.16.64.105/images/THINK%20CHAMP%20logo2.png" alt="Think Champ Logo" class="w-40 mx-auto mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Webinar Attendance Confirmation</h2>
            <p class="text-gray-600 mt-2">Please enter your attendance verification code</p>
        </div>

        <form method="POST" action="{{ route('attendance.submit.webinar') }}" class="space-y-4">
            @csrf

            <!-- Hidden Email Input -->
            <input type="hidden" name="email" value="{{ $email }}">

            <!-- Hidden Webinar Name Input -->
            <input type="hidden" name="webinar_title" value="{{ $webinar_title }}">

            <!-- Verification Code Input -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700">Attendance Verification Code</label>
                <input type="text" name="code" id="code" required
                       class="mt-1 block w-full rounded-md border-black shadow-sm border focus:border-blue-500 focus:ring-blue-500 px-4 py-2">
            </div>

            <button type="submit"
                    class="w-full bg-blue-700 hover:bg-blue-800 text-white  font-semibold py-2 px-4 rounded-md transition duration-300">
                Submit Confirmation
            </button>
        </form>

        <p class="text-sm text-center text-gray-500 mt-4">Thank you for confirming your presence!</p>
    </div>

</body>
</html>
