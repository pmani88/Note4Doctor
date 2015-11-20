<?php
	
	require_once('dbconn.php');
	
	$start = $_POST["start"];
	$end = $_POST["end"];
	$days = $_POST["days"];
	
	$uid = 1;
	
	$sql="SELECT * FROM user_profile WHERE id = ".$uid;
	
	$result = mysqli_query($con,$sql);
	
	$out = "<html><title>Report</title><body>";
	
	$out .= "<table border='0' style='margin:10px auto; width: 700px;'>";

	while($row = mysqli_fetch_array($result)) {
		$out .= "<tr>";
		$out .= "<td><b>Name : </b>" . $row['lastName'] .", " .$row['firstName']. "</td>";
		$out .= "<td><b>Height: </b>" . $row['height'] . " cms.</td>";
		$out .= "<td><b>Weight : </b>" . $row['weight'] . " kgs.</td>";

		$dob = new DateTime($row['dob']);
		$age = $dob->diff(new DateTime);

		$out .= "<td><b>Age :</b>" . $age->y . "</td>";
		$out .= "<td><button onclick='window.print();' style='display: block; margin: 30px auto;'>Print Report</button></td>";
		$out .= "</tr>";
	}
	$out .= "</table>";
	
	if ($days == 1) {
		$sql = "SELECT * FROM n4d_healthactivity WHERE date = '{$start}' order by date";
	} else {
		$sql = "SELECT * FROM n4d_healthactivity WHERE date >= '{$start}' and date <= '{$end}' order by date";
	}
	
	$result = mysqli_query($con,$sql);
	
	$out .= '<div id="nd4report">';
	
	// Health Meter
	$out .= '<div style="text-align: center;"><h3>Health Meter</h3></div>';
	$out .= "<table border='1' style='margin:5px auto; width: 900px; page-break-after:always;'>
	<tr>
	<th>Date</th>
	<th>Heart Rate (bpm)</th>
	<th>Systolic BP (mm Hg)</th>
	<th>Diastolic BP (mm Hg)</th>
	<th>Blood Sugar (mg/dL)</th>
	<th>Total Cholesterol (mg/dL)</th>
	</tr>";	
	
	while($row = mysqli_fetch_array($result)){
		//array_push($data, $row);

		$out .= "<tr>";
		$out .= "<td>" . $row['date'] . "</td>";
		$out .= "<td>" . $row['heart'] . "</td>";
		$out .= "<td>" . $row['sbp'] . "</td>";
		$out .= "<td>" . $row['dbp'] . "</td>";
		$out .= "<td>" . $row['sugar'] . "</td>";
		$out .= "<td>" . $row['cholesterol'] . "</td>";
		$out .= "</tr>";
	 }
	$out .= "</table>";
	$out .= '<div style="text-align: center;"><h4>Heart Rate</h4></div>';
	$out .= '<div id="chartheart" style="height: 200px; margin: 0 auto; padding:5px"></div>';
	
	$out .= '<div style="text-align: center;"><h4>Systolic Blood Pressure</h4></div>';
	$out .= '<div id="chartsbp" style="height: 200px; margin: 0 auto; padding:5px"></div>';
	
	$out .= '<div style="text-align: center;"><h4>Diastolic Blood Pressure</h4></div>';
	$out .= '<div id="chartdbp" style="height: 200px; margin: 0 auto; padding:5px; page-break-after:always;"></div>';
	
	$out .= '<div style="text-align: center;"><h4>Blood Sugar Level</h4></div>';
	$out .= '<div id="chartsugar" style="height: 200px; margin: 0 auto; padding:5px"></div>';
	
	$out .= '<div style="text-align: center;"><h4>Total Cholesterol Level</h4></div>';
	$out .= '<div id="chartcholesterol" style="height: 200px; margin: 0 auto; padding:5px; page-break-after:always;"></div>';
	
	// Activities Meter
	$out .= '<div style="text-align: center;"><h3>Activities Meter</h3></div>';
	$out .= "<table border='1' style='margin:5px auto; width: 600px; page-break-after:always;'>
	<tr>
	<th>Date</th>
	<th>Hours Slept (hrs)</th>
	<th>Cardio Workouts (hrs)</th>
	<th>Strength Workouts (hrs)</th>
	</tr>";	
	
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		$out .= "<tr>";
		$out .= "<td>" . $row['date'] . "</td>";
		$out .= "<td>" . $row['sleep'] . "</td>";
		$out .= "<td>" . $row['cardio'] . "</td>";
		$out .= "<td>" . $row['strength'] . "</td>";
		$out .= "</tr>";
	}
	$out .= "</table>";
	$out .= '<div style="text-align: center;"><h4>Hours Slept</h4></div>';
	$out .= '<div id="chartsleep" style="height: 200px; margin: 0 auto; padding:5px"></div>';
	
	$out .= '<div style="text-align: center;"><h4>Cardio Workouts</h4></div>';
	$out .= '<div id="chartcardio" style="height: 200px; margin: 0 auto; padding:5px"></div>';
	
	$out .= '<div style="text-align: center;"><h4>Strength Workouts</h4></div>';
	$out .= '<div id="chartstrength" style="height: 200px; margin: 0 auto; padding:5px"></div>';
		
	
	$out .= "</div></body></html>";
	
	echo $out;
	
?>