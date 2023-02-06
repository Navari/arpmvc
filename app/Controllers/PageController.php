<?php

namespace App\Controllers;

use App\Core\View;

class PageController
{
    public function index()
    {
        View::show('index');
    }

    public function folder1()
    {
        View::show('folder1');
    }

    public function folder2()
    {
        View::show('folder2');
    }
}