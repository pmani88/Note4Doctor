$(document).ready(function() {

	$.post("user.php", {q:'1'}, function(data) {
		$("#UserPane").html(data);
	});
	
	displayParams();
	
	$("input[id^='datepicker']").each(function(){
		$(this).datepicker({dateFormat: 'yy-mm-dd', minDate: "", maxDate: "+0D"});
	});
	//graph();
	InitHighChart('heart', 'container1');
	InitHighChart('sleep', 'container2');
});

function displayParams() {
	$.post("displayParams.php", function(data) {
		var dataArr = JSON.parse(data);
		
		var health_param = '<table style="margin: 40px auto; width: 280px; min-height: 120px;">';
		health_param += '<tr><td><b>Heart Rate</b> <i>(bpm)</i>:</td><td><a href="javascript:void(0);" onclick="InitHighChart(\'heart\',\'container1\');">'+dataArr["avgHeart"]+'</a></td></tr>';
		health_param += '<tr><td><b>Blood Pressure</b> <i>(mm Hg)</i>:</td><td><a href="javascript:void(0);" onclick="InitHighChart(\'sbp\',\'container1\');">'+dataArr["avgSbp"]+'</a> / <a href="javascript:void(0);" onclick="InitHighChart(\'dbp\',\'container1\');">'+dataArr["avgDbp"]+'</a></td></tr>';
		health_param += '<tr><td><b>Blood Sugar Level</b> <i>(mg/dL)</i>:</td><td><a href="javascript:void(0);" onclick="InitHighChart(\'sugar\',\'container1\');">'+dataArr["avgSugar"]+'</a></td></tr>';
		health_param += '<tr><td><b>Total Cholesterol Level</b> <i>(mg/dL)</i>:</td><td><a href="javascript:void(0);" onclick="InitHighChart(\'cholesterol\',\'container1\');">'+dataArr["avgCholesterol"]+'</a></td></tr>';
		health_param += '</table>';
		
		$("#HealthMeterPane").append(health_param);
		
		var activity_param = '<table style="margin: 40px auto; width: 280px; min-height: 120px;"">';
		activity_param += '<tr><td><b>Hours Slept</b> <i>(hrs)</i>:</td><td><a href="javascript:void(0);" onclick="InitHighChart(\'sleep\',\'container2\');">'+dataArr["avgSleep"]+'</a></td></tr>';
		activity_param += '<tr><td><b>Cardio Workouts</b> <i>(hrs)</i>:</td><td><a href="javascript:void(0);" onclick="InitHighChart(\'cardio\',\'container2\');">'+dataArr["avgCardio"]+'</a></td></tr>';
		activity_param += '<tr><td><b>Strength Workouts</b> <i>(hrs)</i>:</td><td><a href="javascript:void(0);" onclick="InitHighChart(\'strength\',\'container2\');">'+dataArr["avgStrength"]+'</a></td></tr>';
		activity_param += '</table>';
		
		$("#ActivityPane").append(activity_param);
	});
}

function RevertLeftPane(type) {
	$('form[name="'+type+'"] table input').each(function(){
		$(this).val('');
	});
	$('#LeftPane').css('display','block');
    $('#RightPane').css('display','block');
    $('#CustomizationPane').css('display','none');	$('#AddEditPane').css('display','none');
	$('#ViewPrintPane').css('display','none');
	$('#LoadSavePane').css('display','none');
	$('#HealthMeterPane, #ActivityPane').css('display','block');
	
	$('#ButtonsPane button').removeAttr('disabled');
}

function initDate() {
	var date = new Date();
    var curr_date = date.getDate();
    var curr_month = date.getMonth('') + 1; //Months are zero based
    var curr_year = date.getFullYear();
	
	var dateVal = curr_year + "-" + curr_month + "-" + curr_date;
	$("#datepicker1").val(dateVal);
	
	loadAddEditData(dateVal);
}

function AddEditForm() {
	initDate();
	$('#AddEditPane').css('display','block');
	$('#btnAddEdit').attr('disabled','disabled');
	$('#ViewPrintPane, #HealthMeterPane, #ActivityPane').css('display','none');
	$('#btnViewPrint').removeAttr('disabled');
	$('#LoadSavePane').css('display','none');
	$('#btnLoadSave').removeAttr('disabled');
}

function submitAddEdit() {
	var value = {}, type;
	
	$('#AddEditPane table input').each(function(){
		type = $(this).attr('name');
		value[type] = $(this).val();
	});
	
	$.post("addedit.php", {d: value}, function() {
		location.reload();
	});
}

function loadAddEditData(date) {
	$.post("populateAddEditform.php",{date: date}, function(data){
		var dataArr = JSON.parse(data);
		if(dataArr === null){
			$('#AddEditPane table input[name!="date"]').each(function(){
				$(this).val('');
			});
		} else {
			for (var k in dataArr) {
				$('#AddEditPane table input[name="'+k+'"]').val(dataArr[k]);
			}
		}
	});
}

