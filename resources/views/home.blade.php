<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMate</title>

    @vite(['resources/css/app.css'])
</head>

<body class="bg-gradient-to-br from-gray-950 via-indigo-950 to-black text-white overflow-hidden">

    <div class="min-h-screen flex flex-col justify-center items-center px-6">

        <div class="text-center max-w-3xl">

            <h1 class="text-7xl font-extrabold mb-6 bg-gradient-to-r from-purple-400 to-pink-500 bg-clip-text text-transparent">
                TaskMate
            </h1>

            <p class="text-xl text-gray-300 mb-10 leading-relaxed">
                Platform manajemen tugas modern untuk membantu mengatur project,
                deadline, dan kolaborasi tim dengan lebih efektif.
            </p>

            <div class="flex justify-center gap-5">

                <a href="{{ route('login') }}"
                   class="px-8 py-4 bg-purple-600 hover:bg-purple-700 rounded-2xl text-lg font-semibold shadow-lg transition duration-300">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="px-8 py-4 border border-gray-700 bg-gray-900 hover:bg-gray-800 rounded-2xl text-lg font-semibold transition duration-300">
                    Register
                </a>

            </div>

        </div>

        <div class="absolute bottom-8 text-gray-500 text-sm">
            © 2026 TaskMate. All rights reserved.
        </div>

    </div>

</body>
</html>
