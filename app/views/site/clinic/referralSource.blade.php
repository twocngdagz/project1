@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')
{{ Breadcrumbs::render('doctor-edit') }}
<div class="messagerouting"></div>
<!--- Header where buttons and info --->
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-8"><font size=+2><span>Editing {{$referralSource->name}}</span></font></div>
    {{--<div class="col-xs-6 col-md-4"><button type="button" class=" btn btn-default pull-right" id="saveclinicbtn"><span class="glyphicon glyphicon-pencil"></span>&nbsp;Save this Clinic</button></div>--}}
</div>
<div><hr></div>
<!--- /Header where buttons and info --->

<!--- Main window --->
<div class="page-header">
    <form class="form-horizontal" method="POST" action="{{ URL::to('/clinic/updatedoctor') }}" accept-charset="UTF-8">
        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
        <input type="hidden" name="EditProfile" value="default">
        <fieldset>
            <div class="form-group">
                <label class="col-md-2 control-label" for="password">Referral Source</label>
                <div class="col-md-3">
                    <input class="form-control" tabindex="3" value="{{$referralSource->name}}" type="text" name="doctor_name" id="doctor_name">
                    @if($errors->has('doctor_name'))
                        {{ $errors->first('doctor_name') }}
                    @endif
                </div>
            </div>
            {{ Form::hidden('doctor_id', $referralSource->id, array('id' => 'doctor_id')) }}
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <button tabindex="5" type="submit" class="btn btn-primary">Save</button>
                    <button tabindex="26" type="button" class="btn btn-default" id="cancelupdatedoctor">Cancel</button>
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
