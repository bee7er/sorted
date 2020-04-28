<?php

use Illuminate\Support\Facades\Auth;
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

# Front end
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index');
Route::post('/', 'HomeController@validateForm');

Route::get('/about/', function() {
    return view('about');
});

Route::get('/api/is-valid/{sortCode}/{accountNumber}', 'API\AccountValidatorController@isValid');

# Add all the login and registration routes, although here I am avoiding the
# registration of new users and the reset of a new password
Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

# Admin dashboard
Route::get('/admin', 'Admin\AdminController@index')->name('admin');
# Sort Code data
Route::get('/substitutes/refresh', 'Admin\SubstitutesController@refreshData');
Route::get('/weights/refresh', 'Admin\WeightsController@refreshData');
