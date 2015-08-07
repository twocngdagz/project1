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
@if ($referralSource)
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-8"><font size=+2><span>{{$referralSource->referralOffice->name}} </span></font></div>
        <div class="col-xs-6 col-md-4"><form action="/clinic/editclinic/{{$referralSource->id}}"><button type="submit" class=" btn btn-default pull-right" id="editclinicbtn"><span class="glyphicon glyphicon-pencil"></span>&nbsp;Edit this Referral Office</button></form></div>
    </div>
<div><hr></div>
<input id="clinic_id" name="clinic_id" type="hidden" value="{{$referringOffice->id}}">
<input id="referral_source_id" name="referral_source_id" type="hidden" value="{{$referralSource->id}}">
<!--- /Header where buttons and info --->

<!--- Main window --->
<div class="page-header">
    <div class="container-fluid">
        <div class="row">
            <!--- Tabbed Notes/Referrals --->
            <div class="col-xs-12 col-sm-6 col-md-8">
                <!--- tabs --->
                <ul class="nav nav-tabs" role="tablist" id="tabbingview" >
                  <li class="active" data-toggle="tab" href="#notesTab" id="notestabb" ><a href="#notesTab" data-toggle="tab">Notes</a></li>
                  <li data-toggle="tab" href="#referralsTab" id="referralstabb"><a href="#referralsTab" data-toggle="tab">Referrals</a></li>
                </ul>
                <div class="tab-content">
                      <!-- Notes section --->
                      <div class="tab-pane fade in active" id="notesTab"><br>
                            <div style="padding: 15px;">
                            <p class="lead text-left">Add a note about <span>{{$referralSource->name}}</span></p>
                                <div style="padding-bottom: 40px;">
                                    <form  accept-charset="UTF-8">
                                        <textarea class="form-control" rows="4" name="newnote" id="newnote"></textarea>
                                        <input type="hidden"  name="csrf_token" value="{{ csrf_token() }}"><div style="height:5px;"></div>
                                    <button type="button" class="addnotebutton btn pull-right btn-success" id="editclinicbtn">Add note</button></form>
                                </div>
                                <div id="notesalreadyadded">
                                <!--- notes added HERE --->
                                </div>
                            </div>
                        </div>
                      <!-- Notes section --->
                      <!-- Referrals section --->
                      <div class="tab-pane fade" id="referralsTab">
                         <div class="center-block col-xs-12" style="magrin-top:20px;">
                            <center><div class="col-md-6 chart-container">
                                <div id="charttime" class="chart-placeholder" ></div>
                            </div></center>
                         </div>
                             <br>
                             <div class="col-xs-12 center-block" id="clinicpatientsarea">
                              <!--- clinic patient for 12 monthes will be here --->
                            </div>
                      </div>
                      <!-- /Referrals section --->
                </div>
                <!--- tabs --->
            </div>
            <!--- /Tabbed Notes/Referrals --->
            <!-- modal add task code -->
            <div class="modal fade" id="addtaskmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add New Task</h4>
                  </div>
                  <div class="modal-body">
                      <!-- forms -->
                   <form class="form-horizontal" accept-charset="UTF-8">
                    <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="tasktitle">Task Name</label>
                        <div class="col-md-6">
                            <input class="form-control" tabindex="1" placeholder="Enter task Name" type="text" name="tasktitle" id="tasktitle" value="" size="50">
                        </div>
                    </div>
            <!--
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="taskdescription">Task description</label>
                        <div class="col-md-6">
                            <input class="form-control" tabindex="2" placeholder="Enter task description" type="text" name="" id="taskdescription">
                        </div>
                    </div>
            -->
                    </fieldset>
                    </form>
                    <!-- /forms -->

                  </div>
                  <div class="modal-footer">
                    <button tabindex="3" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button tabindex="2" type="button" class="btn btn-primary addtasksavebutton">Save changes</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /modal add task code -->
            <!--- Right boxes --->
            <div class="col-xs-6 col-md-4">
             <div class="container-fluid">
              <h4><span>Tasks</span>    <a id="addtaskbutton" data-toggle="modal" data-target="#addtaskmodal" style="cursor: pointer;"><span class=" pull-right" style="text-decoration: underline;">Add task</span></a></h4>
              <div style="height:9px;"></div>
                <div class="span4" style="border: 1px solid lightgrey; border-radius:10px; padding: 15px; max-height:300px;">
                    <div  id="taskarea" style="border: 0px; padding-top:10px; padding-bottom:10px; max-height:265px; overflow:auto; scrollHeight: 270px;">
              <!-- task area HERE --->
                    </div>
                </div><div style="height:20px;"></div>
                <span class=""><b><h4>Contacts</h4></b></span>
                <div style="height:10px;"></div>
                <div class="span8" style="border: 1px solid lightgrey; border-radius:10px; padding: 15px;">
                    <div class="form-inline" >
                        @foreach ($referralSource->referralOffice->referralSources as $referral)
                            <div class="layout" style="">
                                <label class="text-left">&nbsp;<a href="{{action('ClinicController@getEditdoctor', $referral->id)}}" data-trigger="hover|click" data-toggle="tooltip" title="{{$referral->website}}">{{$referral->name}}</a> </label>
                                <span class="pull-right">{{$referral->referralOffice->phone}}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- <div style="height:20px;"></div>
                 <span class=""><b><h4>Office Information</h4></b></span>
                <div style="height:10px;"></div>
                <div class="span8" style="border: 1px solid lightgrey; border-radius:10px; padding: 15px;">
                     <div class="form-inline">
                         <?php
                                   $cliniclocation = OfficeLocation::where('practice_id', '=', $clinic->id)->first(); ?>
                        <div class="layout" style=""><label>&nbsp; {{ $cliniclocation->phone; }}</label><span class="pull-right">Office</span></div>
                        <div class="layout" style=""><label>&nbsp; {{ $cliniclocation->fax; }}</label><span class="pull-right">Fax</span></div>
                        <div class="layout" style=""><label>&nbsp;<a href="{{ $cliniclocation->website }}" target="_blank" data-trigger="hover|click" data-toggle="tooltip" title="{{ $cliniclocation->website }}">{{ $cliniclocation->website }}</a></label><span class="pull-right">Website</span></div>
                        <div class="layout" style=""><div class="row"><div class="col-md-6" style="width:70%; padding-right:10px;"><label>&nbsp;{{ $cliniclocation->address; }}</label></div><div class="col-sm-1 pull-right"><span class="pull-right">Address</span></div></div></div>
                    </div>
                </div> --->
              </div>
            </div>
        </div>
    <!--- /Right boxes --->
    </div>
<!--- /Main window --->
</div>

@else
<center><h2>There is no Referral Source by this id/name, maybe database fails</h2></center>
@endif

<!--- scripts for only this view --->
<script type="text/javascript" src="{{asset('/js/cliniccrm.js')}}"></script>
<!--- /scripts for only this view --->

    </fieldset>
</form>

@stop
