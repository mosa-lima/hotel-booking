<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../core/BaseController.php';

class AuthController extends BaseController
{
    public function login(): void
    {
        $email =
            filter_input(
                INPUT_POST,
                'email',
                FILTER_SANITIZE_EMAIL
            );

        $password =
            $_POST['password'] ?? '';

        $model =
            new UserModel();

        $user =
            $model->findByEmail(
                $email
            );

        if (
            !$user ||
            !password_verify(
                $password,
                $user['password_hash']
            )
        ) {

            die("Invalid credentials.");
        }

        session_regenerate_id(true);

        $_SESSION['user_id']
            = $user['id'];

        $_SESSION['role']
            = $user['role'];

        $_SESSION['name']
            = $user['name'];

        header(
            "Location: /hotel-booking/public/dashboard"
        );

        exit;
    }



    public static function
    requireReceptionist(): void
    {
        if (
            !isset($_SESSION['role']) ||
            $_SESSION['role']
                !== 'receptionist'
        ) {

            http_response_code(403);

            exit(
                "Access denied."
            );
        }
    }



    public function logout(): void
    {
        session_destroy();

        header(
            "Location: /hotel-booking/public/"
        );

        exit;
    }
}