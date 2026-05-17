<?php

require_once 'config/database.php';

$message = "";
$registration_success = false; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($name) && !empty($email) && !empty($password)) {
        $dbObj = new Database();
        $db = $dbObj->getConnection();

        
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $message = "<div style='color:#f87171; font-weight:bold; font-size:15px; text-align:center; margin-bottom:5px;'>Error: This email is already registered!</div>";
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $role = 'admin';
            $is_active = 1;

            $insert = $db->prepare("INSERT INTO users (name, email, password_hash, role, is_active) VALUES (?, ?, ?, ?, ?)");
            $insert->bind_param("ssssi", $name, $email, $hashed_password, $role, $is_active);
            
            if ($insert->execute()) {
                $registration_success = true; 
                $message = "<div style='color:#34d399; font-weight:700; font-size:18px; text-align:center; line-height:1.5;'>Success!<br><span style='font-weight:400; font-size:15px; color:#cbd5e1;'>Admin registered for</span><br><span style='color:#fbbf24; font-size:16px; word-break:break-all;'>$email</span></div>";
            } else {
                $message = "<div style='color:#f87171; font-weight:bold; font-size:15px; text-align:center; margin-bottom:5px;'>Database Error: " . $db->error . "</div>";
            }
        }
    } else {
        $message = "<div style='color:#fbbf24; font-weight:bold; font-size:15px; text-align:center; margin-bottom:5px;'>Please fill out all fields.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration Utility</title>
</head>
<body style="font-family: sans-serif; background: #0f172a; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;">
    <div style="background: #1e293b; padding: 40px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); border-radius: 16px; width: 380px; min-height: 280px; box-sizing: border-box; display: flex; flex-direction: column; justify-content: center;">
        
        <?php if ($registration_success): ?>
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 24px; animation: fadeIn 0.4s ease-out;">
                <div style="background: rgba(52, 211, 153, 0.1); padding: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <?php echo $message; ?>
                <a href="index.php" style="display: block; width: 100%; text-align: center; padding: 12px; background: #f59e0b; color: #000; font-weight: 700; border-radius: 8px; text-decoration: none; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.2); transition: transform 0.2s;">Go to Login Portal</a>
            </div>
        <?php else: ?>
            <h2 style="margin-top: 0; margin-bottom: 8px; font-size: 26px; font-weight: 800; tracking-tight: -0.025em;">Create Admin Account</h2>
            <p style="color: #94a3b8; font-size: 13px; margin-bottom: 24px; font-weight: 500;">Register any dynamic email/password credentials to seed the user table.</p>
            
            <?php if (!empty($message)) echo $message; ?>
            
            <form method="POST" style="display: flex; flex-direction: column; gap: 18px;">
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Full Name</label>
                    <input type="text" name="name" required style="padding: 10px 14px; border-radius: 8px; border: 1px solid #334155; background: #0f172a; color: #fff; font-size: 14px; outline: none;">
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Custom Email</label>
                    <input type="email" name="email" required style="padding: 10px 14px; border-radius: 8px; border: 1px solid #334155; background: #0f172a; color: #fff; font-size: 14px; outline: none;">
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Custom Password</label>
                    <input type="password" name="password" required style="padding: 10px 14px; border-radius: 8px; border: 1px solid #334155; background: #0f172a; color: #fff; font-size: 14px; outline: none;">
                </div>
                
                <button type="submit" style="padding: 12px; background: #f59e0b; color: #000; font-weight: bold; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; margin-top: 8px;">Register Account</button>
            </form>
            
            <p style="font-size: 13px; text-align: center; margin-top: 24px; margin-bottom: 0;">
                <a href="index.php" style="color: #f59e0b; text-decoration: none; font-weight: 600;">← Go to Login Portal</a>
            </p>
        <?php endif; ?>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</body>
</html>