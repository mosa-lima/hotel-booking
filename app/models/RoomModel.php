<?php

require_once __DIR__ . '/../core/BaseModel.php';

class RoomModel extends BaseModel
{
    public function getAvailableByType(
        int $typeId
    ): array {

        $sql = "
            SELECT
                id,
                room_number,
                floor,
                status
            FROM rooms
            WHERE room_type_id = ?
            AND status = 'available'
            ORDER BY room_number
        ";

        $stmt =
            $this->db->prepare($sql);

        $stmt->bind_param(
            "i",
            $typeId
        );

        $stmt->execute();

        return
            $stmt
            ->get_result()
            ->fetch_all(
                MYSQLI_ASSOC
            );
    }


    public function getStatusBoard(): array
    {
        $sql = "
            SELECT
                room_number,
                floor,
                status
            FROM rooms
            ORDER BY floor, room_number
        ";

        $stmt =
            $this->db->prepare($sql);

        $stmt->execute();

        return
            $stmt
            ->get_result()
            ->fetch_all(
                MYSQLI_ASSOC
            );
    }
}