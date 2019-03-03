<?php 
    //$arr = $_POST['user'];
	$stageID = $_POST['stageID'];
	$rating = $_POST['rating'];
	$userID = $_POST['userID'];
	
    //sindesou me to phpmyadmin
$con = mysqli_connect('localhost','root') or
die('Could not connect to the database');
mysqli_select_db($con,'ptuxiaki');

//$sql="INSERT INTO users (username,password,email,phone,country,favoriteQ,favoriteA,status) VALUES ('$arr[0]','$arr[1]','$arr[2]','$arr[3]','$arr[4]','$arr[5]','$arr[6]','$arr[7]')";
//mysqli_query($con,$sql);
$sql="INSERT INTO rating (stageID,rating,userID) VALUES ($stageID,$rating,'$userID')";
mysqli_query($con,$sql);

?>