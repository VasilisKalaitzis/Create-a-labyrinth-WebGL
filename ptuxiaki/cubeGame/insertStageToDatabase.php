<?php 
    //$arr = $_POST['user'];
	$stageName = $_POST['stageName'];
	$Author = $_POST['Author'];
	$stageLevel = $_POST['stageLevel'];
	$UserX = $_POST['UserX'];
	$UserY = $_POST['UserY'];
	$UserZ = $_POST['UserZ'];
	$numberOfCheeses= $_POST['numberOfCheeses'];
	$ii= $_POST['ii'];
	$building= $_POST['building'];
	$locX=$_POST['locX'];
	$locY=$_POST['locY'];
	$locZ=$_POST['locZ'];
	
    //sindesou me to phpmyadmin
$con = mysqli_connect('localhost','root') or
die('Could not connect to the database');
mysqli_select_db($con,'ptuxiaki');

//$sql="INSERT INTO users (username,password,email,phone,country,favoriteQ,favoriteA,status) VALUES ('$arr[0]','$arr[1]','$arr[2]','$arr[3]','$arr[4]','$arr[5]','$arr[6]','$arr[7]')";
//mysqli_query($con,$sql);
$sql="INSERT INTO stage (stageName,Author,stageLevel,UserX,UserY,UserZ,numberOfCheeses,numberOfbuildings) VALUES ('$stageName','$Author',$stageLevel,$UserX,$UserY,$UserZ,$numberOfCheeses,$ii)";
mysqli_query($con,$sql);
$stageID=mysqli_insert_id();
for($i=0;$i<$ii;$i++)
{
	$sql="INSERT INTO buildings (stageID,building,locX,locY,locZ) VALUES ('$stageID','$building[$i]','$locX[$i]','$locY[$i]','$locZ[$i]')";
	mysqli_query($con,$sql);
}

?>