function ViewPrintForm() {
	$('#AddEditPane, #HealthMeterPane, #ActivityPane').css('display','none');
	$('#btnAddEdit').removeAttr('disabled');
	$('#ViewPrintPane').css('display','block');
	$('#btnViewPrint').attr('disabled','disabled');
	$('#LoadSavePane').css('display','none');
	$('#btnLoadSave').removeAttr('disabled');
}

function LoadSaveForm() {
	$('#AddEditPane, #HealthMeterPane, #ActivityPane').css('display','none');
	$('#btnAddEdit').removeAttr('disabled');
	$('#ViewPrintPane').css('display','none');
	$('#btnViewPrint').removeAttr('disabled');
	$('#LoadSavePane').css('display','block');
	$('#btnLoadSave').attr('disabled','disabled');
}

function viewReport(){
	var data = $("#HiddenReport").html();
	$("#HiddenReport").html('');
	var myWindow = window.open("","",'scrollbars=1,resizable=1,width=950,height=580,left=0,top=0');
	myWindow.document.write(data);
	//location.reload();
}

function generateReport(){
	var start = $('#ViewPrintPane table input[name="startdate"]').val();
	var end = $('#ViewPrintPane table input[name="enddate"]').val();
		
	if(start == '' || end == ''){
		return false;
	} 
	
	var days = ((Date.parse(end) - Date.parse(start))/ (1000*60*60*24)) + 1;
	
	if (days <= 0) {
		alert('Start Date cannot be greater than End Date !!');
	} else if (days > 31){
		alert('Please choose a Maximum of 31 Days');
	} else {
		$.post("report.php", {start: start, end: end, days: days}, function(data) {
			$("#HiddenReport").html(data);
			InitReportHighChart('heart', 'heart', start, end, days);
			InitReportHighChart('sbp', 'sbp', start, end, days);
			InitReportHighChart('dbp', 'dbp', start, end, days);
			InitReportHighChart('sugar', 'sugar', start, end, days);
			InitReportHighChart('cholesterol', 'cholesterol', start, end, days);
			InitReportHighChart('sleep', 'sleep', start, end, days);
			InitReportHighChart('cardio', 'cardio', start, end, days);
			InitReportHighChart('strength', 'strength', start, end, days);	
			//setTimeout(viewReport(), 1000000);
			setTimeout( function(){
			  viewReport();
			}, 1500 );
		});
	}
}

function Cog() {
    $('#LeftPane, #RightPane').css('display','none');
    $('#CustomizationPane').css('display','block');
}

function ChangeColorFont() {
    var Pane = $('#WhichPane').val();
    var Color = $('#WhichColor').val();
    var Font = $('#WhichFont').val();
    var Size = $('#WhichSize').val(); 

    $('#'+Pane).css('background-color', Color);
    $('#'+Pane).css('font-family', Font);
    $('#'+Pane).css("font-size", Size);
}

function InitHighChart(type,render)
{
	$("#"+render).html("Wait, Loading graph...");
	
	var options = {
		chart: {
			renderTo: render
		},
		credits: {
			enabled: false
		},
		title: {
			text: type
		},
		xAxis: {
			categories: [{}]
		},
		tooltip: {
			enabled: false,
			formatter: function() {
				return '<b>'+ this.series.name +'</b><br/>'+
					this.x +': '+ this.y;
			}
		},
		plotOptions: {
			line: {
				dataLabels: {
					enabled: true,
					style: {
						textShadow: '0 0 3px white, 0 0 3px white'
					}
				},
				enableMouseTracking: false
			}
		},
		series: [{}]
	};
	$.post("chart.php", {param: type}, function(data) {
		var dataArr = JSON.parse(data);
		options.xAxis.categories = dataArr.categories;
		options.series[0].name = type;
		options.series[0].data = dataArr[type];
		var chart = new Highcharts.Chart(options);
	});
}

function InitReportHighChart(type,render, start, end, days)
{
	$("#"+render).html("Wait, Loading graph...");
	
	var options = {
		chart: {
			renderTo: 'chart'+render
		},
		credits: {
			enabled: false
		},
		title: {
			text: type
		},
		xAxis: {
			categories: [{}]
		},
		tooltip: {
			enabled: false,
			formatter: function() {
				return '<b>'+ this.series.name +'</b><br/>'+
					this.x +': '+ this.y;
			}
		},
		plotOptions: {
			line: {
				dataLabels: {
					enabled: true,
					style: {
						textShadow: '0 0 3px white, 0 0 3px white'
					}
				},
				enableMouseTracking: false
			}
		},
		series: [{}]
	};
	$.post("reportChart.php", {param: type, start: start, end: end, days: days}, function(data) {
		var dataArr = JSON.parse(data);
		options.xAxis.categories = dataArr.categories;
		options.series[0].name = type;
		options.series[0].data = dataArr[type];
		var chart = new Highcharts.Chart(options);
	});
}