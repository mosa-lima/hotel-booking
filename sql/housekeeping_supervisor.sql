CREATE DATABASE IF NOT EXISTS hotel_housekeeping;
USE hotel_housekeeping;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS loyalty_points;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS seasonal_pricing;
DROP TABLE IF EXISTS maintenance_reports;
DROP TABLE IF EXISTS housekeeping_tasks;
DROP TABLE IF EXISTS service_requests;
DROP TABLE IF EXISTS billing;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS room_types;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(30) DEFAULT NULL,
    nationality VARCHAR(80) DEFAULT NULL,
    id_number VARCHAR(80) DEFAULT NULL,
    role ENUM('guest', 'receptionist', 'housekeeping', 'admin') NOT NULL,
    profile_pic VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE room_types (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    description TEXT NOT NULL,
    price_per_night DECIMAL(10,2) NOT NULL,
    max_capacity INT UNSIGNED NOT NULL,
    thumbnail_path VARCHAR(255) DEFAULT NULL,
    amenities JSON DEFAULT NULL
);

CREATE TABLE rooms (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_type_id INT UNSIGNED NOT NULL,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    floor INT NOT NULL,
    status ENUM('available', 'occupied', 'dirty', 'in_progress', 'maintenance', 'blocked') NOT NULL DEFAULT 'available',
    notes TEXT DEFAULT NULL,
    needs_inspection TINYINT(1) NOT NULL DEFAULT 0,
    last_ready_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_rooms_type FOREIGN KEY (room_type_id) REFERENCES room_types(id) ON DELETE RESTRICT
);

CREATE TABLE bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    guest_id INT UNSIGNED NOT NULL,
    room_id INT UNSIGNED DEFAULT NULL,
    room_type_id INT UNSIGNED NOT NULL,
    checkin_date DATETIME NOT NULL,
    checkout_date DATETIME NOT NULL,
    num_guests INT UNSIGNED NOT NULL DEFAULT 1,
    total_price DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled') NOT NULL DEFAULT 'pending',
    source ENUM('online', 'walk_in') NOT NULL DEFAULT 'online',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_bookings_guest FOREIGN KEY (guest_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_bookings_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL,
    CONSTRAINT fk_bookings_type FOREIGN KEY (room_type_id) REFERENCES room_types(id) ON DELETE RESTRICT
);

