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
Route::post('/task/progress/{task}', 'TasksController@save_progress')->name('save_progress');
Route::get('/my_tasks', 'TasksController@user_tasks')->name('user_tasks');

Route::post('/task/comment/{task_id}', 'CommentController@comment')->name('save_comment');
// Route::get('/task/comment', 'CommentController@index')->name('comment');

Route::get('/category/create', 'CategoriesController@index')->name('create_category');
Route::post('/category/create', 'CategoriesController@create')->name('save_category');

Route::post('/task/reminder/{task_id}', 'RemindersController@save')->name('save_reminder');
Route::get('/task/reminder/{task}', 'RemindersController@index')->name('create_reminder');

Route::post('/user/follow/{user}', 'UsersController@follow')->name('follow_user');
Route::post('/user/unfollow/{user}', 'UsersController@unfollow')->name('unfollow_user');

Route::get('/download/{file}', 'TasksController@download')->name('download_file');

Route::post('/user/reports/daily', 'ReportsController@daily_user_report')->name('daily_user_report');
Route::post('/user/reports/weekly', 'ReportsController@weekly_user_report')->name('weekly_user_report');
Route::post('/department/reports/weekly', 'ReportsController@weekly_dept_report')->name('weekly_dept_report');