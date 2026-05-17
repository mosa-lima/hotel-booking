<?php if(!empty($success_message)): ?>
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 flex items-center space-x-2 text-sm font-semibold">
        <span class="material-icons-outlined text-emerald-600">check_circle</span> <span><?php echo $success_message; ?></span>
    </div>
<?php endif; ?>
<?php if(!empty($error_message)): ?>
    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl mb-6 flex items-center space-x-2 text-sm font-semibold">
        <span class="material-icons-outlined text-rose-600">error</span> <span><?php echo $error_message; ?></span>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-fit">
        <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center space-x-2">
            <span class="material-icons-outlined text-amber-500">add_box</span> <span>Provision New Physical Room</span>
        </h3>
        <form action="index.php?action=rooms" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Room Number Code</label>
                <input type="text" name="room_number" required placeholder="e.g. 101" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-amber-500/20 text-slate-700 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Floor Level</label>
                <input type="number" name="floor" required min="1" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-amber-500/20 text-slate-700 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Room Architectural Type Model</label>
                <select name="room_type_id" required class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-amber-500/20 text-slate-700 focus:outline-none">
                    <option value="">Select Type Model...</option>
                    <?php while($row = $roomTypes->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?> </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status Tier</label>
                <select name="status" required class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-amber-500/20 text-slate-700 focus:outline-none">
                    <option value="available">Available</option>
                    <option value="blocked">Blocked Hold</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 tracking-wider uppercase mb-1">Footnotes & Addendums</label>
                <textarea name="notes" rows="2" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-amber-500/20 text-slate-700 focus:outline-none"></textarea>
            </div>
            <button type="submit" name="add_room" class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold text-sm shadow-md transition">Commit Asset to Database</button>
        </form>
    </div>

    <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100"><h3 class="text-base font-bold text-slate-800">Configured Blueprint Infrastructure Matrix</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100">
                        <th class="py-4 px-6">Room Code</th>
                        <th class="py-4 px-6">Classification Model</th>
                        <th class="py-4 px-6">Floor Level</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Administrative Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                    <?php if($rooms->num_rows == 0): ?>
                        <tr><td colspan="5" class="py-8 text-center text-slate-400">No rooms loaded in database architecture.</td></tr>
                    <?php else: while($room = $rooms->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-bold text-slate-900"><?php echo $room['room_number']; ?></td>
                            <td class="py-4 px-6 text-slate-500"><?php echo $room['room_type_name']; ?></td>
                            <td class="py-4 px-6">Floor <?php echo $room['floor']; ?></td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-0.5 text-xs font-bold uppercase rounded-md border <?php echo $room['status'] === 'available' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200'; ?>"><?php echo $room['status']; ?></span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="index.php?action=rooms&delete_id=<?php echo $room['id']; ?>" onclick="return confirm('Purge infrastructure asset safely? Ensure room has zero active bookings.');" class="inline-flex items-center space-x-1 px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-bold transition border border-rose-200">
                                    <span class="material-icons-outlined text-sm">delete</span> <span>Purge Room</span>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>