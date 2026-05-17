<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black text-slate-900">Business Auditing and Reports</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Financial Revenue Parameters</h3>
            <div class="divide-y divide-slate-100 text-sm font-semibold">
                <div class="py-3 flex justify-between">
                    <span class="text-slate-500">Gross Room Booking Revenue</span>
                    <span class="text-slate-900 font-mono font-bold">$<?php echo number_format($reports['extras']['base'] ?? 0, 2); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>