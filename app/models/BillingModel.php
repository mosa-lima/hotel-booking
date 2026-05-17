<?php

require_once
    __DIR__
    .
    '/../core/BaseModel.php';


class BillingModel
extends BaseModel
{
    public function
    createInvoice(
        int $bookingId,
        int $guestId,
        float $amount
    ): bool {

        $sql = "

            INSERT INTO billing(

                booking_id,
                guest_id,

                base_amount,

                total_amount,

                payment_status

            )

            VALUES(

                ?,
                ?,

                ?,

                ?,

                'pending'

            )
        ";

        $stmt =
            $this->db
                ->prepare(
                    $sql
                );

        $stmt
            ->bind_param(

                "iidd",

                $bookingId,

                $guestId,

                $amount,

                $amount
            );

        return
            $stmt
                ->execute();
    }




    public function
    markPaid(
        int $bookingId,
        string $method
    ): bool {

        $sql = "

            UPDATE billing

            SET

                payment_status='paid',

                payment_method=?,

                paid_at=NOW()

            WHERE booking_id=?
        ";

        $stmt =
            $this->db
                ->prepare(
                    $sql
                );

        $stmt
            ->bind_param(

                "si",

                $method,

                $bookingId
            );

        return
            $stmt
                ->execute();
    }




    public function
    findByBooking(
        int $bookingId
    ): ?array {

        $stmt =
            $this->db
                ->prepare(

                    "
                        SELECT *
                        FROM billing
                        WHERE booking_id=?
                    "
                );

        $stmt
            ->bind_param(
                "i",
                $bookingId
            );

        $stmt
            ->execute();

        $result =
            $stmt
                ->get_result();

        return
            $result
                ->fetch_assoc()
            ?: null;
    }



    public function
    getRevenueToday(): float
    {

        $stmt =
            $this->db
                ->prepare(

                    "
                        SELECT
                            SUM(total_amount)
                            AS revenue

                        FROM billing

                        WHERE
                            payment_status='paid'
                        AND
                            DATE(paid_at)=CURDATE()
                    "
                );

        $stmt->execute();

        $row =
            $stmt
                ->get_result()
                ->fetch_assoc();

        return
            (float)(
                $row['revenue']
                ??
                0
            );
    }
}