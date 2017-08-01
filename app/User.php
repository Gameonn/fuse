<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
    */
    protected $table = 'users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = ['name','username','email','password','avatar','role_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
    */
    protected $hidden = ['password', 'remember_token', 'is_deleted','created_at','updated_at'];

    /** Return the related projects for a given user */
    public function projects(){
        return $this->hasMany('App\Project','user_id');
    }   

    /**  Return the related tasks for a given user */
    public function tasks(){
        return $this->hasMany('App\Task','user_id');
    }

    public function inProjects(){
        return $this->belongsToMany('App\Project');
    }

/**********  Validation Rules ******************/

    public static $loginRules = array(
        'email' => 'required|email',
        'password' => 'required',
    );

    public static $createUserRules = array(
        'email' => 'required|email|Unique:users',
        'role_id' => 'required',
        'name' => 'required',
        'username' => 'required|unique:users,username',
    );

    public static $updateUserRules = array(
        'email' => 'required|unique:users,email,3,id',
        'role_id' => 'required',
        'name' => 'required',
    );


    public static function chooseAvatar(){
        $avatars=["avatar1.png","avatar2.png","avatar3.png","avatar4.png","avatar5.png","avatar6.png","avatar7.png","avatar8.png","avatar9.png","avatar10.png","avatar11.png","avatar12.png","avatar13.png","avatar14.png","avatar15.png","avatar16.png"];

        $k = array_rand($avatars);
        $v = $avatars[$k];
        return $v;
    }

    // Send the welcome email to the user
    static function sendWelcomeMail($data) {
        try{
            Mail::send('emails.welcome', [ 'name' => $data['name'] ] , function($message) use ($data){
                $message->from(getenv('MAIL_FROM'), getenv('MAIL_FROM_NAME'));
                $message->to($data['to'], $data['name'] )->subject('Welcome to PMS');
            });
        } catch(Exception $e){}
        
        return true; 

    }

    public static function createUser($input) {
        $validation = Validator::make($input, self::$createUserRules);
        if($validation->fails()) {
            return array('status'=>0, 'msg'=>$validation->getMessageBag()->first());
        }
        else { 
            $name   =   Input::get('name');
            $username   =   Input::get('username');
            $email      =   Input::get('email');
            $password   =   str_random(10); 
            $role_type   =   Input::get('role_id'); 
            $user               =   new User;
            $user->name    =   $name;
            $user->username    =   $username;
            $user->email        =   $email;
            $user->role_id    =   $role_type;
            $user->avatar    =      self::chooseAvatar();
            $user->password     =   Hash::make($password);
            $user->save();  

            return array('status'=>1, 'msg'=>'User created successfully','user_id'=>$user->id);
        }
    }

    public static function updateUser($input,$id) {
        $validation = Validator::make($input, self::$updateUserRules);
        if($validation->fails()) {
            return array('status'=>0, 'msg'=>$validation->getMessageBag()->first());
        }
        else { 
            $user = User::find(3);
            $user->email=$input['email'];
            $user->name=$input['name'];
            $user->role_id=$input['role_id'];
            $user->save();  

            return array('status'=>1, 'msg'=>'User updated successfully');
        }
    }

    public static function login($input) {
        $validation = Validator::make($input, self::$loginRules);
        if($validation->fails()) {
            return Response::json(array('status'=>0, 'msg'=>$validation->getMessageBag()->first()), 200);
        }
        else {            
            $email      =   Input::get('email');
            $password   =   Input::get('password');

            if( Auth::attempt(array('email' => $email, 'password' => $password)) ){             
                return Redirect::to('hud');
            }else{              
                $validation->getMessageBag()->add('input', 'Incorrect email or password');
                return Redirect::back()->withErrors($validation)->withInput();;
            }   
        }
    }

    public static function getUserList($user_id) {
        $avatar_path=env('AVATAR_PATH');           
        $query=User::select('users.id','name','username','email','role_name','role_id','is_deleted',
                     DB::raw("CONCAT('$avatar_path',avatar) as avatar") )
                    ->join('roles','roles.id','=','users.role_id');

        if ($user_id != 0) {
            $query = $query->where('users.id', '=', $user_id);
            $users=$query->first();
        } else{
            $users=$query->get();
        }
        
        return $users;
    }


    /**
     * Get the total weight of a user
     * @param  [int] $id [the id of the user]
     * @return [int]     [the total weight of all the incomplete tasks a user has]
     */
    public static function weight($id = null){
        if ($id == null) {
            $result = DB::table('tasks')->where('user_id',Auth::id())->whereState('incomplete')->sum('weight');
        }else{
            $result = DB::table('tasks')->where('user_id',$id)->whereState('incomplete')->sum('weight');
        }
        return $result;
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    public static function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }



}
