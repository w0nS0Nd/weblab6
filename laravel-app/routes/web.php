<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "Laravel + Keycloak lab";
});

// заглушка, щоб Laravel не падав
Route::get('/login', function () {
    return response()->json(['message' => 'Login handled by Keycloak'], 401);
})->name('login');
