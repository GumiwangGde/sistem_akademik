<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');  // Mengarahkan ke 'views/homepage.blade.php'
});
