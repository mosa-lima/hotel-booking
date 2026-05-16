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

            font-family:
                Poppins;

            background:
                #eef2f7;
        }


        .header-box{

            background:
                linear-gradient(
                    135deg,
                    #0f172a,
                    #2563eb
                );

            color:
                white;

            border-radius:
                20px;

            padding:
                30px;

            margin-bottom:
                30px;
        }


        .card{

            border:
                none;

            border-radius:
                20px;

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

            border-radius:
                25px;

            font-weight:
                600;
        }


        .info-box{

            background:
                #f8fafc;

            border-radius:
                15px;

            padding:
                20px;
        }

    </style>

</head>



<body>



<div
    class="
        container
        mt-4
        mb-5
    "
>



    <div
        class="
            header-box
        "
    >

        <h2>

            Guest Check-in

        </h2>


        <p>

            Booking #

            <?= htmlspecialchars(
                $booking['id']
            ) ?>

        </p>

    </div>





    <div
        class="
            row
        "
    >




        <div
            class="
                col-md-8
            "
        >


            <div
                class="
                    card
                    p-4
                "
            >


                <h4
                    class="
                        mb-4
                    "
                >

                    Room Assignment

                </h4>



                <form
                    method="POST"
                    action="/hotel-booking/public/checkin/process"
                >


                    <input
                        type="hidden"
                        name="booking_id"
                        value="<?= $booking['id'] ?>"
                    >




                    <label
                        class="
                            mb-2
                        "
                    >

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


                        <?php foreach(
                            $rooms as $room
                        ): ?>


                            <option
                                value="<?= $room['id'] ?>"
                            >

                                Room

                                <?= htmlspecialchars(
                                    $room['room_number']
                                ) ?>

                                —

                                Floor

                                <?= htmlspecialchars(
                                    $room['floor']
                                ) ?>


                            </option>


                        <?php endforeach; ?>


                    </select>






                    <label
                        class="
                            mb-2
                        "
                    >

                        Payment Method

                    </label>



                    <select
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






                    <label
                        class="
                            mb-2
                        "
                    >

                        Loyalty Discount

                    </label>



                    <input
                        type="number"
                        class="
                            form-control
                            mb-4
                        "
                        placeholder="0"
                    >






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







        <div
            class="
                col-md-4
            "
        >




            <div
                class="
                    card
                    p-4
                "
            >


                <h5
                    class="
                        mb-4
                    "
                >

                    Booking Summary

                </h5>





                <div
                    class="
                        info-box
                        mb-3
                    "
                >

                    <strong>

                        Booking ID:

                    </strong>

                    <br>

                    #<?= htmlspecialchars(
                        $booking['id']
                    ) ?>


                </div>






                <div
                    class="
                        info-box
                        mb-3
                    "
                >

                    <strong>

                        Guest Count:

                    </strong>

                    <br>

                    <?= htmlspecialchars(
                        $booking['num_guests']
                    ) ?>


                </div>







                <div
                    class="
                        info-box
                        mb-3
                    "
                >

                    <strong>

                        Check-in:

                    </strong>

                    <br>

                    <?= htmlspecialchars(
                        $booking['checkin_date']
                    ) ?>


                </div>







                <div
                    class="
                        info-box
                    "
                >

                    <strong>

                        Check-out:

                    </strong>

                    <br>

                    <?= htmlspecialchars(
                        $booking['checkout_date']
                    ) ?>


                </div>






                <hr>






                <h5>

                    Billing Preview

                </h5>





                <p>

                    Room Charge:

                    <strong>

                        ৳

                        <?= htmlspecialchars(
                            $booking['total_price']
                        ) ?>

                    </strong>

                </p>






                <p>

                    Taxes:

                    <strong>

                        ৳0

                    </strong>

                </p>






                <p>

                    Discount:

                    <strong>

                        ৳0

                    </strong>

                </p>






                <hr>






                <h4>

                    Total:

                    <span
                        class="
                            text-success
                        "
                    >

                        ৳

                        <?= htmlspecialchars(
                            $booking['total_price']
                        ) ?>

                    </span>

                </h4>


            </div>

        </div>


    </div>



</div>



</body>
</html>