CREATE TABLE billing (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id INT UNSIGNED NOT NULL,
    guest_id INT UNSIGNED NOT NULL,
    base_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    extras_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    payment_method VARCHAR(50) DEFAULT NULL,
    payment_status ENUM('pending', 'paid') NOT NULL DEFAULT 'pending',
    paid_at DATETIME DEFAULT NULL,
    receipt_path VARCHAR(255) DEFAULT NULL,
    CONSTRAINT fk_billing_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    CONSTRAINT fk_billing_guest FOREIGN KEY (guest_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE service_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id INT UNSIGNED NOT NULL,
    guest_id INT UNSIGNED NOT NULL,
    room_id INT UNSIGNED NOT NULL,
    service_type ENUM('extra_bed', 'toiletries', 'laundry', 'room_service', 'other') NOT NULL,
    description TEXT DEFAULT NULL,
    status ENUM('pending', 'in_progress', 'completed') NOT NULL DEFAULT 'pending',
    requested_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_service_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    CONSTRAINT fk_service_guest FOREIGN KEY (guest_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_service_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

CREATE TABLE housekeeping_tasks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_id INT UNSIGNED NOT NULL,
    assigned_to INT UNSIGNED DEFAULT NULL,
    task_type ENUM('cleaning', 'inspection', 'maintenance') NOT NULL,
    priority ENUM('normal', 'urgent') NOT NULL DEFAULT 'normal',
    status ENUM('pending', 'in_progress', 'done') NOT NULL DEFAULT 'pending',
    notes TEXT DEFAULT NULL,
    completion_notes TEXT DEFAULT NULL,
    scheduled_date DATE NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME DEFAULT NULL,
    completed_by INT UNSIGNED DEFAULT NULL,
    CONSTRAINT fk_tasks_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    CONSTRAINT fk_tasks_assigned FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_tasks_completed FOREIGN KEY (completed_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE maintenance_reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_id INT UNSIGNED NOT NULL,
    reported_by INT UNSIGNED DEFAULT NULL,
    description TEXT NOT NULL,
    severity ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'low',
    status ENUM('open', 'in_progress', 'resolved') NOT NULL DEFAULT 'open',
    reported_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    resolved_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_maintenance_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    CONSTRAINT fk_maintenance_reporter FOREIGN KEY (reported_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE seasonal_pricing (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_type_id INT UNSIGNED NOT NULL,
    label VARCHAR(120) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    price_per_night DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_pricing_type FOREIGN KEY (room_type_id) REFERENCES room_types(id) ON DELETE CASCADE
);

CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id INT UNSIGNED NOT NULL,
    guest_id INT UNSIGNED NOT NULL,
    overall_rating TINYINT UNSIGNED NOT NULL,
    cleanliness_rating TINYINT UNSIGNED NOT NULL,
    service_rating TINYINT UNSIGNED NOT NULL,
    review_text TEXT DEFAULT NULL,
    admin_reply TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_guest FOREIGN KEY (guest_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT chk_overall_rating CHECK (overall_rating BETWEEN 1 AND 5),
    CONSTRAINT chk_cleanliness_rating CHECK (cleanliness_rating BETWEEN 1 AND 5),
    CONSTRAINT chk_service_rating CHECK (service_rating BETWEEN 1 AND 5)
);

CREATE TABLE loyalty_points (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    guest_id INT UNSIGNED NOT NULL,
    booking_id INT UNSIGNED DEFAULT NULL,
    points_earned INT NOT NULL DEFAULT 0,
    points_used INT NOT NULL DEFAULT 0,
    balance INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_loyalty_guest FOREIGN KEY (guest_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_loyalty_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL
);

INSERT INTO users (name, email, password_hash, phone, nationality, id_number, role, is_active) VALUES
('Nusrat Jahan', 'supervisor@hotel.test', 'password', '01700000000', 'Bangladeshi', 'HK-SUP-001', 'housekeeping', 1),
('Ariana Rahman', 'ariana@example.test', 'guest-demo', '01711111111', 'Bangladeshi', 'NID-1001', 'guest', 1),
('Samiul Hasan', 'samiul@example.test', 'guest-demo', '01722222222', 'Bangladeshi', 'NID-1002', 'guest', 1),
('Nabila Chowdhury', 'nabila@example.test', 'guest-demo', '01733333333', 'Bangladeshi', 'NID-1003', 'guest', 1),
('Hasib Mahmud', 'hasib@example.test', 'guest-demo', '01744444444', 'Bangladeshi', 'NID-1004', 'guest', 1),
('Tanvir Ahmed', 'tanvir@example.test', 'guest-demo', '01755555555', 'Bangladeshi', 'NID-1005', 'guest', 1);

INSERT INTO room_types (name, description, price_per_night, max_capacity, thumbnail_path, amenities) VALUES
('Standard', 'Comfortable room for short business or family stays.', 4500.00, 2, 'assets/images/standard.jpg', JSON_ARRAY('WiFi', 'AC', 'TV')),
('Deluxe', 'Larger room with premium bedding and city view.', 6500.00, 3, 'assets/images/deluxe.jpg', JSON_ARRAY('WiFi', 'AC', 'TV', 'Mini Bar')),
('Suite', 'Spacious suite with lounge area and priority service.', 12000.00, 4, 'assets/images/suite.jpg', JSON_ARRAY('WiFi', 'AC', 'TV', 'Mini Bar', 'Bathtub')),
('Executive', 'Quiet executive room with workstation and upgraded amenities.', 8500.00, 2, 'assets/images/executive.jpg', JSON_ARRAY('WiFi', 'AC', 'Smart TV', 'Work Desk'));

INSERT INTO rooms (room_type_id, room_number, floor, status, notes, needs_inspection, last_ready_at) VALUES
((SELECT id FROM room_types WHERE name = 'Deluxe'), '101', 1, 'dirty', 'Departed guest requested early checkout cleaning.', 0, NULL),
((SELECT id FROM room_types WHERE name = 'Deluxe'), '102', 1, 'available', 'Ready for tomorrow arrival.', 0, NOW()),
((SELECT id FROM room_types WHERE name = 'Suite'), '103', 1, 'occupied', 'VIP stay in progress.', 0, NOW()),
((SELECT id FROM room_types WHERE name = 'Standard'), '104', 1, 'maintenance', 'AC repair pending.', 0, NULL),
((SELECT id FROM room_types WHERE name = 'Deluxe'), '201', 2, 'dirty', 'Standard turnover required.', 0, NULL),
((SELECT id FROM room_types WHERE name = 'Deluxe'), '202', 2, 'in_progress', 'Inspection started after morning checkout.', 1, NULL),
((SELECT id FROM room_types WHERE name = 'Suite'), '203', 2, 'blocked', 'Held until plumbing issue is reviewed.', 0, NULL),
((SELECT id FROM room_types WHERE name = 'Standard'), '204', 2, 'available', 'Ready room.', 0, NOW()),
((SELECT id FROM room_types WHERE name = 'Executive'), '301', 3, 'dirty', 'Deep clean requested after VIP departure.', 0, NULL),
((SELECT id FROM room_types WHERE name = 'Executive'), '302', 3, 'available', 'Ready room.', 0, NOW());

INSERT INTO bookings (guest_id, room_id, room_type_id, checkin_date, checkout_date, num_guests, total_price, status, source) VALUES
((SELECT id FROM users WHERE email = 'ariana@example.test'), (SELECT id FROM rooms WHERE room_number = '103'), (SELECT room_type_id FROM rooms WHERE room_number = '103'), CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY), ' 14:00:00'), CONCAT(CURDATE(), ' 11:00:00'), 2, 12000.00, 'checked_in', 'online'),
((SELECT id FROM users WHERE email = 'samiul@example.test'), (SELECT id FROM rooms WHERE room_number = '102'), (SELECT room_type_id FROM rooms WHERE room_number = '102'), CONCAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY), ' 16:00:00'), CONCAT(DATE_ADD(CURDATE(), INTERVAL 3 DAY), ' 10:00:00'), 2, 13000.00, 'confirmed', 'online'),
((SELECT id FROM users WHERE email = 'nabila@example.test'), (SELECT id FROM rooms WHERE room_number = '201'), (SELECT room_type_id FROM rooms WHERE room_number = '201'), CONCAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY), ' 15:00:00'), CONCAT(DATE_ADD(CURDATE(), INTERVAL 2 DAY), ' 11:00:00'), 2, 6500.00, 'confirmed', 'walk_in'),
((SELECT id FROM users WHERE email = 'hasib@example.test'), (SELECT id FROM rooms WHERE room_number = '202'), (SELECT room_type_id FROM rooms WHERE room_number = '202'), CONCAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY), ' 13:00:00'), CONCAT(CURDATE(), ' 09:30:00'), 1, 13000.00, 'checked_in', 'online'),
((SELECT id FROM users WHERE email = 'tanvir@example.test'), (SELECT id FROM rooms WHERE room_number = '301'), (SELECT room_type_id FROM rooms WHERE room_number = '301'), CONCAT(DATE_ADD(CURDATE(), INTERVAL 1 DAY), ' 17:00:00'), CONCAT(DATE_ADD(CURDATE(), INTERVAL 4 DAY), ' 11:30:00'), 1, 25500.00, 'confirmed', 'online');

