<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black text-slate-900">Guest Ledger Accounts</h2>
        <form action="index.php" method="GET" class="flex gap-2">
            <input type="hidden" name="action" value="guests">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search name or email..." class="px-4 py-1.5 border border-slate-200 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            <button type="submit" class="px-4 py-1.5 bg-slate-900 text-white text-sm font-bold rounded-xl">Search</button>
        </form>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase text-slate-400 tracking-wider">
                    <th class="p-4">Guest Profile</th>
                    <th class="p-4">Contact Phone</th>
                    <th class="p-4">Nationality</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Policy Interventions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                <?php if($guests->num_rows === 0): ?>
                    <tr><td colspan="5" class="p-8 text-center text-slate-400 font-semibold bg-slate-50/50">No guest records found matching the query criteria.</td></tr>
                <?php else: ?>
                    <?php while($row = $guests->fetch_assoc()): ?>
                        <tr>
                            <td class="p-4">
                                <div class="font-bold text-slate-900"><?php echo htmlspecialchars($row['name']); ?></div>
                                <div class="text-xs text-slate-400 font-normal"><?php echo htmlspecialchars($row['email']); ?></div>
                            </td>
                            <td class="p-4 text-slate-500"><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                            <td class="p-4 text-slate-600 font-semibold"><?php echo htmlspecialchars($row['nationality'] ?? 'N/A'); ?></td>
                            <td class="p-4">
                                <?php if($row['is_active'] == 1): ?>
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full uppercase">Good Standing</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full uppercase">Suspended</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-right">
                                <a href="index.php?action=guests&toggle_active=<?php echo $row['id']; ?>&search=<?php echo urlencode($search); ?>" class="text-xs font-bold <?php echo $row['is_active'] == 1 ? 'text-rose-600' : 'text-emerald-600'; ?> hover:underline">
                                    <?php echo $row['is_active'] == 1 ? 'Suspend Account' : 'Reactivate Account'; ?>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>