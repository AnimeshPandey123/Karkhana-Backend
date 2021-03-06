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
$router->post('v1/login/email', [
    'as' => 'login.email', 'uses' => 'Login\LoginController@login'
]);

$router->get('v1/test', [
    'as' => 'test', 'uses' => 'TestController@test'
]);

$router->group(['prefix' => 'v1/pages'], function () use ($router) {
    $router->get('/{page}', [
    	'as' => 'pages', 'uses' => 'Pages\PageController@index'
	]);
});

$router->get('/languages', [
    'as' => 'lanugages',
    'uses' => 'LanguageController@index',
]);

$router->get('/roles', [
    'middleware' => ['auth'],
    'as' => 'roles.list',
    'uses' => 'Roles\RolesController@listRoles',
]);

$router->get('/roles/auth', [
    'middleware' => ['auth'],
    'as' => 'roles.list',
    'uses' => 'Roles\RolesController@getUserRoles',
]);

$router->post('/me', [
    'middleware' => ['auth'],
    'as' => 'user.info',
    'uses' => 'Profile\ProfilesController@me',
]);

$router->post('/profile/edit', [
    'middleware' => ['auth'],
    'as' => 'profile.edit',
    'uses' => 'Profile\ProfilesController@edit',
]);

$router->get('/guests', [
    'as' => 'guest.index',
    'uses' => 'Users\GuestAuthorsController@index',
]);

$router->post('/mail', [
    'as' => 'mail.send',
    'uses' => 'DropALine\DropALineMailController@send',
]);

$router->get('/team', [
    'as' => 'team.all',
    'uses' => 'Profile\TeamController@list',
]);

$router->get('/team/{id}', [
    'as' => 'team.index',
    'uses' => 'Profile\TeamController@index',
]);