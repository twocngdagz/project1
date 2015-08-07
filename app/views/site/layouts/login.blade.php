@extends('site.layouts.default')

{{-- Title --}}
@section('title')
Sign In!
@parent
@stop

{{-- Content --}}
@section('content')
<div class="page-header">
    <h1>Login into your account</h1>
</div>
<form class="form-horizontal" method="POST" action="{{ URL::to('user/login') }}" accept-charset="UTF-8">
    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
    <fieldset>
        <div class="form-group">
            <label class="col-md-2 control-label" for="email">Email</label>
            <div class="col-md-3">
                <input class="form-control" tabindex="1" placeholder="Enter Email as a Login" type="text" name="email" id="email" value="{{ Input::old('email') }}" size="40">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label" for="password">Password</label>
            <div class="col-md-3">
                <input class="form-control" tabindex="2" placeholder="Enter Password" type="password" name="password" id="password" autocomplete="off">
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <div class="checkbox">
                    <label for="remember">
                        <input tabindex="3" type="checkbox" name="remember" id="remember" value="1">
                        Remember Me
                        <input type="hidden" name="remember" value="0">
                    </label>
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <button tabindex="4" type="submit" class="btn btn-primary">Submit</button>
                <a class="btn btn-default" href="/password/remind">Forgot password?</a>
            </div>
        </div>
    </fieldset>
</form>

@stop
