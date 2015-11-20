<?php
	$con = mysqli_connect('localhost','root','root','note4doc');
	if (!$con) {
	  die('Could not connect: ' . mysqli_error($con));
	}
?>