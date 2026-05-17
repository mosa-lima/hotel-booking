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



            if(
                response.success
            ){

                document
                    .getElementById(
                        "payment_status"
                    )
                    .className =

                    "badge bg-success mb-3";



                document
                    .getElementById(
                        "payment_status"
                    )
                    .innerText =

                    "Paid ✓";



                alert(
                    "Payment completed successfully"
                );

            }else{

                alert(
                    "Payment failed"
                );
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