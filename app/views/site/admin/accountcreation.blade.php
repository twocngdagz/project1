@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')
{{ Breadcrumbs::render('account-creation') }}
<div class="messagerouting"></div>
<!--- Header where buttons and info --->
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-8"><font size=+2><span>Account Creation</span></font></div>
</div>
<div><hr></div>
<!--- /Header where buttons and info --->

<!--- Main window --->
<div class="page-header">
	<form class="form-horizontal" accept-charset="UTF-8">
		<input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
		<input type="hidden" name="accountCreation" value="default">
		<fieldset>
			<h3>Company information</h3>
			<br>
			<div class="row btn-group btn-group-justified">
	               <div class="col-md-4">
	                    <input type="text" tabindex="1" class="form-control" placeholder="Office Name" name="clinicname" id="clinicname">
	               </div>
	                <div class="col-md-4">
	                    <input type="text" tabindex="2" class="form-control" placeholder="Address" name="address" id="address">
	              </div>
	              <div class="col-md-4">
	                    <input type="text" tabindex="3" class="form-control" placeholder="Phone" name="phone" id="phone">
	              </div>
	          </div>
	          <br>
	          <div class="row btn-group btn-group-justified">
	                 <div class="col-md-4">
	                     <input type="text" tabindex="4" class="form-control" placeholder="Website" name="website" id="website">
	                 </div>
	                  <div class="col-md-4">
	                     <input type="text" tabindex="5" class="form-control" placeholder="Fax" name="fax" id="fax">
	                </div>
	            </div>

	                  <br>
	                  <hr>
	          <h3>Information about the company administrator</h3>
	          <br>
				<div class="row btn-group btn-group-justified">
                     <div class="col-md-4">
                         <input type="text" tabindex="6" class="form-control" placeholder="Name" name="user_name" id="user_name">
                     </div>
                      <div class="col-md-4">
                         <input type="text" tabindex="7" class="form-control" placeholder="Email" name="email" id="email">
                    </div>
                </div>
                <br>
                <div class="row btn-group btn-group-justified">
                     <div class="col-md-4">
                         <input class="form-control" tabindex="8" placeholder="Password" type="password" name="password" id="password">
                     </div>
                      <div class="col-md-4">
                         <input class="form-control" tabindex="9" placeholder="Confirm password" type="password" name="password_confirmation" id="password_confirmation">
                    </div>
                </div>
			<br>
				<div class="col-md-9"></div>
	              <div class="col-md-3">
	                  <div style="float: right;">
	                      <button tabindex="10" type="button" class="btn btn-primary" id="savenewaccount">Create account</button>
	                  </div>
                  </div>
		</fieldset>
	</form>
</div>
<!--- /Main window --->

ENV: {{ app()->environment() }}
<!--- scripts for only this view --->
<script type="text/javascript" src="{{asset('/js/account-creation.js')}}"></script>
<!--- /scripts for only this view --->

</fieldset>
</form>

@stop
