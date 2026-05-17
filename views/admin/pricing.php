<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black text-slate-900">Dynamic Seasonal Rates Overrides</h2>
        <p class="text-sm text-slate-500">Configure promotional or premium pricing rules mapped against calendar date ranges.</p>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold"><?php echo $msg; ?></div>
    <?php endif; ?>
    <?php if (!empty($err)): ?>
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold"><?php echo $err; ?></div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Deploy Date Range Rate Adjustment Policy</h3>
        <form action="index.php?action=pricing" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Policy Label Tag</label>
                <input type="text" name="label" required placeholder="e.g., Eid Holiday Premium, Summer Season Sale" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Target Architecture Structural Blueprint</label>
                <select name="room_type_id" required class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    <option value="">-- Choose Target Blueprint Model --</option>
                    <?php 
                    $roomTypes->data_seek(0);
                    while($t = $roomTypes->fetch_assoc()): 
                    ?>
                        <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Policy Operational Start Date</label>
                <input type="date" name="start_date" required class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Policy Operational Termination Date</label>
                <input type="date" name="end_date" required class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Override Price Target Rate ($ per night)</label>
                <input type="number" step="0.01" name="price_per_night" required placeholder="e.g., 275.00" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div class="md:col-span-2 text-right">
                <button type="submit" name="add_override" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl transition shadow-md">Deploy Price Adjustment Clause</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase text-slate-400 tracking-wider">
                    <th class="p-4">Active Adjustment Policy Directive</th>
                    <th class="p-4">Target Blueprint Variant</th>
                    <th class="p-4">Active Calendar Range Span</th>
                    <th class="p-4">Active Override Cost Scale</th>
                    <th class="p-4 text-right">System Removals</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                <?php if($overrides->num_rows === 0): ?>
                    <tr><td colspan="5" class="p-8 text-center text-slate-400 font-semibold bg-slate-50/50">No overrides deployed. Standard base blueprints rates apply universally.</td></tr>
                <?php else: ?>
                    <?php while($row = $overrides->fetch_assoc()): ?>
                        <tr>
                            <td class="p-4 font-bold text-slate-900"><?php echo htmlspecialchars($row['label']); ?></td>
                            <td class="p-4 text-slate-600 font-semibold"><?php echo htmlspecialchars($row['room_type_name']); ?></td>
                            <td class="p-4 text-slate-500 text-xs font-bold uppercase tracking-wider"><?php echo $row['start_date']; ?> <span class="text-slate-300 mx-1">➔</span> <?php echo $row['end_date']; ?></td>
                            <td class="p-4 font-black text-amber-600">$<?php echo number_format($row['price_per_night'], 2); ?>/nt</td>
                            <td class="p-4 text-right">
                                <a href="index.php?action=pricing&delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Retract this dynamic pricing override policy clause?')" class="text-xs font-bold text-rose-600 hover:underline">Retract Directive</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>