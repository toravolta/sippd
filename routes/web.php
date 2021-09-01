<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']],
    function () {
        Route::resource('roles', 'RoleController');
        Route::resource('users', 'UserController');
        Route::resource('permission', 'PermissionController');

        Route::get('/getUser', 'UserController@getUser')->name('user.getUser');
        Route::get('/getRole', 'RoleController@getRole')->name('role.getRole');
        Route::get('/getPermision', 'PermissionController@getPermission')->name('permission.getPermission');
        Route::get('/getGroup', 'PermissionController@getGroup')->name('permission.getGroup');
        Route::get('/getGroup/{id}', 'PermissionController@getGroupById')->name('permission.getGroup');
    }
);
