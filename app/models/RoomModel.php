<?php

require_once __DIR__ . '/../core/BaseModel.php';

class RoomModel extends BaseModel
{
    public function getAvailableByType(
        int $typeId
    ): array {

        if(
            $typeId <= 0
        ){
            return [];
        }


        $stmt =
            $this->db->prepare("

                SELECT *

                FROM rooms

                WHERE

                    room_type_id=?

                AND

                    status='available'

                ORDER BY room_number

            ");


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
        $stmt =
            $this->db->prepare("

                SELECT *

                FROM rooms

                ORDER BY floor

            ");


        $stmt->execute();


        return
            $stmt
                ->get_result()
                ->fetch_all(
                    MYSQLI_ASSOC
                );
    }








    public function
    getTypePrice(
        int $typeId
    ): float
    {

        if(
            $typeId <= 0
        ){
            return 0;
        }


        $stmt =
            $this->db
                ->prepare("

                    SELECT
                        price_per_night

                    FROM room_types

                    WHERE id=?

                    LIMIT 1

                ");


        $stmt
            ->bind_param(
                "i",
                $typeId
            );


        $stmt
            ->execute();


        $row =
            $stmt
                ->get_result()
                ->fetch_assoc();


        if(
            !$row
        ){
            return 0;
        }


        return
            (float)
            $row[
                'price_per_night'
            ];
    }
}