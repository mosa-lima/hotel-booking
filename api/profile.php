<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

require_login(true);

$user = current_user();
$fullName = post_value('full_name');
$phone = post_value('phone');
$password = post_value('password');
$confirmPassword = post_value('confirm_password');

if ($fullName === '') {
    json_response(['success' => false, 'message' => 'Full name is required.'], 422);
}

if ($password !== '' && $password !== $confirmPassword) {
    json_response(['success' => false, 'message' => 'Password confirmation does not match.'], 422);
}

if ($password !== '' && strlen($password) < 6) {
    json_response(['success' => false, 'message' => 'Password must be at least 6 characters long.'], 422);
}

if ($password !== '') {
    $stmt = db()->prepare("UPDATE users SET full_name = ?, phone = ?, password_hash = ? WHERE id = ?");
    $stmt->execute([$fullName, $phone, password_hash($password, PASSWORD_DEFAULT), $user['id']]);
} else {
    $stmt = db()->prepare("UPDATE users SET full_name = ?, phone = ? WHERE id = ?");
    $stmt->execute([$fullName, $phone, $user['id']]);
}

$_SESSION['user']['full_name'] = $fullName;
$_SESSION['user']['phone'] = $phone;

json_response(['success' => true, 'message' => 'Profile updated successfully.']);
