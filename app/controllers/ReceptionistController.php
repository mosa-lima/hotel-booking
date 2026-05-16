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

        $this->view(
            'receptionist/dashboard',
            [

                'checkins'
                    =>
                    $bookingModel
                        ->getTodaysCheckins(),

                'bookings'
                    =>
                    $bookingModel
                        ->getAll(),

                'rooms'
                    =>
                    $roomModel
                        ->getStatusBoard()
            ]
        );
    }



    public function cancel(
        int $bookingId
    ): void {

        (
            new BookingModel()
        )->cancel(
            $bookingId
        );

        header(
            "Location: /hotel-booking/public/dashboard"
        );
    }



    public function checkout(
        int $bookingId
    ): void {

        (
            new BookingModel()
        )->checkout(
            $bookingId
        );

        header(
            "Location: /hotel-booking/public/dashboard"
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
        $success =
            (
                new BookingModel()
            )->checkIn(

                (int)
                $_POST['booking_id'],

                (int)
                $_POST['room_id']
            );


        header(
            "Location: /hotel-booking/public/dashboard"
        );
    }
}