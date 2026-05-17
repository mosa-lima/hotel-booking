<!DOCTYPE html>
<html>

<head>

<title>
Walk-In Booking
</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
rel="stylesheet"
>

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card p-5 shadow">

<h2>
Walk-In Booking
</h2>



<form
method="POST"
action="/hotel-booking/public/walkin/create"
>



<label class="mb-2">
Guest ID
</label>

<input
name="guest_id"
type="number"
required
class="form-control mb-3"
placeholder="2"
>






<label class="mb-2">
Room Type
</label>

<select
name="room_type_id"
required
class="form-select mb-3"
>

<option value="1">
Standard
</option>


<option value="2">
Deluxe
</option>


<option value="3">
Suite
</option>

</select>







<label class="mb-2">
Number of Guests
</label>

<input
name="num_guests"
type="number"
required
value="2"
class="form-control mb-3"
>







<label class="mb-2">
Check-In
</label>

<input
name="checkin_date"
type="date"
required
class="form-control mb-3"
>







<label class="mb-2">
Check-Out
</label>

<input
name="checkout_date"
type="date"
required
class="form-control mb-4"
>







<button
class="btn btn-primary"
>

Create Booking

</button>


</form>

</div>

</div>

</body>
</html>