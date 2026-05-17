<?php

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/RoomModel.php';
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/BillingModel.php';
require_once __DIR__ . '/../models/ServiceRequestModel.php';

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

        $billingModel =
            new BillingModel();

        $serviceModel =
            new ServiceRequestModel();


        $rooms =
            $roomModel
                ->getStatusBoard();


        $occupiedRooms = 0;


        foreach(
            $rooms as $room
        ){

            if(
                $room['status']
                ===
                'occupied'
            ){

                $occupiedRooms++;
            }
        }


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
                    $rooms,

                'revenue'
                    =>
                    $billingModel
                        ->getRevenueToday(),

                'requests'
                    =>
                    $serviceModel
                        ->getPending(),

                'occupiedRooms'
                    =>
                    $occupiedRooms
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








    public function
    payBill(): void
    {

        AuthController
            ::requireReceptionist();


        $bookingId =
            (int)
            $_POST[
                'booking_id'
            ];


        $method =
            $_POST[
                'method'
            ];


        $success =

            (
                new BillingModel()
            )->markPaid(

                $bookingId,

                $method
            );


        header(
            "Content-Type: application/json"
        );


        echo json_encode([

            "success"
                =>
                $success,

            "message"
                =>
                $success
                    ?
                    "Payment completed"
                    :
                    "Payment failed"
        ]);
    }








    public function
    approveEarlyCheckin(
        int $bookingId
    ): void
    {

        (
            new BookingModel()
        )->updateStatus(

            $bookingId,

            'confirmed'
        );

        header(
            "Location: /hotel-booking/public/dashboard"
        );
    }








    public function
    approveLateCheckout(
        int $bookingId
    ): void
    {

        (
            new BookingModel()
        )->extendCheckout(
            $bookingId
        );

        header(
            "Location: /hotel-booking/public/dashboard"
        );
    }








    public function
    updateServiceRequest(): void
    {

        (
            new ServiceRequestModel()
        )->updateStatus(

            (int)
            $_POST[
                'request_id'
            ],

            $_POST[
                'status'
            ]
        );

        header(
            "Location: /hotel-booking/public/dashboard"
        );
    }








    public function
    report(): void
    {

        AuthController
            ::requireReceptionist();


        $bookingModel =
            new BookingModel();

        $billingModel =
            new BillingModel();


        $this->view(

            'receptionist/report',

            [

                'arrivals'
                    =>
                    count(

                        $bookingModel
                            ->getTodaysCheckins()
                    ),

                'departures'
                    =>
                    $bookingModel
                        ->getTodaysDepartures(),

                'revenue'
                    =>
                    $billingModel
                        ->getRevenueToday()
            ]
        );
    }
}