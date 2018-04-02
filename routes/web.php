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

use Facades\Chord\Domain\User\Services\UserCsvService;

Route::get('/', 'Home\Controllers\HomeController@dashboard');

Route::group(['namespace' => 'Auth\Controllers'], function (){
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');
});

Route::get('/home', 'Home\Controllers\HomeController@index')->name('home');
Route::get('/home/csv', 'Home\Controllers\HomeController@getUsersCsv')->name('download.users.csv');
/*
Route::post('/reactions', 'User\Controllers\ReactionController@store');
Route::delete('/reactions', 'User\Controllers\ReactionController@remove');
*/
Route::get('/postcodes/{postcode}', 'Postcode\Controllers\PostcodeController@index');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/houses', 'House\Controllers\ListHousesController@index')->name('houses');
    Route::put('/houses/swap', 'House\Controllers\SwapHousesController@index');

    Route::post('/reactions', 'User\Controllers\ReactionController@store');
    Route::delete('/reactions', 'User\Controllers\ReactionController@remove');

    Route::get('/chats', 'User\Controllers\ChatController@getChat');
});


Route::get('/userchats', function(){

    $result = UserCsvService::generateCsv();
    return 'gotovo';
    die();
    $userDetails = UserCsvRepository::getUsersDetails()->toArray();

    foreach ($userDetails as $key => $r){
        var_dump($key, $r[0]);
    }

    $givenLikes = UserCsvRepository::geGivenLikes();
    $receivedLikes = UserCsvRepository::getReceivedLikes();
    $matches = UserCsvRepository::getUserMatches();
    $userChats = UserCsvRepository::getUserChats();//nema indexa 0
    $numberOfPeople = UserCsvRepository::getPeople();
    $numberOfOldPeople = UserCsvRepository::getPeople(45);
    foreach ($numberOfPeople as $key => $r){
        var_dump($key, $r);
    }


});
