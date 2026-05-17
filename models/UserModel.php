<?php
class UserModel {
    private $conn;
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    // Check if email exists - PREPARED STATEMENT
    public function emailExists($email) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        $stmt->close();
        return $count > 0;
    }
    
    // Register new user - PREPARED STATEMENT
    public function register($name, $email, $password, $phone, $nationality, $id_number) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, email, password_hash, phone, nationality, id_number, role, loyalty_points, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, 'guest', 0, 1)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $hashed_password, $phone, $nationality, $id_number);
        
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    // Login user - PREPARED STATEMENT
    public function login($email, $password) {
        $sql = "SELECT id, name, email, password_hash, role, is_active, loyalty_points FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $stmt->close();
            
            if (password_verify($password, $user['password_hash'])) {
                return $user;
            }
        } else {
            $stmt->close();
        }
        return null;
    }
    
    // Get user by ID - PREPARED STATEMENT
    public function getUserById($user_id) {
        $sql = "SELECT id, name, email, phone, nationality, id_number, profile_pic, loyalty_points FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $stmt->close();
            return $user;
        }
        $stmt->close();
        return null;
    }
    
    // Update profile - PREPARED STATEMENT
    public function updateProfile($user_id, $name, $phone, $nationality, $id_number) {
        $sql = "UPDATE users SET name = ?, phone = ?, nationality = ?, id_number = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $phone, $nationality, $id_number, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    // Update profile picture - PREPARED STATEMENT
    public function updateProfilePic($user_id, $pic_path) {
        $sql = "UPDATE users SET profile_pic = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $pic_path, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    // Change password - PREPARED STATEMENT
    public function changePassword($user_id, $old_password, $new_password) {
        // Get current password
        $sql = "SELECT password_hash FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if (password_verify($old_password, $user['password_hash'])) {
            $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $updateSql = "UPDATE users SET password_hash = ? WHERE id = ?";
            $updateStmt = $this->conn->prepare($updateSql);
            $updateStmt->bind_param("si", $new_hashed, $user_id);
            $result = $updateStmt->execute();
            $updateStmt->close();
            return $result;
        }
        return false;
    }
    
    // Update loyalty points - PREPARED STATEMENT
    public function updateLoyaltyPoints($user_id, $points) {
        $sql = "UPDATE users SET loyalty_points = loyalty_points + ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $points, $user_id);
        $stmt->execute();
        $stmt->close();
        
        // Get new balance
        $sql2 = "SELECT loyalty_points FROM users WHERE id = ?";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $row = $result->fetch_assoc();
        $stmt2->close();
        
        return $row['loyalty_points'];
    }
}
?>