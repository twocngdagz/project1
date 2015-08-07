document.write("<script src='/js/jquery.maskedinput.js'><\/script>");
$(function(){
	$("#phone").mask("(999) 999-9999");
// updating notes when loaded
    function getNotes()
    {
        $('#notesalreadyadded').html(' ');
        $('#newnote').val('');
        var csrftoken = $('.csrf_token').val();
		var clinicid = $.trim($('#clinic_id').val());
        $.ajax({
            type: "POST",
            url:    "/clinic/noteslist",
            data: {'csrf_token':csrftoken,'clinicid':clinicid},
            success: function(data)
            {
                if (data)
                {
                    $('#notesalreadyadded').html(' ');
                    $('#notesalreadyadded').html(data);
                }
            }
        });
    }
    // 
    function getTasks()
    {
        $("#taskarea").html(' ');
        var csrftoken = $('.csrf_token').val();
		var clinicid = $.trim($('#clinic_id').val());
        $.ajax({
            type: "POST",
            url:    "/clinic/taskslist",
            data: {'csrf_token':csrftoken,'clinicid':clinicid},
            success: function(data)
            {
                if (data)
                {
                    $('#taskarea').html(' ');
                    $('#taskarea').html(data);
                }
            }
        });
    }

	function getClinicList()
	{
		$('#allclinicview').html(' ');
		var csrftoken = $('.csrf_token').val();
		$.ajax({
			type: "POST",
			url:    "/clinic/allcliniclist",
			data: {'csrf_token':csrftoken},
			success: function(data)
			{
				if (data)
				{
					$('#allclinicview').html(' ');
					$('#allclinicview').html(data);
				}
			}
		});
	}
    
    function getPatients()
    {
        $('#clinicpatientsarea').html(' ');
        var csrftoken = $('.csrf_token').val();
		var clinicid = $.trim($('#referral_source_id').val());
        $.ajax({
            type : "POST",
            url : "/clinic/patientslist",
            data : {'csrf_token':csrftoken,'clinicid':clinicid},
            success: function(data){
                    if (data)
                    {
                        //~ clearNewPatientForm();
                        $('#clinicpatientsarea').html('');
                        $('#clinicpatientsarea').html(data);
                    }
                }  
        });
    }
    // update info on load
    getNotes();
    getTasks();
	getClinicList();
    
    // add task modal eventing 
   $('#addtaskmodal').on('shown.bs.modal', function (e) 
   { // do focus for keyboard controlling on showing event...
        $(this).find('input:first').focus();
    });
   $('#addtaskmodal').on('hide.bs.modal', function (e) 
   { // do clearing inputs on hide modal
        $(this).blur();
        $(this).find('input').val('');
    });

    // adding task by modal
    function addTaskByModal()
    {
        var csrftoken = $('.csrf_token').val(); // laravel need it anytime everywhere if setuped filter for csrf protection ^_^
        var tasktitle = $.trim($('#tasktitle').val());
        //~ var taskdescription = $.trim($('#taskdescription').val());
        var taskdescription = ' ';
		var clinicid = $.trim($('#clinic_id').val());
        if (!tasktitle)
        {
            $('#addtaskmodal').modal('hide');
            $.growl({ title: '<strong>Error:</strong> ', message: 'Task Name required!'
                            },{ //~ type: 'danger'
                                type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                                placement: { from: 'top', align: 'right' },
                                delay: 6000
                            });
        } else
        {
            $.ajax({
            type : "POST",
            url : "/clinic/addnewtask",
            data : {'csrf_token':csrftoken, 'tasktitle':tasktitle, 'taskdescription':taskdescription,'clinicid':clinicid},
            success: function(data){
                    if (data == 'success')
                    {
                        $('#addtaskmodal').modal('hide');
                        getTasks();
                        $.growl({ title: '<strong>Success:</strong> ', message: 'New task added successfully!'
                            },{ //~ type: 'danger'
                                type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                                placement: { from: 'top', align: 'right' }
                            });
                    } else
                    {
                        $('#addtaskmodal').modal('hide');
                        $.growl({ title: '<strong>Error:</strong> ', message: data
                            },{ //~ type: 'danger'
                                type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                                placement: { from: 'top', align: 'right' }
                            });

                    }
                }
            });
        }
    }
    $('.addtasksavebutton').click(function()
    { 
        addTaskByModal();
    });
    
    $('#tasktitle').keydown(function(e)
    {
        if (e.which == 13)
        {
             addTaskByModal();
             e.preventDefault();
        }
    });
   
    //add Notes by ajax
    $('.addnotebutton').click(function()
    {// addnotes
        var csrftoken = $('.csrf_token').val();
        var textforsend = $('#newnote').val();
		var clinicid = $.trim($('#clinic_id').val());
        
        $.ajax({
                type : "POST",
                url : "/clinic/addnote",
                data : {'csrf_token':csrftoken, 'newnote':textforsend,'clinicid':clinicid},
                success: function(data)
                {
                    if (data == 'success')
                    {
                        getNotes();
                        $.growl({ title: '<strong>Success:</strong> ', message: 'You added note successfully!'
                            },{ //~ type: 'danger'
                                type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                                placement: { from: 'top', align: 'right' }
                            });
                    } else
                    {
                        $.growl({ title: '<strong>Error:</strong> ', message: 'You need to enter a note to textarea!'
                            },{ //~ type: 'danger'
                                type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                                placement: { from: 'top', align: 'right' }
                            });
                    }
                }
        });
    });
    
    //worked tabs
    $('#tabbingview li').click(function (e)
    { // working tabs
        e.preventDefault();
      $(this).tab('show');
    });
 
$('#taskarea').tooltip({
    selector: '[rel="tooltip"]'
});
//~ support function for plot
function gd(year, month, day) {
    return new Date(year, month, day).getTime();
}

$('[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var tabid = $(e.target).attr('id');// activated tab
    if (tabid == 'referralstabb')
    {

        var csrftoken = $('.csrf_token').val();
		var clinicid = $.trim($('#clinic_id').val());
        var referral_source_id = $.trim($('#referral_source_id').val());
        $.ajax({
            type: "POST",
            url:    "/clinic/getcrmdatachart",
            data: {'csrf_token':csrftoken,'clinicid':clinicid, 'referral_source_id': referral_source_id},
            success: function(data)
            {
                if (data)
                {
                    $.plot('#charttime', data, {
                        xaxis: {
                                mode: "time",
                                tickSize: [1, "month"],
                        }
                    });
                }
            }
        });

        getPatients();
    }
    
    if (tabid == 'notestabb')
    {
        getNotes();
    }
});

	// Search routings
	var clinicData = new Bloodhound({
		datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.value); },
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: '/clinic/clinicnameslist/%QUERY',
			filter: function ( clinicData ) {
				return $.map(clinicData, function (clinic) {
					return [{
						'value': clinic['name']
					}];
				});
			}
		},
		cache: false,
		limit:  25
	});

	clinicData.initialize();

	$('#searchclinic .typeahead').typeahead({
		hint: true,
		highlight: true,
		minLength: 1
	},
	{
		name: 'clinicData',
		displayKey: 'value',
		source: clinicData.ttAdapter()
	}).on('typeahead:opened',function(){$('.tt-dropdown-menu').css('width',$(this).width() + 'px');
	}).on("typeahead:selected", function(e, datum)
	{
		var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
		var clinicname =  $.trim(e.target.value);
		$.ajax({
			type: "POST",
			url:    "/clinic/oneclinicbyname",
			data:   {'csrf_token':csrftoken, 'name':clinicname},
			success: function(data)
			{
				window.location.href = '/clinic/details/id/'+data;
			}
		});
	}).on("typeahead:autocompleted", function(e, datum)
	{
		var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
		var clinicname =  $.trim(e.target.value);
		$.ajax({
			type: "POST",
			url:    "/clinic/oneclinicbyname",
			data:   {'csrf_token':csrftoken, 'name':clinicname},
			success: function(data)
			{
				window.location.href = '/clinic/details/id/'+data;
			}
		});
	});

	$('#closenewclinic').click(function() { clearNewClinicForm();});
	$('#newclinictbutton').click(function()
	{// opening/closing patient form + focus/blur
		if ($('#collapseOne').hasClass('in'))
		{
			$('#collapseOne').hide().removeClass('in');
			clearNewClinicForm();
		} else
		{
			$('#collapseOne').addClass('in').show();
			$('#clinicname').focus();
		}
	});

	function saveNewClinic()
	{
		var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
		var clinicname = $.trim($('#clinicname').val());
        var doctor = $.trim($('#doctor').val());
		var address = $.trim($('#address').val());
		var phone = $.trim($('#phone').val());
		var website = $.trim($('#website').val());
		var fax = $.trim($('#fax').val());

		$.ajax({
			type : "POST",
			url : "/clinic/addnewclinic",
			data : {'csrf_token':csrftoken, 'clinicname':clinicname,'address':address,'phone':phone,'website':website,'fax':fax, 'doctor': doctor},
			success: function(data){
				if (data == 'successsavinguser')
				{
					clearNewClinicForm();
					getClinicList();
					//getActivitiesToDefaultPlace(); //update patient list
					$.growl({ title: '<strong>Success:</strong> ', message: 'Clinic added successfully!'
					},{ //~ type: 'danger'
						type: 'success', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
						placement: { from: 'top', align: 'right' }
					});

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

	}
	$('#savenewclinic').click(function(e)
	{
		saveNewClinic();
		e.preventDefault();
	});

	function clearNewClinicForm()
	{// clearing inputs
		$('#collapseOne').hide().removeClass('in');
		$('#collapseOne').blur();
		$('#clinicname').val('');
		$('#address').val('');
		$('#phone').val('');
		$('#website').val('');
		$('#fax').val('');
	}

	$('#cancelupdateclinic').click(function(e)
	{
		var clinicid = $.trim($('#referral_source_id').val());
		window.location.href = '/clinic/details/id/'+clinicid;
		e.preventDefault();
	});
    $('#cancelupdatedoctor').click(function(e)
    {
        var clinicid = $.trim($('#doctor_id').val());
        window.location.href = '/clinic/details/id/'+clinicid;
        e.preventDefault();
    });
});
