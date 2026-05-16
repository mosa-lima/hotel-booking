<?php

class BaseController
{
    protected function view(
        string $path,
        array $data = []
    ): void {

        extract($data);

        require "../app/views/$path.php";
    }
}