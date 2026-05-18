<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

require_login(true);

json_response([
    'success' => true,
    'rooms' => fetch_room_board(db()),
]);
