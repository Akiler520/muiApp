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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'user'], function () use ($router){
    $router->post('login', 'UserController@login');
});

$router->group(['prefix' => 'article'], function () use ($router){
    $router->post('list',           'ArticleController@getList');
});

$router->group(['middleware'=>'permission'], function () use ($router){

    $router->group(['prefix' => 'user'], function () use ($router){
        $router->post('list',           'UserController@getList');
        $router->post('create',         'UserController@create');
        $router->post('update/{id}',    'UserController@update');
        $router->post('delete/{id}',    'UserController@delete');
        $router->post('message',        'UserController@message');
    });

    $router->group(['prefix' => 'article'], function () use ($router){
//        $router->post('list',           'ArticleController@getList');
        $router->post('create',         'ArticleController@create');
        $router->post('update/{id}',    'ArticleController@update');
        $router->post('delete/{id}',    'ArticleController@delete');
        $router->post('share/{id}',     'ArticleController@share');
    });

    $router->group(['prefix' => 'articleImage'], function () use ($router){
        $router->post('delete/{id}',    'ArticleImageController@delete');
    });
});