function loadRooms(typeId)
{
    const xhr =
        new XMLHttpRequest();

    xhr.open(
        "GET",
        "/hotel-booking/public/api/rooms.php?type="
        + typeId
    );

    xhr.onload =
        function()
        {
            const rooms =
                JSON.parse(
                    xhr.responseText
                );

            console.log(
                rooms
            );
        };

    xhr.send();
}