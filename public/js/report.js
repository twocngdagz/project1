$(function(){
	// main datereport list of objects
	window.dateReport = {
		startNowDate: $.datepicker.formatDate('mm/dd/yy', new Date(new Date().getFullYear(), 0, 1)),
		endNowDate: $.datepicker.formatDate('mm/dd/yy', new Date(new Date().getFullYear(), 11, 31)),
		timedivision: 'year',
		cliniccodes: [],
		referralsourcecodes: [],
		maincliniccodes: [],
		diagnosiscodes: [],
		activitycodes: [],
		csrftoken: $('.csrf_token').val(),
		xhrPool: [],

		get_all_by_dateRanges: function()
		{ // getting all data after pickerchanged
			dateReport.getMainChart();
			dateReport.getActivityTable();
			dateReport.getDiagnosisTable();
			dateReport.getLocationsTable();
			dateReport.getReferralsTable();
		},
		xhrPoolabortAll : function() {
			$(dateReport.xhrPool).each(function(idx, jqXHR) {
				jqXHR.abort();
			});
			dateReport.xhrPool.length = 0
		},
		getMainChart: function()
		{ // getting chart
			$.ajax({
				type: "POST",
				url:    "/report/getchartbyrange",
				data: {'csrf_token':dateReport.csrftoken, 'startdate':dateReport.startNowDate, 'enddate':dateReport.endNowDate, 'timedivision':dateReport.timedivision, 'clinicrefcodes':JSON.stringify(dateReport.cliniccodes), 'referralrefcodes':JSON.stringify(dateReport.referralsourcecodes), 'maincliniccodes':JSON.stringify(dateReport.maincliniccodes),'diagnosiscodes':JSON.stringify(dateReport.diagnosiscodes),'activitycodes':JSON.stringify(dateReport.activitycodes)},
				success: function(data)
				{
					if (data)
					{
						window.plot = $.plot('#chartforrangeplace', data, {
							xaxis: {
								mode: "time",//~ tickSize: [1, "month"],
							},
                            yaxis: {
                                min: 0,
                                tickDecimals: 0
                            },
                            series: {
                                lines: {
                                    show: true
                                },
                                points: {
                                    width: 0.1,
                                    show: true
                                }
                            },
                            grid: {
                                hoverable: true,
                                clickable: true
                            }
						});
					}
				}
			});
		},
		getLocationsTable: function()
		{
			$.ajaxSetup({
				beforeSend: function(jqXHR) { // before jQuery send the request we will push it to our array
					dateReport.xhrPool.push(jqXHR);
				},
				complete: function(jqXHR) { // when some of the requests completed it will splice from the array
					var index = dateReport.xhrPool.indexOf(jqXHR);
					if (index > -1) {
						dateReport.xhrPool.splice(index, 1);
					}
				}
			});
			$.ajax({
				type: "POST",
				url:    "/report/getpracticetable",
				data: {'csrf_token':dateReport.csrftoken, 'startdate':dateReport.startNowDate, 'enddate':dateReport.endNowDate, 'timedivision':dateReport.timedivision, 'maincliniccodes':JSON.stringify(dateReport.maincliniccodes), 'clinicrefcodes':JSON.stringify(dateReport.cliniccodes), 'referralrefcodes':JSON.stringify(dateReport.referralsourcecodes),diagnosiscodes:JSON.stringify(dateReport.diagnosiscodes),activitycodes:JSON.stringify(dateReport.activitycodes)},
				success: function(data)
				{
					if (data)
					{
						$('.locationsdiv').html('');
						$('.locationsdiv').html(data);
					}
				}
			});
		},
		getDiagnosisTable: function()
		{
			$.ajaxSetup({
				beforeSend: function(jqXHR) { // before jQuery send the request we will push it to our array
					dateReport.xhrPool.push(jqXHR);
				},
				complete: function(jqXHR) { // when some of the requests completed it will splice from the array
					var index = dateReport.xhrPool.indexOf(jqXHR);
					if (index > -1) {
						dateReport.xhrPool.splice(index, 1);
					}
				}
			});
			$.ajax({
				type: "POST",
				url:    "/report/getdiagnosistable",
				data: {'csrf_token':dateReport.csrftoken, 'startdate':dateReport.startNowDate, 'enddate':dateReport.endNowDate, 'timedivision':dateReport.timedivision, 'maincliniccodes':JSON.stringify(dateReport.maincliniccodes), 'clinicrefcodes':JSON.stringify(dateReport.cliniccodes), 'referralrefcodes':JSON.stringify(dateReport.referralsourcecodes),diagnosiscodes:JSON.stringify(dateReport.diagnosiscodes),activitycodes:JSON.stringify(dateReport.activitycodes)},
				success: function(data)
				{
					if (data)
					{
						//$('#tablesforrange a.tab_activity').removeClass('active');
						//$('#tablesforrange a.tab_diagnosis').tab('show');
						$('.diagnosisdiv').html('');
						$('.diagnosisdiv').html(data);
					}
				}
			});
		},
		getActivityTable: function()
		{
			$.ajaxSetup({
				beforeSend: function(jqXHR) { // before jQuery send the request we will push it to our array
					dateReport.xhrPool.push(jqXHR);
				},
				complete: function(jqXHR) { // when some of the requests completed it will splice from the array
					var index = dateReport.xhrPool.indexOf(jqXHR);
					if (index > -1) {
						dateReport.xhrPool.splice(index, 1);
					}
				}
			});
			$.ajax({
				type: "POST",
				url:    "/report/getactivitytable",
				data: {'csrf_token':dateReport.csrftoken, 'startdate':dateReport.startNowDate, 'enddate':dateReport.endNowDate, 'timedivision':dateReport.timedivision, 'maincliniccodes':JSON.stringify(dateReport.maincliniccodes), 'clinicrefcodes':JSON.stringify(dateReport.cliniccodes), 'referralrefcodes':JSON.stringify(dateReport.referralsourcecodes),diagnosiscodes:JSON.stringify(dateReport.diagnosiscodes),activitycodes:JSON.stringify(dateReport.activitycodes)},
				success: function(data)
                {
					if (data)
					{
						//$('#tablesforrange a.tab_activity').tab('show');
						$('.activitydiv').html('');
						$('.activitydiv').html(data);
					}
				}
			});
		},
		getReferralsTable: function()
		{
			$.ajaxSetup({
				beforeSend: function(jqXHR) { // before jQuery send the request we will push it to our array
					dateReport.xhrPool.push(jqXHR);
				},
				complete: function(jqXHR) { // when some of the requests completed it will splice from the array
					var index = dateReport.xhrPool.indexOf(jqXHR);
					if (index > -1) {
						dateReport.xhrPool.splice(index, 1);
					}
				}
			});
			$.ajax({
				type: "POST",
				url:    "/report/getreferralstable",
				data: {'csrf_token':dateReport.csrftoken, 'startdate':dateReport.startNowDate, 'enddate':dateReport.endNowDate, 'timedivision':dateReport.timedivision, 'maincliniccodes':JSON.stringify(dateReport.maincliniccodes), 'clinicrefcodes':JSON.stringify(dateReport.cliniccodes), 'referralrefcodes':JSON.stringify(dateReport.referralsourcecodes),diagnosiscodes:JSON.stringify(dateReport.diagnosiscodes),activitycodes:JSON.stringify(dateReport.activitycodes)},
				success: function(data)
				{
					if (data)
					{
						$('.referralsdiv').html('');
						$('.referralsdiv').html(data);
					}
				}
			});
		},
		/*
		 initReferralSourceFilter: function()
		 {
		 $.ajax({
		 type: "POST",
		 url:    "/report/initreferralfilter",
		 data: {'csrf_token':dateReport.csrftoken},
		 success: function(data)
		 {
		 if (data)
		 {
		 $('.filtersrefsview').html('');
		 $('.filtersrefsview').html(data);
		 }
		 }
		 });
		 },
		 */
		initClinicFilter: function()
		{
			$.ajax({
				type: "POST",
				url:    "/report/initclinicfilter",
				data: {'csrf_token':dateReport.csrftoken},
				success: function(data)
				{
					if (data)
					{
						$('.filtersclinicsview').html('');
						$('.filtersclinicsview').html(data);
					}
				}
			});
		},
		setDefaultToYearPicker: function()
		{
			$('#timeControls #picker-layout #inputSelector').datepicker({
				changeYear: true,
				onClose: function()
				{
					$('#ui-datepicker-div').removeClass('hide-calendar');
					$('#ui-datepicker-div').removeClass('hide-arrow-next');
					$('#ui-datepicker-div').removeClass('hide-arrow-prev');
					$('#ui-datepicker-div').removeClass('hide-month');
					dateReport.get_all_by_dateRanges();
				},
				beforeShow: function(e, b)
				{
					$('#ui-datepicker-div').addClass('hide-calendar');
					$('#ui-datepicker-div').addClass('hide-arrow-next');
					$('#ui-datepicker-div').addClass('hide-arrow-prev');
					$('#ui-datepicker-div').addClass('hide-month');
				},
				onChangeMonthYear:function(y, m, i){
					var d = i.selectedDay;
					$(this).datepicker('setDate', new Date( y, 0, 1));
					dateReport.startNowDate = $.datepicker.formatDate('mm/dd/yy', new Date( y, 0, 1));
					dateReport.endNowDate = $.datepicker.formatDate('mm/dd/yy', new Date(y, 11, 31));
					$('#timeControls #picker-layout #secondSelector').val(dateReport.endNowDate);
					dateReport.timedivision = 'year';
					$('#timeControls #picker-layout #inputSelector').datepicker('hide');
				}
			});
		}

	};

	function clickReferralsource()
	{
		dateReport.cliniccodes.length = 0;
		dateReport.referralsourcecodes.length = 0;
		$('.filtersrefsview option:selected').each(function() {

			var d = $(this).attr('class');
			var a = $(this).attr('id');
			if (d == 'clinicreffilter')
			{
				dateReport.cliniccodes.push(a);
			} else if (d == 'referralfilter')
			{
				dateReport.referralsourcecodes.push(a);
			}
		});
		dateReport.getMainChart();
		dateReport.getLocationsTable();
		dateReport.getReferralsTable();
	}

	$(document).on('click', '.check_input,.check_all',function(){
		clickClinicFilter();
		setTimeout(function() {
			$('.filtersclinics').addClass('open');
		}, 5);
	});
	$(document).on('click', '.check_input_diagnosis,.check_all_diagnosis',function(){
		clickDiagnosisFilter();
		setTimeout(function() {
			$('.filtersdiagnosis').addClass('open');
		}, 5);
	});
	$(document).on('click', '.check_input_activity,.check_input_general_activity,.check_all_activity',function(){
		clickActivityFilter();
		setTimeout(function() {
			$('.filtersactivity').addClass('open');
		}, 5);
	});

	window.clickClinicFilter = function clickClinicFilter()
	{
		dateReport.maincliniccodes.length = 0;
		$('#oflocation_sel > option:selected').each(function() {
			var a = $(this).attr('id');
			dateReport.maincliniccodes.push(a);
		});
		dateReport.xhrPoolabortAll();
		dateReport.getMainChart();
		dateReport.getLocationsTable();
		dateReport.getReferralsTable();
        dateReport.getActivityTable();
	}
	window.clickDiagnosisFilter = function clickDiagnosisFilter()
	{
		dateReport.diagnosiscodes.length = 0;
		$('#diagnosis_sel > option:selected').each(function() {
			var a = $(this).attr('id');
			dateReport.diagnosiscodes.push(a);
		});
		dateReport.xhrPoolabortAll();
		dateReport.getMainChart();
		dateReport.getLocationsTable();
		dateReport.getReferralsTable();
		dateReport.getDiagnosisTable();
        dateReport.getActivityTable();
	}
	window.clickActivityFilter = function clickActivityFilter()
	{
		dateReport.activitycodes.length = 0;
		$('#activity_sel > option:selected').each(function() {
            if ($(this).hasClass('filter_activity'))
            {
                var a = $(this).val();
                dateReport.activitycodes.push(a);
            }
		});
		dateReport.xhrPoolabortAll();
		dateReport.getMainChart();
		dateReport.getLocationsTable();
		dateReport.getReferralsTable();
		dateReport.getActivityTable();
        dateReport.getDiagnosisTable();
	}

 //load defaults
    $('#timeControls #picker-layout #inputSelector').val(dateReport.startNowDate);
    $('#timeControls #picker-layout #secondSelector').val(dateReport.endNowDate);
    dateReport.get_all_by_dateRanges();
    dateReport.setDefaultToYearPicker();
    //dateReport.initClinicFilter();
   // dateReport.initReferralSourceFilter();


//referral source filters
//    $('#filtersrefsbyclinics').click(function()
//    { //open/close referral source button
//        if ($('.dropdown-show-filtersrefs').css('display') == 'none')
//        {
//            $('.dropdown-show-filtersclinics').hide();
//            $('.dropdown-show-filtersrefs').show();
//        } else
//        {
//            $('.dropdown-show-filtersrefs').hide();
//        }
//    });
//    $('#filterrefsbuttoncancel').click(function()
//    { //close referrals by Cancel button
//        $('.dropdown-show-filtersrefs').hide();
//    });
//    $('#filterrefsbuttonclear').click(function()
//    { // clear referralschecks
//        $('.filtersrefsview').find('input:checked').removeAttr('checked');
//
//    });

//referral source filters end

// clinics filter start
//
//    $('#filtersclinics').click(function()
//    { //open/close referral source button
//        if ($('.dropdown-show-filtersclinics').css('display') == 'none')
//        {
//            $('.dropdown-show-filtersrefs').hide();
//            $('.dropdown-show-filtersclinics').show();
//        } else
//        {
//            $('.dropdown-show-filtersclinics').hide();
//        }
//    });
//    $('#filterclinicsbuttoncancel').click(function()
//    { //close referrals by Cancel button
//        $('.dropdown-show-filtersclinics').hide();
//    });
//    $('#filterclinicsbuttonclear').click(function()
//    { // clear referralschecks
//        $('.filtersclinicsview').find('input:checked').removeAttr('checked');
//
//    });

// clinics filter end


//daterange smart picker
$('#activateSelector').click(function(e)
{
    $('#timeControls #picker-layout #inputSelector').datepicker('show');
});
 
 $('#timespan-week').click(function()
 {
     $('.dropdown-show-filtersclinics').hide();
     $('.dropdown-show-filtersrefs').hide();
     $('#timespanControls button').removeClass('active');
     $(this).addClass('active');
     $('#inputSelector').datepicker('destroy');
     $('#timeControls #picker-layout #inputSelector').datepicker(
     {
        beforeShowDay: $.datepicker.onlyMonday,
        firstDay: 1,
        onSelect: function(dateText, inst) {
                var firstweekday = $(this).datepicker('getDate');
                dateReport.startNowDate = $.datepicker.formatDate('mm/dd/yy', firstweekday);
                var startDay = 1;
                var d = firstweekday.getDay();
                var weekStart = new Date(firstweekday.valueOf() - (d<=0 ? 7-startDay:d-startDay)*86400000);
                var weekEnd = new Date(weekStart.valueOf() + 6*86400000);
                dateReport.endNowDate = $.datepicker.formatDate('mm/dd/yy', weekEnd);
                dateReport.timedivision = 'week';
                $('#timeControls #picker-layout #secondSelector').val(dateReport.endNowDate)
                $('#timeControls #picker-layout #inputSelector').val(dateText);
                dateReport.get_all_by_dateRanges();
            }
     });
     $('#timeControls #picker-layout #inputSelector').datepicker('show');
 });
 $('#timespan-month').click(function()
 {
     $('.dropdown-show-filtersclinics').hide();
     $('.dropdown-show-filtersrefs').hide();
     $('#timespanControls button').removeClass('active');
     $(this).addClass('active');
     $('#inputSelector').datepicker('destroy');
     $('#timeControls #picker-layout #inputSelector').datepicker(
     {
        monthpicker: true,
        onSelect: function(dateText, inst) { 
            var date = $(this).datepicker('getDate');
            dateReport.startNowDate = $.datepicker.formatDate('mm/dd/yy', date);
            day  = date.getDate();
            month = date.getMonth() + 1;           
            year =  date.getFullYear();
            function LastDayOfMonth(Year, Month) {
                return new Date( (new Date(Year, Month,1))-1 );
            };
            dateReport.endNowDate = $.datepicker.formatDate('mm/dd/yy', LastDayOfMonth(year,month));
            dateReport.timedivision = 'month';
            $('#timeControls #picker-layout #secondSelector').val(dateReport.endNowDate);
            dateReport.get_all_by_dateRanges();
        }
     });
     $('#timeControls #picker-layout #inputSelector').datepicker('show');
 });
 $('#timespan-quarter').click(function()
 {
     $('.dropdown-show-filtersclinics').hide();
     $('.dropdown-show-filtersrefs').hide();
     $('#timespanControls button').removeClass('active');
     $(this).addClass('active');
     $('#inputSelector').datepicker('destroy');
     $('#timeControls #picker-layout #inputSelector').datepicker(
     {
        quarterpicker: true,
        onSelect: function(dateText, inst) {
            var date = $(this).datepicker('getDate');
            dateReport.startNowDate = $.datepicker.formatDate('mm/dd/yy', date);
            day  = date.getDate();
            month = date.getMonth() + 1;           
            year =  date.getFullYear();
            function LastDayOfMonth(Year, Month) {
                return new Date( (new Date(Year, Month,1))-1 );
            };
            dateReport.endNowDate = $.datepicker.formatDate('mm/dd/yy', LastDayOfMonth(year,month+2));
            dateReport.timedivision = 'quarter';
            $('#timeControls #picker-layout #secondSelector').val(dateReport.endNowDate);
            dateReport.get_all_by_dateRanges();
        }
     });
     $('#timeControls #picker-layout #inputSelector').datepicker('show');
 });
 $('#timespan-year').click(function()
 {
     $('.dropdown-show-filtersclinics').hide();
     $('.dropdown-show-filtersrefs').hide();
     $('#timespanControls button').removeClass('active');
     $(this).addClass('active');
      $('#inputSelector').datepicker('destroy');
      $('#timeControls #picker-layout #inputSelector').datepicker({
        changeYear: true,
        onClose: function()
        {
            $('#ui-datepicker-div').removeClass('hide-calendar');
            $('#ui-datepicker-div').removeClass('hide-arrow-next');
            $('#ui-datepicker-div').removeClass('hide-arrow-prev');
            $('#ui-datepicker-div').removeClass('hide-month');
            dateReport.get_all_by_dateRanges();
        },
        beforeShow: function(e, b)
        {
            $('#ui-datepicker-div').addClass('hide-calendar');
            $('#ui-datepicker-div').addClass('hide-arrow-next');
            $('#ui-datepicker-div').addClass('hide-arrow-prev');
            $('#ui-datepicker-div').addClass('hide-month');
        },
        onChangeMonthYear:function(y, m, i){                                
            var d = i.selectedDay;
            $(this).datepicker('setDate', new Date( y, 0, 1));
            dateReport.startNowDate = $.datepicker.formatDate('mm/dd/yy', new Date( y, 0, 1));
            dateReport.endNowDate = $.datepicker.formatDate('mm/dd/yy', new Date(y, 11, 31));
            $('#timeControls #picker-layout #secondSelector').val(dateReport.endNowDate);
            dateReport.timedivision = 'year';
            $('#timeControls #picker-layout #inputSelector').datepicker('hide');
        }
      });
      $('#timeControls #picker-layout #inputSelector').datepicker('show');
    });
 // end of daterange smart picker

    var pointClicked = false,
        clicksYet = false;

    function showTooltip(x, y, contents) {

        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    $("#chartforrangeplace").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

        if (1 > 0) {
            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;

                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);

                    showTooltip(item.pageX, item.pageY, y);
                }
            }
            else {
                $("#tooltip").remove();
                clicksYet = false;
                previousPoint = null;
            }
        }
    });

    $("#chartforrangeplace").bind("plotclick", function (event, pos, item) {
        if (item) {
            clicksYet = true;
            pointClicked = (!pointClicked)?true:false;
            $("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
            window.plot.highlight(item.series, item.datapoint);
        }
    });


});
