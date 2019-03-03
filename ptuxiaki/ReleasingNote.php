<!DOCTYPE html>
<html> 
<head> 
 <title> Ledriel's Gaming </title> </head>
<link rel="stylesheet" href="My_Css.css"> 

<?php
session_start();
//MANAGE SESSIONS: an einai logarismenos kapios xrhsths
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1500)) 
{
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}

//CHOOSE BACKGROUND FOR USER AND FOR ADMIN
if (isset($_SESSION['status']) && $_SESSION['status']=="admin")  echo "<body background=\"background2.jpg\" bgcolor=\"ALICEBLUE\">";
else echo "<body background=\"background.jpg\" bgcolor=\"ALICEBLUE\">";

?>

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

//sindesou me to phpmyadmin
$con = mysqli_connect('localhost','root') or
die('Could not connect to the database');
//sindesou me to database store_database
mysqli_select_db($con,'ptuxiaki');

//add new note
if(isset($_POST['newNote']))
{
//see your timezone
//$timezone = date_default_timezone_get();
//echo "The current server timezone is: " . $timezone;

	date_default_timezone_set('Europe/Athens');
	$date = date('Y/m/d h:i:s', time());
	$parNews=$_POST['news'];
	$sql="INSERT INTO news (note,day) VALUES ('$parNews','$date')";
    mysqli_query($con,$sql);
}
// delete a note
else if(isset($_POST['deleteNote']))
{
	$noteI=$_POST['noteId'];
	$sql="DELETE FROM news WHERE noteID=$noteI";
    mysqli_query($con,$sql);
}

