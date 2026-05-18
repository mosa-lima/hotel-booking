<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

require_login();
$user = current_user();
$pdo = db();
$rooms = fetch_room_board($pdo);
$stats = fetch_dashboard_stats($pdo);
$tasks = fetch_today_tasks($pdo);
$maintenance = fetch_open_maintenance($pdo);
$upcoming = fetch_upcoming_activity($pdo);
$report = fetch_daily_report($pdo);
$history = fetch_room_history($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="shell">
        <aside class="sidebar">
            <div>
                <p class="eyebrow">Role 3</p>
                <h2>Housekeeping Supervisor</h2>
                <p class="muted">Monitor room readiness, schedule tasks, and coordinate maintenance.</p>
            </div>
            <nav class="stack-sm">
                <a href="#dashboard-overview">Dashboard</a>
                <a href="#room-board">Room Status Board</a>
                <a href="#task-center">Task Center</a>
                <a href="#maintenance-center">Maintenance</a>
                <a href="#planning-center">Planning</a>
                <a href="#report-center">Daily Report</a>
                <a href="#history-center">Task History</a>
                <a href="#profile-center">Profile</a>
            </nav>
            <a href="logout.php" class="btn ghost">Log Out</a>
        </aside>

        <main class="content">
            <header class="page-header">
                <div>
                    <p class="eyebrow">Hotel Operations</p>
                    <h1>Housekeeping Dashboard</h1>
                    <p class="muted">Live room board and daily operational controls for cleaning, inspections, and maintenance.</p>
                </div>
                <div class="user-chip">
                    <strong><?= htmlspecialchars($user['full_name']); ?></strong>
                    <span><?= htmlspecialchars($user['email']); ?></span>
                </div>
            </header>

            <section id="dashboard-overview" class="card">
                <div class="section-head">
                    <div>
                        <h3>Today at a glance</h3>
                        <p class="muted">Core housekeeping metrics update live as tasks and maintenance reports change.</p>
                    </div>
                </div>
                <div class="stats-grid" id="statsGrid">
                    <article class="stat-card accent-gold">
                        <span>Rooms Dirty</span>
                        <strong><?= $stats['dirty_rooms']; ?></strong>
                    </article>
                    <article class="stat-card accent-blue">
                        <span>Pending Inspection</span>
                        <strong><?= $stats['pending_inspection']; ?></strong>
                    </article>
                    <article class="stat-card accent-red">
                        <span>Open Maintenance</span>
                        <strong><?= $stats['open_maintenance']; ?></strong>
                    </article>
                    <article class="stat-card accent-green">
                        <span>Tasks Completed Today</span>
                        <strong><?= $stats['tasks_completed_today']; ?></strong>
                    </article>
                </div>
            </section>

            <section id="room-board" class="card">
                <div class="section-head">
                    <div>
                        <h3>Full Room Status Board</h3>
                        <p class="muted">AJAX refresh runs automatically every 10 seconds.</p>
                    </div>
                    <button class="btn secondary" data-refresh-board>Refresh now</button>
                </div>
                <div class="room-board" id="roomBoard">
                    <?php foreach ($rooms as $room): ?>
                        <article class="room-tile">
                            <div class="room-title">
                                <strong>Room <?= htmlspecialchars($room['room_number']); ?></strong>
                                <span>Floor <?= htmlspecialchars((string) $room['floor']); ?></span>
                            </div>
                            <p><?= htmlspecialchars($room['type']); ?></p>
                            <div class="badge-row">
                                <span class="badge <?= status_badge_class($room['status']); ?>"><?= htmlspecialchars(task_status_label($room['status'])); ?></span>
                                <?php if ((int) $room['needs_inspection'] === 1): ?>
                                    <span class="badge warning">Needs inspection</span>
                                <?php endif; ?>
                                <?php if ((int) $room['has_open_task'] === 1): ?>
                                    <span class="badge info">Open task</span>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <section id="task-center" class="grid-two">
                <div class="card">
                    <div class="section-head">
                        <div>
                            <h3>Create Housekeeping Task</h3>
                            <p class="muted">Assign cleaning or inspection tasks with priority, notes, and scheduled date.</p>
                        </div>
                    </div>
                    <form id="taskForm" class="stack-md">
                        <div class="form-grid">
                            <label>
                                <span>Room</span>
                                <select name="room_id" required>
                                    <option value="">Select room</option>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?= (int) $room['id']; ?>">Room <?= htmlspecialchars($room['room_number']); ?> (<?= htmlspecialchars($room['status']); ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label>
                                <span>Task Type</span>
                                <select name="task_type" required>
                                    <option value="cleaning">Cleaning</option>
                                    <option value="inspection">Inspection</option>
                                </select>
                            </label>
                            <label>
                                <span>Priority</span>
                                <select name="priority" required>
                                    <option value="normal">Normal</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </label>
                            <label>
                                <span>Scheduled Date</span>
                                <input type="date" name="scheduled_date" value="<?= current_date(); ?>" required>
                            </label>
                        </div>
                        <label>
                            <span>Notes</span>
                            <textarea name="notes" rows="3" placeholder="Add cleaning instructions or inspection notes"></textarea>
                        </label>
                        <button type="submit" class="btn primary">Create task</button>
                    </form>
                </div>

                <div class="card">
                    <div class="section-head">
                        <div>
                            <h3>Today’s Tasks</h3>
                            <p class="muted">Filter by priority or status, then update progress and completion notes.</p>
                        </div>
                    </div>
                    <form id="taskFilterForm" class="inline-filters">
                        <select name="priority">
                            <option value="">All priorities</option>
                            <option value="normal">Normal</option>
                            <option value="urgent">Urgent</option>
                        </select>
                        <select name="status">
                            <option value="">All statuses</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                        <button type="submit" class="btn secondary">Apply</button>
                    </form>
                    <div id="taskList" class="stack-md">
                        <?php foreach ($tasks as $task): ?>
                            <article class="item-card">
                                <div class="item-head">
                                    <div>
                                        <strong>Room <?= htmlspecialchars($task['room_number']); ?> - <?= htmlspecialchars(ucfirst($task['task_type'])); ?></strong>
                                        <p class="muted">Assigned to <?= htmlspecialchars($task['assigned_to_name'] ?? 'Supervisor'); ?></p>
                                    </div>
                                    <div class="badge-row">
                                        <span class="badge <?= status_badge_class($task['priority']); ?>"><?= htmlspecialchars(ucfirst($task['priority'])); ?></span>
                                        <span class="badge <?= status_badge_class($task['status']); ?>"><?= htmlspecialchars(task_status_label($task['status'])); ?></span>
                                    </div>
                                </div>
                                <p><?= htmlspecialchars($task['notes']); ?></p>
                                <div class="action-row">
                                    <?php if ($task['status'] !== 'in_progress' && $task['status'] !== 'done'): ?>
                                        <button class="btn secondary js-task-status" data-id="<?= (int) $task['id']; ?>" data-status="in_progress">Mark In Progress</button>
                                    <?php endif; ?>
                                    <?php if ($task['status'] !== 'done'): ?>
                                        <button class="btn primary js-task-complete" data-id="<?= (int) $task['id']; ?>">Mark Done</button>
                                    <?php endif; ?>
                                    <?php if (room_can_be_ready($task)): ?>
                                        <button class="btn ghost js-room-ready" data-id="<?= (int) $task['id']; ?>">Mark Room Ready</button>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section id="maintenance-center" class="grid-two">
                <div class="card">
                    <div class="section-head">
                        <div>
                            <h3>Log Maintenance Issue</h3>
                            <p class="muted">Submitting a report automatically moves the room to Maintenance.</p>
                        </div>
                    </div>
                    <form id="maintenanceForm" class="stack-md">
                        <div class="form-grid">
                            <label>
                                <span>Room</span>
                                <select name="room_id" required>
                                    <option value="">Select room</option>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?= (int) $room['id']; ?>">Room <?= htmlspecialchars($room['room_number']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label>
                                <span>Severity</span>
                                <select name="severity" required>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </label>
                        </div>
                        <label>
                            <span>Description</span>
                            <textarea name="description" rows="4" required placeholder="Describe the issue clearly"></textarea>
                        </label>
                        <button type="submit" class="btn primary">Log maintenance issue</button>
                    </form>
                </div>

                <div class="card">
                    <div class="section-head">
                        <div>
                            <h3>Open Maintenance Reports</h3>
                            <p class="muted">Update issues to in progress or resolved. Resolved issues restore the room to available.</p>
                        </div>
                    </div>
                    <div id="maintenanceList" class="stack-md">
                        <?php foreach ($maintenance as $issue): ?>
                            <article class="item-card">
                                <div class="item-head">
                                    <div>
                                        <strong>Room <?= htmlspecialchars($issue['room_number']); ?></strong>
                                        <p class="muted"><?= htmlspecialchars($issue['reported_at']); ?></p>
                                    </div>
                                    <div class="badge-row">
                                        <span class="badge <?= status_badge_class($issue['severity']); ?>"><?= htmlspecialchars(ucfirst($issue['severity'])); ?></span>
                                        <span class="badge <?= status_badge_class($issue['status']); ?>"><?= htmlspecialchars(task_status_label($issue['status'])); ?></span>
                                    </div>
                                </div>
                                <p><?= htmlspecialchars($issue['description']); ?></p>
                                <div class="action-row">
                                    <?php if ($issue['status'] !== 'in_progress'): ?>
                                        <button class="btn secondary js-maint-status" data-id="<?= (int) $issue['id']; ?>" data-status="in_progress">Set In Progress</button>
                                    <?php endif; ?>
                                    <?php if ($issue['status'] !== 'resolved'): ?>
                                        <button class="btn primary js-maint-status" data-id="<?= (int) $issue['id']; ?>" data-status="resolved">Resolve Issue</button>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section id="planning-center" class="grid-two">
                <div class="card">
                    <div class="section-head">
                        <div>
                            <h3>Upcoming Check-Outs</h3>
                            <p class="muted">Rooms vacating soonest appear first for cleaning priority planning.</p>
                        </div>
                    </div>
                    <div id="checkoutList" class="stack-md">
                        <?php foreach ($upcoming['checkouts'] as $booking): ?>
                            <article class="item-card compact">
                                <strong>Room <?= htmlspecialchars($booking['room_number']); ?> - <?= htmlspecialchars($booking['guest_name']); ?></strong>
                                <p class="muted">Check-out: <?= htmlspecialchars($booking['checkout_at']); ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="section-head">
                        <div>
                            <h3>Upcoming Check-Ins</h3>
                            <p class="muted">Use this list to confirm each room is clean before arrival time.</p>
                        </div>
                    </div>
                    <div id="checkinList" class="stack-md">
                        <?php foreach ($upcoming['checkins'] as $booking): ?>
                            <article class="item-card compact">
                                <strong>Room <?= htmlspecialchars($booking['room_number']); ?> - <?= htmlspecialchars($booking['guest_name']); ?></strong>
                                <p class="muted">Check-in: <?= htmlspecialchars($booking['checkin_at']); ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section id="report-center" class="card">
                <div class="section-head">
                    <div>
                        <h3>Daily Housekeeping Report</h3>
                        <p class="muted">Summary of assigned tasks, completion progress, and rooms cleared for check-in today.</p>
                    </div>
                    <button class="btn secondary" data-refresh-report>Refresh report</button>
                </div>
                <div class="report-grid" id="reportSummary">
                    <article class="mini-stat">
                        <span>Tasks Assigned</span>
                        <strong><?= $report['assigned']; ?></strong>
                    </article>
                    <article class="mini-stat">
                        <span>Completed</span>
                        <strong><?= $report['completed']; ?></strong>
                    </article>
                    <article class="mini-stat">
                        <span>Pending</span>
                        <strong><?= $report['pending']; ?></strong>
                    </article>
                    <article class="mini-stat">
                        <span>Rooms Cleared</span>
                        <strong><?= $report['rooms_cleared']; ?></strong>
                    </article>
                </div>
            </section>

            <section id="history-center" class="card">
                <div class="section-head">
                    <div>
                        <h3>Historical Task Completion Log</h3>
                        <p class="muted">Track completed cleaning and inspection work by room, cleaner, completion time, and notes.</p>
                    </div>
                    <form id="historyFilterForm" class="inline-filters">
                        <select name="room_id">
                            <option value="">All rooms</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?= (int) $room['id']; ?>">Room <?= htmlspecialchars($room['room_number']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn secondary">Filter</button>
                    </form>
                </div>
                <div id="historyList" class="stack-md">
                    <?php foreach ($history as $item): ?>
                        <article class="item-card compact">
                            <strong>Room <?= htmlspecialchars($item['room_number']); ?> - <?= htmlspecialchars(ucfirst($item['task_type'])); ?></strong>
                            <p class="muted"><?= htmlspecialchars($item['completed_by_name'] ?? 'Supervisor'); ?> completed at <?= htmlspecialchars((string) $item['completed_at']); ?></p>
                            <p><?= htmlspecialchars($item['completion_notes'] ?? 'No notes provided.'); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <section id="profile-center" class="card">
                <div class="section-head">
                    <div>
                        <h3>Manage Profile</h3>
                        <p class="muted">Update your name, phone number, and account password from the same dashboard.</p>
                    </div>
                </div>
                <form id="profileForm" class="stack-md">
                    <div class="form-grid">
                        <label>
                            <span>Full Name</span>
                            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']); ?>" required>
                        </label>
                        <label>
                            <span>Phone</span>
                            <input type="text" name="phone" value="<?= htmlspecialchars((string) ($user['phone'] ?? '')); ?>">
                        </label>
                    </div>
                    <div class="form-grid">
                        <label>
                            <span>New Password</span>
                            <input type="password" name="password" placeholder="Leave blank to keep current password">
                        </label>
                        <label>
                            <span>Confirm Password</span>
                            <input type="password" name="confirm_password" placeholder="Repeat new password">
                        </label>
                    </div>
                    <button type="submit" class="btn primary">Save profile</button>
                </form>
            </section>
        </main>
    </div>

    <div class="toast-stack" id="toastStack"></div>
    <script>
        window.HOUSEKEEPING_APP = {
            userName: <?= json_encode($user['full_name']); ?>
        };
    </script>
    <script src="assets/js/app.js"></script>
</body>
</html>
