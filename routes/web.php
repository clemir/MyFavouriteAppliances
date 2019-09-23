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

Route::get('/', 'AppliancesListController@index')->name('home');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::post('/favorite/{appliance}', 'FavoriteController@favorite')
        ->name('favorite');
    Route::delete('/favorite/{appliance}', 'FavoriteController@destroy')
        ->name('favorite.destroy');
    Route::get('/my-wishlist', 'AppliancesListController@index')
        ->name('my-wishlist');
    Route::get('share', 'ShareWishlistController@create')
        ->name('share.create');
    Route::post('share', 'ShareWishlistController@store')
        ->name('share.send');
});

Route::get('users/{user}/wishlist', 'AppliancesListController@index')
    ->name('user-appliances');

Route::get('mail', function () {
    return new App\Mail\SendWishlist();
});
