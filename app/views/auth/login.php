<!DOCTYPE html>
<html>

<head>

    <title>
        Reception Login
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

        *{
            font-family:
                Poppins;
        }


        body{

            margin:0;

            min-height:100vh;

            background:
                linear-gradient(
                    135deg,
                    #0f172a,
                    #1d4ed8,
                    #2563eb
                );

            display:flex;

            justify-content:center;

            align-items:center;
        }



        .login-card{

            width:430px;

            background:white;

            border-radius:25px;

            padding:45px;

            box-shadow:
                0 20px 60px
                rgba(
                    0,
                    0,
                    0,
                    .25
                );
        }




        .logo{

            width:80px;

            height:80px;

            border-radius:50%;

            background:
                linear-gradient(
                    135deg,
                    #1d4ed8,
                    #2563eb
                );

            color:white;

            font-size:34px;

            font-weight:700;

            display:flex;

            justify-content:center;

            align-items:center;

            margin:auto;

            margin-bottom:20px;
        }




        .title{

            text-align:center;

            font-size:28px;

            font-weight:700;

            color:#0f172a;

            margin-bottom:8px;
        }



        .subtitle{

            text-align:center;

            color:#64748b;

            margin-bottom:35px;
        }



        .form-control{

            height:55px;

            border-radius:14px;

            border:1px solid #cbd5e1;
        }




        .form-control:focus{

            box-shadow:none;

            border-color:#2563eb;
        }




        .btn-login{

            width:100%;

            height:55px;

            border:none;

            border-radius:14px;

            background:
                linear-gradient(
                    135deg,
                    #1d4ed8,
                    #2563eb
                );

            color:white;

            font-weight:600;

            font-size:16px;

            transition:.3s;
        }



        .btn-login:hover{

            transform:
                translateY(-2px);
        }




        .footer{

            margin-top:25px;

            text-align:center;

            color:#64748b;

            font-size:14px;
        }

    </style>

</head>



<body>



<div
    class="
        login-card
    "
>



    <div
        class="
            logo
        "
    >

        H

    </div>





    <div
        class="
            title
        "
    >

        Front Desk Login

    </div>





    <div
        class="
            subtitle
        "
    >

        Hotel Reception Management

    </div>






    <form
        method="POST"
        action="/hotel-booking/public/login"
    >




        <label
            class="
                mb-2
            "
        >

            Email Address

        </label>


        <input

            type="email"

            name="email"

            class="
                form-control
                mb-4
            "

            placeholder="reception@test.com"

            required
        >








        <label
            class="
                mb-2
            "
        >

            Password

        </label>


        <input

            type="password"

            name="password"

            class="
                form-control
                mb-4
            "

            placeholder="••••••••"

            required
        >









        <button
            type="submit"
            class="
                btn-login
            "
        >

            Login To Dashboard

        </button>


    </form>







    <div
        class="
            footer
        "
    >

        Hotel Booking System &copy; 2024

    </div>


</div>



</body>
</html>