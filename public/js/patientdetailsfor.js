document.write("<script src='/js/jquery.maskedinput.js'><\/script>");
document.write("<script src='/bootstrap/js/bootstrap-combobox.js'><\/script>");
$(function(){

$("#patientphonedetail").mask("(999) 999-9999");

$(document).ready(function(){
	$('.combobox').combobox({menu: '<ul class="typeahead typeahead-long dropdown-menu addreff"></ul>'});
    getCases();
    $(document).on('click', '.patient-checkin-evaluation', function(e)
    {
        e.preventDefault();
        e.stopPropagation();
        var case_id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: '/user/patient/checkin/evaluation',
            data: {
                csrf_token: $('input[name="csrf_token"]').val(),
                case_id: case_id
            },

            success: function (data) {
                getCases();
            }
        });
    });

    $(document).on('click', '.patient-checkin-appointment', function(e)
    {
        e.preventDefault();
        e.stopPropagation();
        var case_id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: '/user/patient/checkin/appointment',
            data: {
                csrf_token: $('input[name="csrf_token"]').val(),
                case_id: case_id
            },

            success: function (data) {
                getCases();
            }
        });
    });
});

$(document).ready(function(){
	$('.combobox_2').combobox({menu: '<ul class="typeahead typeahead-long dropdown-menu findusgeneralli"></ul>'});
});

$('#quickaddreferral').on('shown.bs.modal', function (e) {
	$('#doctornamedetails').focus();
});

function getCases()
{
    $.ajax({
        type: 'POST',
        url: '/user/patient/cases',
        data: {
            'patient_id': $('#patient_id').val(),
            'csrf_token': $('input[name="csrf_token"]').val()
        },
        success: function(data) {
            $('#case-table').html(data);
        }
    });
}

