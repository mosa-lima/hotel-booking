const state = {
  taskFilters: { priority: "", status: "" },
  historyRoomId: "",
};

const toastStack = document.getElementById("toastStack");

const renderBadge = (value, extra = "") => {
  const normalized = String(value || "").toLowerCase();
  const classes = {
    available: "success",
    completed: "success",
    resolved: "success",
    dirty: "warning",
    pending: "warning",
    urgent: "danger",
    high: "danger",
    maintenance: "danger",
    in_progress: "info",
    medium: "info",
    occupied: "dark",
    blocked: "muted",
    low: "muted",
    normal: "default",
    open: "warning",
  };

  const label = normalized.replaceAll("_", " ").replace(/\b\w/g, (char) => char.toUpperCase());
  return `<span class="badge ${classes[normalized] || "default"} ${extra}">${label}</span>`;
};

const showToast = (message, type = "success") => {
  const toast = document.createElement("div");
  toast.className = `toast ${type}`;
  toast.textContent = message;
  toastStack.appendChild(toast);
  setTimeout(() => toast.remove(), 3500);
};

const postForm = async (url, formData) => {
  const response = await fetch(url, {
    method: "POST",
    body: formData,
  });
  return response.json();
};

const refreshStats = async () => {
  const response = await fetch("api/dashboard_stats.php");
  const data = await response.json();
  if (!data.success) return;

  const grid = document.getElementById("statsGrid");
  grid.innerHTML = `
    <article class="stat-card accent-gold"><span>Rooms Dirty</span><strong>${data.stats.dirty_rooms}</strong></article>
    <article class="stat-card accent-blue"><span>Pending Inspection</span><strong>${data.stats.pending_inspection}</strong></article>
    <article class="stat-card accent-red"><span>Open Maintenance</span><strong>${data.stats.open_maintenance}</strong></article>
    <article class="stat-card accent-green"><span>Tasks Completed Today</span><strong>${data.stats.tasks_completed_today}</strong></article>
  `;
};

const refreshRoomBoard = async () => {
  const response = await fetch("api/room_statuses.php");
  const data = await response.json();
  if (!data.success) return;

  const board = document.getElementById("roomBoard");
  board.innerHTML = data.rooms
    .map((room) => `
      <article class="room-tile">
        <div class="room-title">
          <strong>Room ${room.room_number}</strong>
          <span>Floor ${room.floor}</span>
        </div>
        <p>${room.type}</p>
        <div class="badge-row">
          ${renderBadge(room.status)}
          ${Number(room.needs_inspection) === 1 ? '<span class="badge warning">Needs inspection</span>' : ""}
          ${Number(room.has_open_task) === 1 ? '<span class="badge info">Open task</span>' : ""}
        </div>
      </article>
    `)
    .join("");
};

const refreshTasks = async () => {
  const params = new URLSearchParams(state.taskFilters);
  const response = await fetch(`api/tasks.php?${params.toString()}`);
  const data = await response.json();
  if (!data.success) return;

  const list = document.getElementById("taskList");
  list.innerHTML = data.tasks
    .map((task) => `
      <article class="item-card">
        <div class="item-head">
          <div>
            <strong>Room ${task.room_number} - ${task.task_type.charAt(0).toUpperCase() + task.task_type.slice(1)}</strong>
            <p class="muted">Assigned to ${task.assigned_to_name || "Supervisor"} | Scheduled ${task.scheduled_date}</p>
          </div>
          <div class="badge-row">
            ${renderBadge(task.priority)}
            ${renderBadge(task.status)}
          </div>
        </div>
        <p>${task.notes || "No notes provided."}</p>
        ${task.completion_notes ? `<p class="muted">Completion notes: ${task.completion_notes}</p>` : ""}
        <div class="action-row">
          ${task.status !== "in_progress" && task.status !== "completed" ? `<button class="btn secondary js-task-status" data-id="${task.id}" data-status="in_progress">Mark In Progress</button>` : ""}
          ${task.status !== "completed" ? `<button class="btn primary js-task-complete" data-id="${task.id}">Mark Done</button>` : ""}
          ${task.status === "completed" ? `<button class="btn ghost js-room-ready" data-id="${task.id}">Mark Room Ready</button>` : ""}
        </div>
      </article>
    `)
    .join("");
};

const refreshMaintenance = async () => {
  const response = await fetch("api/maintenance.php");
  const data = await response.json();
  if (!data.success) return;

  const list = document.getElementById("maintenanceList");
  list.innerHTML = data.issues
    .map((issue) => `
      <article class="item-card">
        <div class="item-head">
          <div>
            <strong>Room ${issue.room_number}</strong>
            <p class="muted">${issue.reported_at}</p>
          </div>
          <div class="badge-row">
            ${renderBadge(issue.severity)}
            ${renderBadge(issue.status)}
          </div>
        </div>
        <p>${issue.description}</p>
        <div class="action-row">
          ${issue.status !== "in_progress" ? `<button class="btn secondary js-maint-status" data-id="${issue.id}" data-status="in_progress">Set In Progress</button>` : ""}
          ${issue.status !== "resolved" ? `<button class="btn primary js-maint-status" data-id="${issue.id}" data-status="resolved">Resolve Issue</button>` : ""}
        </div>
      </article>
    `)
    .join("");
};

const refreshUpcoming = async () => {
  const response = await fetch("api/upcoming.php");
  const data = await response.json();
  if (!data.success) return;

  document.getElementById("checkoutList").innerHTML = data.data.checkouts
    .map((booking) => `
      <article class="item-card compact">
        <strong>Room ${booking.room_number} - ${booking.guest_name}</strong>
        <p class="muted">Check-out: ${booking.checkout_at}</p>
      </article>
    `)
    .join("");

  document.getElementById("checkinList").innerHTML = data.data.checkins
    .map((booking) => `
      <article class="item-card compact">
        <strong>Room ${booking.room_number} - ${booking.guest_name}</strong>
        <p class="muted">Check-in: ${booking.checkin_at}</p>
      </article>
    `)
    .join("");
};

