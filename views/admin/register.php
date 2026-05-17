<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin Account</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body {
            background-color: #0b132b;
            color: #ffffff;
            font-family: sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="bg-[#1c2541] p-8 rounded-2xl shadow-2xl max-w-md w-full border border-slate-700/30 space-y-6">
        
        <div class="text-left">
            <h2 class="text-3xl font-black text-white tracking-tight">Create Admin Account</h2>
        </div>

        <form action="index.php?action=register_process" method="POST" class="space-y-4 text-sm font-medium">
            
            <div class="flex flex-col space-y-1.5">
                <label class="text-slate-300 font-bold">Full Name:</label>
                <input type="text" name="name" required class="w-full px-4 py-2 bg-[#0b132b] text-white border border-slate-700/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/40 transition">
            </div>

            <div class="flex flex-col space-y-1.5">
                <label class="text-slate-300 font-bold">Custom Email:</label>
                <input type="email" name="email" required class="w-full px-4 py-2 bg-[#0b132b] text-white border border-slate-700/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/40 transition">
            </div>

            <div class="flex flex-col space-y-1.5">
                <label class="text-slate-300 font-bold">Custom Password:</label>
                <input type="password" name="password" required class="w-full px-4 py-2 bg-[#0b132b] text-white border border-slate-700/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/40 transition">
            </div>

            <button type="submit" name="register_admin" class="w-full py-3 bg-[#f59e0b] hover:bg-[#d97706] text-slate-950 font-black rounded-xl transition duration-200 shadow-lg text-center mt-2">
                Register Account
            </button>
        </form>

        <div class="pt-2">
            <a href="index.php?action=dashboard" class="text-sm font-bold text-[#f59e0b] hover:underline tracking-wide">Go to Login Portal</a>
        </div>
    </div>

</body>
</html>