<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6 print:hidden">
        <p class="text-sm text-slate-500">Comprehensive consolidated organizational revenue ledger mapping overview index data stream matrix layout.</p>
        <button onclick="window.print()" class="flex items-center space-x-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition shadow-lg shadow-slate-900/10">
            <span class="material-icons-outlined text-sm">print</span> <span>Export Printable Hardcopy Audit Page</span>
        </button>
    </div>

    <div class="hidden print:block text-center border-b-2 border-slate-900 pb-6 mb-8">
        <h1 class="text-3xl font-black uppercase tracking-wider text-slate-900">Grand Financial Audit Operational Breakdown Matrix</h1>
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mt-1">Generated Chronological Sequence Tracking Node Timestamp: <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50"><h3 class="text-base font-bold text-slate-800">Macro Inflow Settlement Aggregation Data Structure Summary</h3></div>
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-100 text-center">
            <div class="p-6">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Gross Baseline Structural Room Inflows</p>
                <h4 class="text-3xl font-black text-slate-900 mt-2">$<?php echo number_format($summary['room_revenue'] ?? 0, 2); ?></h4>
            </div>
            <div class="p-6">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Addendum Peripheral Amenities & Services Revenue</p>
                <h4 class="text-3xl font-black text-slate-900 mt-2">$<?php echo number_format($summary['services_revenue'] ?? 0, 2); ?></h4>
            </div>
            <div class="p-6 bg-amber-50/20">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-800">Net Consolidate Revenue Liquidity</p>
                <h4 class="text-3xl font-black text-amber-600 mt-2">$<?php echo number_format($summary['net_revenue'] ?? 0, 2); ?></h4>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50"><h3 class="text-base font-bold text-slate-800">Revenue Density Categorization Segmented Allocation Maps By Structural Tiers</h3></div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100">
                    <th class="py-4 px-6">Product Line Classification Category Blueprint Model</th>
                    <th class="py-4 px-6 text-right">Settled Account Receivables Net Inflow Stream Contribution</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium text-slate-700 text-sm">
                <?php while($row = $roomTypeRev->fetch_assoc()): ?>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 px-6 font-bold text-slate-900"><?php echo $row['name']; ?> Tier Accommodations</td>
                        <td class="py-4 px-6 text-right font-black text-slate-800">$<?php echo number_format($row['revenue'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>