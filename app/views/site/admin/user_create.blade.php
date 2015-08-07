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
        <input type="hidden" name="practice_id" value="{{$practice_id}}" id="practice_id">
        <fieldset>
            <h3>User Information</h3>
            <hr/>
            <div class="row btn-group btn-group-justified">
                <div class="col-md-4">
                    <input type="text" tabindex="6" class="form-control" placeholder="Name" name="user_name" id="name" required="required">
                </div>
                <div class="col-md-4">
                    <input type="text" tabindex="7" class="form-control" placeholder="Email" name="email" id="email" required="required">
                </div>
            </div>
            <br>
            <div class="row btn-group btn-group-justified">
                <div class="col-md-4">
                    <input class="form-control" tabindex="8" placeholder="Password" type="password" name="password" id="password" required="required">
                </div>
                <div class="col-md-4">
                    <input class="form-control" tabindex="9" placeholder="Confirm password" type="password" name="password_confirmation" id="password_confirmation" required="required">
                </div>
            </div>
            <br>
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <div style="float: right;">
                    <button tabindex="10" type="submit" class="btn btn-primary" id="create-user">Create User</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>

@stop
