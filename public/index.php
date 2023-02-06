<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Core\Route;

require_once __DIR__ . '/../app/routes.php';

Route::dispatch();