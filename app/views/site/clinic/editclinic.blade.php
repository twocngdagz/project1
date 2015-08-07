@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')
{{ Breadcrumbs::render('clinic-edit') }}
<div class="messagerouting"></div>
<!--- Header where buttons and info --->
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-8"><font size=+2><span>Editing {{$referringOffice->name}}</span></font></div>
        {{--<div class="col-xs-6 col-md-4"><button type="button" class=" btn btn-default pull-right" id="saveclinicbtn"><span class="glyphicon glyphicon-pencil"></span>&nbsp;Save this Clinic</button></div>--}}
    </div>
<div><hr></div>
<!--- /Header where buttons and info --->

<!--- Main window --->
<div class="page-header">
<form class="form-horizontal" method="POST" action="{{ URL::to('/clinic/updateclinic') }}" accept-charset="UTF-8">
		<input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
		<input type="hidden" name="EditProfile" value="default">
		<fieldset>
			<div class="form-group">
				<label class="col-md-2 control-label" for="password">Office Name</label>
				<div class="col-md-3">
					<input class="form-control" tabindex="3" value="{{$referringOffice->name}}" type="text" name="clinic_name" id="clinic_name">
					@if($errors->has('clinic_name'))
					{{ $errors->first('clinic_name') }}
					@endif
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label" for="password">Address</label>
				<div class="col-md-3">
					<input class="form-control" tabindex="3" value="{{$referringOffice->address}}" type="text" name="clinic_address" id="clinic_address">
					@if($errors->has('clinic_address'))
						{{ $errors->first('clinic_address') }}
					@endif
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label" for="password">Website</label>
				<div class="col-md-3">
					<input class="form-control" tabindex="4" value="{{$referringOffice->website}}" type="text" name="clinic_website" id="clinic_website">
					@if($errors->has('clinic_website'))
						{{ $errors->first('clinic_website') }}
					@endif
				</div>
			</div>
			<div class="form-group">
                <label class="col-md-2 control-label" for="password">Fax</label>
                <div class="col-md-3">
                    <input class="form-control" tabindex="4" value="{{$referringOffice->fax}}" type="text" name="clinic_fax" id="clinic_fax">
                    @if($errors->has('clinic_fax'))
                        {{ $errors->first('clinic_fax') }}
                    @endif
                </div>
	        </div>
            <div class="form-group">
                <label class="col-md-2 control-label" for="password">Phone</label>
                <div class="col-md-3">
                    <input class="form-control" tabindex="4" value="{{$referringOffice->phone}}" type="text" name="clinic_phone" id="phone">
                    @if($errors->has('clinic_phone'))
                        {{ $errors->first('clinic_phone') }}
                    @endif
                </div>
            </div>
		{{ Form::hidden('clinic_id', $referringOffice->id, array('id' => 'clinic_id')) }}
        {{ Form::hidden('referral_source_id', $referralSource->id, array('id' => 'referral_source_id')) }}
			<div class="form-group">
				<div class="col-md-offset-2 col-md-10">
					<button tabindex="5" type="submit" class="btn btn-primary">Save</button>
					<button tabindex="26" type="button" class="btn btn-default" id="cancelupdateclinic">Cancel</button>
				</div>
			</div>
		</fieldset>
	</form>
<!--- /Right boxes --->
</div>
<!--- /Main window --->

<!--- scripts for only this view --->
<script type="text/javascript" src="{{asset('/js/cliniccrm.js')}}"></script>
<!--- /scripts for only this view --->

    </fieldset>
</form>

@stop
