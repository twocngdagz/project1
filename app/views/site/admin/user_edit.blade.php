@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')

<div class="page-header">
    <form class="form-horizontal" accept-charset="UTF-8">
        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}" id="csrf_token">
        <input type="hidden" name="user_id" value="{{$user->id}}" id="user_id">
        <input type="hidden" name="practice_id" value="{{$user->practice->id}}" id="practice_id">
        <fieldset>
            <h3>User Information</h3>
            <hr/>
            <div class="row btn-group btn-group-justified">
                <div class="col-md-4">
                    <input type="text" tabindex="6" class="form-control" placeholder="Name" name="user_name" id="name" required="required" value="{{$user->name}}">
                </div>
                <div class="col-md-4">
                    <input type="text" tabindex="7" class="form-control" placeholder="Email" name="email" id="email" required="required" value="{{$user->email}}">
                </div>
                <div class="col-md-4">
                    <input type="password" tabindex="7" class="form-control" placeholder="Edit to change password" name="password" id="password" required="required" value="">
                </div>
            </div>
            <br>
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <div style="float: right;">
                    <button tabindex="10" type="submit" class="btn btn-primary" id="update-user">Update User</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>

@stop
