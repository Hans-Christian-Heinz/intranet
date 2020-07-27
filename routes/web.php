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

use App\Project;

Route::get('/', 'HomeController@index')->name('home');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => 'auth'], function () {
    //Routen für die Benutzerverwaltung
    Route::group([
        'prefix' => 'user',
        'as' => 'user.',
    ], function() {
        Route::post('/address', 'UserController@address')->name('address');
    });

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

    //Routen für das Abschlussprojekt
    Route::group([
        'prefix' => 'abschlussprojekt',
        'as' => 'abschlussprojekt.',
    ], function() {
        Route::get('/', 'ProjectController@index')->name('index');
        Route::post('/create', 'ProjectController@create')->name('create');
        //todo
        //Route::patch('{project}/update', 'ProjectController@update')->name('update');

        //Routen für den Projektantrag
        Route::group([
            'prefix' => '/{project}/antrag',
            'as' => 'antrag.',
        ], function() {
            Route::get('/', 'ProposalController@index')->name('index');
            //TODO: sollte keine get Route sein.
            Route::get('/create', 'ProposalController@create')->name('create');
            Route::patch('/{proposal}/lock', 'ProposalController@lock')->name('lock');
            Route::patch('/{proposal}/release', 'ProposalController@release')->name('release');
            Route::post('/', 'ProposalController@store')->name('store');
            Route::get('/history', 'ProposalController@history')->name('history');
            Route::post('/vergleich', 'ProposalController@vergleich')->name('vergleich');
            Route::post('/use_version', 'ProposalController@useVersion')->name('use_version');
            Route::delete('delete_version', 'ProposalController@deleteVersion')->name('delete_version');
            Route::delete('/clear_history', 'ProposalController@clearHistory')->name('clear_history');
            Route::post('pdf', 'ProposalController@pdf')->name('pdf');

            Route::fallback(function($project) {
                return redirect(route('abschlussprojekt.antrag.index', $project));
            });
        });

        //Routen für die Projektdokumentation
        Route::group([
            'prefix' => '/{project}/dokumentation',
            'as' => 'dokumentation.',
        ], function() {
            Route::get('/', 'DocumentationController@index')->name('index');
            //TODO: sollte keine get Route sein.
            Route::get('/create', 'DocumentationController@create')->name('create');
            Route::patch('/{documentation}/lock', 'DocumentationController@lock')->name('lock');
            Route::patch('/{documentation}/release', 'DocumentationController@release')->name('release');
            Route::post('/', 'DocumentationController@store')->name('store');
            Route::get('/history', 'DocumentationController@history')->name('history');
            Route::post('/vergleich', 'DocumentationController@vergleich')->name('vergleich');
            Route::post('/use_version', 'DocumentationController@useVersion')->name('use_version');
            Route::delete('delete_version', 'DocumentationController@deleteVersion')->name('delete_version');
            Route::delete('/clear_history', 'DocumentationController@clearHistory')->name('clear_history');
            Route::post('pdf', 'DocumentationController@pdf')->name('pdf');
            //Routen für die Abschnitte der Dokumentation
            Route::group([
                'prefix' => '/abschnitte',
                'as' => 'abschnitte.'
            ], function() {
                Route::post('/', 'SectionController@create')->name('create');
                Route::delete('/{section}', 'SectionController@delete')->name('delete');
                Route::post('/{section}/edit', 'SectionController@edit')->name('edit');
            });
            //Routen für die Bilder der Dokumentation
            Route::group([
                'prefix' => '/images',
                'as' => 'images.'
            ], function() {
                Route::post('/', 'DocumentationController@addImage')->name('create');
                Route::delete('/', 'DocumentationController@detachImage')->name('detach');
                Route::post('/update', 'DocumentationController@updateImage')->name('update');
            });

            Route::fallback(function($project) {
                return redirect(route('abschlussprojekt.dokumentation.index', $project));
            });
        });

        //Routen für das Verwalten von Bilddateien für die Dokumentation
        Route::group([
            'prefix' => '/{project}/bilder',
            'as' => 'bilder.',
        ], function () {
            Route::get('/', 'ImageController@index')->name('index');
            Route::post('/upload', 'ImageController@upload')->name('upload');
            Route::delete('/', 'ImageController@delete')->name('delete');
        });

        Route::fallback(function() {
            return redirect(route('abschlussprojekt.index'));
        });
    });
});

Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin', 'AdminDashboardController@index')->name('admin.index');

    Route::get('/admin/rules', 'AdminRulesController@edit')->name('admin.rules.edit');
    Route::patch('/admin/rules', 'AdminRulesController@update')->name('admin.rules.update');

    Route::get('/admin/exemptions', 'AdminExemptionController@index')->name('admin.exemptions.index');
    Route::get('/admin/exemptions/{exemption}/edit', 'AdminExemptionController@edit')->name('admin.exemptions.edit');
    Route::get('/admin/exemptions/{exemption}', 'AdminExemptionController@show')->name('admin.exemptions.show');
    Route::patch('/admin/exemptions/{exemption}', 'AdminExemptionController@update')->name('admin.exemptions.update');
    Route::delete('/admin/exemptions/{exemption}', 'AdminExemptionController@destroy')->name('admin.exemptions.destroy');

    Route::get('/admin/users', 'AdminUserController@index')->name('admin.users.index');
    Route::patch('/admin/users/{user}/promote', 'AdminUserController@promote')->name('admin.users.promote');
    Route::patch('/admin/users/{user}/demote', 'AdminUserController@demote')->name('admin.users.demote');

    //Routen für das Abschlussprojekt auf Ausbilderseite
    Route::group([
        'prefix' => '/admin/abschlussprojekt',
        'as' => 'admin.abschlussprojekt.',
    ], function() {
        Route::get('/', 'AdminProjectController@index')->name('index');
        Route::patch('/{project}', function (Project $project) {
            $project->supervisor()->associate(app()->user);
            $project->save();

            return redirect()->back()->with('status', 'Sie wurden zu dem Projekt als Betreuer eingetragen.');
        })->name('betreuer');

        Route::group([
            'prefix' => '/{project}/antrag',
            'as' => 'antrag.',
        ], function() {
            Route::get('/', 'ProposalController@index')->name('index');
            //TODO: sollte keine get Route sein.
            Route::get('/create', 'ProposalController@create')->name('create');
            Route::get('/history', 'ProposalController@history')->name('history');
            Route::post('/vergleich', 'ProposalController@vergleich')->name('vergleich');
            Route::post('/use_version', 'ProposalController@useVersion')->name('use_version');
            Route::delete('delete_version', 'ProposalController@deleteVersion')->name('delete_version');
        });

        Route::group([
            'prefix' => '/{project}/dokumentation',
            'as' => 'dokumentation.',
        ], function() {
            Route::get('/', 'DocumentationController@index')->name('index');
            //TODO: sollte keine get Route sein.
            Route::get('/create', 'DocumentationController@create')->name('create');
            Route::get('/history', 'DocumentationController@history')->name('history');
            Route::post('/vergleich', 'DocumentationController@vergleich')->name('vergleich');
            Route::post('/use_version', 'DocumentationController@useVersion')->name('use_version');
            Route::delete('delete_version', 'DocumentationController@deleteVersion')->name('delete_version');
        });

        Route::fallback(function() {
            return redirect(route('admin.abschlussprojekt.index'));
        });
    });

    Route::fallback(function() {
        return redirect(route('admin.index'));
    });
});

Route::fallback(function() {
    return redirect(route('home'));
});
