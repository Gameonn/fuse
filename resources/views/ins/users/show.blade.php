@extends('templates/ins/master')

@section('content')

<div id="user">
    <div class="row">
        <div class="col-xs-12 page-title-section">
            <h1 class="pull-left">Users</h1>
            <a v-on:click="showCreateForm()" class="btn btn-primary pull-right" title="Create new user">+ New User</a>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="main-section mega-menu">
                <div class="box-body table-responsive no-padding">                        
                    <table class="table table bordered user-table">
                        <thead>
                            <tr>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email </th>
                                <th>Role </th>
                                <th> </th>                                
                            </tr>
                        </thead>
                        <tbody> 
                            @if(count($users))
                                @foreach($users as $user)  
                                    <tr id="user{{$user->id}}" class="{{$user->is_deleted ? 'alert alert-danger' : 'alert alert-success'}}">
                                        <td class="text-center"><img src="{{$user->avatar}}" style="width: 30px;"></td>
                                        <td> {{$user->name}} </td>
                                        <td> {{$user->username}} </td>
                                        <td> {{$user->email}} </td>
                                        <td> {{$user->role_name}} </td>
                                        <td> <a v-on:click="startUserEditMode({{$user->id}})" class="btn btn-default" title="Edit User"><i class="ion-edit"></i></a> 
                                        <a v-on:click="delete({{$user}},{{$user->id}})" class="btn btn-{{$user->is_deleted ? 'success' : 'danger'}}" title="{{$user->is_deleted ? 'Enable User' : 'Disable User'}}"><i class="{{$user->is_deleted ? 'ion-checkmark' : 'ion-close'}}"></i></a> </td>
                                    </tr>
                                @endforeach
                            @endif                                   
                        </tbody>
                    </table>             
                </div><!-- /.box-body -->
            </div>
        </div>
    </div>

    @if(count($users) == 0)
        <div class="clearfix"></div>
        <p class="alert alert-warning">
            All users will be listed here once you create some.
            Create a new user <a v-on:click="showCreateForm()">now</a>.
        </p>
    @endif

    <!-- @include('ins.users.partials.forms') -->

    {{-- FORMS --}}
    <div class="popup-form new-user">
        <header>
            <p class="pull-left">New User</p>
            <div class="actions pull-right">
                <i title="Minimze "class="ion-minus-round"></i>
                <i title="Close" class="ion-close-round"></i>
            </div>
            <div class="clearfix"></div>
        </header>
        <section>
            <form>
                <span class="status-msg"></span>
                <input v-model="user.name" placeholder="Name" type="text" class="form-control first">
                <input v-model="user.username" placeholder="Username" type="text" class="form-control">
                <input v-model="user.email" placeholder="Email" type="email" class="form-control">
                <select v-model="user.role_id" name="role_id" class="form-control">
                    <option v-for="role in user.roles" value="@{{role.id}}"> @{{role.role_name}} </option>
                </select>
            </form>
        </section>
        <footer>
            <a v-on:click="create(user,true)" class="btn btn-primary pull-right">Save</a>
            <div class="clearfix"></div>
        </footer>
    </div>
    <div class="popup-form update-user">
        <header>
            <p class="pull-left">Update User</p>
            <div class="actions pull-right">
                <i title="Minimze "class="ion-minus-round"></i>
                <i title="Close" class="ion-close-round"></i>
            </div>
            <div class="clearfix"></div>
        </header>
        <section>
            <form>
                <span class="status-msg"></span>
                <input v-model="user.user.name" placeholder="Name" type="text" class="form-control first">
                <input v-model="user.user.email" placeholder="Email" type="email" class="form-control">
                <select v-model="user.user.role_id" name="role_id" class="form-control">
                    <option v-for="role in user.roles" value="@{{role.id}}"> @{{role.role_name}} </option>
                </select>
            </form>
        </section>
        <footer>
            <a v-on:click="update()" class="btn btn-primary pull-right">Update</a>
            <div class="clearfix"></div>
        </footer>
    </div>

    <script> megaMenuInit(); </script>
</div>

<script src="{{ asset('assets/js/controllers/user.js') }}"></script>

@stop()



