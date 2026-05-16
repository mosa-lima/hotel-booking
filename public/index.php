<?php

session_start();

require_once
    "../app/controllers/AuthController.php";
require_once
    __DIR__
    . '/../app/controllers/ReceptionistController.php';
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

        case 'dashboard':

    (
        new ReceptionistController()
    )->dashboard();

    break;


    default:

        echo "404";
}