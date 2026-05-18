<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = post_value('email');
    $password = post_value('password');

    if (attempt_login($email, $password)) {
        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME; ?> - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-card">
        <div>
            <p class="eyebrow">WBTech Project</p>
            <h1>Housekeeping Supervisor</h1>
            <p class="muted">Log in to manage room readiness, tasks, inspections, and maintenance updates.</p>
        </div>

        <?php if ($error !== ''): ?>
            <div class="alert error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" class="stack-md">
            <label>
                <span>Email</span>
                <input type="email" name="email" value="supervisor@hotel.test" required>
            </label>
            <label>
                <span>Password</span>
                <input type="password" name="password" value="password" required>
            </label>
            <button type="submit" class="btn primary">Log In</button>
        </form>

        <div class="demo-box">
            <strong>Demo account</strong>
            <p>Email: `supervisor@hotel.test`</p>
            <p>Password: `password`</p>
        </div>
    </div>
</body>
</html>
