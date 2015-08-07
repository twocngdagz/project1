@extends('site.layouts.default')

{{-- Title --}}
@section('title')
Password reminder!
@parent
@stop

{{-- Content --}}
@section('content')

@if (Session::has('error'))
<div class="container">
  <h4>{{ trans(Session::get('reason')) }}</h4>
</div>
@elseif (Session::has('success'))
<div class="container">
  <h3>An email with the password reset has been sent.</h3>
</div>
@endif
 <div class="container">
     <div class="page-header">
        <h3>Password reminder. Enter your email!</h3>
     </div>
 
<form class="form-horizontal" method="POST" action="{{ URL::to('password/remind') }}" accept-charset="UTF-8">
     <div class="form-group form-inline">
        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
        <label class="control-label" for="email">Email</label>
        <input class="form-control" tabindex="1" placeholder="Enter Email" type="text" name="email" id="email" value="" size="40">
        <button tabindex="2" type="submit" class="btn btn-primary">Submit</button>
     </div>
</form>
</div>

@stop
