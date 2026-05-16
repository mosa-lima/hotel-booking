<?php

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/RoomModel.php';
require_once __DIR__ . '/../core/BaseController.php';

class ReceptionistController extends BaseController
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


    public function showCheckin(
        int $bookingId
    ): void {

        AuthController
            ::requireReceptionist();

        $bookingModel =
            new BookingModel();

        $roomModel =
            new RoomModel();


        $booking =
            $bookingModel
                ->findById(
                    $bookingId
                );


        $rooms =
            $roomModel
                ->getAvailableByType(
                    $booking['room_type_id']
                );


        $this->view(
            'receptionist/checkin',
            [
                'booking' => $booking,
                'rooms' => $rooms
            ]
        );
    }


    public function processCheckin(): void
    {
        AuthController
            ::requireReceptionist();

        $bookingId =
            (int)$_POST['booking_id'];

        $roomId =
            (int)$_POST['room_id'];


        $bookingModel =
            new BookingModel();


        $success =
            $bookingModel
                ->checkIn(
                    $bookingId,
                    $roomId
                );


        if ($success) {

            header(
                "Location: /hotel-booking/public/dashboard"
            );

            exit;
        }


        die(
            "Check-in failed."
        );
    }
}