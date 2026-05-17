<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

function is_logged_in(): bool
{
    return isset($_SESSION['user']);
}

function require_login(bool $json = false): void
{
    if (is_logged_in()) {
        return;
    }

    if ($json) {
        json_response(['success' => false, 'message' => 'Authentication required.'], 401);
    }

    header('Location: index.php');
    exit;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function attempt_login(string $email, string $password): bool
{
    $stmt = db()->prepare(
        "SELECT id, full_name, email, password_hash, role, phone
         FROM users
         WHERE email = :email
           AND role = 'housekeeping_supervisor'
         LIMIT 1"
    );
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        return false;
    }

    $passwordMatches = password_verify($password, $user['password_hash']);

    if (!$passwordMatches && hash_equals($user['password_hash'], $password)) {
        $passwordMatches = true;
        $rehashStmt = db()->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $rehashStmt->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
    }

    if (!$passwordMatches) {
        return false;
    }

    unset($user['password_hash']);
    $_SESSION['user'] = $user;

    return true;
}

function logout_user(): void
{
    $_SESSION = [];
    session_destroy();
}
