<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Metro Rail System - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hero-gradient { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); }
    </style>
</head>
<body class="h-screen flex flex-col">
    <nav class="bg-blue-900 text-white p-4 flex justify-between items-center shadow-lg">
        <h1 class="text-xl font-bold italic">METRO RAIL</h1>
        <div class="space-x-4">
            <a href="login.php" class="bg-white text-blue-900 px-4 py-2 rounded-lg font-bold hover:bg-gray-100 transition">Login</a>
            <a href="auth/register.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-500 transition border border-white">Register</a>
        </div>
    </nav>

    <div class="hero-gradient flex-grow flex items-center justify-center text-white text-center px-6">
        <div>
            <h2 class="text-5xl font-extrabold mb-4">Fast. Safe. Reliable.</h2>
            <p class="text-xl mb-8">Experience the future of urban commuting with our Metro Rail System.</p>
            <div class="flex justify-center space-x-6">
                <div class="bg-white/20 p-6 rounded-xl backdrop-blur-md w-64 border border-white/30">
                    <h3 class="font-bold text-lg">Passengers</h3>
                    <p class="text-sm mt-2 opacity-80">Book tickets instantly and track your journey.</p>
                </div>
                <div class="bg-white/20 p-6 rounded-xl backdrop-blur-md w-64 border border-white/30">
                    <h3 class="font-bold text-lg">Employees</h3>
                    <p class="text-sm mt-2 opacity-80">Manage schedules, trains, and station logs.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>