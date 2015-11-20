<?php

	require_once('dbconn.php');

	$uid = 1;
	$type = $_POST['param'];
	
	$day = date('l', time());
	
	if($day != 'Sunday'){

		$start = date('Y-m-d', strtotime('last sunday'));
		$end = date('Y-m-d', strtotime('today'));
		
		$sql="SELECT date, ".$type." FROM n4d_healthactivity WHERE uid = {$uid} and date >= '{$start}' and date <= '{$end}' order by date";
	
	} else {
	
		$today = date('Y-m-d', strtotime('today'));

		$sql="SELECT date, ".$type." FROM n4d_healthactivity WHERE uid = {$uid} and date = '{$today}' order by date";
	}

	$result = mysqli_query($con,$sql);

	$values = [];
	$categories = [];
	
	while($row = mysqli_fetch_array($result)){
		array_push($values,intval($row[$type]));
		array_push($categories, $row['date']);
	}
	
	$graph_data = array('categories'=>$categories, $type=>$values);
	
	echo json_encode($graph_data);
?>