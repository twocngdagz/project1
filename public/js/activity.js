$('#dp1').datepicker();
	$(document).ready(function(){
		getActivitiesToDefaultPlace();
		getColumnsList();
		$('.combobox').combobox({menu: '<ul class="typeahead typeahead-long dropdown-menu addact"></ul>'});
		$('#costinput').mask("#", {reverse: true, maxlength: false});
	});

	$('#quickaddreferral').on('shown.bs.modal', function (e) {
		$('#activitynamedetails').focus();
	});

	$('#buttonaddact').click(function()
	{
		var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
		var activitynamedetails = $.trim($('#activitynamedetails').val());

			$.ajax({
				type : "POST",
				url : "/activity/quickaddactivitytype",
				data : {'csrf_token':csrftoken, 'activitynamedetails':activitynamedetails},
				success: function(data){
					if (data == 'successsavinguser')
					{
						$.growl({ title: '<strong>Success:</strong> ', message: 'Activity Type added successfully!'
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
    $('#closenewactivity').click(function() { clearNewActivityForm();});
    $('#newpatientbutton').click(function()
    {// opening/closing patient form + focus/blur   
        if ($('#collapseOne').hasClass('in'))
        {
            $('#collapseOne').hide().removeClass('in');
			clearNewActivityForm();
        } else
        {
            $('#collapseOne').addClass('in').show();
            $('#campaigninput').focus();
        }
    });
	
	function getActivitiesToDefaultPlace()
	{//accessing by post Patietslist func
		$('.activityview').html(' ');
		var csrftoken = $('.csrf_token').val();
		$.ajax({
			type : "POST",
			url : "/activity/activitylist",
			data : {'csrf_token':csrftoken, 'getpatients':'1'},
			success: function(data){
				if (data)
				{
					
					$('.activityview').html('');
					$('.activityview').html(data);
				}
			}
		});
	}
	
	function getColumnsList()
	{// accessing by post to Columnslist func to columnsview
		$('.columnsview').html(' ');
		var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
		$.ajax({
			type : "POST",
			url : "/patient/columnslist",
			data : {'csrf_token':csrftoken, 'getcolumns':'1'},
			success: function(data){
				if (data)
				{
					$('.columnsview').html('');
					$('.columnsview').html(data);
				}
			}
		});
	}
	

	function saveNewActivity()
    {
        var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
        var campaigname = $.trim($('#campaigninput').val());
        var activity_type_id = $.trim($('#inputreferrals').val());
        var costinput = $('#costinput').val();
        var date = $('#patientfirstapp').val();
        var description = $('#newnote').val();

            $.ajax({
                type : "POST",
                url : "/activity/addactivity",
                data : {'csrf_token':csrftoken, 'campaigname':campaigname, 'costinput':costinput, 'date':date, 'description':description,'activity_type_id':activity_type_id},
                success: function(data){
                        if (data == 'successsavinguser')
                        {
							clearNewActivityForm();
                            getActivitiesToDefaultPlace(); //update patient list
							//resetNewActivityFormDropDowns();
                            $.growl({ title: '<strong>Success:</strong> ', message: 'Activity added successfully!'
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
    $('#savenewactivity').click(function(e)
    {
        saveNewActivity();
        e.preventDefault();
    });
    
	function clearNewActivityForm()
	{// clearing inputs
		refreshactivitycombobox();
		$('#dp1').datepicker('setDate', new Date('now'));
		$('#dp1').datepicker('update');
		$('#dp1').val('');
		$('#collapseOne').hide().removeClass('in');
		$('#collapseOne').blur();
		$('#campaigninput').val('');
		$('#costinput').val('');
		$('#patientfirstapp').val('');
		$('#newnote').val('');
		if($('.combobox-container').val() != ''){
			$('.combobox-container .dropdown-toggle').trigger('click');	
		}
		

	}

	function refreshactivitycombobox(){
		var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
		$.ajax({
			type : "POST",
			url : "/activity/refreshactivitycombobox",
			data : {'csrf_token':csrftoken},
			success: function(data){
				$('#insert_combobox').html('');
				$('#insert_combobox').html(data);
			}
		});
	}