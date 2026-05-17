<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin Engine'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body class="bg-slate-50 min-h-screen font-sans flex m-0 p-0">
    <aside class="w-64 bg-slate-900 text-slate-400 p-6 flex flex-col justify-between shrink-0 min-h-screen box-border">
        <div class="space-y-8">
            <div class="text-white text-2xl font-black tracking-tight border-b border-slate-800 pb-4">Admin</div>
            <nav class="space-y-1 text-sm font-bold flex flex-col">
                <a href="index.php?action=dashboard" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-800 hover:text-white rounded-xl transition text-slate-300"><span class="material-icons-outlined text-sm">dashboard</span> Terminal Home</a>
                <a href="index.php?action=room_types" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-800 hover:text-white rounded-xl transition text-slate-300"><span class="material-icons-outlined text-sm">layers</span> Room Blueprints</a>
                <a href="index.php?action=rooms" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-800 hover:text-white rounded-xl transition text-slate-300"><span class="material-icons-outlined text-sm">bed</span> Physical Inventory</a>
                <a href="index.php?action=pricing" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-800 hover:text-white rounded-xl transition text-slate-300"><span class="material-icons-outlined text-sm">sell</span> Seasonal Pricing</a>
                <a href="index.php?action=staff" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-800 hover:text-white rounded-xl transition text-slate-300"><span class="material-icons-outlined text-sm">badge</span> Manage Internal Staff</a>
                <a href="index.php?action=guests" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-800 hover:text-white rounded-xl transition text-slate-300"><span class="material-icons-outlined text-sm">people</span> Guest Ledgers</a>
                <a href="index.php?action=bookings" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-800 hover:text-white rounded-xl transition text-slate-300"><span class="material-icons-outlined text-sm">receipt_long</span> Operational Bookings</a>
                <a href="index.php?action=reports" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-800 hover:text-white rounded-xl transition text-slate-300"><span class="material-icons-outlined text-sm">analytics</span> Audit Summaries</a>
                <a href="index.php?action=reviews" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-800 hover:text-white rounded-xl transition text-slate-300"><span class="material-icons-outlined text-sm">rate_review</span> Feedback Reviews</a>
                <a href="index.php?action=announcements" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-800 hover:text-white rounded-xl transition text-slate-300"><span class="material-icons-outlined text-sm">campaign</span> Global Notices</a>
            </nav>
        </div>
        <a href="index.php?action=logout" class="flex items-center gap-3 px-4 py-2.5 bg-rose-950/40 border border-rose-900/40 text-rose-400 hover:bg-rose-900 hover:text-white rounded-xl transition text-sm font-bold no-underline"><span class="material-icons-outlined text-sm">logout</span> Logout</a>
    </aside>

    <main class="flex-1 p-10 overflow-y-auto box-border flex flex-col justify-between min-h-screen">
        <div class="w-full">