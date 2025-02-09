<?php

Route::redirect('/', '/login');
Route::redirect('/home', '/admin');
Auth::routes(['register' => false]);




 Route::any('/customLogin', 'Auth\LoginController@customLogin')->name('customLogin');
 Route::any('/task_detail/{id}', 'Admin\TaskController@task_detail')->name('task_detail');

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
    Route::any('admin', 'UsersController@admin')->name('admin');
    Route::any('admin_create', 'UsersController@admin_create')->name('admin.create');
    Route::any('admin_store', 'UsersController@admin_store')->name('admin.store');
    Route::any('admin_edit/{id}', 'UsersController@admin_edit')->name('admin.edit');
    Route::any('admin_update/{id}', 'UsersController@admin_update')->name('admin.update');

    Route::any('notesStore/{id}', 'UsersController@notesStore')->name('notesStore');
    Route::any('view_data/{id}', 'UsersController@view_data')->name('view_data');
    Route::any('contacts', 'UsersController@contacts')->name('contacts');

    Route::any('contact_view/{id}', 'UsersController@contact_view')->name('contact_view');
    

    Route::any('contactDelete/{id}', 'UsersController@contactDelete')->name('contactDelete');
    Route::any('contactEdit/{id}', 'UsersController@contactEdit')->name('contactEdit');
    
    Route::any('contactUpdate/{id}', 'UsersController@contactUpdate')->name('contactUpdate');
    

    Route::any('createContact', 'UsersController@createContact')->name('createContact');
    Route::any('contactStore', 'UsersController@contactStore')->name('contactStore');

    Route::resource('users', 'UsersController');

    // Case Intake
    Route::resource('case_intake', 'CaseController');
    Route::resource('message', 'MessageController');
    Route::resource('task', 'TaskController');
    
    Route::any('approve/{id}', 'LeadsController@salesApprove')->name('sales.approve');
    Route::any('salesRejct/{id}', 'LeadsController@salesRejct')->name('sales.reject');
    Route::any('salesUpdate/{id}', 'LeadsController@salesUpdate')->name('salesUpdate');
    Route::any('changeStatus/{id}', 'TaskController@changeStatus')->name('task.changeStatus');


    // Training module
    Route::any('training', 'TaskController@training')->name('training');
    Route::any('trainingCreate', 'TaskController@trainingCreate')->name('trainingCreate');
    Route::any('trainingStore', 'TaskController@trainingStore')->name('trainingStore');
    Route::any('mark-video-watched', 'TaskController@markVideoWatched')->name('markVideoWatched');
    Route::any('trainingDelete/{id}', 'TaskController@trainingDelete')->name('trainingDelete');
     
    Route::any('chat/{id}', 'ChatController@index')->name('chat.index');
    Route::any('getUnreadMessageCounts', 'ChatController@getUnreadMessageCounts')->name('getUnreadMessageCounts');
    Route::any('chat', 'ChatController@store')->name('salesUpdate')->name('chatStore');
    Route::any('chat/sse/{receiverId}', 'ChatController@sse')->name('salesUpdate')->name('chat.sse');

    // Route::get('chat/{receiverId}', [ChatController::class, 'index'])->name('chat.index');
    // Route::post('chat', [ChatController::class, 'store'])->name('chat.store');
    // Route::get('chat/sse/{receiverId}', [ChatController::class, 'sse'])->name('chat.sse');


});
