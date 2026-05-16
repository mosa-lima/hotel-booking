<?php

class Database
{
    private static ?mysqli
        $connection = null;


    public static function
    connect(): mysqli
    {
        if(
            self::$connection
            ===
            null
        ){

            self::$connection =
                new mysqli(

                    "localhost",

                    "root",

                    "",

                    "receptionist"
                );


            if(
                self::$connection
                    ->connect_error
            ){

                die(
                    "Database connection failed."
                );
            }


            self::$connection
                ->set_charset(
                    "utf8mb4"
                );
        }


        return
            self::$connection;
    }
}