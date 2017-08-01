{{--<a href="{{ route('home') }}" class="logo-section">--}}
	{{--<img src="{{\App\Helpers\Helpers::logoUrl()}}" alt="Ribbbon">--}}
{{--</a>--}}

<div class="user-section">
	<a href="{{ route('profile') }}"><img class="circle" src="{{ App\User::get_gravatar(Auth::user()->email) }}">
	<p>{{Auth::user()->name}}</p>
	</a>
</div>

{!! Form::open(array('action' => 'HomeController@search','method' => 'get')) !!}
	<div class="form-group search">
		{!! Form::text( 'q', null, array('class' => 'form-control search-bar', "placeholder" => "Search" )) !!}
	</div>	    			
{!! Form::close() !!}	

<div class="menu">
	<a class="<?php echo ( Request::is('hud') ) ? 'active' : 'false'; ?> <?php echo ( Request::is('/') ) ? 'active' : 'false'; ?>" href="{{ route('home') }}"><i class="icon ion-ios-home"></i> Hud</a>
	<a class="<?php echo ( Request::is('roles') ) ? 'active' : 'false'; ?>" href="{{ route('roles') }}"><i class="icon ion-lock-combination"></i> Roles</a>
	<a class="<?php echo ( Request::is('users') ) ? 'active' : 'false'; ?>" href="{{ route('users') }}"><i class="icon ion-android-contacts"></i> Users</a>
	<a class="<?php echo ( Request::is('profile') ) ? 'active' : 'false'; ?>" href="{{ route('profile') }}"><i class="icon ion-gear-b"></i> Settings</a>
	<a href="{{ route('logout') }}"><i class="icon ion-android-exit"></i> Logout</a>
</div>

<div class="line"><hr></div>

