<!DOCTYPE html>
<html>
<head>
    <title>
        Guest Check-in
    </title>
</head>

<body>

<h2>

    Check-in Booking #

    <?= htmlspecialchars(
        $booking['id']
    ) ?>

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


    <label>

        Select Room

    </label>


    <select
        name="room_id"
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

        </option>

        <?php endforeach; ?>

    </select>


    <br><br>


    <button type="submit">

        Confirm Check-in

    </button>

</form>

</body>
</html>