//////////////////////////
// ---CLINIC FILTER ---
//////////////////////////
var found_items=[];
var checked = [];
var check_option;

$("#oflocation_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input  class='check_all' type='checkbox' value='SelectAll'/><b>Select All</b></label></a></li>");
$("#oflocation_sel > option").each(function() {
	$("#oflocation_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input class='check_input' type='checkbox' value='"+this.text+"'/>"+this.text+"</label></a></li>");
});


$(".filtersclinics .input-group-addon").on('click',function(e){
	e.stopPropagation()
	$("#custom_serch_clinic.dropdown-toggle").dropdown('toggle');
});

//$('.dropdown-menu input, .dropdown-menu label').click(function(e) {
//        e.stopPropagation();
//});
//$('.dropdown').on('hide.bs.dropdown', function (e) {
//    e.stopPropagation()
//});

//$('.dropdown').on('hide.bs.dropdown', function (e) {
//    var target = $(e.target);
//    if(target.hasClass("open") || target.parents(".open").length)
//        return false; // returning false should stop the dropdown from hiding.
//});

$(document).on('keyup', '#custom_serch_clinic', function(){
	if(!$(".filtersclinics").hasClass('open')){
		$("#custom_serch_clinic.dropdown-toggle").dropdown('toggle');
	}

	var hold = $('#custom_serch_clinic').val();
	hold= hold.toLowerCase();
	found_items=[];

	$("#oflocation_sel > option").each(function() {
		this.text= this.text.toLowerCase();
		if ($(this).filter(":contains("+hold+")").length != 0) {
			found_items.push(this.text);
		}
	});
	$('#oflocation_ul').find('li').remove();

	if( $('#custom_serch_clinic').val().length == 0){
		if($.inArray('select all', checked) != -1){
			$("#oflocation_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input  class='check_all' type='checkbox' checked='checked' value='SelectAll'/><b>Select All</b></label></a></li>");
		} else {
			$("#oflocation_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input  class='check_all' type='checkbox' value='SelectAll'/><b>Select All</b></label></a></li>");
		}
	}

	$.each(found_items,function(index, value) {
		if($.inArray(this.toString(), checked) != -1){
			$("#oflocation_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input class='check_input' type='checkbox' checked='checked' value='"+this+"'/>"+this+"</label></a></li>");
		} else {
			$("#oflocation_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input class='check_input' type='checkbox' value='"+this+"'/>"+this+"</label></a></li>");
		}
	});
});
$(document).ready(function() {
        var input_diagnosis= $("#diagnosis_ul input");
        input_diagnosis.each(function() {
            $(this).prop('checked', true);
            checked_diagnosis.push($(this).parent().text().toLowerCase());
        });
        $("#diagnosis_sel > option").each(function() {
            $(this).prop('selected', true);
        });
        var input= $("#oflocation_ul input");
        input.each(function() {
            $(this).prop('checked', true);
            checked.push($(this).parent().text().toLowerCase());
        });

        $("#oflocation_sel > option").each(function() {
            $(this).prop('selected', true);
        });
        var input_activity= $("#activity_ul input");
        input_activity.each(function() {
            $(this).prop('checked', true);
            checked_activity.push($(this).parent().text().toLowerCase());
        });

        $("#activity_sel > option").each(function() {
            $(this).prop('selected', true);
        });
        dateReport.maincliniccodes.length = 0;
        $('#oflocation_sel > option:selected').each(function() {
            var a = $(this).attr('id');
            dateReport.maincliniccodes.push(a);
        });
        dateReport.diagnosiscodes.length = 0;
        $('#diagnosis_sel > option:selected').each(function() {
            var a = $(this).attr('id');
            dateReport.diagnosiscodes.push(a);
        });
        dateReport.activitycodes.length = 0;
        $('#activity_sel > option:selected').each(function() {
            if ($(this).hasClass('filter_activity'))
            {
                var a = $(this).val();
                dateReport.activitycodes.push(a);
            }
        });
        dateReport.getMainChart();
        dateReport.getLocationsTable();
        dateReport.getDiagnosisTable();
        dateReport.getActivityTable();
        dateReport.getReferralsTable();
});

