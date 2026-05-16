<!DOCTYPE html>
<html>

<head>

    <title>
        Reception Dashboard
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

        .hero{
            background:
                linear-gradient(
                    135deg,
                    #0f172a,
                    #1d4ed8
                );

            color:white;

            border-radius:20px;

            padding:35px;

            margin-bottom:30px;
        }

        .card{
            border:none;

            border-radius:20px;

            box-shadow:
                0 8px 25px
                rgba(
                    0,
                    0,
                    0,
                    .08
                );
        }

        .status-badge{

            padding:
                8px 14px;

            border-radius:
                20px;
        }

        .btn-premium{

            border-radius:
                25px;

            font-weight:
                600;
        }

    </style>

</head>

<body>

<div class="container mt-4">


    <div class="hero">

        <h2>

            Welcome,

            <?= htmlspecialchars(
                $_SESSION['name']
            ) ?>

        </h2>


        <p>

            Hotel Reception Operations

        </p>

    </div>




    <div class="card p-4 mb-4">

        <h4 class="mb-4">

            Today's Check-ins

        </h4>


        <table class="table table-hover">

            <thead>

            <tr>

                <th>ID</th>

                <th>Guest</th>

                <th>Room</th>

                <th>Guests</th>

                <th>Actions</th>

            </tr>

            </thead>


            <tbody>

            <?php foreach(
                $checkins as $item
            ): ?>

                <tr>

                    <td>

                        #<?= $item['id'] ?>

                    </td>


                    <td>

                        <?= htmlspecialchars(
                            $item['name']
                        ) ?>

                    </td>


                    <td>

                        <?= htmlspecialchars(
                            $item['room_type']
                        ) ?>

                    </td>


                    <td>

                        <?= $item['num_guests'] ?>

                    </td>



                    <td>


                        <?php if(

                            $item['status']
                            ===
                            'checked_in'

                        ): ?>


                            <a
                                href="/hotel-booking/public/checkout/<?= $item['id'] ?>"
                                class="
                                    btn
                                    btn-danger
                                    btn-sm
                                    btn-premium
                                "
                            >

                                Check Out

                            </a>


                        <?php else: ?>


                            <a
                                href="/hotel-booking/public/checkin/<?= $item['id'] ?>"
                                class="
                                    btn
                                    btn-success
                                    btn-sm
                                    btn-premium
                                "
                            >

                                Check In

                            </a>


                        <?php endif; ?>



                        <a
                            href="/hotel-booking/public/cancel/<?= $item['id'] ?>"
                            class="
                                btn
                                btn-warning
                                btn-sm
                                btn-premium
                            "
                        >

                            Cancel

                        </a>



                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>





    <div class="card p-4">

        <h4 class="mb-4">

            Room Status Board

        </h4>


        <table class="table">

            <thead>

            <tr>

                <th>Room</th>

                <th>Floor</th>

                <th>Status</th>

            </tr>

            </thead>


            <tbody>

            <?php foreach(
                $rooms as $room
            ): ?>

                <tr>

                    <td>

                        <?= htmlspecialchars(
                            $room['room_number']
                        ) ?>

                    </td>


                    <td>

                        <?= htmlspecialchars(
                            $room['floor']
                        ) ?>

                    </td>


                    <td>

                        <?php

                        $class =
                            match(
                                $room['status']
                            ){

                                'available'
                                    => 'bg-success',

                                'occupied'
                                    => 'bg-danger',

                                'dirty'
                                    => 'bg-warning',

                                default
                                    => 'bg-secondary'
                            };

                        ?>


                        <span
                            class="
                                badge
                                <?= $class ?>
                                status-badge
                            "
                        >

                            <?= htmlspecialchars(
                                $room['status']
                            ) ?>

                        </span>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>




    <div class="mt-4">

        <a
            href="/hotel-booking/public/logout"
            class="
                btn
                btn-dark
                btn-premium
            "
        >

            Logout

        </a>

    </div>


</div>

</body>
</html>