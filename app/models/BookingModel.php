<?php

require_once __DIR__ . '/../core/BaseModel.php';

class BookingModel extends BaseModel
{
    public function getTodaysCheckins(): array
    {
        $today = date("Y-m-d");

        $sql = "
            SELECT
                b.id,
                u.name,
                b.checkin_date,
                b.checkout_date,
                b.num_guests,
                b.room_type_id,
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

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param(
            "s",
            $today
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(
                MYSQLI_ASSOC
            );
    }


    public function findById(
        int $bookingId
    ): ?array {

        $sql = "
            SELECT *
            FROM bookings
            WHERE id = ?
            LIMIT 1
        ";

        $stmt =
            $this->db->prepare($sql);

        $stmt->bind_param(
            "i",
            $bookingId
        );

        $stmt->execute();

        $result =
            $stmt->get_result();

        return
            $result->fetch_assoc()
            ?: null;
    }


    public function checkIn(
        int $bookingId,
        int $roomId
    ): bool {

        $this->db->begin_transaction();

        try {

            $sql1 = "
                UPDATE bookings
                SET
                    room_id = ?,
                    status = 'checked_in'
                WHERE id = ?
            ";

            $stmt1 =
                $this->db->prepare($sql1);

            $stmt1->bind_param(
                "ii",
                $roomId,
                $bookingId
            );

            $stmt1->execute();


            $sql2 = "
                UPDATE rooms
                SET status = 'occupied'
                WHERE id = ?
            ";

            $stmt2 =
                $this->db->prepare($sql2);

            $stmt2->bind_param(
                "i",
                $roomId
            );

            $stmt2->execute();


            $this->db->commit();

            return true;

        } catch(Exception $e){

            $this->db->rollback();

            return false;
        }
    }
}