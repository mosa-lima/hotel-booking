<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black text-slate-900">Customer Feedback Audits</h2>
    </div>
    <div class="space-y-4">
        <?php if (isset($reviews['list']) && $reviews['list']->num_rows > 0): ?>
            <?php while($row = $reviews['list']->fetch_assoc()): ?>
                <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <div class="font-black text-slate-900"><?php echo htmlspecialchars($row['guest_name']); ?></div>
                        <div class="text-xs font-bold text-amber-500">Score: <?php echo $row['overall_rating']; ?>/5</div>
                        <p class="text-slate-600 text-sm italic mt-2">"<?php echo htmlspecialchars($row['comment'] ?? ''); ?>"</p>
                    </div>
                    <div class="md:col-span-2 bg-slate-900 text-white p-4 rounded-xl">
                        <span class="text-[10px] uppercase text-amber-400 font-bold block mb-2">Management Response</span>
                        <?php if(!empty($row['admin_reply'])): ?>
                            <p class="text-sm"><?php echo htmlspecialchars($row['admin_reply']); ?></p>
                        <?php else: ?>
                            <form action="index.php?action=reviews" method="POST" class="flex gap-2">
                                <input type="hidden" name="review_id" value="<?php echo $row['id']; ?>">
                                <input type="text" name="admin_reply" required placeholder="Type response..." class="flex-1 px-3 py-1.5 rounded bg-white text-slate-900 text-sm">
                                <button type="submit" name="submit_reply" class="px-4 py-1.5 bg-amber-500 text-slate-900 font-bold text-xs rounded uppercase">Reply</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="bg-white p-6 rounded-2xl text-center text-slate-400 border border-slate-200">No reviews found.</div>
        <?php endif; ?>
    </div>
</div>