$(document).on('click', '.check_all', function(){
	var input= $("#oflocation_ul input");
	if($(this).is(':checked')){
		checked=[];
		input.each(function() {
			$(this).prop('checked', true);
			checked.push($(this).parent().text().toLowerCase());
		});

		$("#oflocation_sel > option").each(function() {
			$(this).prop('selected', true);
		});
	} else {
		checked=[];
		input.each(function() {
			$(this).prop('checked', false);
		});

		$("#oflocation_sel > option").each(function() {
			$(this).prop('selected', false);
		});
	}
});

$(document).on('click', '.check_input',function(){
	//var input= $("#oflocation_ul input").not('.check_all').length;
	//var count_checked = 0;
	//$("#oflocation_ul input:checked").not('.check_all').each(function() {
	//	count_checked = count_checked +1;
	//});
	//if(input > count_checked && $("#oflocation_ul input:first").is(':checked') && $("#oflocation_ul input:first").hasClass('check_all')){
	//	$("#oflocation_ul input:first").prop('checked', false);
	//	checked.splice($.inArray('selectall', checked), 1);
	//} else if (input == count_checked && !$("#oflocation_ul input:first").is(':checked') && $("#oflocation_ul input:first").hasClass('check_all')){
	//	$("#oflocation_ul input:first").prop('checked', true);
	//	checked.push('selectall');
	//}

	var value = $(this).parent().text().toLowerCase();
	if($(this).is(':checked')){
		checked.push(value);
		check_option =$(this).parent().text();
		$("#oflocation_sel > option").each(function() {
			if (this.text == check_option ) {
				$(this).prop('selected', true);
			}
		});
	} else {
		checked.splice($.inArray(value, checked), 1);
		check_option =$(this).parent().text()
		$("#oflocation_sel > option").each(function() {
			if (this.text == check_option ) {
				$(this).prop('selected', false);
			}
		});
	}
});
//////////////////////////
// --- END CLINIC FILTER ---
//////////////////////////

//////////////////////////
// ---Diagnosis FILTER ---
//////////////////////////

var found_items_diagnosis=[];
var checked_diagnosis = [];
var check_option_diagnosis;

$("#diagnosis_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input  class='check_all_diagnosis' type='checkbox' value='SelectAll'/><b>Select All</b></label></a></li>");
$("#diagnosis_sel > option").each(function() {
	$("#diagnosis_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input class='check_input_diagnosis' type='checkbox' value='"+this.text+"'/>"+this.text+"</label></a></li>");
});

$(".filtersdiagnosis .input-group-addon").on('click',function(e){
	e.stopPropagation()
	$("#custom_serch_diagnosis.dropdown-toggle").dropdown('toggle');
});
//$('.dropdown-menu input, .dropdown-menu label').click(function(e) {
//        e.stopPropagation();
//});
//$('.dropdown').on('hide.bs.dropdown', function (e) {
//    e.stopPropagation()
//});

//$('.dropdown').on('hide.bs.dropdown', function (e) {
//    var target = $(e.target);
//    if(target.hasClass("open") || target.parents(".open").length)
//        return false; // returning false should stop the dropdown from hiding.
//});

$(document).on('keyup', '#custom_serch_diagnosis', function(){
	if(!$(".filtersdiagnosis").hasClass('open')){
		$("#custom_serch_diagnosis.dropdown-toggle").dropdown('toggle');
	}

	var hold_diagnosis = $('#custom_serch_diagnosis').val();
	hold_diagnosis= hold_diagnosis.toLowerCase();
	found_items_diagnosis=[];

	$("#diagnosis_sel > option").each(function() {
		this.text= this.text.toLowerCase();
		if ($(this).filter(":contains("+hold_diagnosis+")").length != 0) {
			found_items_diagnosis.push(this.text);
		}
	});
	$('#diagnosis_ul').find('li').remove();

	if( $('#custom_serch_diagnosis').val().length == 0){
		if($.inArray('select all', checked_diagnosis) != -1){
			$("#diagnosis_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input  class='check_all_diagnosis' type='checkbox' checked='checked' value='SelectAll'/><b>Select All</b></label></a></li>");
		} else {
			$("#diagnosis_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input  class='check_all_diagnosis' type='checkbox' value='SelectAll'/><b>Select All</b></label></a></li>");
		}
	}

	$.each(found_items_diagnosis,function(index, value) {
		if($.inArray(this.toString(), checked_diagnosis) != -1){
			$("#diagnosis_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input class='check_input_diagnosis' type='checkbox' checked='checked' value='"+this+"'/>"+this+"</label></a></li>");
		} else {
			$("#diagnosis_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input class='check_input_diagnosis' type='checkbox' value='"+this+"'/>"+this+"</label></a></li>");
		}
	});
});


