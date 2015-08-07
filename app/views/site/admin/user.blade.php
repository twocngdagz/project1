@extends('site.layouts.default')

@section('title')

@parent
@stop

@section('content')
{{ Breadcrumbs::render('account-creation') }}
<div class="messagerouting"></div>
<!--- Header where buttons and info --->
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
<div class="row">
    <div class="col-md-4 col-md-offset-4" style="text-align: center"><font size=+2><span>ClinicRise Admin Tools</span></font></div>
</div>
<div><hr></div>
{{link_to_action('AdminController@createUser', 'Add User', array('practice_id'=>$practice_id), array('role'=>'button', 'class'=>'btn btn-success'))}}
<div class="page-header">
    <table id="userTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>{{link_to_action('AdminController@editUser', 'edit', array('practice_id'=>$user->id))}}
                {{link_to_action('AdminController@removeUser', 'remove', array('practice_id'=>$user->id),
                array('data-toggle'=>"modal", 'data-target'=>'#modalUser', 'data-user'=>$user->id, 'data-practice'=>$practice_id))}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">User delete confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <a href="#" class="btn btn-primary" id="deleteLink">Yes</a>
            </div>
        </div>
    </div>
</div>


@stop