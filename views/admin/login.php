<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin System Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl overflow-hidden p-8 border border-slate-100">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Grand Admin</h2>
            <p class="text-sm font-semibold text-slate-400 mt-1 uppercase tracking-widest">Internal Security Core Gateway</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-3 text-xs font-bold mb-6">
                Authentication Failed: Invalid credentials or unauthorized link attempt.
            </div>
        <?php endif; ?>

        <form action="index.php?action=process_login" method="POST" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Administrative Email</label>
                <input type="email" name="email" required placeholder="admin@grandhotel.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 text-slate-700 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Master Access Key</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/20 text-slate-700 focus:outline-none">
            </div>
            <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-sm transition shadow-lg shadow-slate-900/20">Authorize Terminal Access</button>
        </form>
    </div>
</body>
</html>