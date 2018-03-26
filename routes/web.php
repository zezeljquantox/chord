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

use Illuminate\Support\Facades\Auth;

Route::get('/', 'Home\Controllers\HomeController@dashboard');

Route::group(['namespace' => 'Auth\Controllers'], function (){
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');
});

Route::get('/home', 'Home\Controllers\HomeController@index')->name('home');

Route::post('/reaction', 'User\Controllers\ReactionController@store');
Route::delete('/reaction', 'User\Controllers\ReactionController@remove');

Route::get('/postcodes/{postcode}', 'Postcode\Controllers\PostcodeController@index');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/houses', 'House\Controllers\ListHousesController@index')->name('houses');

    Route::post('/reaction', 'User\Controllers\ReactionController@store');
    Route::delete('/reaction', 'User\Controllers\ReactionController@remove');

    Route::get('/chat', 'User\Controllers\ChatController@getChat');
});

Route::get('/broadcast', function(){
    event(new \Chord\Domain\User\Events\UserLikedHouse('first'));
});
