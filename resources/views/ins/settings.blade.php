@extends('templates/ins/master')

@section('content')
    <div id="user">
        <div class="row">
            <div class="col-xs-12 page-title-section">
                <h1 class="pull-left">Settings</h1>
                <a v-on:click="update()" class="btn btn-primary pull-right" title="Create new client">Save Changes</a>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="row settings-container">
            <div class="col-xs-12">
                <section>
                <div class="col-xs-12 col-md-4 left-side">
                    <a href="{{ route('profile') }}"><img class="circle" width="100px" src="@{{user.user.avatar}}"></a>
                    <div class="info">
                        <p class="name" style="text-transform: capitalize;">@{{ user.user.name }}</p>
                        <p class="color-primary">@{{ user.user.email }}</p>
                        <p class="color-primary" style="text-transform: capitalize;">@{{ user.user.username }}</p>
                        <p class="color-primary" style="text-transform: capitalize;">@{{ user.user.role_name }}</p>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <hr>
                </div>
                <div class="col-xs-12 col-md-8 right-side">
                    <div class="mega-menu">
                        <p v-if="msg.error != null" class="status-msg error-msg">@{{ msg.error }}</p>
                        <p v-if="msg.success != null" class="status-msg success-msg">@{{ msg.success }}</p>
                        <div class="links">
                            <a class="" data-id="settings_info" href="">Personal Info</a>
                            <a class="" data-id="settings" href="">Account Settings</a>
                        </div>
                        <div class="content">
                            <div class="item" id="settings_info">
                                <div class="form">
                                    <form>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input v-model="user.user.name" type="text" class="form-control" placeholder="Name">
                                        </div>
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input v-model="user.user.username" type="text" class="form-control" placeholder="Username">
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input v-model="user.user.email" type="text" class="form-control" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <label>Role</label>
                                            <select v-model="user.user.role_id" name="role_id" class="form-control">
                                                <option v-for="role in user.roles" value="@{{role.id}}" :selected=" role.id == user.user.role_id ? 'true' : 'false' "> @{{role.role_name}}
                                                 </option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="item" id="settings">
                                <label>Current Password</label>
                                {!! Form::open(array('action' => array('UsersController@resetPassword', Auth::id() ))) !!}
                                <div class="form-group">
                                    {!! Form::password( 'current_pwd', array('class' => 'form-control', "placeholder" => "Current Password" )) !!}
                                </div>
                                <label>New Password</label>
                                <div class="form-group">
                                    {!! Form::password( 'new_pwd', array('class' => 'form-control', "placeholder" => "New Password" )) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::submit( 'Update Password', array('class' => 'btn btn-default pull-right')) !!}
                                    <div class="clearfix"></div>
                                </div>
                                {!! Form::close() !!}
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        megaMenuInit();
    </script>
    <script src="{{ asset('assets/js/controllers/user.js') }}"></script>
@stop()
