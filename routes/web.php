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

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => 'auth'], function () {
    // Routen für Berichtsheft:
    Route::get('/berichtshefte', 'BerichtsheftController@index')->name('berichtshefte.index');
    Route::get('/berichtshefte/create', 'BerichtsheftController@create')->name('berichtshefte.create');
    Route::post('/berichtshefte', 'BerichtsheftController@store')->name('berichtshefte.store');
    Route::get('/berichtshefte/{berichtsheft}/edit', 'BerichtsheftController@edit')->name('berichtshefte.edit');
    Route::get('/berichtshefte/{berichtsheft}', 'BerichtsheftController@show')->name('berichtshefte.show');
    Route::patch('/berichtshefte/{berichtsheft}', 'BerichtsheftController@update')->name('berichtshefte.update');
    Route::delete('/berichtshefte/{berichtsheft}', 'BerichtsheftController@destroy')->name('berichtshefte.destroy');

    // Routen für Werkstattregeln:
    Route::get('/rules', 'RulesController@index')->name('rules.index');
    Route::patch('/rules', 'RulesController@accept')->name('rules.accept');

    // Routen für Freistellungen:
    Route::get('/exemptions', 'ExemptionController@index')->name('exemptions.index');
    Route::get('/exemptions/create', 'ExemptionController@create')->name('exemptions.create');
    Route::post('/exemptions', 'ExemptionController@store')->name('exemptions.store');
    Route::get('/exemptions/{exemption}/edit', 'ExemptionController@edit')->name('exemptions.edit');
    Route::patch('/exemptions/{exemption}', 'ExemptionController@update')->name('exemptions.update');
    Route::delete('/exemptions/{berichtsheft}', 'ExemptionController@destroy')->name('exemptions.destroy');
});

Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin', 'AdminDashboardController@index')->name('admin.index');

    Route::get('/admin/rules', 'AdminRulesController@edit')->name('admin.rules.edit');
    Route::patch('/admin/rules', 'AdminRulesController@update')->name('admin.rules.update');

    Route::get('/admin/exemptions', 'AdminExemptionController@index')->name('admin.exemptions.index');
    Route::get('/admin/exemptions/{exemption}/edit', 'AdminExemptionController@edit')->name('admin.exemptions.edit');
    Route::get('/admin/exemptions/{exemption}', 'AdminExemptionController@show')->name('admin.exemptions.show');
    Route::patch('/admin/exemptions/{exemption}', 'AdminExemptionController@update')->name('admin.exemptions.update');
    Route::delete('/admin/exemptions/{berichtsheft}', 'AdminExemptionController@destroy')->name('admin.exemptions.destroy');

    Route::get('/admin/users', 'AdminUserController@index')->name('admin.users.index');
    Route::get('/admin/users/{user}/promote', 'AdminUserController@promote')->name('admin.users.promote');
    Route::get('/admin/users/{user}/demote', 'AdminUserController@demote')->name('admin.users.demote');
});
