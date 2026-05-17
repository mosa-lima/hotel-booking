# Hotel Housekeeping Supervisor Module

Standalone PHP + MySQL implementation of **Role 3 - Housekeeping Supervisor** for a hotel room booking system project.

## Features included

- Login and profile management
- Housekeeping dashboard with dirty rooms, pending inspections, open maintenance, and tasks completed today
- Full live room status board with AJAX refresh
- Create, filter, and update housekeeping tasks
- Mark rooms clean/ready after completed tasks
- Log and resolve maintenance issues with automatic room status updates
- Upcoming check-outs and check-ins for cleaning planning
- Daily housekeeping report
- Historical task completion log by room

## XAMPP setup

1. Copy this folder into `htdocs`, for example:
   `C:\xampp\htdocs\hotel-housekeeping`
2. Start **Apache** and **MySQL** from XAMPP.
3. Open **phpMyAdmin** and import [sql/housekeeping_supervisor.sql](/d:/Hotel%20Booking%20System/sql/housekeeping_supervisor.sql).
4. Update database credentials in [includes/config.php](/d:/Hotel%20Booking%20System/includes/config.php) if needed.
5. Visit `http://localhost/hotel-housekeeping/`

## Demo login

- Email: `supervisor@hotel.test`
- Password: `password`
