<?php

require_once
    __DIR__
    .
    '/../core/BaseModel.php';


class ServiceRequestModel
extends BaseModel
{
    public function
    getPending(): array
    {

        $stmt =
            $this->db
                ->prepare(

                    "
                        SELECT *
                        FROM service_requests
                        WHERE status!='completed'
                        ORDER BY requested_at DESC
                    "
                );

        $stmt->execute();

        return
            $stmt
                ->get_result()
                ->fetch_all(
                    MYSQLI_ASSOC
                );
    }



    public function
    updateStatus(
        int $id,
        string $status
    ): bool
    {

        $stmt =
            $this->db
                ->prepare(

                    "
                        UPDATE service_requests
                        SET status=?
                        WHERE id=?
                    "
                );

        $stmt
            ->bind_param(
                "si",
                $status,
                $id
            );

        return
            $stmt
                ->execute();
    }
}