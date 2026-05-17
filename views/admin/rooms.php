<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black text-slate-900">Physical Inventory Cells Control</h2>
        <p class="text-sm text-slate-500">Manage individual physical rooms, floor positions, and live operational statuses.</p>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold"><?php echo $msg; ?></div>
    <?php endif; ?>
    <?php if (!empty($err)): ?>
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold"><?php echo $err; ?></div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Physical Asset Provisioning</h3>
        <form action="index.php?action=rooms" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <input type="hidden" name="id" id="room-id">
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Room Number</label>
                <input type="text" name="room_number" id="room-number" required placeholder="e.g., 304" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Floor Assignment</label>
                <input type="number" name="floor" id="room-floor" required placeholder="e.g., 3" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Structural Architecture Blueprint Type</label>
                <select name="room_type_id" id="room-type-id" required class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    <option value="">-- Choose Blueprint Model --</option>
                    <?php 
                    $roomTypes->data_seek(0);
                    while($t = $roomTypes->fetch_assoc()): 
                    ?>
                        <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['name']); ?> ($<?php echo $t['price_per_night']; ?>/nt)</option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Live Operations Status</label>
                <select name="status" id="room-status" required class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="dirty">Dirty</option>
                    <option value="maintenance">Under Maintenance</option>
                    <option value="blocked">Blocked / Restricted</option>
                </select>
            </div>

            <div class="md:col-span-2 space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Administrative Internal Notes</label>
                <input type="text" name="notes" id="room-notes" placeholder="e.g., Proximity to elevator shafts, ocean view orientation..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div class="md:col-span-3 text-right">
                <button type="submit" name="save_room" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl transition shadow-md">Deploy Physical Node</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase text-slate-400 tracking-wider">
                    <th class="p-4">Room Block</th>
                    <th class="p-4">Floor Level</th>
                    <th class="p-4">Assigned Blueprint Category</th>
                    <th class="p-4">Operations State</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                <?php while($row = $rooms->fetch_assoc()): ?>
                    <tr>
                        <td class="p-4 font-black text-slate-900">Room <?php echo htmlspecialchars($row['room_number']); ?></td>
                        <td class="p-4 text-slate-500">Level <?php echo $row['floor']; ?></td>
                        <td class="p-4 font-semibold text-slate-700"><?php echo htmlspecialchars($row['room_type_name']); ?></td>
                        <td class="p-4">
                            <?php if($row['status'] === 'available'): ?>
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full uppercase">Available</span>
                            <?php elseif($row['status'] === 'occupied'): ?>
                                <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full uppercase">Occupied</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-full uppercase"><?php echo $row['status']; ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <button onclick='editRoom(<?php echo json_encode($row); ?>)' class="text-xs font-bold text-amber-600 hover:underline">Modify</button>
                            <a href="index.php?action=rooms&delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Purge this room node permanently from the inventory schema? Only allowed if no active reservations exist.')" class="text-xs font-bold text-rose-600 hover:underline">Purge</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function editRoom(data) {
    document.getElementById('room-id').value = data.id;
    document.getElementById('room-number').value = data.room_number;
    document.getElementById('room-floor').value = data.floor;
    document.getElementById('room-type-id').value = data.room_type_id;
    document.getElementById('room-status').value = data.status;
    document.getElementById('room-notes').value = data.notes;
}
</script>