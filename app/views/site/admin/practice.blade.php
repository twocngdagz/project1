@extends('site.layouts.default')

@section('title')

@parent
@stop


@section('styles')

/* The total progress gets shown by event listeners */
#total-progress {
opacity: 0;
transition: opacity 0.3s linear;
}

/* Hide the progress bar when finished */
#previews .file-row.dz-success .progress {
opacity: 0;
transition: opacity 0.3s linear;
}

/* Hide the delete button initially */
#previews .file-row .delete {
display: none;
}

/* Hide the start and cancel buttons and show the delete button */

#previews .file-row.dz-success .start,
#previews .file-row.dz-success .cancel {
display: none;
}
#previews .file-row.dz-success .delete {
display: block;
}
@@stop

@section('content')
{{ Breadcrumbs::render('account-creation') }}
<div class="messagerouting"></div>
<!--- Header where buttons and info --->
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
<div class="row">
    <div class="col-md-4 col-md-offset-4" style="text-align: center"><font size=+2><span>ClinicRise Admin Tools</span></font></div>
</div>
<div><hr></div>
{{link_to_action('AdminController@postAccountCreation', 'Create Account', array(), array('role'=>'button', 'class'=>'btn btn-success'))}}
<div class="page-header">
    <table id="tenantTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Company (Practice)</th>
            <th>Diagnosis</th>
            <th>Insurance</th>
            <th>Reason Not Schedule</th>
            <th>Location</th>
            <th>User</th>
            <th>Referral Source</th>
        </tr>
        </thead>
        <tbody>
        @foreach($practices as $practice)
        <tr>
            <td>{{$practice->name}}</td>
            <td>{{link_to_action('AdminController@editDiagnosis', 'Edit Diagnosis', array('practice_id'=>$practice->id))}}</td>
            <td>{{link_to_action('AdminController@editInsurance', 'Edit Insurance', array('practice_id'=>$practice->id))}}</td>
            <td>{{link_to_action('AdminController@editReason', 'Edit Reason', array('practice_id'=>$practice->id))}}</td>
            <td>{{link_to_action('AdminController@editLocation', 'Edit Location', array('practice_id'=>$practice->id))}}</td>
            <td>{{link_to_action('AdminController@showUsers', 'Users', array('practice_id'=>$practice->id))}}</td>
            <td><a href="#" data-toggle="modal" data-target="#referral_source_modal" data-practice="{{$practice->id}}" class="import-referrals">Import Referral Source</a></td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>


<div class="modal fade" id="referral_source_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Import Referral Source</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('files' => true, 'id' => 'uploadForm', 'action'=>array('AdminController@importReferralSource'), 'class'=>'dropzone')) }}

                {{ Form::close() }}
                <div class="table table-striped" class="files" id="previews">

                    <div id="template" class="file-row">
                        <!-- This is used as the file preview template -->
                        <div>
                            <span class="preview"><img data-dz-thumbnail /></span>
                        </div>
                        <div>
                            <p class="name" data-dz-name></p>
                            <strong class="error text-danger" data-dz-errormessage></strong>
                            <strong class="success text-success"></strong>
                        </div>
                        <div>
                            <p class="size" data-dz-size></p>
                            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-primary start">
                                <i class="glyphicon glyphicon-upload"></i>
                                <span>Start</span>
                            </button>
                            <button data-dz-remove class="btn btn-warning cancel">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                                <span>Cancel</span>
                            </button>
                            <button data-dz-remove class="btn btn-danger delete">
                                <i class="glyphicon glyphicon-trash"></i>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@stop
