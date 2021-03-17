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

$router->get("get-all-accounts", function() {
    $data = DB::table("accounts")->get();

    return response()->json($data);
});

$router->get("try-postgres", function() {
    $host = getenv('POSTGRES_DB_HOST');
    $port = getenv('POSTGRES_DB_PORT');
    $name = getenv('POSTGRES_DB_DATABASE');
    $user = getenv('POSTGRES_DB_USERNAME');
    $password = getenv('POSTGRES_DB_PASSWORD');

    $dbconn = pg_connect("host=" . $host . " port=" . $port . " dbname=" . $name . " user=" . $user . " password=" . $password);

    if (!$dbconn) {
        $result = (object)array(
            "api_status" => 0,
            "api_message" => "Failed on connecting to database"
        );
    
        return response()->json($result);
    }

    $data = pg_fetch_all(pg_query($dbconn, "SELECT * FROM accounts;"));

    $result = (object)array(
        "api_status" => 1,
        "api_message" => "Success retrieving data",
        "data" => $data
    );

    return response()->json($result);
});

$router->get("info", function() {
    phpinfo();
});