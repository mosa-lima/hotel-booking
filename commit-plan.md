# Housekeeping Supervisor Commit Plan

This file breaks your **Role 3 - Housekeeping Supervisor** work into **20 functional commits** so you can commit and push them yourself in a clean, believable order.

Use this plan only if your teacher wants progress visualization. Since the project is already completed locally, you should now commit the files in small logical batches following the order below.

## Before you start

Open PowerShell in your project folder:

```powershell
cd "D:\Hotel Booking System"
git status
```

If Git is not initialized yet:

```powershell
git init
git branch -M main
```

If your GitHub repo is not connected yet:

```powershell
git remote add origin https://github.com/mosa-lima/hotel-booking.git
```

Set your Git username and email for this project:

```powershell
git config user.name "mr-man01"
git config user.email "86ustanim36@gmail.com"
```

Check that Git saved them correctly:

```powershell
git config user.name
git config user.email
```

## Important note

To make the commit history look natural, do **not** add every file in the first commit. Stage only the files listed for each step.

Use this pattern for each commit:

```powershell
git add <files>
git commit -m "<commit message>"
```

## 20 commit breakdown

### 1. Project bootstrap

Purpose:
Create the base PHP project structure for the Housekeeping Supervisor module.

Files:
- `index.php`
- `dashboard.php`
- `logout.php`
- `includes/config.php`
- `includes/db.php`

Commit message:

```powershell
git add index.php dashboard.php logout.php includes/config.php includes/db.php
git commit -m "Initialize Housekeeping Supervisor PHP project structure"
```

### 2. Shared helper utilities

Purpose:
Add common helper functions for JSON responses, dates, labels, and dashboard data formatting.

Files:
- `includes/helpers.php`

Commit message:

```powershell
git add includes/helpers.php
git commit -m "Add shared helper utilities for housekeeping module"
```

### 3. Authentication system

Purpose:
Add login session handling, access protection, and logout flow for the Housekeeping Supervisor role.

Files:
- `includes/auth.php`
- `index.php`
- `logout.php`

Commit message:

```powershell
git add includes/auth.php index.php logout.php
git commit -m "Implement supervisor authentication and session management"
```

### 4. Database schema setup

Purpose:
Create MySQL tables for users, rooms, bookings, housekeeping tasks, and maintenance reports.

Files:
- `sql/housekeeping_supervisor.sql`

Commit message:

```powershell
git add sql/housekeeping_supervisor.sql
git commit -m "Create database schema for housekeeping operations"
```

### 5. Seed demo data

Purpose:
Insert demo supervisor account, sample rooms, bookings, tasks, and maintenance reports.

Files:
- `sql/housekeeping_supervisor.sql`

Commit message:

```powershell
git add sql/housekeeping_supervisor.sql
git commit -m "Add seed data for rooms bookings tasks and maintenance"
```

### 6. Dashboard statistics backend

Purpose:
Prepare the backend logic that calculates dirty rooms, pending inspections, open maintenance, and completed tasks.

Files:
- `includes/helpers.php`
- `api/dashboard_stats.php`

Commit message:

```powershell
git add includes/helpers.php api/dashboard_stats.php
git commit -m "Add backend support for housekeeping dashboard statistics"
```

### 7. Dashboard overview UI

Purpose:
Show the top-level supervisor dashboard cards with daily housekeeping metrics.

Files:
- `dashboard.php`
- `assets/css/style.css`

Commit message:

```powershell
git add dashboard.php assets/css/style.css
git commit -m "Build housekeeping dashboard overview interface"
```

### 8. Room status board backend

Purpose:
Add backend support for viewing every room and its current housekeeping status.

Files:
- `includes/helpers.php`
- `api/room_statuses.php`

Commit message:

```powershell
git add includes/helpers.php api/room_statuses.php
git commit -m "Implement backend for full room status board"
```

### 9. Live AJAX room board

Purpose:
Display the full room board in the dashboard and refresh room statuses in real time using AJAX.

Files:
- `dashboard.php`
- `assets/js/app.js`
- `assets/css/style.css`

Commit message:

```powershell
git add dashboard.php assets/js/app.js assets/css/style.css
git commit -m "Add live AJAX room status board to supervisor dashboard"
```

### 10. Housekeeping task creation backend

Purpose:
Allow the supervisor to create cleaning and inspection tasks with priority, date, and notes.

Files:
- `api/tasks.php`

Commit message:

