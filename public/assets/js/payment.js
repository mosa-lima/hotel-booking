function payBill(
    bookingId
){

    const method =
        document
            .getElementById(
                "payment_method"
            )
            .value;


    const xhr =
        new XMLHttpRequest();


    xhr.open(

        "POST",

        "/hotel-booking/public/payment/process"
    );


    xhr.setRequestHeader(

        "Content-Type",

        "application/x-www-form-urlencoded"
    );



    xhr.onload =
        function(){

            const response =

                JSON.parse(
                    xhr.responseText
                );


            alert(
                response.message
            );


            if(
                response.success
            ){

                location.reload();
            }
        };



    xhr.send(

        "booking_id="
        +
        bookingId

        +

        "&method="
        +
        method
    );
}