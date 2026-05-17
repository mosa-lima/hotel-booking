CREATE DATABASE IF NOT EXISTS hotel_housekeeping;
USE hotel_housekeeping;

DROP TABLE IF EXISTS maintenance_reports;
DROP TABLE IF EXISTS housekeeping_tasks;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    phone VARCHAR(30) DEFAULT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('housekeeping_supervisor') NOT NULL DEFAULT 'housekeeping_supervisor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rooms (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    floor INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    status ENUM('available', 'dirty', 'in_progress', 'maintenance', 'occupied', 'blocked') NOT NULL DEFAULT 'available',
    needs_inspection TINYINT(1) NOT NULL DEFAULT 0,
    last_ready_at DATETIME DEFAULT NULL
);

CREATE TABLE bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_id INT UNSIGNED NOT NULL,
    guest_name VARCHAR(120) NOT NULL,
    checkin_at DATETIME NOT NULL,
    checkout_at DATETIME NOT NULL,
    status ENUM('confirmed', 'checked_in', 'checked_out', 'cancelled') NOT NULL DEFAULT 'confirmed',
    CONSTRAINT fk_bookings_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

CREATE TABLE housekeeping_tasks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_id INT UNSIGNED NOT NULL,
    assigned_to INT UNSIGNED DEFAULT NULL,
    created_by INT UNSIGNED DEFAULT NULL,
    completed_by INT UNSIGNED DEFAULT NULL,
    task_type ENUM('cleaning', 'inspection') NOT NULL,
    priority ENUM('normal', 'urgent') NOT NULL DEFAULT 'normal',
    status ENUM('pending', 'in_progress', 'completed') NOT NULL DEFAULT 'pending',
    scheduled_date DATE NOT NULL,
    notes TEXT DEFAULT NULL,
    completion_notes TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL,
    completed_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_tasks_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    CONSTRAINT fk_tasks_assigned FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_tasks_created FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_tasks_completed FOREIGN KEY (completed_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE maintenance_reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_id INT UNSIGNED NOT NULL,
    description TEXT NOT NULL,
    severity ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'low',
    status ENUM('open', 'in_progress', 'resolved') NOT NULL DEFAULT 'open',
    reported_at DATETIME NOT NULL,
    CONSTRAINT fk_maintenance_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

INSERT INTO users (full_name, email, phone, password_hash, role) VALUES
('Nusrat Jahan', 'supervisor@hotel.test', '01700000000', 'password', 'housekeeping_supervisor');

INSERT INTO rooms (room_number, floor, type, status, needs_inspection, last_ready_at) VALUES
('101', 1, 'Deluxe King', 'dirty', 0, NULL),
('102', 1, 'Deluxe Twin', 'available', 0, NOW()),
('103', 1, 'Suite', 'occupied', 0, NOW()),
('104', 1, 'Standard Queen', 'maintenance', 0, NULL),
('201', 2, 'Deluxe King', 'dirty', 0, NULL),
('202', 2, 'Deluxe Twin', 'in_progress', 1, NULL),
('203', 2, 'Suite', 'blocked', 0, NULL),
('204', 2, 'Standard Queen', 'available', 0, NOW()),
('301', 3, 'Executive King', 'dirty', 0, NULL),
('302', 3, 'Executive Twin', 'available', 0, NOW());

INSERT INTO bookings (room_id, guest_name, checkin_at, checkout_at, status) VALUES
((SELECT id FROM rooms WHERE room_number = '103'), 'Ariana Rahman', CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY), ' 14:00:00'), CONCAT(CURDATE(), ' 11:00:00'), 'checked_in'),
((SELECT id FROM rooms WHERE room_number = '102'), 'Samiul Hasan', CONCAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY), ' 16:00:00'), CONCAT(DATE_ADD(CURDATE(), INTERVAL 3 DAY), ' 10:00:00'), 'confirmed'),
((SELECT id FROM rooms WHERE room_number = '201'), 'Nabila Chowdhury', CONCAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY), ' 15:00:00'), CONCAT(DATE_ADD(CURDATE(), INTERVAL 2 DAY), ' 11:00:00'), 'confirmed'),
((SELECT id FROM rooms WHERE room_number = '202'), 'Hasib Mahmud', CONCAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY), ' 13:00:00'), CONCAT(CURDATE(), ' 09:30:00'), 'checked_in'),
((SELECT id FROM rooms WHERE room_number = '301'), 'Tanvir Ahmed', CONCAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY), ' 17:00:00'), CONCAT(DATE_ADD(CURDATE(), INTERVAL 4 DAY), ' 11:30:00'), 'confirmed');

INSERT INTO housekeeping_tasks (room_id, assigned_to, created_by, completed_by, task_type, priority, status, scheduled_date, notes, completion_notes, created_at, completed_at) VALUES
((SELECT id FROM rooms WHERE room_number = '101'), 1, 1, NULL, 'cleaning', 'urgent', 'pending', CURDATE(), 'Guest is checking out early. Prepare room before noon.', NULL, NOW(), NULL),
((SELECT id FROM rooms WHERE room_number = '201'), 1, 1, NULL, 'cleaning', 'normal', 'in_progress', CURDATE(), 'Standard turnover cleaning.', NULL, NOW(), NULL),
((SELECT id FROM rooms WHERE room_number = '202'), 1, 1, 1, 'inspection', 'normal', 'completed', CURDATE(), 'Verify minibar and linen replacement.', 'Inspection completed. Ready for release after final check.', NOW(), NOW()),
((SELECT id FROM rooms WHERE room_number = '301'), 1, 1, 1, 'cleaning', 'urgent', 'completed', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Deep clean after VIP departure.', 'Room cleaned fully, amenities restocked.', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY));

INSERT INTO maintenance_reports (room_id, description, severity, status, reported_at) VALUES
((SELECT id FROM rooms WHERE room_number = '104'), 'Air conditioning is not cooling properly.', 'high', 'open', NOW()),
((SELECT id FROM rooms WHERE room_number = '203'), 'Bathroom tap leaks intermittently.', 'medium', 'in_progress', DATE_SUB(NOW(), INTERVAL 4 HOUR));
