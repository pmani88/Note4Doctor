<?php

	require_once('dbconn.php');

	$output = "";

	$sql = "SELECT * FROM n4d_healthactivity";
	$result = mysqli_query($con, $sql);

	$fieldcount = mysqli_num_fields($result);

	while ($fieldinfo = mysqli_fetch_field($result)) {
		$heading = $fieldinfo->name;
		$output .= '"'.$heading.'",';
	}
	$output .="\n";

	while ($row = mysqli_fetch_array($result)) {
		for ($i = 0; $i < $fieldcount; $i++) {
			$output .='"'.$row["$i"].'",';
		}
		$output .="\n";
	}

	// Download the file
	$filename = "Note4Doctor.csv";
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);

	echo $output;
	exit;

?>