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
        Route::post('/profile', 'UserController@profile')->name('profile');
        Route::get('/nachrichten', 'UserController@nachrichten')->name('nachrichten');
        Route::get('/nachrichten/{message}', 'UserController@showMessage')->name('nachrichten.detail');
        Route::delete('/nachrichten/{message}', 'UserController@deleteMessage')->name('nachrichten.delete');
        Route::post('/nachrichten/delete_many', 'UserController@deleteMany')->name('nachrichten.delete_many');
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
    Route::delete('/exemptions/{exemption}', 'ExemptionController@destroy')->name('exemptions.destroy');
    Route::get('/exemptions/{exemption}', 'ExemptionController@show')->name('exemptions.show');

    //Routen für das Abschlussprojekt
    Route::group([
        'prefix' => 'abschlussprojekt',
        'as' => 'abschlussprojekt.',
    ], function() {
        Route::get('/', 'ProjectController@index')->name('index');
        //Route::post('/create', 'ProjectController@create')->name('create');
        Route::delete('/comment/{comment}', 'CommentController@delete')->name('delete_comment');

        Route::post('/abschnitte/{section}/sperren', 'SectionController@lock')->name('sections.lock');

        //Routen für den Projektantrag
        Route::group([
            'prefix' => '/{project}/antrag',
            'as' => 'antrag.',
        ], function() {
            Route::get('/', 'ProposalController@index')->name('index');
            Route::get('/create', 'ProposalController@create')->name('create');
            Route::patch('/{proposal}/lock', 'ProposalController@lock')->name('lock');
            Route::patch('/{proposal}/release', 'ProposalController@release')->name('release');
            Route::post('/', 'ProposalController@store')->name('store');
            Route::post('pdf', 'ProposalController@pdf')->name('pdf');
            Route::post('/{proposal}/comment', 'CommentController@addToProposal')->name('comment');

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
            Route::get('/create', 'DocumentationController@create')->name('create');
            Route::patch('/{documentation}/lock', 'DocumentationController@lock')->name('lock');
            Route::patch('/{documentation}/release', 'DocumentationController@release')->name('release');
            Route::post('/', 'DocumentationController@store')->name('store');
            Route::post('pdf', 'DocumentationController@pdf')->name('pdf');
            Route::post('/{documentation}/comment', 'CommentController@addToDocumentation')->name('comment');
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

        //Routen für das Verwalten von Versionen
        Route::group([
            'prefix' => '/{project}/{doc_type}/verlauf',
            'as' => 'versionen.',
        ], function() {
            Route::get('/', 'VersionController@index')->name('index');
            Route::post('/vergleich', 'VersionController@vergleich')->name('vergleich');
            Route::post('/use_version', 'VersionController@useVersion')->name('use_version');
            Route::delete('delete_version', 'VersionController@deleteVersion')->name('delete_version');
            Route::delete('/clear_history', 'VersionController@clearHistory')->name('clear_history');
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

        //Routen für das Verwalten von Hochzuladenden und Einzubindenden Dokumenten für die Dokumentation
        //Routen werden im Moment nicht verwendet, da die Funktionalität des Einbindens anderer PDF-Dokumente in eine
        //Abschlussdokumentation im Moment nicht implementiert ist und somit keine Dokumente hochgeladen und verwendet werden.
        Route::group([
            'prefix' => '/dokumente',
            'as' => 'dokumente.',
        ], function() {
            Route::post('/upload', 'UploadDocumentsController@upload')->name('upload');
        });

        Route::fallback(function() {
            return redirect(route('abschlussprojekt.index'));
        });
    });

    //Routen für das Bewerbungs-Toolkit
    Route::group([
        'prefix' => 'bewerbungen',
        'as' => 'bewerbungen.',
    ], function() {
        // Company Routes
        Route::get("/companies", "CompanyController@index")->name("companies.index");
        Route::get("/companies/create", "CompanyController@create")->name("companies.create");
        Route::post("/companies", "CompanyController@store")->name("companies.store");
        Route::get("/companies/{company}", "CompanyController@show")->name("companies.show");
        Route::get("/companies/{company}/edit", "CompanyController@edit")->name("companies.edit");
        Route::delete("/companies/{company}", "CompanyController@destroy")->name("companies.destroy");
        Route::patch("/companies/{company}", "CompanyController@update")->name("companies.update");

        // Review Routes
        Route::delete("/companies/{company}/reviews/{review}", "ReviewController@destroy")->name("reviews.destroy");
        Route::get("/companies/{company}/reviews/create", "ReviewController@create")->name("companies.reviews.create");
        Route::post("/companies/{company}/reviews", "ReviewController@store")->name("companies.reviews.store");
        Route::get("/companies/{company}/reviews/{review}/edit", "ReviewController@edit")->name("companies.reviews.edit");
        Route::patch("/companies/{company}/reviews/{review}", "ReviewController@update")->name("companies.reviews.update");

        // Resume Routes
        Route::get("/resumes", "ResumeController@index")->name("resumes.index");
        Route::post("/resumes/print", "ResumeController@print")->name("resumes.print");
        Route::post("/resumes/{user}", "ResumeController@store")->name("resumes.store");
        Route::get("/resumes/{user}", "ResumeController@show")->name("resumes.show");
        Route::post("/resumes/{user}/passbild", "ResumeController@uploadPassbild")->name("resumes.uploadPassbild");
        Route::delete("/resumes/{user}/passbild", "ResumeController@deletePassbild")->name("resumes.deletePassbild");
        Route::post("/resumes/{user}/signature", "ResumeController@uploadSignature")->name("resumes.uploadSignature");
        Route::delete("/resumes/{user}/signature", "ResumeController@deleteSignature")->name("resumes.deleteSignature");

        // Application Routes
        Route::get("/applications", "ApplicationController@index")->name("applications.index");
        Route::post("/companies/{company}/applications", "ApplicationController@store")->name("applications.store");
        Route::get("/applications/{application}/edit", "ApplicationController@edit")->name("applications.edit");
        Route::patch("/applications/{application}", "ApplicationController@update")->name("applications.update");
        Route::delete("/applications/{application}", "ApplicationController@destroy")->name("applications.destroy");
        Route::post("/applications/{application}/printNew", "ApplicationController@showNew")->name("applications.printNew");
        Route::get("/applications/templatesNew/{version?}", "ApplicationController@templatesNew")->name("applications.templatesNew");
    });
});

Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin', 'AdminDashboardController@index')->name('admin.index');

    Route::get('/admin/rules', 'AdminRulesController@edit')->name('admin.rules.edit');
    Route::patch('/admin/rules', 'AdminRulesController@update')->name('admin.rules.update');

    Route::group([
        'as' => 'admin.berichtshefte.',
        'prefix' => '/admin/berichtshefte',
    ], function() {
        Route::get('/index', 'AdminBerichtsheftController@index')->name('index');
        Route::get('/{azubi}/liste', 'AdminBerichtsheftController@liste')->name('liste');
        Route::get('{berichtsheft}/edit', 'BerichtsheftController@edit')->name('edit');
        Route::patch('{berichtsheft}', 'BerichtsheftController@update')->name('update');
        Route::delete('{berichtsheft}', 'BerichtsheftController@destroy')->name('destroy');
        Route::get('/{azubi}/create', 'AdminBerichtsheftController@create')->name('create');
        Route::post('/{azubi}', 'AdminBerichtsheftController@store')->name('store');
    });

    Route::get('/admin/exemptions', 'AdminExemptionController@index')->name('admin.exemptions.index');
    Route::get('/admin/exemptions/{exemption}/edit', 'AdminExemptionController@edit')->name('admin.exemptions.edit');
    Route::get('/admin/exemptions/{exemption}', 'AdminExemptionController@show')->name('admin.exemptions.show');
    Route::patch('/admin/exemptions/{exemption}', 'AdminExemptionController@update')->name('admin.exemptions.update');
    Route::delete('/admin/exemptions/{exemption}', 'AdminExemptionController@destroy')->name('admin.exemptions.destroy');

    Route::get('/admin/users', 'AdminUserController@index')->name('admin.users.index');
    Route::delete('/admin/users/{user}', 'AdminUserController@delete')->name('admin.users.delete');

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
            Route::get('/create', 'ProposalController@create')->name('create');
        });

        Route::group([
            'prefix' => '/{project}/dokumentation',
            'as' => 'dokumentation.',
        ], function() {
            Route::get('/', 'DocumentationController@index')->name('index');
            Route::get('/create', 'DocumentationController@create')->name('create');
        });

        //Routen für das Verwalten von Versionen
        Route::group([
            'prefix' => '/{project}/{doc_type}/verlauf',
            'as' => 'versionen.',
        ], function() {
            Route::get('/', 'VersionController@index')->name('index');
            Route::post('/vergleich', 'VersionController@vergleich')->name('vergleich');
            Route::post('/use_version', 'VersionController@useVersion')->name('use_version');
            Route::delete('delete_version', 'VersionController@deleteVersion')->name('delete_version');
            Route::delete('/clear_history', 'VersionController@clearHistory')->name('clear_history');
        });

        Route::fallback(function() {
            return redirect(route('admin.abschlussprojekt.index'));
        });
    });

    Route::group([
        'as' => 'admin.bewerbungen.',
        'prefix' => '/admin/bewerbungen',
    ], function() {
        Route::group([
            'as' => 'categories.',
            'prefix' => '/categories',
        ], function() {
            Route::get("/", "AdminCategoryController@index")->name("index");
            Route::post("/", "AdminCategoryController@store")->name("store");
            Route::get("/create", "AdminCategoryController@create")->name("create");
            Route::get("/{category}/edit", "AdminCategoryController@edit")->name("edit");
            Route::patch("/{category}", "AdminCategoryController@update")->name("update");
            Route::delete("/{category}", "AdminCategoryController@destroy")->name("destroy");
        });

        Route::group([
            'as' => 'templates.',
            'prefix' => '/templates',
        ], function() {
            Route::get('/', 'AdminTemplateController@index')->name('index');
            Route::patch('/new', 'AdminTemplateController@updateNew')->name('updateNew');
            Route::patch('/defaultNew', 'AdminTemplateController@restoreDefaultNew')->name('restoreDefaultNew');
            Route::get('/versionen', 'AdminTemplateController@versionen')->name('versionen');
            Route::delete('/unused', 'AdminTemplateController@deleteUnused')->name('deleteUnused');
            Route::delete('/all', 'AdminTemplateController@deleteAll')->name('deleteAll');
            Route::delete('/versionen/{tpl}', 'AdminTemplateController@delete')->name('delete');
        });
    });

    Route::fallback(function() {
        return redirect(route('admin.index'));
    });
});

Route::fallback(function() {
    return redirect(route('home'));
});
