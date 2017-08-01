<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\View;
use App\Project;
use App\Role;
use App\Task;

class UsersController extends BaseController {

	// Go to user settings page
	public function index()
	{	
		return View::make('ins/settings')->with('pTitle', Auth::user()->name);
	}

	// Logout the user
	public function logout(){
		Auth::logout();
		return Redirect::to('/');
	}

	// Login the user
	public function login() {				
		$input = Input::all();
        return User::login($input);
	}

	public function show() {			
		$users = User::getUserList(0);
        return  View::make('ins/users/show')->with('pTitle', 'Users')->with('users',$users);
	}

	public function showRoles() {			
		$roles = Role::getRoles();
        return  View::make('ins/users/show')->with('pTitle', 'Users')->with('users',$users);
	}	

	// Register the user and start a new session
	public function createUser() {	
		$input=Input::all();
		$user=User::createUser($input);
		if($user['status']){
        	// $data = ['to' => 'ankit@skywaltzlabs.com','name' => 'Ankit'];
			// $user_data = User::sendWelcomeMail($data);
			$user_data = User::getUserList($user['user_id']);
			return $this->setStatusCode(200)->makeResponse($user['msg'],$user_data);
		}
		else
			return $this->setStatusCode(400)->makeResponse($user['msg']);
	}

	    // Update the given user
    public function updateUser($id){
    	$input=Input::all();
    	$user=User::updateUser($input,$id);
    	if($user['status']) {
			$user_data = User::getUserList($id);
			return $this->setStatusCode(200)->makeResponse($user['msg'],$user_data);
		}
		else
			return $this->setStatusCode(400)->makeResponse($user['msg']);
        
    }	

	// Get all users 
	public function getAllUsers(){
		$users = User::getUserList(0);
		return $this->setStatusCode(200)->makeResponse('Users List',$users->toArray());
	}

	// Reset the user password
	public function resetPassword($id) {		
		// ----------------------------------------
		$user = User::find(Auth::id());
		$created = $user->tasks_created;
		$completed = $user->tasks_completed;

		if ($created == "") {
			$created = 0;
		}

		if ($completed == "") {
			$completed = 0;
		}
		// ----------------------------------------

		$current_pwd	=	Input::get('current_pwd');
		$new_pwd		=	Input::get('new_pwd');

		// lets validate the users input
		$validator = Validator::make(
			array(
					'current_pwd' 	=>	$current_pwd,
					'new_pwd' 		=> 	$new_pwd
			),
			array(
					'current_pwd'	=> 	'required',
					'new_pwd'		=>	'required'
			)
		);

		if ($validator->fails()){
		    return Redirect::back()->withErrors($validator)->with('user', $user)->with('created', $created)->with('completed', $completed);
		}

		if ( !Auth::validate(array('email' => $user->email, 'password' => $current_pwd)) ) {
			$validator->getMessageBag()->add('password', 'That password is incorrect');
			return Redirect::back()->withErrors($validator)->with('user', $user)->with('created', $created)->with('completed', $completed);	
		}

		// Store the new password and redirect;
		$user->password = Hash::make($new_pwd);
		$user->save();

		return Redirect::back()
								->with('user', $user)
								->with('created', $created)->with('completed', $completed)
								->with('success', "Your password has been updated!");

	}

    // Get the current user
    public function getUser(){
        $user = User::getUserList(1);
        $roles = Role::all();
        return ['user'=>$user,'roles'=>$roles];
    }


    // Delete the users account
    public function deleteUser($user_id) {
    	$user = User::find($user_id);
    	if($user->is_deleted)
        	User::where('id', $user_id)->update(['is_deleted'=>0]);
        else
        	User::where('id', $user_id)->update(['is_deleted'=>1]);
        return $this->setStatusCode(200)->makeResponse('User deleted successfully');
    }

}