<?php

session_start();

require_once
    __DIR__
    . '/../../app/controllers/AuthController.php';

require_once
    __DIR__
    . '/../../app/models/RoomModel.php';


AuthController
    ::requireReceptionist();


header(
    'Content-Type: application/json'
);


$typeId =
    (int)(
        $_GET['type']
        ?? 0
    );


$model =
    new RoomModel();


echo json_encode(
    $model
        ->getAvailableByType(
            $typeId
        )
);