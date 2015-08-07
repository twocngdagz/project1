@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')
{{ Breadcrumbs::render('activity') }}
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
<div class="messagerouting"></div>
  <div class="row form-inline">
  <!--
     <div class="col-md-3">

        <div id="searchpats" class="custom-search-input col-md-3">
            <input class="typeahead form-control searchpatsinput" type="text" placeholder="Search">
        </div>    
         <span class="tt-dropdown-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none; right: auto;"><div class="tt-dataset-states"></div></span>
        
    </div>
    -->
    <div class="col-md-3">
        <button type="button" class="btn btn-primary" id="newpatientbutton">New Activity</button>
    </div>

  

</div>

<!--- Collapsed New Patient adding form -->
<div id="collapseOne" class="panel-collapse collapse">
<div class="panel-body">
 <div class="row btn-group btn-group-justified">      
       <div class="col-md-6">
            <input type="text" tabindex="1" class="form-control" placeholder="Campaign name" name="campaignname" id="campaigninput">
            
       </div>

       <div id="insert_combobox" class="col-md-4">
           <select class="form-control combobox" tabindex="2" name="inputreferrals" id="inputreferrals" value="">
                <option value="" disabled selected>Activity Type</option>
				@if(count(Auth::user()->practice->activityTypes) != 0)
                    @foreach (Auth::user()->practice->activityTypes as $activity)
                            <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                    @endforeach
                 @endif
				</select>

       </div>
  </div>  
          
          <br>
 <div class="row  btn-group btn-group-justified">
                <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                 <input type="text" tabindex="3" class="form-control" placeholder="Cost" name="costname" id="costinput">
                </div>
                  
                </div>
				
                <div class="col-md-6">

                    <div data-date-format="mm/dd/yy" data-date="" id="dp1" class="input-append date datefirst ">
							
                        <input type="text" tabindex="4" id="patientfirstapp" value="" size="16" placeholder="Select date" class="span2 form-control">
                        <span class="add-on">
							<i class="icon-calendar"></i>
						</span>
                    </div>

                </div>
 </div><br>
 

 <!-- Description -->
  <div class="row  btn-group btn-group-justified">
		  <div class="col-md-12">
		  <textarea id="newnote" tabindex="5" name="newnote" rows="4" class="form-control"></textarea>
		  </div>
	
  </div></br>
 
          <div class="row btn-group btn-group-justified">
               <div class="col-md-5"></div>
               <div class="col-md-4"></div>
                  <div class="col-md-3">
                            <div style="float: right;">
                                <button tabindex="7" type="button" class="btn btn-default" id="closenewactivity">Cancel</button>
                                <button tabindex="6" type="button" class="btn btn-primary" id="savenewactivity">Add Activity</button>
                            </div>
                   </div>
          </div>
   </div>
</div>

<!-- Modal -->
<div class="modal fade" id="quickaddreferral" tabindex="-1" role="dialog" aria-labelledby="quickaddreferralLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="quickaddreferralLabel">Quick Add Activity Type</h4>
      </div>
      <div class="modal-body">
			<form class="form-horizontal" accept-charset="UTF-8">
                <fieldset>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <!-- description --->
                            <label class="col-xs-4 control-label" for="doctornamedetails">
                                <b>Activity name:</b>
                            </label>
                            <!-- form field -->
                            <div class="col-xs-6">
                                <input type="text" tabindex="1" placeholder=" " class="form-control"  name="activitynamedetails" id="activitynamedetails" value="">
                                <input type="hidden"  class="doctorid" name="doctorid" id="doctorid" value="">
                            </div>
                       </div>
                    </div>
                </fieldset>
            </form>
      </div>
      <div class="modal-footer">
        <button id="buttonaddact" tabindex="1" type="button" class="btn btn-primary">Add Activity Type</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->


<!--- /Collapsed New Patient adding form --->
<!--- Tables going wild --->
<div class="page-header activityview">
<!--- HERE we setuped ajax query patients list --->    
</div>
<!--- /Tables going wild --->

<!--- scripts for only this view --->
<script type="text/javascript" src="{{asset('/js/bootstrap-datepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('/bootstrap/js/bootstrap-combobox.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/activity.js')}}"></script>
<!--- /scripts for only this view --->
<!--- scripts for only this view --->

<!--- /scripts for only this view --->

<!--- stylesheet for only this view --->
<link rel="stylesheet" type="text/css"  href="{{asset('bootstrap/css/bootstrap-combobox.css')}}">
<!--- /stylesheet for only this view --->
<!--
<br><br>
-->


    </fieldset>
</form>

@stop