$('#buttonaddref').click(function()
{
	var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
	var doctornamedetails = $.trim($('#doctornamedetails').val());
	var clinicnamedetails = $.trim($('#clinicnamedetails').val());

	$.ajax({
		type : "POST",
		url : "/patient/quickaddreferral",
		data : {'csrf_token':csrftoken, 'doctornamedetails':doctornamedetails, 'clinicnamedetails':clinicnamedetails},
		success: function(data){
			if (data == 'successsavinguser')
			{
				$.growl({ title: '<strong>Success:</strong> ', message: 'Referral Source added successfully!'
				},{ //~ type: 'danger'
					type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
					placement: { from: 'top', align: 'right' }
				});
				$('#quickaddreferral').modal('hide');

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
	});
});

$('#goback').click(function(e)
{
    window.history.back();
    window.location.href = '/user/patient/';
});
$('#goback1').click(function(e)
{
    window.history.back();
    window.location.href = '/user/patient/';

});


$('#dp1').datepicker();
$('#dp2').datepicker();
$('#patientnamedetails').focus();

 
    // save/cancel buttons
    $('#savenewpatientdetails').click(function(e)
    {
        updatePatient();
        e.preventDefault();
    });
    $("#savenewpatientdetails").keyup(function(e)
    {
        if (e.which == 13)
        {
            updatePatient();
            e.preventDefault();
        }
    });
    $('#cancelnewpatientdetails').click(function(e)
    {
        window.location.href = '/user/patient';
        e.preventDefault();
    });
    $("#cancelnewpatientdetails").keyup(function(e)
    {
        if (e.which == 13)
        {
            window.location.href = '/user/patient';
            e.preventDefault();
        }
    });

    $('#case-update').click(function (e) {
        updateCase();
        e.preventDefault();
    });

    $('#case-update').keyup(function (e) {
        if (e.which == 13)
        {
            updateCase();
            e.preventDefault();
        }
    });$('#case-cancel').click(function (e) {
        window.location.href = '/user/patient/id/' + $('#patient_id').val();
        e.preventDefault();
    });

    $('#case-cancel').keyup(function (e) {
        if (e.which == 13)
        {
            window.location.href = '/user/patient/id/' + $('#patient_id').val();
            e.preventDefault();
        }
    });




    function updateCase()
    {
        var csrftoken = $('.csrf_token').val();
        var patient_id = $('#patient_id').val();
        var insurance = $('#insurance option:selected').val();
        var therapist = $('#therapist option:selected').val();
        var reason = $('#reason option:selected').val();
        var referral = $('#referral').val();
        var referral_office = $('#referral_office').val();
        var first_appointment_date = $('#first_appointment_date').val();
        var free_evaluation_date = $('#free_evaluation_date').val();
        var case_id = $('#case_id').val();
        var activity = $('#activity').val();
        var is_scheduled = $('#is_scheduled').val();
        var diagnosis = $('#diagnosis').val();


        $.ajax({
            type : "POST",
            url : "/patient/case/update",
            data: {
                csrf_token: csrftoken,
                case_id: case_id,
                insurance: insurance,
                therapist: therapist,
                reason: reason,
                referral: referral,
                referral_office: referral_office,
                first_appointment_date: first_appointment_date,
                free_evaluation_date: free_evaluation_date,
                activity: activity,
                is_scheduled: is_scheduled,
                diagnosis: diagnosis
            },
            success: function (data) {
                if (data == 'success')
                {
                    $.growl({ title: '<strong>Success:</strong> ', message: 'Patient case updated successfully!'
                    },{
                        type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' }
                    });
                    window.setTimeout(function() { window.location.href = '/user/patient/id/' + patient_id; }, 5000);
                } else
                {
                    if (data == 'neednameatleast')
                    {
                        $.growl({ title: '<strong>Error:</strong> ', message: 'Patient`s name is REQUIRED!'
                        },{
                            type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                            placement: { from: 'top', align: 'right' }
                        });
                    } else {
                        $.growl({ title: '<strong>Error:</strong> ', message: data
                        },{
                            type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                            placement: { from: 'top', align: 'right' }
                        });
                    }
                    //~ window.setTimeout(function() { $(".alert-danger").alert('close'); }, 5000);
                }
            }

        })

    }


    // updatePatient function
    
    function updatePatient()
    {
        var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
        var patientid = $.trim($('#patientid').val());
        var patientname = $.trim($('#patientnamedetails').val());
        var patientinsurance = $.trim($('#patientinsurance').val());
        var patienttherapist = $.trim($('#patienttherapist').val());
        var patientclinic = $.trim($('#patientclinic').val());
        var address1 = $.trim($('#address1').val());
        var address2 = $.trim($('#address2').val());
        var city = $.trim($('#city').val());
        var state = $.trim($('#state').val());
        var zip = $.trim($('#zip').val());
        var patientphonedetail = $.trim($('#patientphonedetail').val());
        var patientemail = $.trim($('#patientemail').val());
        var patientreferral = $.trim($('#inputreferrals').val());
		var inputdiagnosis = $.trim($('#patientdiagnosis').val());
        var patienthowfind = $.trim($('#patienthowfind').val());
        var patientisscheduled = $.trim($('#patientisscheduled').val());
        var patientreason1 = $.trim($('#patientreason').val()); // need preselect
        var patientfirstapp = $.trim($('#patientfirstapp').val());
        var patientdiagnos = $.trim($('#patientdiagnos').val());
        var patientbirth = $.trim($('#patientbirth').val());
        var patientsex = $.trim($('#patientsex').val());
        var patientemployer = $.trim($('#patientemployer').val());
        var patientworkstatus = $.trim($('#patientworkstatus').val());
        var patientoccupation = $.trim($('#patientoccupation').val());
        var patientfamily = $.trim($('#patientfamily').val());
        var patientnotes = $.trim($('#patientnotes').val());
        var patientreason = '';
        
        
        var pregphonedet = new RegExp('^[\+\\(\\)\0-9]+$');
        var resultphonetestdet = pregphonedet.test(patientphonedetail);
        
        if (resultphonetestdet)
        {
            $.ajax({
                type : "POST",
                url : "/patient/updatedetailedpatient",
                data : {'csrf_token':csrftoken, 'patientid':patientid, 'patientname':patientname, 'patientinsurance':patientinsurance, 'patienttherapist':patienttherapist, 'patientclinic':patientclinic, 'address1':address1, 'address2':address2, 'city':city, 'state':state, 'zip':zip, 'patientphonedetail':patientphonedetail, 'patientemail':patientemail, 'patientreferral':patientreferral, 'patienthowfind':patienthowfind, 'patientisscheduled':patientisscheduled, 'patientreason':patientreason1, 'patientfirstapp':patientfirstapp, 'patientdiagnos':patientdiagnos, 'patientbirth':patientbirth, 'patientsex':patientsex, 'patientemployer':patientemployer, 'patientworkstatus':patientworkstatus, 'patientoccupation':patientoccupation, 'patientfamily':patientfamily, 'patientnotes':patientnotes,'inputdiagnosis':inputdiagnosis},
                success: function(data){
                        if (data == 'success')
                        {
                            $.growl({ title: '<strong>Success:</strong> ', message: 'Patient details updated successfully!'
                                },{
                                    type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                                    placement: { from: 'top', align: 'right' }
                                });
                            window.setTimeout(function() { window.location.href = '/user/patient'; }, 5000);
                        } else
                        {
                            if (data == 'neednameatleast')
                            {
                                    $.growl({ title: '<strong>Error:</strong> ', message: 'Patient`s name is REQUIRED!'
                                    },{ 
                                        type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                                        placement: { from: 'top', align: 'right' }
                                    });
                            } else {
                                $.growl({ title: '<strong>Error:</strong> ', message: data
                                },{
                                    type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                                    placement: { from: 'top', align: 'right' }
                                });
                            }
                            //~ window.setTimeout(function() { $(".alert-danger").alert('close'); }, 5000);
                        }
                    }
                });
        } else
        {
            $.growl({ title: '<strong>Errors:</strong> ', message: 'Phone number must be 0-9 digits with +/(/) symbols!' },{ //~ type: 'danger'
                            type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                            placement: { from: 'top', align: 'right' },
                            delay: '6000'
                        });
        }
        
        
        
    }
    
// states    
    
    var substringMatcher = function(strs) {
      return function findMatches(q, cb) {
        var matches, substrRegex;
     
        // an array that will be populated with substring matches
        matches = [];
     
        // regex used to determine if a string contains the substring `q`
        substrRegex = new RegExp(q, 'i');
     
        // iterate through the pool of strings and for any string that
        // contains the substring `q`, add it to the `matches` array
        $.each(strs, function(i, str) {
          if (substrRegex.test(str)) {
            // the typeahead jQuery plugin expects suggestions to a
            // JavaScript object, refer to typeahead docs for more info
            matches.push({ value: str });
          }
        });
     
        cb(matches);
      };
    };
     
    var states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
      'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
      'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
      'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
      'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
      'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
      'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
      'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
      'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
    ];
     
    $('#state').typeahead({
      hint: true,
      highlight: true,
      minLength: 1
    },
    {
      name: 'states',
      displayKey: 'value',
      source: substringMatcher(states)
    });


    $(document).on('click', '#checkin', function() {
        $.ajax({
            type : "POST",
            url : "/patient/checkin",
            data : {
                'csrf_token':$('.csrf_token').val(),
                'patient_id':$('#patientid').val()
            },
            success: function(data){
                if (data == 'successsavinguser')
                {
                    $.growl({ title: '<strong>Success:</strong> ', message: 'Patient checkin successfully!'
                    },{ //~ type: 'danger'
                        type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' }
                    });
                    window.location.href = '/user/patient/id/'+$('#patientid').val();
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
        });
    });

    $('#case-save').on('click', function(e) {
        var referral = $('input[name="referral"]').val();
        var activity = $('input[name="activities"]').val();
        var scheduled = $('#scheduled').val();
        var diagnosis = $('#diagnosis').val();
        var csrf_token = $('input[name="csrf_token"]').val();
        var patient = $('input[name="patientid"]').val();

        $.ajax({
            type : "POST",
            url : "/user/patient/cases/add",
            data : {
                'csrf_token':csrf_token,
                'referral_id':referral,
                'activity_id':activity,
                'scheduled':scheduled,
                'diagnosis_id':diagnosis,
                'patient_id': patient
            },
            success: function (data) {
                if (data == 'success')
                {
                    $.growl({ title: '<strong>Success:</strong> ', message: 'Patient case added successfully!'
                    },{ //~ type: 'danger'
                        type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                        placement: { from: 'top', align: 'right' }
                    });
                    window.location.href = '/user/patient/id/'+$('#patientid').val();
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
        });
    });

    $(document).on('click', 'table tr', function(e) {
        var case_id = $(this).attr('id');
        window.location.href = '/user/case/id/'+ case_id;
    });
});
