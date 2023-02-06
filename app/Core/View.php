<?php

namespace App\Core;

class View
{
    public static function show(string $view, array $data = []): void
    {
        $view = str_replace('.', '/', $view);
        $view = __DIR__ . '/../../views/' . $view . '.php';
        if (file_exists($view)) {
            extract($data);
            require_once $view;
        } else {
            echo 'View not found';
        }
    }
}