@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')
{{ Breadcrumbs::render('clinic') }}
<div class="messagerouting"></div>
<!--- Header where buttons and info --->
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
    <div class="row">
        <div class="col-xs-6 col-md-4">
            <div id="searchclinic" class="custom-search-input">
				<input style="width: 100%;" class="typeahead form-control searchclinicsinput" tabindex="2" type="text" placeholder="Search">
            </div>
            <span class="tt-dropdown-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none; right: auto;"><div class="tt-dataset-states"></div></span>
		</div>
		<div class="col-md-3">
                <button type="button" class="btn btn-primary" id="newclinictbutton">New Referral Source</button>
        </div>
    </div>
<div><hr></div>
<!--- /Header where buttons and info --->

<!--- Collapsed New Clinic adding form -->
<div id="collapseOne" class="panel-collapse collapse">
<div class="panel-body">
 <div class="row btn-group btn-group-justified">
     <div class="col-md-4">
         <input type="text" tabindex="1" class="form-control" placeholder="Doctor Name" name="doctor" id="doctor">

     </div>
       <div class="col-md-4">
            <input type="text" tabindex="1" class="form-control" placeholder="Referral Source Name" name="clinicname" id="clinicname">

       </div>
        <div class="col-md-4">
                   <input type="text" tabindex="1" class="form-control" placeholder="Address" name="address" id="address">

      </div>
  </div>
  <br>
  <div class="row btn-group btn-group-justified">
      <div class="col-md-4">
          <input type="text" tabindex="1" class="form-control" placeholder="Phone" name="phone" id="phone">

      </div>
     <div class="col-md-4">
          <input type="text" tabindex="1" class="form-control" placeholder="Website" name="website" id="website">

     </div>
      <div class="col-md-4">
                 <input type="text" tabindex="1" class="form-control" placeholder="Fax" name="fax" id="fax">

    </div>

    </div>

          <br>

          <div class="row btn-group btn-group-justified">
               <div class="col-md-5"></div>
               <div class="col-md-4"></div>
                  <div class="col-md-3">
                            <div style="float: right;">
                                <button tabindex="7" type="button" class="btn btn-default" id="closenewclinic">Cancel</button>
                                <button tabindex="6" type="button" class="btn btn-primary" id="savenewclinic">Add Referral Source</button>
                            </div>
                   </div>
          </div>
   </div>
</div>

<!--- /Collapsed New Clinic adding form --->

<!--- Main window --->
<div class="page-header">
<div class="container-fluid">
<div class="row">

</div>
	<div id="allclinicview">

   </div>
</div>
</div>

<!--- scripts for only this view --->
<script type="text/javascript" src="{{asset('/js/cliniccrm.js')}}"></script>
<!--- /scripts for only this view --->

    </fieldset>
</form>

@stop