INSERT INTO housekeeping_tasks (room_id, assigned_to, task_type, priority, status, notes, completion_notes, scheduled_date, created_at, completed_at, completed_by) VALUES
((SELECT id FROM rooms WHERE room_number = '101'), (SELECT id FROM users WHERE email = 'supervisor@hotel.test'), 'cleaning', 'urgent', 'pending', 'Guest is checking out early. Prepare room before noon.', NULL, CURDATE(), NOW(), NULL, NULL),
((SELECT id FROM rooms WHERE room_number = '201'), (SELECT id FROM users WHERE email = 'supervisor@hotel.test'), 'cleaning', 'normal', 'in_progress', 'Standard turnover cleaning.', NULL, CURDATE(), NOW(), NULL, NULL),
((SELECT id FROM rooms WHERE room_number = '202'), (SELECT id FROM users WHERE email = 'supervisor@hotel.test'), 'inspection', 'normal', 'done', 'Verify minibar and linen replacement.', 'Inspection completed. Ready for release after final check.', CURDATE(), NOW(), NOW(), (SELECT id FROM users WHERE email = 'supervisor@hotel.test')),
((SELECT id FROM rooms WHERE room_number = '301'), (SELECT id FROM users WHERE email = 'supervisor@hotel.test'), 'cleaning', 'urgent', 'done', 'Deep clean after VIP departure.', 'Room cleaned fully, amenities restocked.', DATE_SUB(CURDATE(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), (SELECT id FROM users WHERE email = 'supervisor@hotel.test'));

INSERT INTO maintenance_reports (room_id, reported_by, description, severity, status, reported_at, resolved_at) VALUES
((SELECT id FROM rooms WHERE room_number = '104'), (SELECT id FROM users WHERE email = 'supervisor@hotel.test'), 'Air conditioning is not cooling properly.', 'high', 'open', NOW(), NULL),
((SELECT id FROM rooms WHERE room_number = '203'), (SELECT id FROM users WHERE email = 'supervisor@hotel.test'), 'Bathroom tap leaks intermittently.', 'medium', 'in_progress', DATE_SUB(NOW(), INTERVAL 4 HOUR), NULL);
