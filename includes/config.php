<?php

declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';
const DB_NAME = 'hotel_housekeeping';
const DB_USER = 'root';
const DB_PASS = '';

const APP_NAME = 'Hotel Housekeeping Supervisor';
const APP_TIMEZONE = 'Asia/Dhaka';

date_default_timezone_set(APP_TIMEZONE);
session_start();
