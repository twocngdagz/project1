@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')
{{ Breadcrumbs::render('cases-edit', $case->patient->id) }}
<div class="page-header">
    <button class="btn btn-default" id="goback">Go Back</button>
        <div class="container" id="patientdetailsglobal" style="margin-top: 10px;">
            <div class="row col-xs-12">

                <div class="col-xs-12">
                    <h3>{{ $case->patient->name }} case details</h3>
                    <input type="hidden"  class="case_id" id="case_id" value="{{ $case->id }}">
                    <input type="hidden"  class="patient_id" id="patient_id" value="{{ $case->patient->id }}">
                    <input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
                </div>
                <!-- here begin forms -->
                <div class="col-xs-12" style="margin-top: 15px;">
                    <form class="form-horizontal" accept-charset="UTF-8">
                        <fieldset>


                        <!-- insurance-->
                        <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientinsurance">
                                    <b>Insurance:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <?php
                                    if ($case->insurance_id)
                                    {
                                        $insuranceID = $case->insurance_id;
                                    } else
                                    {
                                        $insuranceID = 'notdefined';
                                    }
                                    ?>
                                    <select class="form-control" tabindex="2" name="insurance" id="insurance" value="{{ $insuranceID }}">
                                        <option value="notdefined" disabled
                                            @if($insuranceID == 'notdefined')
                                                selected
                                            @endif
                                            >Not Defined</option>
                                            @foreach ($insurances as $insurance)
                                                <option value="{{ $insurance->id }}">{{ $insurance->name }}</option>
                                            @endforeach
                                    </select>
                                    <script>$('#insurance').val('{{ $insuranceID }}');</script>
                                </div>
                            </div>
                        </div>
                        <!-- /insurance name -->


                        <!-- therapist -->
                        <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patienttherapist">
                                    <b>Therapist:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">

                                    <?php
                                    if ($case->therapist_id)
                                    {
                                        $therapistID = $case->therapist_id;
                                    } else
                                    {
                                        $therapistID = 'notdefined';
                                    }
                                    ?>

                                    <select class="form-control" tabindex="3" name="therapist" id="therapist" value="{{ $therapistID }}">
                                        <option value="notdefined" disabled
                                            @if($therapistID == 'notdefined')
                                                selected
                                            @endif
                                            >Not Defined</option>
                                            @foreach ($therapists as $therapist)
                                                <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                                            @endforeach
                                    </select>
                                    <script>$('#therapist').val('{{ $therapistID }}');</script>
                                </div>
                            </div>
                        </div>
                        <!-- /therapist -->

                        <!-- Referral source -->
                        <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="inputreferrals">
                                    <b>Referral Source (Individual Name):</b>
                                </label>
                                <!-- form field -->
                                <div id="insert_combobox" class="col-xs-4">
                                    <?php
                                    if ($case->referralsource_id)
                                    {
                                        $referral_source_id = $case->referralsource_id;
                                    } else
                                    {
                                        $referral_source_id = 'notdefined';
                                    }
                                    ?>
                                    <select class="form-control combobox" tabindex="2" name="referral" id="referral" value="">
                                        <option value="" disabled selected>Referral Source</option>
                                        @if ($case->referralSource)
                                            <?php $doctorname=$case->referralSource->name ?>
                                        @else 
                                            <?php $doctorname = ''; ?>
                                        @endif
                                        @foreach ($referralsources as $referrals)
                                            @if($referrals->name==$doctorname)
                                                <option value="{{ $referrals->id }}" selected>{{ $referrals->name }}</option>
                                            @else
                                                <option value="{{ $referrals->id }}">{{ $referrals->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>


                                </div>
                            </div>
                        </div>
                        <!-- /referral source-->


                        <!-- Referring Office -->
                        <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="inputreferrals">
                                    <b>Referring Group (Business Name):</b>
                                </label>
                                <!-- form field -->
                                <div id="insert_combobox" class="col-xs-4">
                                    <select class="form-control combobox" tabindex="2" name="referral_office" id="referral_office" value="">
                                        <option value="" disabled selected>Referring Group</option>
                                        @if ($case->referralSource)
                                            <?php $doctorname=$case->referralSource->referralOffice->name; ?>
                                        @else
                                            <?php $doctorname = ''; ?>
                                        @endif
                                        @foreach ($referralOffices as $referrals)
                                            @if($referrals->name==$doctorname)
                                                <option value="{{ $referrals->id }}" selected>{{ $referrals->name }}</option>
                                            @else
                                                <option value="{{ $referrals->id }}">{{ $referrals->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>


                                </div>
                            </div>
                        </div>
                        <!-- /Referring Office-->


                        <!-- How you find us-->
                        <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patienthowfind">
                                    <b>How you find us?:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <select class="form-control combobox_2" tabindex="3" name="activity" id="activity" value="">
                                        <option value="" disabled selected>How did you find us?</option>
                                        @if ($case->activity_id)
                                            <?php $findname=$case->activity->campaign_name; ?>
                                        @else
                                            <?php $findname = ''; ?>
                                        @endif
                                        @foreach ($activity_types as $activities_type)
                                            <option value="{{ $activities_type->id }}">{{ $activities_type->name }}</option>
                                            @foreach($activities_type->activities as $activity)
                                                @if($activity->campaign_name==$findname)
                                                    <option class="findus_activity" value="{{ $activity->id }}" selected>— {{ $activity->campaign_name }}</option>
                                                @else
                                                    <option class="findus_activity" value="{{ $activity->id }}">— {{ $activity->campaign_name }}</option>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>
                        <!-- /how you find us -->

                        <!-- diagnosis -->
                        <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientdiagnosis">
                                    <b>Diagnosis:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">

                                    <select class="form-control" tabindex="12" name="diagnosis" id="diagnosis" value="">
                                            @foreach ($diagnosises as $diagnosis)
                                                <option value="{{ $diagnosis->id }}">{{ $diagnosis->name }}</option>
                                            @endforeach
                                    </select>

                                    @if($case->diagnosis_id)
                                        <script>$('#diagnosis').val({{ $case->diagnosis_id }});</script>
                                    @else
                                        <script>$('#diagnosis').val(' ');</script>
                                    @endif



                                </div>
                            </div>
                        </div>
                        <!-- /diagnosis source-->





<!-- is scheduled? -->
<div class="form-group">
    <div class="col-xs-12">
        <!-- description --->
        <label class="col-xs-4 control-label" for="patientisscheduled">
            <b>Appointment scheduled?:</b>
        </label>
        <!-- form field -->
        <div class="col-xs-4">
            <select class="form-control" tabindex="14" name="is_scheduled" id="is_scheduled" value="{{ $case->is_scheduled }}">
                <option value="1" >Yes</option>
                <option value="0" >No</option>
            </select>
            <script>$('#is_scheduled').val({{ $case->is_scheduled }});</script>
        </div>
    </div>
</div>
<!-- /is scheduled? -->


<!-- reason not scheduled -->
<div class="form-group">
    <div class="col-xs-12">
        <!-- description --->
        <label class="col-xs-4 control-label" for="patientreason">
            <b>Reason not scheduled:</b>
        </label>
        <!-- form field -->
        <div class="col-xs-4">
            <?php
            if ($case->reasonnotscheduled_id)
            {
                $reasonID = $case->reasonnotscheduled_id;
            } else
            {
                $reasonID = 'notdefined';
            }
            ?>
            <select class="form-control" tabindex="15" name="reason" id="reason">
                <option value="notdefined" disabled
                @if($reasonID == 'notdefined')
                    selected
                @endif
                >Not Defined</option>
                @if(count($reasons) != 0)
                    @foreach ($reasons as $reason)
                        <option value="{{ $reason->id }}">{{ $reason->description }}</option>
                    @endforeach
                @endif
            </select>
            <script>$('#reason').val('{{$reasonID}}');</script>

        </div>
    </div>
</div>
<!-- /reason not scheduled -->


<!-- date first appointment attended-->
<div class="form-group">
    <div class="col-xs-12">
        <!-- description --->
        <label class="col-xs-4 control-label" for="patientfirstapp">
            <b>Date first Appointment attended:</b>
        </label>
        <!-- form field -->
        <div class="col-xs-4">
            <div class="input-append date datefirst " id="dp1" data-date="" data-date-format="mm/dd/yyyy">
                <input class="span2 form-control" size="16" type="text" value="<?php if ($case->first_appointment){echo date("m/d/Y", strtotime($case->first_appointment));}?>" id="first_appointment_date" tabindex="16">
                <span class="add-on"><i class="icon-th"></i></span>
            </div>
        </div>
    </div>
</div>
<!-- /date first appointment attended -->


                        <!-- date first appointment attended-->
                        <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientfirstapp">
                                    <b>Date Free Evaluation attended:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <div class="input-append date datefirst " id="dp2" data-date="" data-date-format="mm/dd/yyyy">
                                        <input class="span2 form-control" size="16" type="text" value="<?php if ($case->free_evaluation){echo date("m/d/Y", strtotime($case->free_evaluation));}?>" id="free_evaluation_date" tabindex="16">
                                        <span class="add-on"><i class="icon-th"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /date first appointment attended -->

</fieldset>
</form>
</div>
<!-- here end forms -->
<div class="col-xs-10">
    <div class="pull-right">
        <button tabindex="25" type="button" class="btn btn-primary" id="case-update">Save</button>
        <button tabindex="26" type="button" class="btn btn-default" id="case-cancel">Cancel</button>
    </div>
</div>
</div>
</div>



</div>
<script type="text/javascript" src="{{asset('/js/bootstrap-datepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/patientdetailsfor.js')}}"></script>
<!--- stylesheet for only this view --->
<link rel="stylesheet" type="text/css"  href="{{asset('bootstrap/css/bootstrap-combobox.css')}}">
<!--- /stylesheet for only this view --->
<!--
<br><br>
-->
@stop

<!--
-->