$(document).on('click', '.check_all_diagnosis', function(){
	var input_diagnosis= $("#diagnosis_ul input");
	if($(this).is(':checked')){
		checked_diagnosis=[];
		input_diagnosis.each(function() {
			$(this).prop('checked', true);
			checked_diagnosis.push($(this).parent().text().toLowerCase());
		});

		$("#diagnosis_sel > option").each(function() {
			$(this).prop('selected', true);
		});
	} else {
		checked_diagnosis=[];
		input_diagnosis.each(function() {
			$(this).prop('checked', false);
		});

		$("#diagnosis_sel > option").each(function() {
			$(this).prop('selected', false);
		});
	}
});

$(document).on('click', '.check_input_diagnosis',function(){
	//var input= $("#oflocation_ul input").not('.check_all').length;
	//var count_checked = 0;
	//$("#oflocation_ul input:checked").not('.check_all').each(function() {
	//	count_checked = count_checked +1;
	//});
	//if(input > count_checked && $("#oflocation_ul input:first").is(':checked') && $("#oflocation_ul input:first").hasClass('check_all')){
	//	$("#oflocation_ul input:first").prop('checked', false);
	//	checked.splice($.inArray('selectall', checked), 1);
	//} else if (input == count_checked && !$("#oflocation_ul input:first").is(':checked') && $("#oflocation_ul input:first").hasClass('check_all')){
	//	$("#oflocation_ul input:first").prop('checked', true);
	//	checked.push('selectall');
	//}

	var value_diagnosis = $(this).parent().text().toLowerCase();
	if($(this).is(':checked')){
		checked_diagnosis.push(value_diagnosis);
		check_option_diagnosis =$(this).parent().text();
		$("#diagnosis_sel > option").each(function() {
			if (this.text == check_option_diagnosis ) {
				$(this).prop('selected', true);
			}
		});
	} else {
		checked_diagnosis.splice($.inArray(value_diagnosis, checked_diagnosis), 1);
		check_option_diagnosis =$(this).parent().text();
		$("#diagnosis_sel > option").each(function() {
			if (this.text == check_option_diagnosis ) {
				$(this).prop('selected', false);
			}
		});
	}
});
//////////////////////////
// --- END Diagnosis FILTER ---
//////////////////////////

//////////////////////////
// --- Marketing FILTER ---
//////////////////////////

var found_items_activity=[];
var checked_activity = [];
var check_option_activity;
var parrent_id=[];
var parrent=[];

$("#activity_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input  class='check_all_activity' type='checkbox' value='SelectAll'/><b>Select All</b></label></a></li>");
$("#activity_sel > option").each(function() {
	if($(this).hasClass('general_filter_activity')){
		$("#activity_ul").append("<ul id='ul_siblings"+$(this).val()+"'><li><a style='text-decoration: none !important;color: #000 !important;' href='javascript:void(0);'><label class='checkbox'><input parent='"+$(this).val()+"' class='check_input_general_activity' type='checkbox' value='"+this.text+"'/>"+this.text+"</label></a></li></ul>");
	}
});
$("#activity_sel > option").each(function() {
	if($(this).hasClass('filter_activity')){
		$("#ul_siblings"+$(this).attr('general_id')+"").append("<li style='margin-left: 15px;'><a style='text-decoration: none !important; color: #000 !important;' href='javascript:void(0);'><label style='font-weight: 400 !important;' class='checkbox'><input class='check_input_activity' type='checkbox' value='"+this.text+"'/>"+this.text+"</label></a></li>");
	}
});

