<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/change_password', 'Auth\ChangePasswordController@change_password');

    Route::get('/statistics', 'HomeController@statistics');

    Route::get('/delete_categories', 'CategoryController@delete_categories');
    Route::get('/delete_tag', 'CategoryController@delete_tag');
    Route::post('/add_categories', 'CategoryController@add_categories');
    Route::post('/update_categories', 'CategoryController@update_categories');

    Route::get('/delete_workouts', 'WorkoutController@delete_workouts');
    Route::get('/delete_weekday', 'WorkoutController@delete_weekday');
    Route::post('/add_workouts', 'WorkoutController@add_workouts');
    Route::post('/update_workouts', 'WorkoutController@update_workouts');

    Route::post('/workout_categories', 'WorkoutController@get_workout_categories');
    Route::post('/create_workout_category', 'WorkoutController@create_workout_category');
    Route::post('/delete_workout_category', 'WorkoutController@delete_workout_category');

    Route::get('/delete_exercises', 'ExerciseController@delete_exercises');
    Route::post('/update_exercises', 'ExerciseController@update_exercises');
    Route::post('/update_exercises_1', 'ExerciseController@update_exercises_1');
    Route::post('/add_exercises', 'ExerciseController@add_exercises');

    Route::get('/get_settings', 'SettingsController@get');
    Route::post('/update_settings', 'SettingsController@update');

});

Route::post('login', 'UserController@login');

Route::get('/get_categories', 'CategoryController@get_categories');
Route::get('/get_categories_1', 'CategoryController@get_categories_1');
Route::get('/get_categories_qrcode_checked', 'CategoryController@get_categories_qrcode_checked');
Route::get('/get_exercise_from_tag', 'CategoryController@get_exercise_from_tag');
Route::get('/get_category_content', 'CategoryController@get_category_content');

Route::get('/get_workouts', 'WorkoutController@get_workouts');
Route::get('/get_workouts_1', 'WorkoutController@get_workouts_1');
Route::get('/get_workout_content', 'WorkoutController@get_workout_content');
Route::get('/get_exercise_from_day', 'WorkoutController@get_exercise_from_day');

Route::get('/get_exercises', 'ExerciseController@get_exercises');
Route::get('/get_exercise_content', 'ExerciseController@get_exercise_content');
Route::get('/increase_views', 'ExerciseController@increase_views');

Route::get('/get_workout_categories', 'WorkoutController@get_workout_categories_for_app');
Route::get('/get_settings_app', 'SettingsController@get');
