<!DOCTYPE html>
<html>

<head>

    <title>Reception Dashboard</title>

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
            background:#f4f7fc;
        }

        .hero{
            background:linear-gradient(
                135deg,
                #1d3557,
                #457b9d
            );

            color:white;

            border-radius:20px;

            padding:30px;

            margin-bottom:30px;
        }

        .card{
            border:none;
            border-radius:18px;
            box-shadow:
                0 8px 20px rgba(0,0,0,.08);
        }

        .status-badge{
            font-size:13px;
            padding:8px 14px;
            border-radius:20px;
        }

        table{
            font-size:15px;
        }

        .btn-premium{
            border-radius:25px;
            font-weight:600;
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
            Front Desk Operations Dashboard
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

                <th>Room Type</th>

                <th>Guests</th>

                <th>Action</th>

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

                        <span
                            class="
                                badge
                                bg-primary
                            "
                        >

                            <?= htmlspecialchars(
                                $item['room_type']
                            ) ?>

                        </span>

                    </td>

                    <td>

                        <?= $item['num_guests'] ?>

                    </td>


                    <td>

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


        <table class="table table-hover">

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