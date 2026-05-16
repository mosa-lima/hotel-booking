<!DOCTYPE html>
<html>

<head>

    <title>Guest Check-in</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body
    class="bg-light"
>

<div
    class="
        container
        mt-5
    "
>

    <div
        class="
            card
            p-5
            shadow
        "
    >

        <h2 class="mb-4">

            Guest Check-in

        </h2>


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
                class="mb-2"
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
                        <?= $room['room_number'] ?>

                    </option>

                <?php endforeach; ?>

            </select>



            <button
                class="
                    btn
                    btn-success
                "
            >

                Confirm Check-in

            </button>

        </form>

    </div>

</div>

</body>
</html>