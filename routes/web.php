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
  //Route::get('read_excel','Controller@readExcel');


  Route::get('lang/{lang}', 'LanguageController@swap')->name('lang.swap');

Route::group(['middleware' => ['auth','web']], function() {
  Route::resource("machines",'MachineController');
  Route::resource("parts",'PartController');
  Route::get("parts_rank",'PartController@createByRank');
  Route::post("parts_store_rank",'PartController@storeByRank');
  Route::resource("lookups",'LookupController');
  Route::resource("machine-brands",'MachineBrandController');
  Route::resource("permissions",'PermissionController');
  Route::get("permissions_rank",'PermissionController@createByRank');
  Route::post("permissions_store_rank",'PermissionController@storeByRank');
  Route::resource("percentage_price",'PercentagePriceController');
  Route::resource("clients","ClientController");
  Route::resource("address","AddressController");
  Route::resource("game_catalog","GameCatalogController");
  Route::get("/parts/gallery/{id}",'PartController@gallery');
  Route::delete('/parts/delete-image/{id}', 'PartController@deleteImage');
  Route::post('/parts/create-image/{id}', 'PartController@createImage');
  Route::resource("image_part_brand",'ImagePartBrandController');
  Route::resource("users","UserController");
  Route::resource("charges","ChargesController");
  Route::post("charges/store_data","ChargesController@storeData");
  Route::post("charges/store_initial_numbers","ChargesController@storeInitialNumbers");
  Route::get('/charges/delete/{key}', 'ChargesController@deleteData');

  Route::get('/roles-configuration/{rol?}', 'RoleConfigurationController@index');
  Route::post('/roles-configuration-save', 'RoleConfigurationController@rolesConfigurationSave');
});
