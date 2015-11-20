<?php

	require_once('dbconn.php');

	$uid = 1;
	
	$day = date('l', time());
	
	if($day != 'Sunday'){

		$start = date('Y-m-d', strtotime('last sunday'));
		$end = date('Y-m-d', strtotime('today'));
		
		$sql="SELECT round(round(avg(heart), 0), 0) as avgHeart, round(round(avg(sbp), 0), 0) as avgSbp, round(round(avg(dbp), 0), 0) as avgDbp, round(round(avg(sugar), 0), 0) as avgSugar, round(round(avg(cholesterol), 0), 0) as avgCholesterol, round(round(avg(sleep), 0), 0) as avgSleep, round(round(avg(cardio), 0), 0) as avgCardio, round(round(avg(strength), 0), 0) as avgStrength FROM n4d_healthactivity WHERE uid = {$uid} and date >= '{$start}' and date <= '{$end}'";
	
	} else {
	
		$today = date('Y-m-d', strtotime('today'));

		$sql="SELECT round(avg(heart), 0) as avgHeart, round(avg(sbp), 0) as avgSbp, round(avg(dbp), 0) as avgDbp, round(avg(sugar), 0) as avgSugar, round(round(avg(cholesterol), 0), 0) as avgCholesterol, round(avg(sleep), 0) as avgSleep, round(avg(cardio), 0) as avgCardio, round(avg(strength), 0) as avgStrength FROM n4d_healthactivity WHERE uid = {$uid} and date = '{$today}'";
	}

	$result = mysqli_query($con,$sql);

	$data = mysqli_fetch_array($result);
	
	if(is_null($data[0])){
		foreach ($data as $key => $value) {
			$data[$key] = 0;
		}
	}
	echo json_encode($data);
?>