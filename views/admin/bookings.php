<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-2xl font-black text-slate-900">System Booking Ledger Matrix</h2>
        
        <form method="GET" action="index.php" class="bg-white border border-slate-200 p-3 rounded-xl shadow-sm flex flex-wrap gap-3 text-xs font-bold text-slate-600 items-end">
            <input type="hidden" name="action" value="bookings">
            
            <div class="flex flex-col gap-1">
                <span class="uppercase tracking-wider font-bold text-slate-400 text-[10px]">Status</span>
                <select name="status" class="px-2 py-1 border border-slate-200 rounded bg-white">
                    <option value="">All statuses</option>
                    <option value="pending" <?php if(($filters['status']??"")==='pending') echo 'selected';?>>Pending</option>
                    <option value="confirmed" <?php if(($filters['status']??"")==='confirmed') echo 'selected';?>>Confirmed</option>
                    <option value="checked_in" <?php if(($filters['status']??"")==='checked_in') echo 'selected';?>>Checked In</option>
                    <option value="checked_out" <?php if(($filters['status']??"")==='checked_out') echo 'selected';?>>Checked Out</option>
                    <option value="cancelled" <?php if(($filters['status']??"")==='cancelled') echo 'selected';?>>Cancelled</option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <span class="uppercase tracking-wider font-bold text-slate-400 text-[10px]">Source</span>
                <select name="source" class="px-2 py-1 border border-slate-200 rounded bg-white">
                    <option value="">All channels</option>
                    <option value="online" <?php if(($filters['source']??"")==='online') echo 'selected';?>>Online Portal</option>
                    <option value="walk_in" <?php if(($filters['source']??"")==='walk_in') echo 'selected';?>>Walk-in Desk</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-1.5 bg-slate-900 text-white font-bold rounded hover:bg-slate-800 transition">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase text-slate-400 tracking-wider">
                    <th class="p-4">Booking ID / Guest</th>
                    <th class="p-4">Assigned Room Unit</th>
                    <th class="p-4">Stay Duration Span</th>
                    <th class="p-4">Channel Source</th>
                    <th class="p-4">Processing Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                <?php if($bookings->num_rows === 0): ?>
                    <tr><td colspan="5" class="p-8 text-center text-slate-400 font-semibold bg-slate-50/50">No reservations currently fit the selected filtering parameter configurations.</td></tr>
                <?php else: ?>
                    <?php while($row = $bookings->fetch_assoc()): ?>
                        <tr>
                            <td class="p-4">
                                <div class="font-black text-slate-900">#BK-<?php echo $row['id']; ?></div>
                                <div class="text-xs text-slate-400 font-semibold"><?php echo htmlspecialchars($row['guest_name']); ?></div>
                            </td>
                            <td class="p-4 font-semibold text-slate-700">
                                Room <?php echo $row['room_number'] ?? 'Unassigned'; ?>
                                <span class="block text-[11px] text-slate-400 font-normal"><?php echo htmlspecialchars($row['room_type_name']); ?></span>
                            </td>
                            <td class="p-4 text-slate-600 font-mono text-xs font-bold">
                                <?php echo $row['checkin_date']; ?> ➔ <?php echo $row['checkout_date']; ?>
                            </td>
                            <td class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <?php echo $row['source'] === 'walk_in' ? 'Walk-in' : 'Online Website'; ?>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 text-xs font-bold uppercase rounded-full border border-slate-200 bg-slate-50 text-slate-700">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>