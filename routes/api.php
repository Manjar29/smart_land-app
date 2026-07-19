<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

 

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/bd-districts', function () {
    return \Illuminate\Support\Facades\Http::get('https://bdapis.com/api/v1.2/districts')->json();
});

Route::get('/bd-upazilas/{district}', function ($district) {
    return \Illuminate\Support\Facades\Http::get("https://bdapis.com/api/v1.2/district/{$district}")->json();
});
