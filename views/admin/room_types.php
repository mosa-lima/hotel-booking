<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black text-slate-900">Manage Room Types</h2>
        <p class="text-sm text-slate-500">Configure base pricing blueprints and capacities</p>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold"><?php echo $msg; ?></div>
    <?php endif; ?>
    <?php if (!empty($err)): ?>
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold"><?php echo $err; ?></div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Room Type Configuration Blueprint</h3>
        <form action="index.php?action=room_types" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <input type="hidden" name="id" id="type-id">
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Room Type Name</label>
                <input type="text" name="name" id="type-name" required placeholder="e.g., Deluxe Suite" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Price Per Night ($)</label>
                <input type="number" step="0.01" name="price_per_night" id="type-price" required placeholder="150.00" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Maximum Guest Capacity</label>
                <input type="number" name="max_capacity" id="type-capacity" required placeholder="3" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Thumbnail Image Upload</label>
                <input type="file" name="thumbnail" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
            </div>

            <div class="md:col-span-2 space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Description Details</label>
                <textarea name="description" id="type-desc" rows="2" placeholder="Describe the configuration layout traits..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20"></textarea>
            </div>

            <div class="md:col-span-2 space-y-2">
                <label class="text-xs font-bold text-slate-500 uppercase block">Amenities Checklist</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="flex items-center gap-2 text-sm text-slate-700 font-medium"><input type="checkbox" name="amenities[]" value="WiFi" class="rounded text-amber-500"> Free high-speed WiFi</label>
                    <label class="flex items-center gap-2 text-sm text-slate-700 font-medium"><input type="checkbox" name="amenities[]" value="AC" class="rounded text-amber-500"> Air Conditioning</label>
                    <label class="flex items-center gap-2 text-sm text-slate-700 font-medium"><input type="checkbox" name="amenities[]" value="TV" class="rounded text-amber-500"> Smart TV Premium</label>
                    <label class="flex items-center gap-2 text-sm text-slate-700 font-medium"><input type="checkbox" name="amenities[]" value="MiniBar" class="rounded text-amber-500"> Local Mini Bar</label>
                </div>
            </div>

            <div class="md:col-span-2 text-right">
                <button type="submit" name="save_room_type" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl transition shadow-md">Save Room Type Blueprint</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase text-slate-400 tracking-wider">
                    <th class="p-4">Type Model Blueprint</th>
                    <th class="p-4">Capacity</th>
                    <th class="p-4">Price / Night</th>
                    <th class="p-4 text-right">Operational Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                <?php while($row = $roomTypes->fetch_assoc()): ?>
                    <tr>
                        <td class="p-4">
                            <div class="font-bold text-slate-900"><?php echo htmlspecialchars($row['name']); ?></div>
                            <div class="text-xs text-slate-400 font-normal max-w-md truncate"><?php echo htmlspecialchars($row['description']); ?></div>
                        </td>
                        <td class="p-4 text-slate-500"><?php echo $row['max_capacity']; ?> Guests max</td>
                        <td class="p-4 font-bold text-slate-900">$<?php echo number_format($row['price_per_night'], 2); ?></td>
                        <td class="p-4 text-right space-x-2">
                            <button onclick='editType(<?php echo json_encode($row); ?>)' class="text-xs font-bold text-amber-600 hover:underline">Edit parameters</button>
                            <a href="index.php?action=room_types&delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Purge this room type configuration model template?')" class="text-xs font-bold text-rose-600 hover:underline">Delete blueprint</a>
                        </td>
                    </tr>
                <?php endwhile; ?> </tbody>
        </table>
    </div>
</div>

<script>
function editType(data) {
    document.getElementById('type-id').value = data.id;
    document.getElementById('type-name').value = data.name;
    document.getElementById('type-price').value = data.price_per_night;
    document.getElementById('type-capacity').value = data.max_capacity;
    document.getElementById('type-desc').value = data.description;
}
</script>