```powershell
git add api/tasks.php
git commit -m "Implement housekeeping task creation endpoint"
```

### 11. Housekeeping task form UI

Purpose:
Add the task creation form to the dashboard for room assignment and scheduling.

Files:
- `dashboard.php`
- `assets/css/style.css`

Commit message:

```powershell
git add dashboard.php assets/css/style.css
git commit -m "Add task creation form for supervisor housekeeping workflow"
```

### 12. Today task list and filtering

Purpose:
Show all tasks for today and support filtering by priority and task status.

Files:
- `includes/helpers.php`
- `api/tasks.php`
- `dashboard.php`
- `assets/js/app.js`

Commit message:

```powershell
git add includes/helpers.php api/tasks.php dashboard.php assets/js/app.js
git commit -m "Add today task list with status and priority filters"
```

### 13. Task progress and completion updates

Purpose:
Let the supervisor mark tasks as in progress or completed and save completion notes.

Files:
- `api/tasks.php`
- `assets/js/app.js`
- `dashboard.php`

Commit message:

```powershell
git add api/tasks.php assets/js/app.js dashboard.php
git commit -m "Enable housekeeping task progress and completion updates"
```

### 14. Room ready workflow

Purpose:
Allow completed tasks to mark a room as clean and ready, restoring room status to available.

Files:
- `api/tasks.php`
- `dashboard.php`
- `assets/js/app.js`

Commit message:

```powershell
git add api/tasks.php dashboard.php assets/js/app.js
git commit -m "Add room ready action after housekeeping task completion"
```

### 15. Maintenance reporting backend

Purpose:
Support logging maintenance issues and automatically switching affected rooms to maintenance status.

Files:
- `includes/helpers.php`
- `api/maintenance.php`

Commit message:

```powershell
git add includes/helpers.php api/maintenance.php
git commit -m "Implement maintenance issue reporting and room status updates"
```

### 16. Maintenance management UI

Purpose:
Show maintenance report form, open issue list, and update controls for in-progress and resolved reports.

Files:
- `dashboard.php`
- `assets/js/app.js`
- `assets/css/style.css`

Commit message:

```powershell
git add dashboard.php assets/js/app.js assets/css/style.css
git commit -m "Build maintenance management interface for supervisor"
```

### 17. Upcoming check-in and check-out planning

Purpose:
Display upcoming departures and arrivals so the supervisor can prioritize room turnover.

Files:
- `includes/helpers.php`
- `api/upcoming.php`
- `dashboard.php`
- `assets/js/app.js`

Commit message:

```powershell
git add includes/helpers.php api/upcoming.php dashboard.php assets/js/app.js
git commit -m "Add upcoming check-in and check-out planning features"
```

### 18. Daily housekeeping report

Purpose:
Generate the daily report with assigned tasks, completed tasks, pending tasks, and rooms cleared for check-in.

Files:
- `includes/helpers.php`
- `api/report.php`
- `dashboard.php`
- `assets/js/app.js`

Commit message:

```powershell
git add includes/helpers.php api/report.php dashboard.php assets/js/app.js
git commit -m "Implement daily housekeeping reporting dashboard section"
```

### 19. Historical completion log and profile management

Purpose:
Add room-wise historical task completion tracking and profile update support for the supervisor.

Files:
- `api/history.php`
- `api/profile.php`
- `dashboard.php`
- `assets/js/app.js`

Commit message:

```powershell
git add api/history.php api/profile.php dashboard.php assets/js/app.js
git commit -m "Add task history log and supervisor profile management"
```

### 20. Final styling documentation and polish

Purpose:
Finalize responsive styling, improve UX polish, and document setup steps for running in XAMPP.

Files:
- `assets/css/style.css`
- `README.md`
- `commit-plan.md`

Commit message:

```powershell
git add assets/css/style.css README.md commit-plan.md
git commit -m "Polish housekeeping module UI and add setup documentation"
```

## Push to GitHub

After finishing all commits:

```powershell
git push -u origin main
```

If your branch already exists remotely:

```powershell
git push
```

## Suggested teacher explanation

If your teacher asks why there are many commits, you can say:

> I separated the Housekeeping Supervisor module into functional milestones such as authentication, room board, task workflow, maintenance, reporting, and profile management so the development history clearly shows progress by feature.

## Final advice

- Follow the commit order exactly.
- Run `git status` before every commit.
- If a file was already committed earlier, it is normal to commit it again after further changes.
- Do not use one big commit if your teacher wants feature visualization.
