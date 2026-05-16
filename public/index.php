<?php

session_start();

require_once
    __DIR__
    . '/../app/controllers/AuthController.php';

require_once
    __DIR__
    . '/../app/controllers/ReceptionistController.php';


$url =
    $_GET['url']
    ?? '';



if(
    preg_match(
        '/^checkin\/(\d+)$/',
        $url,
        $matches
    )
){

    (
        new ReceptionistController()
    )->showCheckin(
        (int)$matches[1]
    );

    exit;
}



if(
    preg_match(
        '/^checkout\/(\d+)$/',
        $url,
        $matches
    )
){

    (
        new ReceptionistController()
    )->checkout(
        (int)$matches[1]
    );

    exit;
}



if(
    preg_match(
        '/^cancel\/(\d+)$/',
        $url,
        $matches
    )
){

    (
        new ReceptionistController()
    )->cancel(
        (int)$matches[1]
    );

    exit;
}



switch ($url) {

    case '':

        require
            __DIR__
            . '/../app/views/auth/login.php';

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


    case 'checkin/process':

        (
            new ReceptionistController()
        )->processCheckin();

        break;


    default:

        echo "404";
}