<?php

	require_once('dbconn.php');
	
	$start = $_POST["start"];
	$end = $_POST["end"];
	$days = $_POST["days"];
	
	$uid = 1;
	$type = $_POST['param'];
	
	$day = date('l', time());
	
	if($days != 1){
		$sql="SELECT date, ".$type." FROM n4d_healthactivity WHERE uid = {$uid} and date >= '{$start}' and date <= '{$end}' order by date";
	} else {
		$sql="SELECT date, ".$type." FROM n4d_healthactivity WHERE uid = {$uid} and date = '{$start}' order by date";
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