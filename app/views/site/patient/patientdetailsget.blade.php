@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')
{{ Breadcrumbs::render('patients-edit', 1) }}
<div class="page-header">
<button class="btn btn-default" id="goback">Go Back</button>

@if(count($thepatient) != '0')
@foreach ($thepatient as $master)
<div class="row" style="margin-top: 20px; margin-bottom: 20px">
    <div class="col-md-12" id="case-table">
    </div>
</div>

<button class="btn btn-success" id="add-new-case" data-toggle="modal" data-target="#modal-case">Add New Case</button>


<!-- Modal -->
<div class="modal fade" id="modal-case" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add New Case</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 15px;">
                    <div id="insert_combobox" class="col-md-6">
                        <?php
                            $referring_office = ReferringOffice::firstOrCreate(array('name' => 'Direct Access', 'practice_id'=> Auth::user()->practice->id));
                            $referral_source = ReferralSource::firstOrCreate(array('name' => 'Direct Access', 'referring_office_id' => $referring_office->id));
                        ?>
                        <select class="form-control combobox" tabindex="3" name="referral" id="referral" value="">
                            <option value="" disabled selected>Referral Source</option>
                            <option value="{{$referral_source->id}}"> {{$referral_source->name}} </option>
                            @foreach ($referralsource as $referral)
                                @if ($referral->name != 'Direct Access')
                                    <option value="{{ $referral->id }}">{{ $referral->name }}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>

                    <?php
                        $act_type = ActivityTypes::firstOrCreate(array('name' => 'Activity Type', 'practice_id'=> Auth::user()->practice->id));
                        $act = Activities::firstOrCreate(array('campaign_name' => 'Referral Source', 'practice_id'=> Auth::user()->practice->id, 'activity_type_id'=> $act_type->id));
                    ?>

                    <div class="col-md-6">
                        <select class="form-control combobox_2" tabindex="4" name="activities" id="activities" value="">
                            <option value="" disabled selected>How did you find us?</option>
                            <option value="{{$act_type->id}}">{{ $act_type->name }}</option>
                            <option class="findus_activity" value="{{ $act->id }}">— {{ $act->campaign_name }}</option>
                            @foreach ($arr_activitytypes as $activity_type)
                                @if ($activity_type->name != 'Activity Type')
                                    <option value="{{ $activity_type->id }}">{{ $activity_type->name }}</option>
                                @endif
                                @foreach($activity_type->activities as $activity)
                                    @if ($activity->campaign_name != 'Referral Source')
                                        <option class="findus_activity" value="{{ $activity->id }}">— {{ $activity->campaign_name }}</option>
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-control" tabindex="5" name="scheduled" id="scheduled" value="">
                            <option value="" disabled selected>Scheduled?</option>
                            <option value="1" >Yes</option>
                            <option value="0" >No</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <select class="form-control" tabindex="8" name="diagnosis" id="diagnosis" value="">
                            <option value="" disabled selected>Diagnosis</option>
                            @foreach ($arr_diagnoses as $diagnosis)
                                <option value="{{ $diagnosis->id }}">{{ $diagnosis->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="case-save">Save </button>
            </div>
        </div>
    </div>
</div>


<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
<input type="hidden" id="patient_id" value="{{ $master->id }}">


<div class="container" id="patientdetailsglobal" style="margin-top: 10px;">
    <div class="row col-xs-12">
        
        <div class="col-xs-12">
            <h3>{{ $master->name }}`s details</h3>
            <input type="hidden"  class="patientid" name="patientid" value="{{ $master->id }}">
        </div>
        <!-- here begin forms -->
        <div class="col-xs-12" style="margin-top: 15px;">
          <form class="form-horizontal" accept-charset="UTF-8">
            <fieldset>
                
                <!-- patient name --> 
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientnamedetails">
                                    <b>Name:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <input type="text" tabindex="1" placeholder=" " class="form-control"  name="patientnamedetails" id="patientnamedetails" value="{{ $master->name }}">
                                    <input type="hidden"  class="patientid" name="patientid" id="patientid" value="{{ $master->id }}">
                                </div>
                           </div> 
                </div>
                <!-- /patient name -->

                <!-- clinic location -->
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientclinic">
                                    <b>Location:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                <?php
                                    if ($master->practice_location_id)
                                    {
                                        $loationID =  OfficeLocation::find($master->practice_location_id)->id;
                                    } else
                                    {
                                        $loationID = 'notdefined';
                                    }
                                ?>
                                    <select class="form-control" tabindex="4" name="patientclinic" id="patientclinic" value="{{ $loationID }}">
                                        <option value="notdefined" disabled
                                        @if($loationID == 'notdefined')
                                        selected
                                        @endif
                                        >Not Defined</option>
                                         @if(count($arr_locations) != 0)
                                            @foreach ($arr_locations as $cliniclocation)
                                                <option value="{{ $cliniclocation->id }}">{{ $cliniclocation->name }}</option>
                                            @endforeach
                                          @endif 
                                     </select>
                                    <script>$('#patientclinic').val({{ $loationID }});</script>
                                </div>
                           </div> 
                </div>
                <!-- /clinic location -->

                
                <!-- address-->
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="address1">
                                    <b>Address 1:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <input type="text" tabindex="5" placeholder=" " class="form-control"  name="address1" id="address1" value="{{ $master->address1 }}">
                                </div>
                           </div> 
                </div>
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="address2">
                                    <b>Address 2:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <input type="text" tabindex="6" placeholder=" " class="form-control"  name="address2" id="address2" value="{{ $master->address2 }}">
                                </div>
                           </div> 
                </div>
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="">
                                    <b>City / State / Zip:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <div style="padding-left: 0px;" class="col-xs-5"><input type="text" tabindex="7" class="form-control"  name="city" id="city"  value="{{ $master->city }}"></div>
                                    <div style="padding-left: 7px;padding-right:7px;" class="col-xs-4"><input type="text" tabindex="8" class="form-control"  name="state" id="state" value="{{ $master->state }}"></div>
                                    <div style="padding-right:0px;" class="col-xs-3"><input type="text" tabindex="9" class="form-control"  name="zip" id="zip" value="{{ $master->zip }}" maxlength="5"></div>
                                </div>
                           </div> 
                </div>
                <!-- /address -->
                

                <!-- phone -->
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientphonedetail">
                                    <b>Phone number:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <input type="text" tabindex="10" placeholder=" " class="form-control"  name="patientphonedetail" id="patientphonedetail" value="{{ $master->phone }}">
                                </div>
                           </div> 
                </div>
                <!-- /phone -->
                
                
                <!-- email -->
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientemail">
                                    <b>Email:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <input type="text" tabindex="11" placeholder=" " class="form-control"  name="patientemail" id="patientemail" value="{{ $master->email }}">
                                </div>
                           </div> 
                </div>
                <!-- /email -->

                <!-- date added -->
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientdateadded">
                                    <b>Date added:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <input type="text" tabindex="-1" placeholder="" class="form-control"  name="patientdateadded" id="patientdateadded" disabled value="{{ date("m/d/Y", strtotime($master->created_at)) }}">
                                </div>
                           </div>
                </div>
                <!-- /date added -->


                <!-- date updated -->
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientupdated">
                                    <b>date updated:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <input type="text" tabindex="-1" placeholder=" " class="form-control"  name="patientupdated" id="patientupdated" disabled value="{{ date("m/d/Y", strtotime($master->updated_at)) }}">
                                </div>
                           </div>
                </div>
                <!-- /date updated -->





                <!-- date of birth -->
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientbirth">
                                    <b>Date of Birth:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <div class="input-append date datefirst " id="dp2" data-date="" data-date-format="mm/dd/yyyy">
                                      <input class="span2 form-control" size="16" type="text" value="<?php if ($master->date_of_birth){echo date("m/d/Y", strtotime($master->date_of_birth));}?>" id="patientbirth" tabindex="18">
                                      <span class="add-on"><i class="icon-th"></i></span>
                                    </div>
                                </div>
                           </div>
                </div>
                <!-- /date of birth  -->


                <!-- Sex m/f -->
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientsex">
                                    <b>Sex:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <?php

                                            if ($master->sex)
                                            {
                                                $echosex =  $master->sex;
                                            } else
                                            {
                                                $echosex =  'notdefined';
                                            }

                                            ?>
                                    <select class="form-control" tabindex="19" name="patientsex" id="patientsex" >
                                         <option value="notdefined" disabled >Not Defined</option>
                                         <option value="Male" >Male</option>
                                         <option value="Female" >Female</option>
                                     </select>
                                    <script>$('#patientsex').val('{{ $echosex }}');</script>

                                </div>
                           </div>
                </div>
                <!-- /sex m/f -->


                <!-- employer-->
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientemployer">
                                    <b>Employer:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <input type="text" tabindex="20" placeholder=" " class="form-control"  name="patientemployer" id="patientemployer" value="{{ $master->employer }}">
                                </div>
                           </div>
                </div>
                <!-- /employer -->


                <!-- workstatus -->
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientworkstatus">
                                    <b>Work Status:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <?php
                                             if ($master->workstatus)
                                             {
                                                 $echowrk =  $master->workstatus;
                                             } else
                                             {
                                                 $echowrk = 'notdefined';
                                             }
                                             ?>
                                    <select class="form-control" tabindex="21" name="patientworkstatus" id="patientworkstatus" >
                                         <option value="notdefined" disabled >Not Defined</option>
                                         <option value="Not employed">Not employed</option>
                                          <option value="Self-Employed">Self-Employed</option>
                                          <option value="Part time working">Part time working</option>
                                          <option value="Employed">Employed</option>
                                     </select>
                                    <script>$('#patientworkstatus').val('{{ $echowrk }}');</script>
                                </div>
                           </div>
                </div>
                <!-- /workstatus -->


                <!-- occupation-->
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientoccupation">
                                    <b>Occupation:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <?php
                                             if ($master->occupation)
                                             {
                                                 $echoocc =  $master->occupation;
                                             } else
                                             {
                                                 $echoocc = 'notdefined';
                                             }
                                             ?>
                                    <select class="form-control" tabindex="21" name="patientoccupation" id="patientoccupation" >
                                         <option value="notdefined" disabled >Not Defined</option>
                                         <option value="Manager">Manager</option>
                                          <option value="Professional">Professional</option>
                                          <option value="Technician and associate professional">Technician and associate professional</option>
                                          <option value="Clerical support worker">Clerical support worker</option>
                                          <option value="Service and sales worker">Service and sales worker</option>
                                          <option value="Skilled agricultural, forestry and fishery worker">Skilled agricultural, forestry and fishery worker</option>
                                          <option value="Craft and related trades worker">Craft and related trades worker</option>
                                          <option value="Plant and machine operator, and assembler">Plant and machine operator, and assembler</option>
                                          <option value="Elementary  occupation">Elementary  occupation</option>
                                          <option value="Armed forces occupation">Armed forces occupation</option>
                                     </select>
                                    <script>$('#patientoccupation').val('{{ $echoocc }}');</script>

                                </div>
                           </div>
                </div>
                <!-- /occupation -->


                <!-- family orientation -->
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientfamily">
                                    <b>Family orientation:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <?php
                                             if ($master->family_orients)
                                             {
                                                 $echofamily = $master->family_orients;
                                             } else
                                             {
                                                 $echofamily = 'notdefined';
                                             }
                                             ?>

                                     <select class="form-control" tabindex="23" name="patientfamily" id="patientfamily" >
                                         <option value="notdefined" disabled >Not Defined</option>
                                         <option value="Single" >Single</option>
                                          <option value="Married">Married</option>
                                          <option value="Divorced">Divorced</option>
                                          <option value="Widowed">Widowed</option>
                                          <option value="Civil union">Civil union</option>
                                          <option value="Parent">Parent</option>
                                          <option value="Grandparent">Grandparent</option>
                                     </select>
                                    <script>$('#patientfamily').val('{{ $echofamily }}');</script>

                                </div>
                           </div>
                </div>
                <!-- /famili orientation -->


                <!-- notes -->
                <div class="form-group">
                            <div class="col-xs-12">
                                <!-- description --->
                                <label class="col-xs-4 control-label" for="patientnotes">
                                    <b>Notes:</b>
                                </label>
                                <!-- form field -->
                                <div class="col-xs-4">
                                    <textarea class="form-control" rows="4" name="patientnotes" id="patientnotes" tabindex="24">{{ $master->notes }}</textarea>
                                </div>
                           </div>
                </div>
                <!-- /notes -->
                
           </fieldset>
          </form>
        </div>
        <!-- here end forms -->
        <div class="col-xs-10">
            <div class="pull-right">
                  <button tabindex="25" type="button" class="btn btn-primary" id="savenewpatientdetails">Save</button>
                  <button tabindex="25" type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete-patient-modal" data-name="{{$master->name}}" data-id="{{$master->id}}">Delete</button>
                  <button tabindex="26" type="button" class="btn btn-default" id="cancelnewpatientdetails">Cancel</button>
            </div>
        </div>
    </div>
</div>



@endforeach
@else
<center><h2>There is no patient by this id/name, maybe database fails</h2></center>
@endif

<!-- Modal -->
<div class="modal fade" id="quickaddreferral" tabindex="-1" role="dialog" aria-labelledby="quickaddreferralLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="quickaddreferralLabel">Quick Add Referral Source</h4>
      </div>
      <div class="modal-body">
			<form class="form-horizontal" accept-charset="UTF-8">
                <fieldset>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <!-- description --->
                            <label class="col-xs-4 control-label" for="doctornamedetails">
                                <b>Doctor name:</b>
                            </label>
                            <!-- form field -->
                            <div class="col-xs-6">
                                <input type="text" tabindex="1" placeholder=" " class="form-control"  name="doctornamedetails" id="doctornamedetails" value="">
                                <input type="hidden"  class="doctorid" name="doctorid" id="doctorid" value="">
                            </div>
                       </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <!-- description --->
                            <label class="col-xs-4 control-label" for="clinicnamedetails">
                                <b>Office Name:</b>
                            </label>
                            <!-- form field -->
                            <div class="col-xs-6">
                                <input type="text" tabindex="1" placeholder=" " class="form-control"  name="clinicnamedetails" id="clinicnamedetails" value="">
                                <input type="hidden"  class="clinicid" name="clinicid" id="clinicid" value="">
                            </div>
                       </div>
                    </div>
                </fieldset>
            </form>
      </div>
      <div class="modal-footer">
        <button id="buttonaddref" tabindex="1" type="button" class="btn btn-primary">Add Referral Source</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->



<!-- Modal -->
<div class="modal fade" id="delete-patient-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="delete-patient">Delete</button>
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

<script>
    $(document).ready(function() {
        $('#delete-patient-modal').on('show.bs.modal', function (e) {
            var button = $(e.relatedTarget);
            var name = button.data('name');
            var id = button.data('id');
            $('#delete-patient-modal .modal-title').text('Delete ' + name);
            $('#delete-patient-modal .modal-body').text('Are you want to delete patient ' + name + '?');
            $('#delete-patient').data('id', id);

        });

        $('#delete-patient').on('click', function (e) {
            $.ajax({
                type: 'POST',
                url: '/patient/delete',
                data: {
                    csrf_token: $('.csrf_token').val(),
                    id: $(this).data('id')
                },
                success: function (data) {
                    if (data === true)
                    {
                        $.growl({ title: '<strong>Success:</strong> ', message: 'Patient deleted successfully'
                        },{ //~ type: 'danger'
                            type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                            placement: { from: 'top', align: 'right' }
                        });
                        window.setTimeout(function() { window.location.href = '/user/patient'; }, 2000);
                    } else
                    {
                        $.growl({ title: '<strong>Errors:</strong> ', message: data
                        },{ //~ type: 'danger'
                            type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                            placement: { from: 'top', align: 'right' },
                            delay: '6000'
                        });

                    }
                }
            })
        });
    });
</script>
@stop

<!--
-->

