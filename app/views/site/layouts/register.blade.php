@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')
<div class="page-header">
    <h1>Register!</h1>
</div>

<form class="form-horizontal" method="POST" action="{{ URL::to('user/register') }}" accept-charset="UTF-8">
    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
    <fieldset>
        <div class="form-group">
            <label class="col-md-2 control-label" for="name">Username</label>
            <div class="col-md-3">
                <input class="form-control" tabindex="1" placeholder="Your Name here" type="name" name="name" id="name">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label" for="email">Email</label>
            <div class="col-md-3">
                <input class="form-control" tabindex="2" placeholder="Enter Email as a Login" type="text" name="email" id="email" value="{{ Input::old('email') }}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label" for="password">Password</label>
            <div class="col-md-3">
                <input class="form-control" tabindex="3" placeholder="Enter Password" type="password" name="password" id="password">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label" for="password">Confirm Password</label>
            <div class="col-md-3">
                <input class="form-control" tabindex="4" placeholder="Confirm Password" type="password" name="password_confirmation" id="password_confirmation">
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <button tabindex="5" type="submit" class="btn btn-primary">Register</button>
            </div>
        </div>
    </fieldset>
</form>

@stop
