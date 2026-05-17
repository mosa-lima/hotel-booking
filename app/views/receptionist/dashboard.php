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

        .sidebar{

            position:fixed;

            top:0;
            left:0;

            width:240px;

            height:100%;

            background:
                linear-gradient(
                    180deg,
                    #0f172a,
                    #1d4ed8
                );

            color:white;

            padding:30px;
        }


        .sidebar a{

            display:block;

            color:white;

            text-decoration:none;

            margin-bottom:18px;

            font-weight:500;
        }


        .content{

            margin-left:270px;

            padding:30px;
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


        .stat-number{

            font-size:32px;

            font-weight:700;
        }

    </style>

</head>

<body>




<div class="sidebar">

    <h4>

        Front Desk

    </h4>


    <br>


   <a href="/hotel-booking/public/dashboard">
Dashboard
</a>

<a href="/hotel-booking/public/walkin">
Walk-In Booking
</a>

<a href="/hotel-booking/public/checkins">
Check-Ins
</a>

<a href="/hotel-booking/public/services">
Service Requests
</a>

<a href="/hotel-booking/public/report">
Reports
</a>


    <a
        href="/hotel-booking/public/logout"
    >

        Logout

    </a>

</div>





<div class="content">



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






    <div class="row mb-4">


        <div class="col-md-3">

            <div class="card p-4">

                <div>

                    Today's Revenue

                </div>


                <div
                    class="
                        stat-number
                        text-success
                    "
                >

                    ৳<?= $revenue ?>

                </div>

            </div>

        </div>





        <div class="col-md-3">

            <div class="card p-4">

                <div>

                    Occupied Rooms

                </div>


                <div
                    class="
                        stat-number
                        text-danger
                    "
                >

                    <?= $occupiedRooms ?>

                </div>

            </div>

        </div>






        <div class="col-md-3">

            <div class="card p-4">

                <div>

                    Pending Requests

                </div>


                <div
                    class="
                        stat-number
                        text-warning
                    "
                >

                    <?= count(
                        $requests
                    ) ?>

                </div>

            </div>

        </div>






        <div class="col-md-3">

            <div class="card p-4">

                <div>

                    Today's Arrivals

                </div>


                <div
                    class="
                        stat-number
                        text-primary
                    "
                >

                    <?= count(
                        $checkins
                    ) ?>

                </div>

            </div>

        </div>


    </div>








    <!-- KEEPING YOUR CHECKIN TABLE -->

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









   <!-- NEW SERVICE REQUESTS -->

<div class="card p-4 mb-4">

    <h4 class="mb-4">

        Pending Service Requests

    </h4>


    <table class="table table-hover">

        <thead>

        <tr>

            <th>ID</th>

            <th>Service</th>

            <th>Status</th>

            <th>Action</th>

        </tr>

        </thead>


        <tbody>

        <?php foreach(
            $requests as $request
        ): ?>


            <tr>



                <td>

                    #<?= htmlspecialchars(
                        $request['id']
                    ) ?>

                </td>





                <td>

                    <?= htmlspecialchars(
                        $request['service_type']
                    ) ?>

                </td>






                <td>

                    <span
                        class="
                            badge
                            bg-warning
                        "
                    >

                        <?= htmlspecialchars(
                            $request['status']
                        ) ?>

                    </span>

                </td>







                <td>

                    <form
                        method="POST"
                        action="/hotel-booking/public/service/update"
                    >


                        <input
                            type="hidden"
                            name="request_id"
                            value="<?= $request['id'] ?>"
                        >



                        <select
                            name="status"
                            class="
                                form-select
                                mb-2
                            "
                        >

                            <option
                                value="pending"
                                <?= $request['status']=='pending' ? 'selected' : '' ?>
                            >

                                pending

                            </option>


                            <option
                                value="in_progress"
                                <?= $request['status']=='in_progress' ? 'selected' : '' ?>
                            >

                                in_progress

                            </option>


                            <option
                                value="completed"
                                <?= $request['status']=='completed' ? 'selected' : '' ?>
                            >

                                completed

                            </option>


                        </select>





                        <button
                            class="
                                btn
                                btn-primary
                                btn-sm
                            "
                        >

                            Update

                        </button>


                    </form>

                </td>



            </tr>


        <?php endforeach; ?>


        </tbody>

    </table>

</div>





    <!-- KEEPING YOUR ROOM STATUS -->

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



</div>

</body>
</html>