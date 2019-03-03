<!DOCTYPE html>
<html> 
<head> 
 <title> Ledriel's Gaming </title> </head>
<link rel="stylesheet" href="My_Css.css"> 
 <body background="background2.jpg" bgcolor="ALICEBLUE">

<My_Title>Ledriel's Gaming </My_Title>
<button_Image><img src="button.png" style="height:4em; width:50em;">  </button_Image>
<button_Pos>
<div class="background">
   <button id="button" ONCLICK="window.location.href='/home.php'" onmouseover="" style="cursor: pointer;">Home Page</button>
   &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
   <button id="button" ONCLICK="window.location.href='/games.php'" onmouseover="" style="cursor: pointer;">Games</button>
   &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
   <button id="button" ONCLICK="window.location.href='/aboutus.php'" onmouseover="" style="cursor: pointer;">About Me</button>
</div>
</button_Pos>
 
<?php

session_start();
//sindesou me to phpmyadmin
$con = mysqli_connect('localhost','root') or
die('Could not connect to the database');
//sindesou me to database store_database
mysqli_select_db($con,'ptuxiaki');

//activate a user
if(isset($_POST['activate']))
{
		$tempUser=$_POST['username'];
		$sql="UPDATE users SET status='active' WHERE username='$tempUser'";
		mysqli_query($con,$sql);
}
// ban a user
else if(isset($_POST['ban']))
{
		$tempUser=$_POST['username'];
		$sql="UPDATE users SET status='banned' WHERE username='$tempUser'";
		mysqli_query($con,$sql);
}
// unban a user
else if(isset($_POST['unban']))
{
	$tempUser=$_POST['username'];
	$sql="UPDATE users SET status='unbanned' WHERE username='$tempUser'";
    mysqli_query($con,$sql);
}
// delete a user
else if(isset($_POST['delete']))
{
	$tempUser=$_POST['username'];
	$sql="DELETE FROM users WHERE username='$tempUser'";
    mysqli_query($con,$sql);
}
//an einai logarismenos kapios xrhsths
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1500)) 
{
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
//if(isset($_COOKIE["user"]))


//first Echo
?><white_bold2><My_TopRight><?php 
if (isset($_SESSION['user']))
{	
	//an o logarismenos einai o admin tote
	if (isset($_SESSION['status']) && $_SESSION['status']=="admin") 
	{
		$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
		$display_block = "<green> Welcome   " .$_SESSION['user'];
		//$display_block = "Welcome   " .$_COOKIE["user"];
		$display_block .= "<form method=\"POST\" action=\"/home.php\">";
		$display_block .= "<br>";
		$display_block .= "<input type=\"Submit\" name=\"logout\" value=\"Log out\"/>";
		$display_block .= "<input type=button onClick=\"location.href='/cubeGame/myMaps.php'\" value='My Maps'>";
		$display_block .= "</Form>";
		$display_block .= "<form method=\"POST\" action=\"/userSetup.php\">";
		$display_block .= "<br>";
		$display_block .= "<input type=\"Submit\" name=\"userSetup\" value=\"Users Setup\"/>";
		$display_block .= "</Form></green>";
	}
	else
	{
		header("Location: /home.php");
	}
}
//an den einai logarismenos kapios xrhsths
else 
{
	header("Location: /home.php");
}

echo "$display_block <br>";
?></fieldset></My_TopRight></white_bold2><?php
//2o echo
//check if user is admin
?><C><h3><green>Users Setup<green></h3> </C> 


<User_Setup>
<?php //<img src="1.png" alt="pic1" width="1000em" height="1000em">
//FIX THE GUI (DESIGN) FOR EACH USER, *GUI FOR USER_SETUP
$result = mysqli_query($con,"SELECT * FROM users");
$row = mysqli_fetch_row($result);
$n = mysqli_num_rows($result);
for($i=0;$i<$n;$i++) 
{
	echo "<img src=\"2.png\" alt=\"pic1\" width=\"500em\" height=\"120em\">";
	echo "<br><BR>";
}


?></User_Setup>
<User_Setup>
<?php //<img src="1.png" alt="pic1" width="1000em" height="1000em">
//emfanhse olous tous users

for($i=0;$i<$n;$i++) 
{
	echo "&nbsp&nbsp <b> status </b>: $row[7]<br>";
	echo "&nbsp&nbsp <b> username </b> : $row[0] &nbsp&nbsp <b> email </b>: $row[2]";
	echo "<br> &nbsp&nbsp <b> password </b> : ****** &nbsp&nbsp <b> phone </b> : $row[3] &nbsp&nbsp <b> country </b> : $row[4]";
	
	if (isset($_SESSION['status']) && $_SESSION['status']=="admin") 
	{
		echo "<form method=\"POST\" action=\"/userSetup.php\">";
		echo "<input type=\"Hidden\" name=\"status\" value='$row[0]'>";
		echo "<input type=\"Hidden\" name=\"username\" value='$row[0]'><br>&nbsp&nbsp";
		//an h eggrafh pou emfanizete tra einai o admin
		if($row[7]=="admin")
		{
			?>
			<input type="Submit" name="activate" value="Activate" disabled>
			<input type="Submit" name="ban" value="Ban" disabled>
			<input type="Submit" name="unban" value="Unban" disabled>
			<input type="Submit" name="delete" value="Delete" disabled>
			<?php
		}
		//an h eggrafh einai gia enan active xrhsth
		else if($row[7]=="active")
		{
			?>
			<input type="Submit" name="activate" value="Activate" disabled>
			<input type="Submit" name="ban" value="Ban">
			<input type="Submit" name="unban" value="Unban" disabled>
			<input type="Submit" name="delete" value="Delete">
			<?php
		}
		//an h eggrafh einai gia enan xrhsth pou efage ban kai tra 3ebannaristhke
		else if($row[7]=="unbanned")
		{
			?>
			<input type="Submit" name="activate" value="Activate" disabled>
			<input type="Submit" name="ban" value="Ban">
			<input type="Submit" name="unban" value="Unban" disabled>
			<input type="Submit" name="delete" value="Delete">
			<?php
		}
		//gia enan xrhsth banned
		else if($row[7]=="banned")
		{
			?>
			<input type="Submit" name="activate" value="Activate" disabled>
			<input type="Submit" name="ban" value="Ban" disabled>
			<input type="Submit" name="unban" value="Unban">
			<input type="Submit" name="delete" value="Delete">
			<?php
		}
		//gia enan xrhsth innactive
		else
		{
			?>
			<input type="Submit" name="activate" value="Activate">
			<input type="Submit" name="ban" value="Ban">
			<input type="Submit" name="unban" value="Unban" disabled>
			<input type="Submit" name="delete" value="Delete">
			<?php
		}
		
		echo "</Form>";
		echo "<br>";
	}
	
	echo "<br>";
	$row = mysqli_fetch_row($result);
}
?></User_Setup>

<?php

//3o echo
//if(isset($_POST['login']))
//{
//	$result = mysqli_query($con,"SELECT status FROM users WHERE username='".$_POST['userText']."' AND password='".$_POST['passText']."'");
//	$row = mysqli_fetch_row($result);
//	$n = mysqli_num_rows($result);
//	if($n==1) 
//	{
//		if($row[0]!="active" and $row[0]!="unbanned" and $row[0]!="admin")
//		{
//			$_SESSION['user']=$_POST['userText'];
//			$_SESSION['status']=$row[0];
//			$_SESSION['LAST_ACTIVITY'] = time();
//			//setcookie("user", $_POST['userText'], time()+400);
//			header("Location: /home.php");
//		}
//		else if($row[0]=="banned")
//		{
//			header("Location: /home.php?loginStatus=BANNED");
//		}
//		else
//		{
//			header("Location: /home.php?loginStatus=INNACTIVE");
//		}
//	}
//	else header("Location: /home.php?loginStatus=BAD_COMBINATION");
//}
//else if(isset($_POST['register']))
//{
//	header("Location: /register.php");
//}
//else if(isset($_POST['logout']))
//{
//	//setcookie("user", "", time()-1);
//    session_unset();     // unset $_SESSION variable for the run-time 
//    session_destroy();   // destroy session data in storage
//	
//	header("Location: /home.php");
//}
?>

<script>
//// IF YOU PRESS DOWN A KEY FROM KEYBOARD, MAKE THE FLAG=1
//document.addEventListener('keydown', function(event) {
//    if(event.keyCode == 13) { //13 is the keyCode for enter
//		//prevent the default listener
//		event.preventDefault();
//		if (event.target.id==1 && event.which==13) 
//		{
//			document.getElementById("area").innerHTML+="\n";
//		}
//	}
//});




</script>
</fieldset>

 </body>
</html>