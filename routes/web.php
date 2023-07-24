<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Auth\AuthController@formLogin')->name('formLogin');
Route::post('/auth/doLogin', 'Auth\AuthController@doLogin')->name('doLogin');

Route::middleware(['auth'])->group(function () {
    Route::get('/auth/logout', 'Auth\AuthController@logout')->name('logout');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        // Users
        Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
            Route::get('/', 'Settings\UsersController@index')->name('index')->middleware('permissions:view');
            Route::get('/get-data', 'Settings\UsersController@getData')->name('getData')->middleware('permissions:view');
            Route::get('/create', 'Settings\UsersController@create')->name('create')->middleware('permissions:add');
            Route::post('/store', 'Settings\UsersController@store')->name('store')->middleware('permissions:add');
            Route::get('/{id}', 'Settings\UsersController@detail')->where('id', '[0-9]+')->name('detail')->middleware('permissions:edit');
            Route::post('/{id}', 'Settings\UsersController@update')->where('id', '[0-9]+')->name('update')->middleware('permissions:edit');
            Route::delete('/{id}', 'Settings\UsersController@destroy')->where('id', '[0-9]+')->name('destroy')->middleware('permissions:delete');
        });
        // End Users
        // Menus
        Route::group(['prefix' => 'menus', 'as' => 'menus.'], function () {
            Route::get('/', 'Settings\MenusController@index')->name('index')->middleware('permissions:view');
            Route::get('/create', 'Settings\MenusController@create')->name('create')->middleware('permissions:add');
            Route::post('/store', 'Settings\MenusController@store')->name('store')->middleware('permissions:add');
            Route::get('/{id}', 'Settings\MenusController@detail')->where('id', '[0-9]+')->name('detail')->middleware('permissions:edit');
            Route::post('/{id}', 'Settings\MenusController@update')->where('id', '[0-9]+')->name('update')->middleware('permissions:edit');
            Route::delete('/{id}', 'Settings\MenusController@destroy')->where('id', '[0-9]+')->name('destroy')->middleware('permissions:delete');

            Route::get('/list-roles', 'Settings\MenusController@listRoles')->name('listRoles');
            Route::post('check-exist-url', 'Settings\MenusController@checkExistUrl')->name('checkExistUrl');
        });
        // End Menus
        // Roles
        Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
            Route::get('/', 'Settings\RolesController@index')->name('index')->middleware('permissions:view');
            Route::get('/get-data', 'Settings\RolesController@getData')->name('getData')->middleware('permissions:view');
            Route::get('/create', 'Settings\RolesController@create')->name('create')->middleware('permissions:view');
            Route::post('/store', 'Settings\RolesController@store')->name('store')->middleware(['permissions:add']);
            Route::get('/{id}', 'Settings\RolesController@detail')->where('id', '[0-9]+')->name('detail')->middleware('permissions:view');
            Route::post('/{id}', 'Settings\RolesController@update')->where('id', '[0-9]+')->name('update')->middleware(['permissions:edit']);
            Route::delete('/{id}', 'Settings\RolesController@destroy')->where('id', '[0-9]+')->name('destroy')->middleware(['permissions:delete']);
        });
        // End Roles
    });
});
