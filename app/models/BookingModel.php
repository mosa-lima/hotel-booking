<?php

require_once __DIR__ . '/../core/BaseModel.php';

class BookingModel extends BaseModel
{
    public function getTodaysCheckins(): array
    {
        $today =
            date("Y-m-d");

        $sql = "
            SELECT
                b.id,
                u.name,
                b.checkin_date,
                b.checkout_date,
                b.num_guests,
                rt.name AS room_type

            FROM bookings b

            INNER JOIN users u
                ON b.guest_id = u.id

            INNER JOIN room_types rt
                ON b.room_type_id = rt.id

            WHERE
                b.checkin_date = ?
            AND
                b.status = 'confirmed'

            ORDER BY
                b.created_at ASC
        ";

        $stmt =
            $this->db->prepare($sql);

        $stmt->bind_param(
            "s",
            $today
        );

        $stmt->execute();

        return
            $stmt
            ->get_result()
            ->fetch_all(
                MYSQLI_ASSOC
            );
    }
}