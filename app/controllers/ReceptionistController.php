<?php

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/RoomModel.php';
require_once __DIR__ . '/../core/BaseController.php';

class ReceptionistController
extends BaseController
{
    public function dashboard(): void
    {
        AuthController
            ::requireReceptionist();

        $bookingModel =
            new BookingModel();

        $roomModel =
            new RoomModel();

        $checkins =
            $bookingModel
                ->getTodaysCheckins();

        $rooms =
            $roomModel
                ->getStatusBoard();

        $this->view(
            'receptionist/dashboard',
            [
                'checkins' => $checkins,
                'rooms' => $rooms
            ]
        );
    }
}