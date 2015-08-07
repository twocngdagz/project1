@extends('site.layouts.default')

{{-- Title --}}
@section('title')
Password reseting!
@parent
@stop

{{-- Content --}}
@section('content')

@if (Session::has('error'))
<div class="container">
  <h4>{{ trans(Session::get('reason')) }}</h4>
</div>
@endif
 
 <div class="container">
   <form class="form-horizontal" method="POST" action="{{ URL::to('password/reset') }}" accept-charset="UTF-8">
      <div class="form-group">
        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
        <label class="control-label col-md-2" for="email">Email</label>
        <div class="col-md-3">
        <input class="form-control" tabindex="1" placeholder="Enter Email" type="text" name="email" id="email" value="" size="40">
        </div>
      </div> 
      
      <div class="form-group">
        <label class="control-label col-md-2" for="password">Password</label>
        <div class="col-md-3">
        <input class="form-control" tabindex="2" placeholder="Enter New Password" type="password" name="password" id="password">
        </div>
      </div> 
      
      <div class="form-group"> 
        <label class="control-label col-md-2" for="password">Confirm Password</label>
        <div class="col-md-3">
        <input class="form-control" tabindex="4" placeholder="Confirm New Password" type="password" name="password_confirmation" id="password_confirmation">
        </div>
      </div>  
      
        {{ Form::hidden('token', $token) }}
      <div class="form-group"> 
          <div class="col-md-offset-2 col-md-10">
                <button tabindex="4" type="submit" class="btn btn-primary">Submit</button>
          </div>
      </div> 
        
   </form>
 </div>
 


@stop