//login-logout-register
if(isset($_POST['login']))
{
	$result = mysqli_query($con,"SELECT status FROM users WHERE username='".$_POST['userText']."' AND password='".$_POST['passText']."'");
	$row = mysqli_fetch_row($result);
	$n = mysqli_num_rows($result);
	if($n==1) 
	{
		if($row[0]=="active" or $row[0]=="unbanned" or $row[0]=="admin")
		{
			$_SESSION['user']=$_POST['userText'];
			$_SESSION['status']=$row[0];
			$_SESSION['LAST_ACTIVITY'] = time();
			//setcookie("user", $_POST['userText'], time()+400);
			header("Location: /home.php");
		}
		else if($row[0]=="banned")
		{
			header("Location: /home.php?loginStatus=BANNED");
		}
		else
		{
			header("Location: /home.php?loginStatus=INNACTIVE");
		}
	}
	else header("Location: /home.php?loginStatus=BAD_COMBINATION");
}
else if(isset($_POST['register']))
{
	header("Location: /register.php");
}
else if(isset($_POST['logout']))
{
	//setcookie("user", "", time()-1);
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
	
	header("Location: /home.php");
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




//first Echo
?><white_bold2><My_TopRight><?php 
if (isset($_SESSION['user']))
{
	//an o logarismenos einai o admin tote
	if (isset($_SESSION['status']) && $_SESSION['status']=="admin") 
	{
		$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
		$display_block = "<green>Welcome   " .$_SESSION['user'];
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
		$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
		$display_block = "Welcome   " .$_SESSION['user'];
		//$display_block = "Welcome   " .$_COOKIE["user"];
		$display_block .= "<form method=\"POST\" action=\"/home.php\">";
		$display_block .= "<br>";
		$display_block .= "<input type=\"Submit\" name=\"logout\" value=\"Log out\"/>";
		$display_block .= "<input type=button onClick=\"location.href='/cubeGame/myMaps.php'\" value='My Maps'>";
		$display_block .= "</Form>";
	}
}
//an den einai logarismenos kapios xrhsths
else 
{
	?><fieldset>  <legend>Log In </legend><?php
	//an prospa8hse na kanei login kai apetuxe
	if(isset($_GET['loginStatus']))
	{
		$display_block = "<br><b> LOGIN FAILED</b><br>";
		$display_block .= "Anonymous";
		if($_GET['loginStatus']=="INNACTIVE") echo "<script type='text/javascript'>alert('YOUR ACCOUNT IS STILL INNACTIVE');</script>";
		if($_GET['loginStatus']=="BANNED") echo "<script type='text/javascript'>alert('YOUR ACCOUNT HAD BEEN BANNED');</script>"; 
	}
	else if(isset($_GET["New_Account"]))
	{
		$display_block = "Your account had been created";
	}
	//den exei prospa8hsei na kanei akoma login
	else $display_block = "Anonymous";
	
	//an dn einai kaneis logarismenos tote na mporei na kanei o anonymos user logIn
	$display_block .= "<form method=\"POST\">";
	$display_block .= "<br>";
	$display_block .= "<b>username: <input type=\"text\" size=16 name=\"userText\">";
	$display_block .= "<br>";
	$display_block .= "password: <input type=\"password\" size=16 name=\"passText\"> </b>";
	$display_block .= "<br>";
	$display_block .= "<input type=\"Submit\" name=\"login\" value=\"Log In\"/>";
	$display_block .= "<input type=\"Submit\" name=\"register\" value=\"Register\">";
	$display_block .= "</Form>";
}

echo "$display_block <br>";
?></fieldset></My_TopRight></white_bold2><?php
//2o echo
//check if user is admin
?><C><h3><green>ALL NEWS<green></h3> </C>
<My_Center> 
</p>
<?php //<img src="1.png" alt="pic1" width="1000em" height="1000em">
if (isset($_SESSION['status']) && $_SESSION['status']=="admin") 
{
	?><form method="POST" action="/ReleasingNote.php">
	<br>
	<textarea class="FormElement" name="news" id="term" cols="40" rows="9"></textarea>

	<br>
	<My_Right>  <input type="Submit" name="newNote" value="Submit News">  </My_Right>
	</Form>
	<br><?php
}
?></My_Center><?php
if(isset($_SESSION['status']) && $_SESSION['status']=="admin") echo "<Notes_Position2>";
else echo "<Notes_Position4>";

//Show all realising notes (News)
?><br><br><?php
$result = mysqli_query($con,"SELECT * FROM news ORDER BY day DESC");
$row = mysqli_fetch_row($result);
$n = mysqli_num_rows($result);
//fix picture behind every note
for($i=0;$i<$n;$i++) 
{
	//count new lines in string!!! ("\n"). Fix the size of background pictures
	$newlinesInString=substr_count(nl2br($row[0]), "\n" );

	if(isset($_SESSION['status']) && $_SESSION['status']=="admin")
	{
		$height=18*($newlinesInString)+141;
		if($row[0]=='') $height=$height-28;
	}
	else
	{
		$height=16*($newlinesInString)+144;
		if($row[0]=='') $height=$height-11;
	}
	$heightEm=$height+"em";
	echo "<img src=\"2.png\" alt=\"pic1\" width=\"600em\" height=\"$heightEm\">";
	echo "<br><br>";
	$row = mysqli_fetch_row($result);
}

if(isset($_SESSION['status']) && $_SESSION['status']=="admin") echo "</Notes_Position2><Notes_Position>";
else echo "</Notes_Position4><Notes_Position3>";
?><br><br><?php
//echo the notes
$result = mysqli_query($con,"SELECT * FROM news ORDER BY day DESC");
$row = mysqli_fetch_row($result);
$n = mysqli_num_rows($result);
for($i=0;$i<$n;$i++) 
{
	echo "<deleteNot>$row[1]<br></deleteNot><br>";
	echo nl2br($row[0]);
	
	if (isset($_SESSION['status']) && $_SESSION['status']=="admin") 
	{
		echo "<form method=\"POST\" action=\"/ReleasingNote.php\">";
		echo "<input type=\"Hidden\" name=\"noteId\" value='$row[2]'>";
		?><deleteNot> <input type="Submit" name="deleteNote" value="Delete"> </deleteNot><?php
		echo "</Form>";
	}
	else echo "<br><br>";
	echo "<br><br><br><br>";
	
	$row = mysqli_fetch_row($result);
}
echo '<deleteNot> <a href=/ReleasingNote.php>View more</a> </deleteNot>';


if(isset($_SESSION['status']) && $_SESSION['status']=="admin") echo "</Notes_Position>";
else echo "</Notes_Position3>";
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