<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\SeedApiController;
use Illuminate\Routing\Route;

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('auth/api/register', ['uses' => 'AuthApiController@register']);
$router->post('auth/api/login', ['uses' => 'AuthApiController@login']);
$router->post('auth/api/logout', ['uses' => 'AuthApiController@logout']);
$router->get('auth/api/me', ['uses' => 'AuthApiController@me']);


$router->group(['middleware' => 'auth:api', 'prefix' => 'api'], function () use ($router) {

    $router->get('seeds',  ['uses' => 'SeedApiController@showAllSeed']);
    $router->post('seeds', ['uses' => 'SeedApiController@create']);
    $router->put('seeds/{id}', ['uses' => 'SeedApiController@update']);
    $router->delete('seeds/{id}', ['uses' => 'SeedApiController@destroy']);

    $router->get('seeds/user/{id}', ['uses' => 'UserSeedApiController@showAllUserSeed']);
    $router->get('seeds/user/{id}/last', ['uses' => 'UserSeedApiController@showLastSeed']);
    $router->post('seeds/user/{id}', ['uses' => 'UserSeedApiController@createNewRandomSeed']);

});