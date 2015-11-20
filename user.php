<?php

	require_once('dbconn.php');

	$q = intval($_POST['q']);

	$sql="SELECT * FROM user_profile WHERE id = '".$q."'";

	$result = mysqli_query($con,$sql);
	$out = '';

	while($row = mysqli_fetch_array($result)){
		$out .= "Welcome " . $row['lastName'] .", " .$row['firstName'];

		$dob = new DateTime($row['dob']);
		$age = $dob->diff(new DateTime);
	}

	echo $out;
	mysqli_close($con);

?>