const refreshReport = async () => {
  const response = await fetch("api/report.php");
  const data = await response.json();
  if (!data.success) return;

  document.getElementById("reportSummary").innerHTML = `
    <article class="mini-stat"><span>Tasks Assigned</span><strong>${data.report.assigned}</strong></article>
    <article class="mini-stat"><span>Completed</span><strong>${data.report.completed}</strong></article>
    <article class="mini-stat"><span>Pending</span><strong>${data.report.pending}</strong></article>
    <article class="mini-stat"><span>Rooms Cleared</span><strong>${data.report.rooms_cleared}</strong></article>
  `;
};

const refreshHistory = async () => {
  const params = new URLSearchParams();
  if (state.historyRoomId) {
    params.set("room_id", state.historyRoomId);
  }

  const response = await fetch(`api/history.php?${params.toString()}`);
  const data = await response.json();
  if (!data.success) return;

  document.getElementById("historyList").innerHTML = data.history
    .map((item) => `
      <article class="item-card compact">
        <strong>Room ${item.room_number} - ${item.task_type.charAt(0).toUpperCase() + item.task_type.slice(1)}</strong>
        <p class="muted">${item.completed_by_name || "Supervisor"} completed at ${item.completed_at}</p>
        <p>${item.completion_notes || "No notes provided."}</p>
      </article>
    `)
    .join("");
};

const refreshAll = async () => {
  await Promise.all([
    refreshStats(),
    refreshRoomBoard(),
    refreshTasks(),
    refreshMaintenance(),
    refreshUpcoming(),
    refreshReport(),
    refreshHistory(),
  ]);
};

document.getElementById("taskForm")?.addEventListener("submit", async (event) => {
  event.preventDefault();
  const formData = new FormData(event.currentTarget);
  formData.set("action", "create");
  const data = await postForm("api/tasks.php", formData);
  showToast(data.message, data.success ? "success" : "error");
  if (!data.success) return;
  event.currentTarget.reset();
  event.currentTarget.querySelector('input[name="scheduled_date"]').valueAsDate = new Date();
  await refreshAll();
});

document.getElementById("taskFilterForm")?.addEventListener("submit", async (event) => {
  event.preventDefault();
  const formData = new FormData(event.currentTarget);
  state.taskFilters.priority = String(formData.get("priority") || "");
  state.taskFilters.status = String(formData.get("status") || "");
  await refreshTasks();
});

document.getElementById("maintenanceForm")?.addEventListener("submit", async (event) => {
  event.preventDefault();
  const formData = new FormData(event.currentTarget);
  formData.set("action", "create");
  const data = await postForm("api/maintenance.php", formData);
  showToast(data.message, data.success ? "success" : "error");
  if (!data.success) return;
  event.currentTarget.reset();
  await refreshAll();
});

document.getElementById("historyFilterForm")?.addEventListener("submit", async (event) => {
  event.preventDefault();
  const formData = new FormData(event.currentTarget);
  state.historyRoomId = String(formData.get("room_id") || "");
  await refreshHistory();
});

document.getElementById("profileForm")?.addEventListener("submit", async (event) => {
  event.preventDefault();
  const data = await postForm("api/profile.php", new FormData(event.currentTarget));
  showToast(data.message, data.success ? "success" : "error");
  if (data.success) {
    event.currentTarget.querySelector('input[name="password"]').value = "";
    event.currentTarget.querySelector('input[name="confirm_password"]').value = "";
  }
});

document.body.addEventListener("click", async (event) => {
  const target = event.target;

  if (target.matches("[data-refresh-board]")) {
    await refreshRoomBoard();
    await refreshStats();
    return;
  }

  if (target.matches("[data-refresh-report]")) {
    await refreshReport();
    return;
  }

  if (target.matches(".js-task-status")) {
    const formData = new FormData();
    formData.set("action", "update_status");
    formData.set("task_id", target.dataset.id);
    formData.set("status", target.dataset.status);
    const data = await postForm("api/tasks.php", formData);
    showToast(data.message, data.success ? "success" : "error");
    if (data.success) await refreshAll();
    return;
  }

  if (target.matches(".js-task-complete")) {
    const notes = window.prompt("Add completion notes for this task:", "");
    if (notes === null) return;
    const formData = new FormData();
    formData.set("action", "update_status");
    formData.set("task_id", target.dataset.id);
    formData.set("status", "completed");
    formData.set("completion_notes", notes);
    const data = await postForm("api/tasks.php", formData);
    showToast(data.message, data.success ? "success" : "error");
    if (data.success) await refreshAll();
    return;
  }

  if (target.matches(".js-room-ready")) {
    const formData = new FormData();
    formData.set("action", "mark_ready");
    formData.set("task_id", target.dataset.id);
    const data = await postForm("api/tasks.php", formData);
    showToast(data.message, data.success ? "success" : "error");
    if (data.success) await refreshAll();
    return;
  }

  if (target.matches(".js-maint-status")) {
    const formData = new FormData();
    formData.set("action", "update_status");
    formData.set("issue_id", target.dataset.id);
    formData.set("status", target.dataset.status);
    const data = await postForm("api/maintenance.php", formData);
    showToast(data.message, data.success ? "success" : "error");
    if (data.success) await refreshAll();
  }
});

refreshAll();
setInterval(async () => {
  await refreshStats();
  await refreshRoomBoard();
  await refreshMaintenance();
  await refreshUpcoming();
  await refreshReport();
}, 10000);
