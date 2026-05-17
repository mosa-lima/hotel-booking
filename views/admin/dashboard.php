<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-900">Admin Dashboard</h2>
            <p class="text-sm text-slate-500">Real-time property operations and live metrics summary overview.</p>
        </div>
        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full uppercase tracking-wider flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active Session Verified
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        
        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Occupancy Rate</div>
            <div class="text-2xl font-black text-slate-900"><?php echo isset($stats['occupancy_rate']) ? $stats['occupancy_rate'] : '0'; ?>%</div>
            <div class="text-[11px] text-slate-400 mt-2 font-medium">Capacity utilization ratio</div>
        </div>

        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Today's Revenue</div>
            <div class="text-2xl font-black text-amber-500">$<?php echo isset($stats['today_revenue']) ? number_format($stats['today_revenue'], 2) : '0.00'; ?></div>
            <div class="text-[11px] text-emerald-600 font-bold mt-2">Paid settlements today</div>
        </div>

        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Available vs Occupied</div>
            <div class="text-xl font-black text-slate-900">
                <?php 
                    $total = $stats['total_rooms'] ?? 0;
                    $occ = $stats['occupied_rooms'] ?? 0;
                    $avail = $total - $occ;
                    echo "<span class='text-emerald-600'>{$avail}</span> <span class='text-slate-300 font-normal text-sm'>vs</span> <span class='text-rose-600'>{$occ}</span>";
                ?>
            </div>
            <div class="text-[11px] text-slate-400 mt-2 font-medium">Total inventory: <?php echo $total; ?> units</div>
        </div>

        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Active Maintenance</div>
            <div class="text-2xl font-black text-rose-600"><?php echo isset($stats['active_maintenance']) ? $stats['active_maintenance'] : '0'; ?></div>
            <div class="text-[11px] text-slate-400 mt-2 font-medium">Unresolved service tickets</div>
        </div>

        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pending Reviews</div>
            <div class="text-2xl font-black text-indigo-600"><?php echo isset($stats['pending_reviews']) ? $stats['pending_reviews'] : '0'; ?></div>
            <div class="text-[11px] text-indigo-500 font-semibold mt-2">Awaiting hotel response</div>
        </div>

    </div>

    <div class="bg-slate-900 text-white rounded-2xl p-6 border border-slate-800 shadow-xl">
        <h3 class="text-sm font-bold uppercase tracking-wider text-amber-400 mb-4 flex items-center gap-2">
            <span class="material-icons-outlined text-sm">sync</span> Asynchronous Telemetry Feed (Live AJAX Checking)
        </h3>
        <div class="grid grid-cols-3 gap-4 text-center">
            <div class="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                <div class="text-2xl font-black text-white" id="ajax-total">--</div>
                <div class="text-[10px] font-bold text-slate-400 uppercase mt-1">Total System Nodes</div>
            </div>
            <div class="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                <div class="text-2xl font-black text-emerald-400" id="ajax-avail">--</div>
                <div class="text-[10px] font-bold text-slate-400 uppercase mt-1">Ready Cells</div>
            </div>
            <div class="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                <div class="text-2xl font-black text-rose-400" id="ajax-occupied">--</div>
                <div class="text-[10px] font-bold text-slate-400 uppercase mt-1">Occupied Units</div>
            </div>
        </div>
    </div>
</div>

<script>

function synchronizeTelemetryData() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'api_stats.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if(response.status === 'success') {
                    document.getElementById('ajax-total').innerText = response.total_inventory;
                    document.getElementById('ajax-avail').innerText = response.available;
                    document.getElementById('ajax-occupied').innerText = response.occupied;
                }
            } catch(e) {
                console.error("Telemetry parse exception encountered.");
            }
        }
    };
    xhr.send();
}

window.addEventListener('DOMContentLoaded', synchronizeTelemetryData);
</script>