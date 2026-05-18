# Housekeeping Supervisor Commit Plan

This plan is updated for your current situation:

- You already pushed the old **20 commits**.
- Do not rewrite or redo commits 1-20.
- Add only the new commits **21-29** for the updated Word-file database and changed Role 3 code.
- After creating commits 21-29 locally, you can push them yourself.

## Project Folder

Open PowerShell in this project:

```powershell
cd "E:\Supervisor\hotel-booking"
git status
```

## What Changed Now

The new update makes Role 3 match the Word file better:

- Database now uses the shared hotel schema.
- `users` table now has `name`, `role`, `is_active`, `nationality`, `id_number`, and profile fields.
- `room_types` table was added.
- `rooms` now links to `room_types`.
- `bookings` now links to guest users and room types.
- Missing shared tables were added: `billing`, `service_requests`, `seasonal_pricing`, `reviews`, `loyalty_points`.
- Housekeeping tasks now use Word-file status `done`.
- Maintenance reports now save `reported_by` and `resolved_at`.
- PHP code was updated to work with the new database names.
- XAMPP database import and localhost testing were completed.

## New Commit Breakdown

Use these commits after your existing 20 commits.

### 21. Upgrade database to shared hotel schema

Purpose:
Replace the old housekeeping-only database with the shared hotel database required by the Word file.

Files:
- `sql/housekeeping_supervisor.sql`

Commit:

```powershell
git add sql/housekeeping_supervisor.sql
git commit -m "Upgrade housekeeping database to shared hotel schema"
```

### 22. Add shared hotel tables and relationships

Purpose:
Add the missing shared tables such as room types, billing, service requests, seasonal pricing, reviews, and loyalty points.

Files:
- `sql/housekeeping_supervisor.sql`

Commit:

```powershell
git add sql/housekeeping_supervisor.sql
git commit -m "Add shared hotel tables for assignment schema"
```

### 23. Add updated role three seed data

Purpose:
Add demo data for the housekeeping supervisor, guests, room types, rooms, bookings, housekeeping tasks, and maintenance reports.

Files:
- `sql/housekeeping_supervisor.sql`

Commit:

```powershell
git add sql/housekeeping_supervisor.sql
git commit -m "Add updated seed data for housekeeping supervisor role"
```

### 24. Align login and profile with shared users table

Purpose:
Update authentication and profile updates to use `users.name`, `role = 'housekeeping'`, and `is_active`.

Files:
- `includes/auth.php`
- `api/profile.php`

Commit:

```powershell
git add includes/auth.php api/profile.php
git commit -m "Align supervisor account code with shared users table"
```

### 25. Update dashboard queries for room types and bookings

Purpose:
Update helper queries to join `rooms` with `room_types`, read guest names from `users`, and use `checkin_date` / `checkout_date`.

Files:
- `includes/helpers.php`

Commit:

```powershell
git add includes/helpers.php
git commit -m "Update housekeeping dashboard queries for shared schema"
```

### 26. Convert task workflow to done status

Purpose:
Update task filtering, task completion, daily reports, and history to use the Word-file task status `done`.

Files:
- `includes/helpers.php`
- `api/tasks.php`
- `dashboard.php`
- `assets/js/app.js`

Commit:

```powershell
git add includes/helpers.php api/tasks.php dashboard.php assets/js/app.js
git commit -m "Convert housekeeping task workflow to done status"
```

### 27. Improve maintenance reporting fields

Purpose:
Store who reported maintenance issues and save the resolution time when an issue is resolved.

Files:
- `api/maintenance.php`
- `sql/housekeeping_supervisor.sql`

Commit:

```powershell
git add api/maintenance.php sql/housekeeping_supervisor.sql
git commit -m "Track maintenance reporter and resolution time"
```

### 28. Verify XAMPP database and localhost setup

Purpose:
Record the XAMPP import and local run instructions after testing the app at `http://localhost/hotel-booking/`.

Files:
- `commit-plan.md`

Commit:

```powershell
git add commit-plan.md
git commit -m "Document XAMPP verification for housekeeping database"
```

### 29. Final shared schema cleanup plan

Purpose:
Finalize the commit plan so the new commits clearly continue after the already pushed 20 commits.

Files:
- `commit-plan.md`

Commit:

```powershell
git add commit-plan.md
git commit -m "Update commit plan for commits twenty one through twenty nine"
```

## Important Note About Commits 21-23

Commits 21, 22, and 23 all touch the same SQL file. If your current working copy already contains the final SQL file, Git may not let you split it perfectly unless you stage parts interactively.

Simple option:

```powershell
git add sql/housekeeping_supervisor.sql
git commit -m "Upgrade housekeeping database to shared hotel schema"
```

Then continue from commit 24.

Detailed option:

```powershell
git add -p sql/housekeeping_supervisor.sql
```

Use `git add -p` only if you are comfortable staging parts of the SQL file manually.

## Recommended Easy Commit Set

If you want a clean but easier path, use these commits:

```powershell
git add sql/housekeeping_supervisor.sql
git commit -m "Upgrade housekeeping database to shared hotel schema"

git add includes/auth.php api/profile.php
git commit -m "Align supervisor account code with shared users table"

git add includes/helpers.php
git commit -m "Update housekeeping dashboard queries for shared schema"

git add api/tasks.php dashboard.php assets/js/app.js
git commit -m "Convert housekeeping task workflow to done status"

git add api/maintenance.php
git commit -m "Track maintenance reporter and resolution time"

git add commit-plan.md
git commit -m "Update commit plan for new database changes"
```

This gives you new commits after the old 20 without forcing awkward partial staging.

## XAMPP Run Instructions

Start Apache and MySQL in XAMPP.

Import the database:

```powershell
C:\xampp\mysql\bin\mysql.exe -u root < sql\housekeeping_supervisor.sql
```

Open:

```text
http://localhost/hotel-booking/
```

Login:

```text
Email: supervisor@hotel.test
Password: password
```

## Push After New Commits

After you make the new commits:

```powershell
git push
```

If Git asks for upstream:

```powershell
git push -u origin main
```

## Teacher Explanation

If your teacher asks about the extra commits, say:

> After completing the original Housekeeping Supervisor module, I updated the database and code to match the shared schema from the Word file. Commits 21-29 show the database upgrade, query updates, task status changes, maintenance improvements, and XAMPP verification.
