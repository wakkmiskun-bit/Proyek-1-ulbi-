<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>

    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-950 text-white flex justify-center items-center min-h-screen">

    <div class="text-center">

        <h1 class="text-5xl font-bold mb-4 text-purple-500">
            Selamat Datang User
        </h1>

        <p class="text-gray-400 mb-8">
            Anda berhasil login sebagai user.
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-xl">
                Logout
            </button>
        </form>

    </div>

</body>
</html>
