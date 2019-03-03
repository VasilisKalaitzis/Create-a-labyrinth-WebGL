<?php 
    //$arr = $_POST['user'];
	$stageID = $_POST['stageID'];
	$user = $_POST['user'];
	$score = $_POST['score'];
	
    //sindesou me to phpmyadmin
$con = mysqli_connect('localhost','root') or
die('Could not connect to the database');
mysqli_select_db($con,'ptuxiaki');

//$sql="INSERT INTO users (username,password,email,phone,country,favoriteQ,favoriteA,status) VALUES ('$arr[0]','$arr[1]','$arr[2]','$arr[3]','$arr[4]','$arr[5]','$arr[6]','$arr[7]')";
//mysqli_query($con,$sql);
$sql="INSERT INTO time (user,stage,score) VALUES ('$user',$stageID,$score)";
mysqli_query($con,$sql);

?>