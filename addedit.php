<?php

	require_once('dbconn.php');

	$value = $_POST["d"];

	$sql = "SELECT * FROM n4d_healthactivity WHERE date = '{$value["date"]}'";
	$result = mysqli_query($con, $sql);

	if(!$result->num_rows) {
		$sql = "INSERT INTO n4d_healthactivity VALUES (1, '{$value["date"]}', {$value["heart"]}, {$value["sbp"]}, {$value["dbp"]}, {$value["sugar"]},{$value["cholesterol"]}, {$value["sleep"]}, {$value["cardio"]}, {$value["strength"]})";
		mysqli_query($con, $sql);
	} else {
		$sql = "UPDATE n4d_healthactivity SET heart = {$value["heart"]}, sbp = {$value["sbp"]}, dbp = {$value["dbp"]}, sugar = {$value["sugar"]},  cholesterol = {$value["cholesterol"]}, sleep = {$value["sleep"]}, cardio = {$value["cardio"]}, strength = {$value["strength"]} WHERE date='{$value["date"]}'";
		mysqli_query($con, $sql);
	}

	mysqli_close($con);

?>