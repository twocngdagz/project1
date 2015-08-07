@extends('site.layouts.default')

{{-- Title --}}
@section('title')

@parent
@stop

{{-- Content --}}
@section('content')
{{ Breadcrumbs::render('dashboard') }}
<!--- header panel buttons --->
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
<div class="page-header container">
  <div class="row form-inline">
        <div class="col-md-3">
            <font size=+2>Referral Conversions</font>
        </div>
<!--- filter clinics checkbox -->
<!--        <div class="col-md-3">-->
<!--			<select class="multiselect" multiple="multiple">-->
<!--				<option value="alemeda">Alemeda</option>-->
<!--				<option value="bernal_heights">Bernal Heights</option>-->
<!--				<option value="cow_hollow">Cow Hollow</option>-->
<!--				<option value="marina">Marina</option>-->
<!--				<option value="mission">Mission</option>-->
<!--			</select>-->
<!--         </div>-->
<!--- /filter clinics checkbox --->
<!--- date picker --->
        <div class="col-md-12">
               <div id="reportrange2" class="btn form-inline pull-right" style="display: inline-block; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                  <span>{{ date("F j, Y", mktime(0,0,0,1,1,date("Y"))) }}-{{ date("F j, Y", mktime(0,0,0,12,31,date("Y")))}}</span> <b class="caret"></b>
               </div>
         </div>
<!--- /date picker --->
  </div> 
</div>
<!--- /header panel buttons --->
<div class="container">
<!--- begin all --->
<!--- charts --->
 <div class="row form-inline">
    <div class="col-md-6">
	    <h2>Conversion Rate</h2>
	     <div style="width: 550px" class="chart-container">
	        <div id="charttimedash" class="chart-placeholder"></div>
	     </div>
     </div>
     <div class="col-md-6">
	     <h2>Reason not scheduled</h2>
	     <div style="width: 550px" class="chart-container">
	        <div id="chartpie" class="chart-placeholder"></div>
	     </div>
     </div>
 </div>
<!--- /charts --->

<div class="row conversion-table">
<!--- tables --->

</div>
<!--- /tables --->

<!--- end all --->
</div>


<!--- scripts for only this view --->
<script type="text/javascript" src="{{asset('/js/dashboard.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.multiselect').multiselect();
	});
</script>
<!--- /scripts for only this view --->

    </fieldset>
</form>

@stop
