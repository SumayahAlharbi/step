<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Teams
    Route::delete('teams/destroy', 'TeamController@massDestroy')->name('teams.massDestroy');
    Route::resource('teams', 'TeamController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Strategic Plans
    Route::delete('strategic-plans/destroy', 'StrategicPlansController@massDestroy')->name('strategic-plans.massDestroy');
    Route::get('strategic-plans/archive/{strategic_plan}', 'StrategicPlansController@archive')->name('strategic-plans.archive');
    Route::get('strategic-plans/restore', 'StrategicPlansController@archiveList')->name('strategic-plans.archiveList');
    Route::get('strategic-plans/restore/{strategic_plan}', 'StrategicPlansController@restore')->name('strategic-plans.restore');
    Route::post('strategic-plans/media', 'StrategicPlansController@storeMedia')->name('strategic-plans.storeMedia');
    Route::post('strategic-plans/ckmedia', 'StrategicPlansController@storeCKEditorImages')->name('strategic-plans.storeCKEditorImages');
    Route::post('strategic-plans/parse-csv-import', 'StrategicPlansController@parseCsvImport')->name('strategic-plans.parseCsvImport');
    Route::post('strategic-plans/process-csv-import', 'StrategicPlansController@processCsvImport')->name('strategic-plans.processCsvImport');
    Route::resource('strategic-plans', 'StrategicPlansController');

    // Goals
    Route::delete('goals/destroy', 'GoalsController@massDestroy')->name('goals.massDestroy');
    Route::post('goals/media', 'GoalsController@storeMedia')->name('goals.storeMedia');
    Route::post('goals/ckmedia', 'GoalsController@storeCKEditorImages')->name('goals.storeCKEditorImages');
    Route::post('goals/parse-csv-import', 'GoalsController@parseCsvImport')->name('goals.parseCsvImport');
    Route::post('goals/process-csv-import', 'GoalsController@processCsvImport')->name('goals.processCsvImport');
    Route::resource('goals', 'GoalsController');

    // Projects
    Route::delete('projects/destroy', 'ProjectsController@massDestroy')->name('projects.massDestroy');
    Route::post('projects/media', 'ProjectsController@storeMedia')->name('projects.storeMedia');
    Route::post('projects/ckmedia', 'ProjectsController@storeCKEditorImages')->name('projects.storeCKEditorImages');
    Route::post('projects/parse-csv-import', 'ProjectsController@parseCsvImport')->name('projects.parseCsvImport');
    Route::post('projects/process-csv-import', 'ProjectsController@processCsvImport')->name('projects.processCsvImport');
    Route::resource('projects', 'ProjectsController');

    // Initiatives
    Route::delete('initiatives/destroy', 'InitiativesController@massDestroy')->name('initiatives.massDestroy');
    Route::post('initiatives/media', 'InitiativesController@storeMedia')->name('initiatives.storeMedia');
    Route::post('initiatives/ckmedia', 'InitiativesController@storeCKEditorImages')->name('initiatives.storeCKEditorImages');
    Route::post('initiatives/parse-csv-import', 'InitiativesController@parseCsvImport')->name('initiatives.parseCsvImport');
    Route::post('initiatives/process-csv-import', 'InitiativesController@processCsvImport')->name('initiatives.processCsvImport');
    Route::resource('initiatives', 'InitiativesController');

    // Action Plans
    Route::delete('action-plans/destroy', 'ActionPlansController@massDestroy')->name('action-plans.massDestroy');
    Route::post('action-plans/media', 'ActionPlansController@storeMedia')->name('action-plans.storeMedia');
    Route::post('action-plans/ckmedia', 'ActionPlansController@storeCKEditorImages')->name('action-plans.storeCKEditorImages');
    Route::post('action-plans/parse-csv-import', 'ActionPlansController@parseCsvImport')->name('action-plans.parseCsvImport');
    Route::post('action-plans/process-csv-import', 'ActionPlansController@processCsvImport')->name('action-plans.processCsvImport');
    Route::resource('action-plans', 'ActionPlansController');

    // Risks
    Route::delete('risks/destroy', 'RisksController@massDestroy')->name('risks.massDestroy');
    Route::post('risks/media', 'RisksController@storeMedia')->name('risks.storeMedia');
    Route::post('risks/ckmedia', 'RisksController@storeCKEditorImages')->name('risks.storeCKEditorImages');
    Route::post('risks/parse-csv-import', 'RisksController@parseCsvImport')->name('risks.parseCsvImport');
    Route::post('risks/process-csv-import', 'RisksController@processCsvImport')->name('risks.processCsvImport');
    Route::resource('risks', 'RisksController');

    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
    Route::get('team-members', 'TeamMembersController@index')->name('team-members.index');
    Route::post('team-members', 'TeamMembersController@invite')->name('team-members.invite');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
// Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
