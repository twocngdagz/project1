document.write("<script src='/js/patientdropdown.js'><\/script>");
document.write("<script src='/js/jquery.maskedinput.js'><\/script>");
document.write("<script src='/bootstrap/js/bootstrap-combobox.js'><\/script>");

$(function(){
// getting initial data
	getColumnsList();
	getFiltersList();
	getPatietnsToDefaultPlace();

	$("#patientphonecollapse").mask("(999) 999-9999");

	$(document).ready(function(){
		$('.combobox').combobox({menu: '<ul class="typeahead typeahead-long dropdown-menu addreff"></ul>'});
	});

	$(document).ready(function(){
		$('.combobox_2').combobox({menu: '<ul class="typeahead typeahead-long dropdown-menu findusgeneralli"></ul>'});
	});

	$('#quickaddreferral').on('shown.bs.modal', function (e) {
		$('#doctornamedetails').focus();
	});

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

//start patients js 
// onload functions get worked after page load

//    // Columns form actions starts
//    $('#filtercolumns').click(function()
//    { //open/close  columns by columns button
//        if ($('.dropdown-show-columns').css('display') == 'none')
//        {
//            $('.dropdown-show-columns').show();
//            $('.dropdown-show-filters').hide();
//            $('#referralsmenu').hide();
//            $('#findusmenu').hide();
//            $('#is_scheduledmenu').hide();
//            $('#reasonnotscheduledmenu').hide();
//        } else
//        {
//            $('.dropdown-show-columns').hide();
//        }
//    });
//    $('#clmbuttoncancel').click(function()
//    { //close columns by Cancel button
//        $('.dropdown-show-columns').hide();
//    });
//    $('#clmbuttonclear').click(function()
//    { // clear columnschecks
//        $('.columnsview').find('input:checked').removeAttr('checked');
//    });
    
//    // Filters form actions starts
//    $('#filters').click(function()
//    { //open/close  columns by columns button
//        if ($('.dropdown-show-filters').css('display') == 'none')
//        {
//            $('.dropdown-show-filters').show();
//            $('.dropdown-show-columns').hide();
//            $('#referralsmenu').hide();
//            $('#findusmenu').hide();
//            $('#is_scheduledmenu').hide();
//            $('#reasonnotscheduledmenu').hide();
//        } else
//        {
//            $('.dropdown-show-filters').hide();
//        }
//    });
//    $('#filterbuttoncancel').click(function()
//    { //close columns by Cancel button
//        $('.dropdown-show-filters').hide();
//    });
//    $('#filterbuttonclear').click(function()
//    { // clear columnschecks
//        $('.filtersview').find('input:checked').removeAttr('checked');
//    });
    
    function resetNewPatientFormDropDowns()
    {

        $('#inputreferrals').val('notdefined');
        $('#inputfindus').val('notdefined');
        $('#inputscheduled').val('notdefined');
		$('#inputdiagnosis').val('notdefined');
        $('#inputreasonnotscheduled').val('notdefined');
    }
    
    function saveNewPatient()
    {
        var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
        var patientname = $.trim($('#patientnameinput').val());
        var patientphone = $.trim($('#patientphonecollapse').val());
        var inputreferrals = $('#inputreferrals').val();
        var inputfindus = $('#inputfindus').val();
        var inputscheduled = $('#inputscheduled').val();
		var inputdiagnosis = $('#inputdiagnosis').val();
        var inputreasonnotscheduled = $('#inputreasonnotscheduled').val();
        var location = $('#location').val();
        //testing phone number
        var pregphone = new RegExp('^[\+\\(\\)\0-9]+$');
        var resultphonetest = pregphone.test(patientphone);
        var ref_type = $( "#inputreferrals" ).hasClass("ref_activity");
		
        if (resultphonetest)
        {
            $.ajax({
                type : "POST",
                url : "/patient/addpatient",
                data : {
                    'csrf_token':csrftoken,
                    'patientname':patientname,
                    'patientphone':patientphone,
                    'inputreferrals':inputreferrals,
                    'inputfindus':inputfindus,
                    'inputscheduled':inputscheduled,
                    'inputreasonnotscheduled':inputreasonnotscheduled,
                    'ref_type':ref_type,
                    'inputdiagnosis':inputdiagnosis,
                    'location': location,
                },
                success: function(data){
                        if (data == 'successsavinguser')
                        {
                            clearNewPatientForm();
							$('.combobox-container .dropdown-toggle').trigger('click');
                            getPatietnsToDefaultPlace(); //update patient list
                            resetNewPatientFormDropDowns();
                            $.growl({ title: '<strong>Success:</strong> ', message: 'Patient added successfully!'
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
        } else 
        { 
                    $.growl({ title: '<strong>Errors:</strong> ', message: 'Phone number must be 0-9 digits with +/(/) symbols!' },{
                                    type: 'danger', animate: {  enter: 'animated fadeInRight',  exit: 'animated fadeOutRight' },
                                    placement: { from: 'top', align: 'right' },
                                    delay: '6000'
                                });
        }
        
        
        
    }
    $('#savenewpatient').click(function(e)
    {
        saveNewPatient();
        e.preventDefault();
    });

    $('#closenewpatient').click(function() { clearNewPatientForm(); resetNewPatientFormDropDowns();});
    $('#newpatientbutton').click(function()
    {// opening/closing patient form + focus/blur   
        if ($('#collapseOne').hasClass('in'))
        {
            $('#collapseOne').fadeOut(400, function()
            {
                $('#collapseOne').removeClass('in').height(0);
            });
            clearNewPatientForm();
        } else
        {
            $('#collapseOne').addClass('in').height(150);
            $('#collapseOne').fadeIn(400);
            $('#patientnameinput').focus();
        }
    });
    
     
   // focusin dropdowns fixes
   $('input').focusin(function()
   {
      $('.dropdown-show-filters').hide();
      $('.dropdown-show-columns').hide();
    });
    
 
    
    // Columns form actions end
    
   // Search routings
   var patientsData = new Bloodhound({
      datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.value); },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
            url: '/patient/patientsnameslist/%QUERY',
            filter: function ( patientsData ) {
                return $.map(patientsData, function (patient) {
                        return [{
                           'value': patient['name']
                            }];
                    });
            }
        },
        cache: false,
        limit:  25
    });
 
    patientsData.initialize();
 
    $('#searchpats .typeahead').typeahead({
      hint: true,
      highlight: true,
      minLength: 1
    },
    {
      name: 'patientsData',
      displayKey: 'value',
      source: patientsData.ttAdapter()
    }).on('typeahead:opened',function(){$('.tt-dropdown-menu').css('width',$(this).width() + 'px');
    }).on("typeahead:selected", function(e, datum)
    {
        var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
        var patname =  $.trim(e.target.value);
              $.ajax({
                    type: "POST",
                    url:    "/patient/onepatientbyname",
                    data:   {'csrf_token':csrftoken, 'name':patname},
                    success: function(data)
                    {
                        window.location.href = '/user/patient/id/'+data;
                    }
                });
    }).on("typeahead:autocompleted", function(e, datum)
    {
        var csrftoken = $('.csrf_token').val(); // don`t forget tokens, Laravel watches
        var patname =  $.trim(e.target.value);
              $.ajax({
                    type: "POST",
                    url:    "/patient/onepatientbyname",
                    data:   {'csrf_token':csrftoken, 'name':patname},
                    success: function(data)
                    {
                        window.location.href = '/user/patient/id/'+data;

                    }
                });
    });



    
    $(document).keyup(function(e)
    {
        if ((e.which == 78) && !$('#collapseOne').hasClass('in') && !$('input.typeahead').is(':focus') && !$('input').is(':focus'))
        {
                $('#collapseOne').addClass('in').height(150);
                $('#collapseOne').fadeIn(400);
                $('#patientnameinput').focus();
         }
        if (e.which == 83 && !$('input.typeahead').is(':focus') && !$('#collapseOne').hasClass('in') && !$('input').is(':focus'))
        {
            closeNewPatientWindow();
            $('input.typeahead').focus();
        }
    });
   
    $('input.typeahead').focusout(function()
    {
        $('input.typeahead').val('');
    });
    
    
    $("#patientnameinput").keydown(function(e)
    {
        if (e.which == 13)
        {
            saveNewPatient();
            e.preventDefault();
        }
    });
    $("#patientphonecollapse").keydown(function(e)
    {
        if (e.which == 13)
        {
            saveNewPatient();
            e.preventDefault();
        }
    });
    
    
    
    function closeNewPatientWindow()
    {
        $('#collapseOne').fadeOut(400, function()
        {
            $('#collapseOne').removeClass('in').height(0);
        });
        clearNewPatientForm();
    }

	$('#inputscheduled').on('change', function(){
		$("#inputscheduled option:selected").each(function () {
			if($(this).attr('value')==1){
				$('#inputreasonnotscheduled').prop("disabled", true);
				$("#inputreasonnotscheduled :first").attr("selected", "selected");
				$('#patientphonecollapse').focus();
			} else {
				$('#inputreasonnotscheduled').prop("disabled", false);
			}
		});
	});
	$('#inputscheduled').on('keydown',function(e) {
		var code = e.keyCode || e.which;
		if (code == '9') {
			$("#inputscheduled option:selected").each(function () {
				if($(this).attr('value')==1){
					$('#patientphonecollapse').focus();
				} else {
					$('#inputreasonnotscheduled').focus();
				}
			});
			return false;
		}
	});

    $('#inputfindus').change(function(e) { 
        var selected = $(this).find('option:selected');
        if (selected.data('header'))
        {
            $(this).val(selected.next().val());
            $(this).combobox('refresh');
        }
        // if (selected.data('header'))
        // {
        //     console.log($(selected:first);
        // }
    });

    // Search routings end
    
    //Keyboard
    //~ arrow = {left: 37, up: 38, right: 39, down: 40 };


 //end patients js
});
