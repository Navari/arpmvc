<?php
use App\Core\Route;

Route::get('/', 'PageController@index');
Route::get('/folder1', 'PageController@folder1');
Route::get('/folder2', 'PageController@folder2');