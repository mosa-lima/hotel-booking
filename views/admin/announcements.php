<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black text-slate-900">Global Announcements Board</h2>
        <p class="text-sm text-slate-500">Broadcast notification feeds directly to active customer dashboard views.</p>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Compose Global System Notice</h3>
        <form action="index.php?action=announcements" method="POST" class="space-y-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Announcement Headline Title</label>
                <input type="text" name="title" required placeholder="e.g., Scheduled Core Maintenance, Swimming Pool Renovation Schedules" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Notice Content Message Body</label>
                <textarea name="content" rows="3" required placeholder="Type notice parameters here..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20"></textarea>
            </div>
            <div class="text-right">
                <button type="submit" name="post_announcement" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl transition shadow-md">Broadcast Notice Feed</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Broadcast Audit Log Logs</h3>
        <div class="divide-y divide-slate-100">
            <?php while($row = $announcements->fetch_assoc()): ?>
                <div class="py-4 first:pt-0 last:pb-0 space-y-1">
                    <div class="flex justify-between items-center">
                        <h4 class="font-bold text-slate-900"><?php echo htmlspecialchars($row['title']); ?></h4>
                        <span class="text-[10px] text-slate-400 font-mono"><?php echo $row['created_at']; ?></span>
                    </div>
                    <p class="text-sm text-slate-600"><?php echo htmlspecialchars($row['content']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>