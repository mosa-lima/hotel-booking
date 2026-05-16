<?php

require_once "../app/core/BaseModel.php";

class UserModel extends BaseModel
{
    public function findByEmail(
        string $email
    ): ?array {

        $sql = "
            SELECT *
            FROM users
            WHERE email = ?
            AND is_active = 1
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param(
            "s",
            $email
        );

        $stmt->execute();

        $result =
            $stmt->get_result();

        return
            $result->fetch_assoc()
            ?: null;
    }
}