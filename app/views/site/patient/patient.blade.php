@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')
{{ Breadcrumbs::render('patients') }}
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
<div class="messagerouting"></div>
<div class="page-header">
<div class="row form-inline">
  <div class="col-md-4">
        <div id="searchpats" class="custom-search-input">
            <input tabindex="1" style="width: 100%;" class="typeahead form-control searchpatsinput" type="text" placeholder="Search">
        </div>    
         <span class="tt-dropdown-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none; right: auto;"><div class="tt-dataset-states"></div></span>
    </div>
    
    <div class="col-md-2">
        <button type="button" class="btn btn-primary" id="newpatientbutton">New Patient</button>
    </div>

<div class="col-md-6">
        <div style="display: inline-block;" class="column form-horizontal">
                <div class="columnsview">

				</div>
        </div>
        <div style="display: inline-block;" class="filters form-horizontal">
                <div class="filtersview">

                </div>
        </div>
</div>

  

</div>

</div>

<div id="collapseOne" class="panel-collapse collapse">

  
<div class="panel-body">
 <div class="row btn-group btn-group-justified">      
       <div class="col-md-4">
            <input type="text" tabindex="2" class="form-control" placeholder="Patient name" name="patientname" id="patientnameinput">
            
       </div>
         <?php
         // Breaking Code 1/2
             $referring_office = ReferringOffice::where('name', 'Direct Access')->where('practice_id', Auth::user()->practice->id)->first();
             $referral_source = ReferralSource::where('name', 'Direct Access')->where('referring_office_id', $referring_office->id)->first();
         ?>
       <div id="insert_combobox" class="col-md-4">
           <select class="form-control combobox" tabindex="3" name="inputreferrals" id="inputreferrals" value="">
            <option value="" disabled selected>Referral Source</option>
               <option value="{{$referral_source->id}}"> {{$referral_source->name}} </option>
				@if(count($referralsource) != 0)
                    @foreach ($referralsource as $referrals)
                        @if ($referrals->name != 'Direct Access')
                            <option value="{{ $referrals->id }}">{{ $referrals->name }}</option>
                        @endif
                    @endforeach
                  @endif
			</select>

       </div>
        <?php
            $act_type = ActivityTypes::where('name', 'Activity Type')->where('practice_id', Auth::user()->practice->id)->first();
            $act = Activities::where('campaign_name', 'Referral Source')->where('practice_id', Auth::user()->practice->id)->where('activity_type_id', $act_type->id)->first();
        ?>
       <div class="col-md-4">
             <select class="form-control combobox_2" tabindex="4" name="inputfindus" id="inputfindus" value="">
                 <option value="" disabled selected>How did you find us?</option>
                 <option value="{{$act_type->id}}" data-header=1>{{ $act_type->name }}</option>
                 <option class="findus_activity" value="{{ $act->campaign_name }}">— {{ $act->campaign_name }}</option>
                 @if(count($arr_activitytypes) != 0)
                    @foreach ($arr_activitytypes as $activities_type)
                        @if ($activities_type->name != 'Activity Type')
                            <option value="{{ $activities_type->id }}" data-header=1>{{ $activities_type->name }}</option>
                        @endif
                         @foreach($activities_type->activities as $activity)
                            @if ($activity->campaign_name != 'Referral Source')
                                <option class="findus_activity" value="{{ $activity->campaign_name }}">— {{ $activity->campaign_name }}</option>
                            @endif
						 @endforeach
                    @endforeach
                  @endif 
             </select>
        </div>
        
  </div>  
          
          <br>
          <div class="row  btn-group btn-group-justified">
                <div class="col-md-4">
                    <select class="form-control" tabindex="5" name="inputscheduled" id="inputscheduled" value="">
                         <option value="notdefined" disabled selected>Scheduled?</option>
                         <option value="1" >Yes</option>
                         <option value="0" >No</option>
                     </select>
                </div>
                <div class="col-md-4">
                     <select class="form-control" tabindex="6" name="inputreasonnotscheduled" id="inputreasonnotscheduled" value="">
                         <option value="notdefined" disabled selected>Reason not scheduled</option>
                         @foreach ($reasons as $reason)
                            <option value = {{ $reason->id }}> {{$reason->description}}</option>
                         @endforeach
                     </select>

                </div>
                <div class="col-md-4"><input tabindex="7" type="text" class="form-control" placeholder="Phone" name="patientphone" id="patientphonecollapse"></div>
          </div><br>
          <div class="row  btn-group btn-group-justified">
              <div class="col-md-4">
                  <select class="form-control" tabindex="8" name="inputdiagnosis" id="inputdiagnosis" value="">
                       <option value="notdefined" disabled selected>Diagnosis</option>
                       @if(count($arr_diagnoses) != 0)
                       @foreach ($arr_diagnoses as $diagnosis)
                           <option value="{{ $diagnosis->id }}">{{ $diagnosis->name }}</option>
                       @endforeach
                     @endif
                   </select>
              </div>
              <div class="col-md-4">
                  <select class="form-control" tabindex="8" name="location" id="location" value="">
                      @if (count($location) > 1)
                        <option value="" disabled selected>Location</option>
                      @endif
                      @if(count($location) != 0)
                          @foreach ($location as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                          @endforeach
                      @endif
                  </select>
              </div>
              <div class="col-md-3">
                  <div style="float: right;">
                      <button tabindex="10" type="button" class="btn btn-default" id="closenewpatient">Cancel</button>
                      <button tabindex="9" type="button" class="btn btn-primary" id="savenewpatient">Save</button>
                  </div>
                 </div>
              </div>
          {{--<br>--}}
          {{--<div class="row btn-group btn-group-justified">--}}
               {{--<div class="col-md-5"></div>--}}
               {{--<div class="col-md-4"></div>--}}
                  {{--<div class="col-md-3">--}}
                            {{--<div style="float: right;">--}}
                                {{--<button tabindex="8" type="button" class="btn btn-default" id="closenewpatient">Cancel</button>--}}
                                {{--<button tabindex="7" type="button" class="btn btn-primary" id="savenewpatient">Save</button>--}}
                            {{--</div>--}}
                   {{--</div>--}}
          {{--</div>--}}
   </div>
</div>

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
                            <label class="col-xs-6 control-label" for="doctornamedetails">
                                <b>Referral Source (Individual Name):</b>
                            </label>
                            
                            <div class="col-xs-6">
                                <input type="text" tabindex="1" placeholder=" " class="form-control"  name="doctornamedetails" id="doctornamedetails" value="">
                                <input type="hidden"  class="doctorid" name="doctorid" id="doctorid" value="">
                            </div>
                       </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            
                            <label class="col-xs-6 control-label" for="clinicnamedetails">
                                <b>Referring Group (Business Name):</b>
                            </label>
                            
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




<div class="page-header patientview">
  
</div>



<script type="text/javascript" src="{{asset('/js/clinicpatients.js')}}"></script>



<link rel="stylesheet" type="text/css"  href="{{asset('bootstrap/css/bootstrap-combobox.css')}}">


    </fieldset>
</form>

@stop
