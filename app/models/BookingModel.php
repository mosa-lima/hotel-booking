<?php

require_once __DIR__ . '/../core/BaseModel.php';

class BookingModel extends BaseModel
{
    public function getTodaysCheckins(): array
    {
        $today = date("Y-m-d");

        $sql = "
            SELECT
                b.*,
                u.name,
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

        $stmt->bind_param("s", $today);

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(
                MYSQLI_ASSOC
            );
    }



    public function getAll(): array
    {
        $sql = "
            SELECT
                b.*,
                u.name,
                rt.name AS room_type

            FROM bookings b

            INNER JOIN users u
                ON b.guest_id = u.id

            INNER JOIN room_types rt
                ON b.room_type_id = rt.id

            ORDER BY b.id DESC
        ";

        $stmt =
            $this->db->prepare($sql);

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(
                MYSQLI_ASSOC
            );
    }



    public function create(
        int $guestId,
        int $roomTypeId,
        string $checkin,
        string $checkout,
        int $guests,
        float $price
    ): bool {

        $sql = "
            INSERT INTO bookings(

                guest_id,
                room_type_id,
                checkin_date,
                checkout_date,
                num_guests,
                total_price,
                status,
                source

            ) VALUES (

                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                'confirmed',
                'walk_in'

            )
        ";

        $stmt =
            $this->db->prepare(
                $sql
            );

        $stmt->bind_param(
            "iissid",
            $guestId,
            $roomTypeId,
            $checkin,
            $checkout,
            $guests,
            $price
        );

        return
            $stmt->execute();
    }



    public function cancel(
        int $bookingId
    ): bool {

        $sql = "
            UPDATE bookings
            SET status='cancelled'
            WHERE id=?
        ";

        $stmt =
            $this->db->prepare(
                $sql
            );

        $stmt->bind_param(
            "i",
            $bookingId
        );

        return
            $stmt->execute();
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

            $stmt1 =
                $this->db->prepare("
                    UPDATE bookings
                    SET
                        room_id = ?,
                        status='checked_in'
                    WHERE id=?
                ");

            $stmt1->bind_param(
                "ii",
                $roomId,
                $bookingId
            );

            $stmt1->execute();


            $stmt2 =
                $this->db->prepare("
                    UPDATE rooms
                    SET status='occupied'
                    WHERE id=?
                ");

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



    public function checkout(
        int $bookingId
    ): bool {

        $booking =
            $this->findById(
                $bookingId
            );

        $this->db->begin_transaction();

        try {

            $stmt1 =
                $this->db->prepare("
                    UPDATE bookings
                    SET status='checked_out'
                    WHERE id=?
                ");

            $stmt1->bind_param(
                "i",
                $bookingId
            );

            $stmt1->execute();



            $stmt2 =
                $this->db->prepare("
                    UPDATE rooms
                    SET status='dirty'
                    WHERE id=?
                ");

            $stmt2->bind_param(
                "i",
                $booking['room_id']
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