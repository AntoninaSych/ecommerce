<?php

use Illuminate\Support\Facades\Route;

Route::get('/elastic', 'ElasticController@index');


Route::get('/', function () {
    return view('welcome');
});


// Required App Health Check for monitoring availability - https://bitbucket.org/geiger-it/development-guidelines/
Route::get('check-status', function () {
    return 'OK';
});
