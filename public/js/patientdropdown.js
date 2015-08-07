
	function getPatietnsToDefaultPlace()
	{//accessing by post Patietslist func
		$('.patientview').html(' ');
		var csrftoken = $('.csrf_token').val();
		$.ajax({
			type : "POST",
			url : "/patient/patientslist",
			data : {'csrf_token':csrftoken, 'getpatients':'1'},
			success: function(data){
				if (data)
				{
					clearNewPatientForm();
					$('.patientview').html('');
					$('.patientview').html(data);
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
					clearNewPatientForm();
					$('.columnsview').html('');
					$('.columnsview').html(data);
				}
			}
		});
	}

	function getFiltersList()
	{
		$('.filtersview').html(' ');
		var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
		$.ajax({
			type : "POST",
			url : "/patient/filterslist",
			data : {'csrf_token':csrftoken, 'getfilters':'1'},
			success: function(data){
				if (data)
				{
					$('.filtersview').html('');
					$('.filtersview').html(data);
				}
			}
		});
	}

	function clickColumns()
	{ //saving columns with updating it
		var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
		var dateinitiatedclm = $('.dateinitiatedclm').is(':selected');
		var patientclm = $('.patientclm').is(':selected');
		var findusclm = $('.findusclm').is(':selected');
		var insuranceclm = $('.insuranceclm').is(':selected');
		var isscheduled = $('.isscheduled').is(':selected');
		var reasonclm= $('.reasonclm').is(':selected');
		var referralclm = $('.referralclm').is(':selected');
		var clinicclm = $('.clinicclm').is(':selected');
		var valueclm = $('.valueclm').is(':selected');
		var diagnosisclm = $('.diagnosisclm').is(':selected');
		var showedupclm = $('.showedupclm').is(':selected');
        var phoneclm = $('.phoneclm').is(':selected');

		$.ajax({
			type : "POST",
			url : "/patient/savecolumns",
			data : {'csrf_token':csrftoken, 'dateinitiatedclm':dateinitiatedclm, 'patientclm':patientclm, 'findusclm':findusclm, 'insuranceclm':insuranceclm, 'isscheduled':isscheduled, 'reasonclm':reasonclm, 'referralclm':referralclm, 'clinicclm':clinicclm, 'valueclm':valueclm,'diagnosisclm':diagnosisclm,'showedupclm':showedupclm, 'phoneclm':phoneclm},
			success: function(data){
				if (data == 'success')
				{
					//getColumnsList(); //update list of checked
					getPatietnsToDefaultPlace(); //update patient list
				} else
				{
					$.growl({ title: '<strong>Error:</strong> ', message: 'Can`t send data to server, check your internet connection or try later!'
					},{ //~ type: 'danger'
						type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
						placement: { from: 'top', align: 'right' }
					});

				}
			}
		});
	}

	function clickFilters()
	{
		var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches

		var selectedvalues = [];
		$('.multiselect_1 :selected').each(function(i, selected) {
			selectedvalues[i] = $(selected).val();
		});

		$.ajax({
			type : "POST",
			url : "/patient/savefilters",
			data : {'csrf_token':csrftoken,'selectedvalues':JSON.stringify(selectedvalues)},
			success: function(data){
				if (data == 'success')
				{
					//getFiltersList(); //update list of checked
					getPatietnsToDefaultPlace(); //update patient list
				} else
				{
					$.growl({ title: '<strong>Error:</strong> ', message: 'Can`t send data to server, check your internet connection or try later!'
					},{ //~ type: 'danger'
						type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
						placement: { from: 'top', align: 'right' }
					});

				}
			}
		});

	}

	function clearNewPatientForm()
	{// clearing inputs
		$('#collapseOne').hide().removeClass('in');
		$('#collapseOne').blur();
		$('#patientnameinput').val('');
		$('#patientphonecollapse').val('');
		$('#patientnameinput').blur();
		$('#patientphonecollapse').blur();
		//$('.combobox-container .dropdown-toggle').trigger('click');
	}


