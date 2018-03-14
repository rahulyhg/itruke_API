<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
// see function `addRoute` at app/bootstrap/functions.php
add_route('data');
add_route('admin');
add_route('github');
$app->get('/wx', 'WxController@serve');
$app->post('/wx', 'WxController@serve');
