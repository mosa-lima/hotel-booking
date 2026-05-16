<?php

require_once __DIR__ . '/../core/BaseModel.php';

class RoomModel extends BaseModel
{
    public function getAvailableByType(
        int $typeId
    ): array {

        $stmt =
            $this->db->prepare("
                SELECT
                    *
                FROM rooms
                WHERE
                    room_type_id=?
                AND
                    status='available'
            ");

        $stmt->bind_param(
            "i",
            $typeId
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(
                MYSQLI_ASSOC
            );
    }



    public function getStatusBoard(): array
    {
        $stmt =
            $this->db->prepare("
                SELECT *
                FROM rooms
                ORDER BY floor
            ");

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(
                MYSQLI_ASSOC
            );
    }
}