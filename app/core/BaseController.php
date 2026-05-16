<?php

class BaseController
{
    protected function view(
        string $path,
        array $data = []
    ): void {

        extract(
            $data
        );


        require
            __DIR__
            .
            '/../views/'
            .
            $path
            .
            '.php';
    }
}