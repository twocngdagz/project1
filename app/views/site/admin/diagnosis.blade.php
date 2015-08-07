@extends('site.layouts.default')

@section('title')

@parent
@stop

@section('content')
{{ Breadcrumbs::render('account-creation') }}
<div class="messagerouting"></div>
<!--- Header where buttons and info --->
<input type="hidden"  class="csrf_token" name="csrf_token" value="{{ csrf_token() }}">
<input type="hidden" id="practice_id" name="practice_id" value="{{$practice_id}}">
<div class="row">
    <div class="col-md-4 col-md-offset-4" style="text-align: center"><font size=+2><span>ClinicRise Admin Tools</span></font></div>
</div>
<div><hr></div>
{{link_to_action('AdminController@getPractice', 'Back', array(), array('role'=>'button', 'class'=>'btn btn-default'))}}
<div class="page-header">
    <table id="diagnosisTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Diagnosis Name</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($diagnosis as $diag)
        <tr>
            <td>{{Form::text('name', $diag->name, array('class'=>'form-control', 'style'=>'width:100%','id'=>$diag->id))}}</td>
            <td><a href="#" data-toggle="modal" data-target="#modalDiagnosis" data-practice="{{$practice_id}}" data-diagnosis="{{$diag->id}}">Delete</a> </td>
        </tr>
        @endforeach
        @foreach(range(1,10) as $i)
        <tr>
            <td>{{Form::text('name', null, array('class'=>'form-control', 'style'=>'width:100%'))}}</td>
            <td></td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<button class="btn btn-success" id="save-diagnosis">
    Save All
</button>

<div class="modal fade" id="modalDiagnosis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Diagnosis delete confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <a href="#" class="btn btn-primary" id="deleteLink">Yes</a>
            </div>
        </div>
    </div>
</div>
@stop
