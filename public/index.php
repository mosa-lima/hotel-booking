<?php

session_start();

require_once
    "../app/controllers/AuthController.php";

$url =
    $_GET['url']
    ?? '';

switch ($url) {

    case '':

        require
            "../app/views/auth/login.php";

        break;


    case 'login':

        (
            new AuthController()
        )->login();

        break;


    case 'logout':

        (
            new AuthController()
        )->logout();

        break;


    default:

        echo "404";
}