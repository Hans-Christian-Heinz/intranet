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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::group(["middleware" => "auth"], function () {
    // Routen für Berichtsheft:
    Route::get("/berichtshefte", "BerichtsheftController@index")->name("berichtshefte.index");
    Route::get("/berichtshefte/create", "BerichtsheftController@create")->name("berichtshefte.create");
    Route::post("/berichtshefte", "BerichtsheftController@store")->name("berichtshefte.store");
    Route::get("/berichtshefte/{berichtsheft}/edit", "BerichtsheftController@edit")->name("berichtshefte.edit");
    Route::get("/berichtshefte/{berichtsheft}", "BerichtsheftController@show")->name("berichtshefte.show");
    Route::patch("/berichtshefte/{berichtsheft}", "BerichtsheftController@update")->name("berichtshefte.update");
    Route::delete("/berichtshefte/{berichtsheft}", "BerichtsheftController@destroy")->name("berichtshefte.destroy");

    // Routen für Werkstattregeln:
    Route::get("/rules", "RulesController@index")->name("rules.index");
});

Route::group(["middleware" => "admin"], function () {
    Route::get("/admin", "AdminDashboardController@index")->name("admin.index");
    
    Route::get("/admin/berichtshefte", "AdminBerichtsheftController@index")->name("admin.berichtshefte.index");
    
    Route::get("/admin/rules", "AdminRulesController@edit")->name("admin.rules.edit");
    Route::patch("/admin/rules", "AdminRulesController@update")->name("admin.rules.update");
    
    Route::get("/admin/exemptions", "AdminExemptionController@index")->name("admin.exemptions.index");
});
