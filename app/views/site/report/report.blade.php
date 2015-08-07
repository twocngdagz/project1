@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')
{{ Breadcrumbs::render('report') }}
<link rel="stylesheet" type="text/css"  href="{{asset('/css/jquery-ui-1.10.4.css')}}">
<link rel="stylesheet" type="text/css"  href="{{asset('/css/jquery.ui.theme.css')}}">
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
<div class="page-header">
    <div class="container">
        <div class="col-sm-2"><h3>Referrals </h3></div>
        <div class="col-sm-2">
            <!-- clinic filter --->
            <div class="filtersclinics form-horizontal dropdown input-group">
                <select id="oflocation_sel" multiple="multiple" style="display: none;">
                    @foreach(Auth::user()->practice->officeLocations as $cliniccopy)
                        <option id="{{ $cliniccopy->id }}" class="clinicfilter"> {{ $cliniccopy->name }}</option>
                    @endforeach
                </select>
                <input style="border-radius: 5px 0 0 5px;" id="custom_serch_clinic" class="form-control dropdown-toggle" data-toggle="dropdown" type="text" placeholder="Office Location">
                <span style="border-radius: 0 5px 5px 0;" class="input-group-addon">
                  <span class="caret"></span>
                </span>
                <ul id="oflocation_ul" class="dropdown-menu multiselect-container" style="max-height: 300px;overflow-y: auto;">

                </ul>
            </div>
            <!-- /clinic filter --->
        </div>
        <div class="col-sm-2">
            <!-- Diagnosis filter --->
            <div class="filtersdiagnosis form-horizontal dropdown input-group">
                <select id="diagnosis_sel" multiple="multiple" style="display: none;">
                    @foreach($diagnosis_arr as $diagnosis)
                        <option id="{{ $diagnosis->id }}" class="diagnosisfilter"> {{ $diagnosis->name }}</option>
                    @endforeach
                    <option id="-1" class="diagnosisfilter"> No option selected</option>
                </select>
                <input style="border-radius: 5px 0 0 5px;" id="custom_serch_diagnosis" class="form-control dropdown-toggle" data-toggle="dropdown" type="text" placeholder="Diagnosis">
                <span style="border-radius: 0 5px 5px 0;" class="input-group-addon">
                  <span class="caret"></span>
                </span>
                <ul id="diagnosis_ul" class="dropdown-menu multiselect-container" style="max-height: 300px;overflow-y: auto;">

                </ul>
            </div>
            <!-- /Diagnosis filter --->
        </div>
        <div class="col-sm-2">
            <!-- Marketing Activity/Source filter --->
            <div class="filtersactivity form-horizontal dropdown input-group">
                <select id="activity_sel" multiple="multiple" style="display: none;">
                    @if (Auth::user()->practice->activityTypes)
                        @foreach (Auth::user()->practice->activityTypes as $activities_type)
                            <option class="general_filter_activity" value="{{ $activities_type->id }}">{{ $activities_type->name }}</option>
                             @foreach($activities_type->activities as $activity)
                                    <option general_id="{{$activities_type->id}}" class="filter_activity" value="{{ $activity->id }}">{{ $activity->campaign_name }}</option>
                             @endforeach
                        @endforeach
                    @endif
                </select>
                    <input style="border-radius: 5px 0 0 5px;" id="custom_serch_activity" class="form-control dropdown-toggle" data-toggle="dropdown" type="text" placeholder="Activity/Source">
                   <span style="border-radius: 0 5px 5px 0;" class="input-group-addon">
                       <span class="caret"></span>
                   </span>
                <ul id="activity_ul" class="dropdown-menu multiselect-container" style="max-height: 300px;overflow-y: auto;min-width: 300px;">

                </ul>
            </div>
            <!-- /Marketing Activity/Source filter --->
        </div>
        <div class="col-sm-2"> 
            <!--- Filters referral source by clinics---->
                    <div class="filtersrefs form-horizontal">
                        <!--- here entered columns templatewithdata --->
                            <div class="filtersrefsview">

                            </div>
                    </div>

            <!--- /Filters referral source by clinics---->
        </div>
        <div class="col-sm-2"></div>
        <div class="col-sm-4"> 
            <!-- datepicker -->
            
            <div id="timeControls" class="pull-right">
                 <table id="picker-layout" class="table-bordered" style="width:100%;">
                     <tbody>
                     <tr>
                         <td id="activateSelector" style="border: 1px; border-radius: 5px; border-color: black;">
                             <input id="inputSelector" class="btn" readonly="readonly" size="10">-<input id="secondSelector" class="btn" readonly="readonly" size="10">

                         </td>
                     </tr>
                     </tbody>
                 </table>

                  <div id="timespanControls" class="btn-group">
                      <button data-timespan="week" class="btn btn-default" id="timespan-week" style="z-index: 1;">Week</button>
                      <button data-timespan="month" class="btn btn-default" id="timespan-month" style="z-index: 1;">Month</button>
                      <button data-timespan="quarter" class="btn btn-default" id="timespan-quarter" style="z-index: 1;">Quarter</button>
                      <button data-timespan="year" class="btn btn-default active" id="timespan-year" style="z-index: 1;">Year</button>
                  </div>
            </div>

            
 
                <!-- /datepicker -->
        </div>
    </div>
</div>
<div class="container">
    <!-- chart here -->
    <div class="row" id="chartforrange">
        <div class="col-md-6 chart-container">
            <div id="chartforrangeplace" class="chart-placeholder"></div>
        </div>
     </div>
     <!-- /chart here -->
     <!-- tabbed tables here -->
     <div class="row" id="tablesforrange">
        <ul class="nav nav-tabs">
          <li ><a href="#referralreporttab" data-toggle="tab">Referrals</a></li>
          <li class="active"><a href="#locationreporttab" data-toggle="tab">Location</a></li>
          <li ><a class="tab_diagnosis" href="#diagnosistab" data-toggle="tab">Diagnosis</a></li>
          <li ><a class="tab_activity" href="#activitytab" data-toggle="tab">Marketing Activity</a></li>
        </ul>
        <div class="tab-content">
           <!-- referrals tab -->
          <div class="tab-pane fade" id="referralreporttab">
              <div class="container">
                  <div class="row">
                    <div class="col-xs-12 referralsdiv">
                        referrals
                    </div>
                  </div>
              </div>
          </div>
          <!-- /referrals tab -->
          <!-- location tab -->
          <div class="tab-pane active fade in" id="locationreporttab">
              <div class="container">
                  <div class="row">
                    <div class="col-xs-12 locationsdiv">
                        location
                    </div>
                  </div>
              </div>
          </div>
          <!-- /location tab -->
          <!-- Diagnosis tab -->
	        <div class="tab-pane fade" id="diagnosistab">
	            <div class="container">
	                <div class="row">
	                  <div class="col-xs-12 diagnosisdiv">

	                  </div>
	                </div>
	            </div>
	        </div>
	        <!-- /Diagnosis tab -->
	        <!-- Marketing tab -->
            <div class="tab-pane fade" id="activitytab">
                <div class="container">
                    <div class="row">
                      <div class="col-xs-12 activitydiv">

                      </div>
                    </div>
                </div>
            </div>
            <!-- /Marketing tab -->
        </div>
     </div>
     <!-- /tabbed tables here -->
</div>

<br>
<script type="text/javascript" src="{{asset('/js/jquery-ui-1.10.4.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/jquery.ui.period.datepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/report.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/reportaddition.js')}}"></script>
<!--
<script type="text/javascript" src="{{asset('/js/datepickerus.js')}}"></script>
-->
@stop
