$(function(){
    
//-------datepicker--------//
    $('#reportrange2').daterangepicker(
    {
        opens: 'left',
        format: 'YYYY-MM-DD',
        startDate: new Date().getFullYear()+'-01-01',
        endDate: new Date().getFullYear()+'-12-31'
    },
    function(start, end) {
        //~ alert(start.format('YYYY-MM-DD'));
        $('#reportrange2 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        getChartNewPatientsByRange(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
		getChartPie(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        getConverstionTable(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        console.log(start);
    });

//--------datepicker--------//

//------ pie chart ------//

	function getChartPie(begin, end)
	{

		var csrftoken = $('.csrf_token').val();
		$.ajax({
			type: "POST",
			url:    "/dashboard/getdashpiereason",
			data: {'csrf_token':csrftoken, 'begin':begin, 'end':end},
			success: function(data)
			{
				if (data)
				{
					$.plot('#chartpie', data, {
						series: {
							pie: {
								show: true,
                                radius: 1,
                                label: {
                                    show: true,
                                    radius: 2/3,
                                    formatter: labelFormatter,
                                    threshold: 0.1
                                }
							}
						}
					});
				}
			}
		});
	}
	getChartPie(new Date().getFullYear()+'-01-01', new Date().getFullYear()+'-12-31');
//------ pie chart ------//

//------ time chart ------//

    function labelFormatter(label, series) {
        return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
    }
     
    function getChartNewPatientsByRange(begin, end)
    {

        var csrftoken = $('.csrf_token').val();
        $.ajax({
            type: "POST",
            url:    "/dashboard/getdashpatientschart",
            data: {'csrf_token':csrftoken, 'begin':begin, 'end':end},
            success: function(data)
            {
                if (data)
                {
                    $.plot('#charttimedash', data, {
                        xaxis: {
                                mode: "time",
                                //~ tickSize: [1, "month"],
                        },
                        yaxis: {
                            min: 0,
                            max: 100,
                            tickFormatter: function (val, axis) { return val + "%"}
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

    }

    function getConverstionTable(begin, end)
    {
        var csrftoken = $('.csrf_token').val();
        $.ajax({
            type: "POST",
            url:    "/dashboard/getdashtable",
            data: {'csrf_token':csrftoken, 'begin':begin, 'end':end},
            success: function(data)
            {
                if (data)
                {
                    $('.conversion-table').html('');
                    $('.conversion-table').html(data);
                }
            }
        });
    }
    getChartNewPatientsByRange(new Date().getFullYear()+'-01-01', new Date().getFullYear()+'-12-31');
    getConverstionTable(new Date().getFullYear()+'-01-01', new Date().getFullYear()+'-12-31');


    var pointClicked = false,
        clicksYet = false;

    function showTooltip(x, y, contents) {

        $('<div id="tooltip">' + contents +"%" + '</div>').css( {
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

    $("#charttimedash").bind("plothover", function (event, pos, item) {
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

    $("#charttimedash").bind("plotclick", function (event, pos, item) {
        if (item) {
            clicksYet = true;
            pointClicked = (!pointClicked)?true:false;
            $("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
            window.plot.highlight(item.series, item.datapoint);
        }
    });
    
//------ time chart ------//

});
