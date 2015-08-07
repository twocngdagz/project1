@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')
{{ Breadcrumbs::render('profile-edit') }}
<div class="messagerouting"></div>
<!--- Header where buttons and info --->
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-8"><font size=+2><span>Edit Profile</span></font></div>
</div>
<div><hr></div>
<!--- /Header where buttons and info --->

<!--- Main window --->
<div class="page-header">
	<form class="form-horizontal" method="POST" action="{{ URL::to('/user/editprofile') }}" accept-charset="UTF-8">
		<input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
		<input type="hidden" name="EditProfile" value="default">
		<fieldset>
			<div class="form-group">
				<label class="col-md-2 control-label" for="password">Old Password</label>
				<div class="col-md-3">
					<input class="form-control" tabindex="3" placeholder="Enter Old Password" type="password" name="old_password" id="password">
					@if($errors->has('old_password'))
					{{ $errors->first('old_password') }}
					@endif
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label" for="password">New Password</label>
				<div class="col-md-3">
					<input class="form-control" tabindex="3" placeholder="Enter New Password" type="password" name="new_password" id="password">
					@if($errors->has('new_password'))
						{{ $errors->first('new_password') }}
					@endif
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label" for="password">Confirm Password</label>
				<div class="col-md-3">
					<input class="form-control" tabindex="4" placeholder="Confirm New Password" type="password" name="password_confirmation" id="password_confirmation">
					@if($errors->has('password_confirmation'))
						{{ $errors->first('password_confirmation') }}
					@endif
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-offset-2 col-md-10">
					<button tabindex="5" type="submit" class="btn btn-primary">Save</button>
				</div>
			</div>
		</fieldset>
	</form>
</div>
<!--- /Main window --->

<!--- scripts for only this view --->
<script type="text/javascript" src="{{asset('/js/cliniccrm.js')}}"></script>
<!--- /scripts for only this view --->

</fieldset>
</form>

@stop
