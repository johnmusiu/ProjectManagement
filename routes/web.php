<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/mytasks', 'HomeController@usertasks')->name('my_tasks');

Route::get('/task/create', 'TasksController@index')->name('create_task');
Route::post('/task/create', 'TasksController@create')->name('save_task');
Route::get('/task/{task}', 'TasksController@view_details')->name('view_task');
Route::post('/task/{task}', 'TasksController@edit')->name('edit_task');
Route::post('/task/mark-ongoing/{task}', 'TasksController@mark_ongoing')->name('mark_ongoing');
Route::post('/task/follow/{task}', 'TasksController@follow')->name('follow_task');

Route::get('/category/create', 'CategoriesController@index')->name('create_category');
Route::post('/category/create', 'CategoriesController@create')->name('save_category');

