<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

use Illuminate\Support\Facades\Log;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get("test-path-param/{tahun}/{bulan}", function($tahun, $bulan) {
    $result = (object)array(
        "tahun" => $tahun,
        "bulan" => $bulan
    );

    return response()->json($result);
});

$router->get("test-query-param", function() {
    $result = (object)array(
        "first_param" => request("first_param"),
        "second_param" => request("second_param")
    );

    return response()->json($result);
});

$router->post("test-post", function() {
    $first_param = request("first_param");
    
    if (!isset($first_param)) {
        $first_param = json_decode(request()->getContent())->first_param;
    }

    $second_param = request("second_param");

    if (!isset($second_param)) {
        $second_param = json_decode(request()->getContent())->second_param;
    }

    $result = (object)array(
        "first_param" => $first_param,
        "second_param" => $second_param
    );

    Log::debug("Log Test");

    return response()->json($result);
});