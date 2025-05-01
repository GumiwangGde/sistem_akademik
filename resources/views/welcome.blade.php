<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Laravel</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="antialiased bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center space-y-4">
        <h1 class="text-3xl font-bold">Welcome to Laravel</h1>
        <div class="space-x-4">
            <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Login</a>
            <a href="{{ route('register') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Register</a>
        </div>
    </div>
</body>
</html>
