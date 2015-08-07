@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')
{{ Breadcrumbs::render('profile') }}
<div class="messagerouting"></div>
<!--- Header where buttons and info --->
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-8"><font size=+2><span>Profile</span></font></div>
	<div class="col-xs-6 col-md-4"><form action="/user/editprofile"><button type="submit" class=" btn btn-default pull-right" id="editprofilebtn"><span class="glyphicon glyphicon-pencil"></span>&nbsp;Edit profile</button></form></div>
</div>
<div><hr></div>
<!--- /Header where buttons and info --->

<!--- Main window --->
<div class="page-header">
	<p><b>Name:</b> {{$user->name}}</p>
	<p><b>Email:</b> {{$user->email}}</p>
	<!--- /Right boxes --->
</div>
<!--- /Main window --->

<!--- scripts for only this view --->
<script type="text/javascript" src="{{asset('/js/cliniccrm.js')}}"></script>
<!--- /scripts for only this view --->

</fieldset>
</form>

@stop
