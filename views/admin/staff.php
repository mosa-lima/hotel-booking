<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black text-slate-900">Manage Internal Staff</h2>
        <p class="text-sm text-slate-500">Provision, monitor, and manage access parameters for hotel personnel accounts.</p>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold"><?php echo $msg; ?></div>
    <?php endif; ?>
    <?php if (!empty($err)): ?>
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold"><?php echo $err; ?></div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Provision Staff Credentials</h3>
        <form action="index.php?action=staff" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Full Name</label>
                <input type="text" name="name" required placeholder="e.g., Jane Smith" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Work Email Address</label>
                <input type="email" name="email" required placeholder="jane@hotel.com" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">Temporary Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 uppercase">System Role Clearance</label>
                <select name="role" required class="w-full px-4 py-2 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    <option value="receptionist">Receptionist</option>
                    <option value="housekeeping">Housekeeping Supervisor</option>
                </select>
            </div>

            <div class="md:col-span-4 text-right">
                <button type="submit" name="save_staff" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl transition shadow-md">Create Staff Account</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase text-slate-400 tracking-wider">
                    <th class="p-4">Staff Member</th>
                    <th class="p-4">Email Channel</th>
                    <th class="p-4">Operational Role</th>
                    <th class="p-4">Access Status</th>
                    <th class="p-4 text-right">Clearance Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                <?php while($row = $staff->fetch_assoc()): ?>
                    <tr>
                        <td class="p-4 font-bold text-slate-900"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td class="p-4 text-slate-500"><?php echo htmlspecialchars($row['email']); ?></td>
                        <td class="p-4 uppercase text-xs tracking-wider font-semibold text-slate-600">
                            <?php echo $row['role'] === 'housekeeping' ? 'Housekeeping Supervisor' : 'Receptionist'; ?>
                        </td>
                        <td class="p-4">
                            <?php if($row['is_active'] == 1): ?>
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full uppercase">Active</span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full uppercase">Deactivated</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-right">
                            <a href="index.php?action=staff&toggle_active=<?php echo $row['id']; ?>" class="text-xs font-bold <?php echo $row['is_active'] == 1 ? 'text-rose-600' : 'text-emerald-600'; ?> hover:underline">
                                <?php echo $row['is_active'] == 1 ? 'Deactivate Account' : 'Activate Account'; ?>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>