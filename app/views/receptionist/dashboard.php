<!DOCTYPE html>
<html>
<head>

    <title>
        Reception Dashboard
    </title>

</head>

<body>

<h1>
    Welcome,
    <?= htmlspecialchars(
        $_SESSION['name']
    ) ?>
</h1>


<h2>
    Today's Check-ins
</h2>

<table border="1">

    <tr>
        <th>ID</th>
        <th>Guest</th>
        <th>Room Type</th>
        <th>Guests</th>
    </tr>

    <?php foreach(
        $checkins as $item
    ): ?>

    <tr>

        <td>
            <?= htmlspecialchars(
                $item['id']
            ) ?>
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
            <?= htmlspecialchars(
                $item['num_guests']
            ) ?>
        </td>

    </tr>

    <?php endforeach; ?>

</table>


<br><br>


<h2>
    Room Status Board
</h2>


<table border="1">

    <tr>
        <th>Room</th>
        <th>Floor</th>
        <th>Status</th>
    </tr>

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
            <?= htmlspecialchars(
                $room['status']
            ) ?>
        </td>

    </tr>

    <?php endforeach; ?>

</table>


<a href="/logout">
    Logout
</a>

</body>
</html>