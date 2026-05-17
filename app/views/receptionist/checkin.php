<!DOCTYPE html>
<html>

<head>

    <title>
        Guest Check-in
    </title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <style>

        body{
            font-family:Poppins;
            background:#eef2f7;
        }

        .header-box{

            background:
                linear-gradient(
                    135deg,
                    #0f172a,
                    #2563eb
                );

            color:white;

            border-radius:20px;

            padding:30px;

            margin-bottom:30px;
        }

        .card{

            border:none;

            border-radius:20px;

            box-shadow:
                0 10px 30px
                rgba(
                    0,
                    0,
                    0,
                    .08
                );
        }

        .btn-premium{

            border-radius:25px;

            font-weight:600;
        }

        .info-box{

            background:#f8fafc;

            border-radius:15px;

            padding:20px;
        }

    </style>

</head>

<body>



<div class="container mt-4 mb-5">



    <div class="header-box">

        <h2>

            Guest Check-in

        </h2>


        <p>

            Booking #

            <?= $booking['id'] ?>

        </p>

    </div>






    <div class="row">




        <div class="col-md-8">



            <div class="card p-4">



                <h4 class="mb-4">

                    Room Assignment

                </h4>





                <!-- DEBUG INFO -->


                <div
                    class="
                        alert
                        alert-info
                    "
                >

                    Booking Room Type ID:

                    <strong>

                        <?= $booking['room_type_id'] ?>

                    </strong>

                    <br>

                    Available Rooms:

                    <strong>

                        <?= count(
                            $rooms
                        ) ?>

                    </strong>

                </div>







                <form
                    method="POST"
                    action="/hotel-booking/public/checkin/process"
                >



                    <input
                        type="hidden"
                        name="booking_id"
                        value="<?= $booking['id'] ?>"
                    >






                    <label class="mb-2">

                        Select Available Room

                    </label>






                    <select
                        name="room_id"
                        class="
                            form-select
                            mb-4
                        "
                        required
                    >




                        <?php if(
                            count(
                                $rooms
                            ) > 0
                        ): ?>




                            <?php foreach(
                                $rooms as $room
                            ): ?>



                                <option
                                    value="<?= $room['id'] ?>"
                                >

                                    Room

                                    <?= $room['room_number'] ?>

                                    —

                                    Floor

                                    <?= $room['floor'] ?>


                                </option>



                            <?php endforeach; ?>




                        <?php else: ?>



                            <option>

                                No available rooms found

                            </option>



                        <?php endif; ?>




                    </select>








                    <label class="mb-2">

                        Payment Method

                    </label>




                    <select
                        id="payment_method"
                        class="
                            form-select
                            mb-4
                        "
                    >

                        <option>

                            Cash

                        </option>


                        <option>

                            Card

                        </option>


                        <option>

                            Mobile Banking

                        </option>

                    </select>








                    <button
                        class="
                            btn
                            btn-success
                            btn-premium
                            px-4
                        "
                    >

                        Confirm Check-in

                    </button>






                    <button
                        type="button"
                        onclick="payBill(<?= $booking['id'] ?>)"
                        class="
                            btn
                            btn-primary
                            btn-premium
                            px-4
                        "
                    >

                        Pay Now

                    </button>






                    <a
                        href="/hotel-booking/public/dashboard"
                        class="
                            btn
                            btn-secondary
                            btn-premium
                            px-4
                        "
                    >

                        Back

                    </a>




                </form>


            </div>


        </div>






        <div class="col-md-4">



            <div class="card p-4">



                <h5 class="mb-4">

                    Booking Summary

                </h5>



                <div class="info-box mb-3">

                    Booking:

                    #<?= $booking['id'] ?>

                </div>



                <div class="info-box mb-3">

                    Guests:

                    <?= $booking['num_guests'] ?>

                </div>



                <div class="info-box mb-3">

                    Check-in:

                    <?= $booking['checkin_date'] ?>

                </div>



                <div class="info-box mb-3">

                    Check-out:

                    <?= $booking['checkout_date'] ?>

                </div>



                <hr>



                <div
                    id="payment_status"
                    class="
                        badge
                        bg-warning
                        mb-3
                    "
                >

                    Pending

                </div>



                <h4>

                    ৳<?= $booking['total_price'] ?>

                </h4>


            </div>


        </div>



    </div>



</div>



<script src="/hotel-booking/public/assets/js/payment.js"></script>


</body>
</html>