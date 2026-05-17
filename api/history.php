<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

require_login(true);

$roomId = (int) get_value('room_id');

json_response([
    'success' => true,
    'history' => fetch_room_history(db(), $roomId),
]);
