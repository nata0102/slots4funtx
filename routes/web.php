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

/*Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
*/

  Route::get('/',['as'=>'login','uses'=>'MainController@index']);
  Route::post('/login', 'MainController@login');
  Route::post('/logout', 'MainController@logout');

  Route::get('lang/{lang}', 'LanguageController@swap')->name('lang.swap');


Route::group(['middleware' => ['auth','admin','web']], function() {
  Route::resource("machines",'MachineController');
  Route::resource("parts",'PartController');
  Route::resource("lookups",'LookupController');
  Route::resource("machine-brands",'MachineBrandController');
  Route::resource("permissions",'PermissionController');
  Route::get("permissions_rank",'PermissionController@createByRank');
  Route::post("permissions_store_rank",'PermissionController@storeByRank');
  Route::resource("percentage_price",'PercentagePriceController');
  Route::resource("clients","ClientController");
  Route::resource("address","AddressController");
});
