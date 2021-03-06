<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//----------------- API routes
	
	// USER 
    Route::get('user', 'UsersController@getUser');
    Route::post('user/{id}', 'UsersController@updateUser');
	Route::delete('user/{id}', 'UsersController@deleteUser');    
    Route::get('users', 'UsersController@getAllUsers');
    Route::post('create_account', 'UsersController@createUser');

	// CLIENT
	Route::get('clients/{withWeight?}', 'ClientsController@getAllUserClients');
	Route::put('clients/{id}', 'ClientsController@updateClient');
	Route::post('clients', 'ClientsController@storeClient');
	Route::delete('clients/{id}', 'ClientsController@removeClient');

	// PROJECT
    Route::get('projects/', 'ProjectsController@getAllUserProjects');
    Route::get('projects/shared', 'ProjectsController@getAllMemberProjects');
    Route::get('projects/{id}','ProjectsController@getProject');
    Route::get('projects/{id}/owner','ProjectsController@getOwner');
    Route::get('projects/{id}/members','ProjectsController@getMembers');
	Route::post('projects', 'ProjectsController@storeProject');
    Route::put('projects/{id}', 'ProjectsController@updateProject');
    Route::post('projects/{id}/{email}/invite', 'ProjectsController@invite');
    Route::delete('projects/{id}/{member_id}/remove', 'ProjectsController@removeMember' );

	// TASK
    Route::get('tasks', 'TasksController@getAllUserOpenTasks');
    Route::post('tasks/{client_id}/{project_id}', 'TasksController@storeTask');
    Route::delete('tasks/{id}', 'TasksController@removeTask');
    Route::put('tasks/{id}', 'TasksController@updateTask');

	// CREDENTIALS
    Route::get('credentials/{id}','CredentialsController@getProjectCredentials');
    Route::post('credentials', 'CredentialsController@storeCredential');
    Route::put('credentials/{id}', 'CredentialsController@updateCredential');
    Route::delete('credentials/{id}', 'CredentialsController@removeCredential');
