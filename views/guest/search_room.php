<!DOCTYPE html>
<html>
<head>
    <title>Search Rooms</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>



<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guest'){
    header("Location: login.php");
    exit();
}
?>

<div class="menu">
    <a href="dashboard.php">Dashboard</a>
    <a href="search_room.php">Search Rooms</a>
    <a href="my_bookings.php">My Bookings</a>
    <a href="profile.php">My Profile</a>
    <a href="service_request.php">Service Requests</a>
    <a href="billing_history.php">Billing</a>
    <a href="../../controllers/Logout.php">Logout</a>
</div>

<div class="container">

<h1>Search Available Rooms</h1>

<div class="search-box">
    <table style="width: 100%;">
        <tr>
            <td>Check-in Date: </td>
            <td><input type="date" id="checkin" required> </td>
        </tr>
        <tr>
            <td>Check-out Date: </td>
            <td><input type="date" id="checkout" required> </td>
        </tr>
        <tr>
            <td>Number of Guests: </td>
            <td><input type="number" id="guests" min="1" max="10" value="1"> </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <button type="button" onclick="searchRooms()">Search</button>
            </td>
        </tr>
    </table>
</div>

<div id="results" style="text-align: center;"></div>

<script>
function searchRooms() {
    var checkin = document.getElementById('checkin').value;
    var checkout = document.getElementById('checkout').value;
    var guests = document.getElementById('guests').value;
    
    if(checkin === "" || checkout === "") {
        alert("Please select both check-in and check-out dates");
        return;
    }
    
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        var data = JSON.parse(xhttp.responseText);
        var display = "";
        
        if(data.success && data.rooms.length > 0) {
            display = "<h2>Available Rooms (" + data.rooms.length + " found)</h2>";
            for(var i = 0; i < data.rooms.length; i++) {
                var room = data.rooms[i];
                var amenities = JSON.parse(room.amenities);
                display += "<div class='room-card'>";
                display += "<h3>" + room.name + "</h3>";
                display += "<p>" + room.description + "</p>";
                display += "<p><strong>Max Capacity:</strong> " + room.max_capacity + "</p>";
                display += "<p><strong>Original Price:</strong> $" + room.price_per_night + "/night</p>";
                display += "<p><strong>Current Price:</strong> $" + room.current_price + "/night</p>";
                display += "<p><strong>Amenities:</strong> " + amenities.join(", ") + "</p>";
                display += "<p><strong>Available Rooms:</strong> " + room.available_rooms + "</p>";
                display += "<form action='book_room.php' method='POST'>";
                display += "<input type='hidden' name='room_type_id' value='" + room.id + "'>";
                display += "<input type='hidden' name='checkin' value='" + checkin + "'>";
                display += "<input type='hidden' name='checkout' value='" + checkout + "'>";
                display += "<input type='hidden' name='guests' value='" + guests + "'>";
                display += "<input type='hidden' name='price' value='" + room.current_price + "'>";
                display += "<button type='submit'>Book Now</button>";
                display += "</form>";
                display += "</div>";
            }
        } else {
            display = "<p class='error'>No rooms available for selected dates.</p>";
        }
        document.getElementById('results').innerHTML = display;
    };
    xhttp.open("GET", "../../ajax/search_ajax.php?checkin=" + checkin + "&checkout=" + checkout + "&guests=" + guests, true);
    xhttp.send();
}
</script>

</div>
</body>
</html>