//$('.dropdown-menu input, .dropdown-menu label').click(function(e) {
//        e.stopPropagation();
//});
//$('.dropdown').on('hide.bs.dropdown', function (e) {
//    e.stopPropagation();
//});

//$('.dropdown').on('hide.bs.dropdown', function (e) {
//    var target = $(e.target);
//    if(target.hasClass("open") || target.parents(".open").length)
//        return false; // returning false should stop the dropdown from hiding.
//});
$(".filtersactivity	.input-group-addon").on('click',function(e){
	e.stopPropagation()
	$("#custom_serch_activity.dropdown-toggle").dropdown('toggle');
});

$(document).on('keyup', '#custom_serch_activity', function(){
	if(!$(".filtersactivity").hasClass('open')){
		$("#custom_serch_activity.dropdown-toggle").dropdown('toggle');
	}

	var hold_activity = $('#custom_serch_activity').val();
	hold_activity= hold_activity.toLowerCase();
	found_items_activity=[];
	parrent_id=[];
	parrent=[];

	$("#activity_sel > option").each(function() {
		this.text= this.text.toLowerCase();
		if ($(this).filter(":contains("+hold_activity+")").length != 0) {
			found_items_activity.push(this.text);
			if($(this).hasClass('general_filter_activity')){
				parrent_id.push(0);
				parrent.push($(this).val());
			}else{
				parrent_id.push($(this).attr('general_id'));
				parrent.push(0);
			}
		}
	});

	$('#activity_ul').find('li').remove();
	$('#activity_ul').find('ul').remove();

	if( $('#custom_serch_activity').val().length == 0){
		if($.inArray('select all', checked_activity) != -1){
			$("#activity_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input  class='check_all_activity' type='checkbox' checked='checked' value='SelectAll'/><b>Select All</b></label></a></li>");
		} else {
			$("#activity_ul").append("<li><a href='javascript:void(0);'><label class='checkbox'><input  class='check_all_activity' type='checkbox' value='SelectAll'/><b>Select All</b></label></a></li>");
		}
	}

	$.each(found_items_activity,function(index, value) {
		if(parrent_id[index]==0){
			if($.inArray(this.toString(), checked_activity) != -1){
				$("#activity_ul").append("<ul id='ul_siblings"+parrent[index]+"'><li><a style='text-decoration: none !important;color: #000 !important;' href='javascript:void(0);'><label class='checkbox'><input parent='"+parrent[index]+"' class='check_input_general_activity' type='checkbox' checked='checked' value='"+this+"'/>"+this+"</label></a></li></ul>");
			} else {
				$("#activity_ul").append("<ul id='ul_siblings"+parrent[index]+"'><li><a style='text-decoration: none !important;color: #000 !important;' href='javascript:void(0);'><label class='checkbox'><input parent='"+parrent[index]+"' class='check_input_general_activity' type='checkbox' value='"+this+"'/>"+this+"</label></a></li></ul>");
			}
		}
	});
	$.each(found_items_activity,function(index, value) {
		if(parrent[index]== 0) {
			if($.inArray(this.toString(), checked_activity) != -1){
				if($("#ul_siblings"+parrent_id[index]+"").length) {
					$("#ul_siblings"+parrent_id[index]+"").append("<li style='margin-left: 15px;'><a style='text-decoration: none !important; color: #000 !important;' href='javascript:void(0);'><label style='font-weight: 400 !important;' class='checkbox'><input class='check_input_activity' type='checkbox' checked='checked' value='" + this + "'/>" + this + "</label></a></li>");
				}else{
					$("#activity_ul").append("<li style='margin-left: 15px;'><a style='text-decoration: none !important; color: #000 !important;' href='javascript:void(0);'><label style='font-weight: 400 !important;' class='checkbox'><input class='check_input_activity' type='checkbox' checked='checked' value='" + this + "'/>" + this + "</label></a></li>");
				}
			} else {
				if($("#ul_siblings"+parrent_id[index]+"").length) {
					$("#ul_siblings" + parrent_id[index] + "").append("<li style='margin-left: 15px;'><a style='text-decoration: none !important; color: #000 !important;' href='javascript:void(0);'><label style='font-weight: 400 !important;' class='checkbox'><input class='check_input_activity' type='checkbox' value='" + this + "'/>" + this + "</label></a></li>");
				}else{
					$("#activity_ul").append("<li style='margin-left: 15px;'><a style='text-decoration: none !important; color: #000 !important;' href='javascript:void(0);'><label style='font-weight: 400 !important;' class='checkbox'><input class='check_input_activity' type='checkbox' value='" + this + "'/>" + this + "</label></a></li>");
				}
			}
		}
	});
});


$(document).on('click', '.check_all_activity', function(){
	var input_activity= $("#activity_ul input");
	if($(this).is(':checked')){
		checked_activity=[];
		input_activity.each(function() {
			$(this).prop('checked', true);
			checked_activity.push($(this).parent().text().toLowerCase());
		});

		$("#activity_sel > option").each(function() {
			$(this).prop('selected', true);
		});
	} else {
		checked_activity=[];
		input_activity.each(function() {
			$(this).prop('checked', false);
		});

		$("#activity_sel > option").each(function() {
			$(this).prop('selected', false);
		});
	}
});

$(document).on('click', '.check_input_general_activity', function(){
	if($(this).is(':checked')){
		checked_activity.push($(this).parent().text().toLowerCase());
		var hold_ul = "#ul_siblings"+$(this).attr('parent');
		var hold_general =$(this).parent().text();
		$("#activity_sel > option").each(function () {
			if (this.text == hold_general) {
				$(this).prop('selected', true);
			}
		});
		$(hold_ul+" input.check_input_activity").each(function() {
			$(this).prop('checked', true);
			checked_activity.push($(this).parent().text().toLowerCase());
			var hold_siblings =$(this).parent().text();
			$("#activity_sel > option").each(function () {
				if (this.text == hold_siblings) {
					$(this).prop('selected', true);
				}
			});
		});
	} else {
		checked_activity.splice($.inArray($(this).parent().text().toLowerCase(), checked_activity), 1);
		var hold_ul = "#ul_siblings"+$(this).attr('parent');
		var hold_general =$(this).parent().text();
		$("#activity_sel > option").each(function () {
			if (this.text == hold_general) {
				$(this).prop('selected', false);
			}
		});
		$(hold_ul+" input.check_input_activity").each(function() {
			$(this).prop('checked', false);
			checked_activity.splice($.inArray($(this).parent().text().toLowerCase(), checked_activity), 1);
			var hold_siblings =$(this).parent().text();
			$("#activity_sel > option").each(function () {
				if (this.text == hold_siblings) {
					$(this).prop('selected', false);
				}
			});
		});
	}
});

$(document).on('click', '.check_input_activity',function(){
	//var input= $("#oflocation_ul input").not('.check_all').length;
	//var count_checked = 0;
	//$("#oflocation_ul input:checked").not('.check_all').each(function() {
	//	count_checked = count_checked +1;
	//});
	//if(input > count_checked && $("#oflocation_ul input:first").is(':checked') && $("#oflocation_ul input:first").hasClass('check_all')){
	//	$("#oflocation_ul input:first").prop('checked', false);
	//	checked.splice($.inArray('selectall', checked), 1);
	//} else if (input == count_checked && !$("#oflocation_ul input:first").is(':checked') && $("#oflocation_ul input:first").hasClass('check_all')){
	//	$("#oflocation_ul input:first").prop('checked', true);
	//	checked.push('selectall');
	//}

	var value_activity = $(this).parent().text().toLowerCase();
	if($(this).is(':checked')){
		checked_activity.push(value_activity);
		check_option_activity =$(this).parent().text();
		$("#activity_sel > option").each(function() {
			if (this.text == check_option_activity ) {
				$(this).prop('selected', true);
			}
		});
	} else {
		checked_activity.splice($.inArray(value_activity, checked_activity), 1);
		check_option_activity =$(this).parent().text();
		$("#activity_sel > option").each(function() {
			if (this.text == check_option_activity ) {
				$(this).prop('selected', false);
			}
		});
	}
});

//////////////////////////
// --- END Marketing FILTER ---
//////////////////////////