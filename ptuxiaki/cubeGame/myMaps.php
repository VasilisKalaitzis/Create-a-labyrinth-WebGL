<!DOCTYPE html>
<html> 
<head> 
 <script src="../jquery.js"></script>
 <title> Ledriel's Gaming </title> </head>
<link rel="stylesheet" href="../My_Css.css"> 
<?php

session_start();
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1500)) 
{
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
//CHOOSE BACKGROUND FOR USER AND FOR ADMIN
if (isset($_SESSION['status']) && $_SESSION['status']=="admin")  echo "<body background=\"../background2.jpg\" bgcolor=\"ALICEBLUE\">";
else echo "<body background=\"../background.jpg\" bgcolor=\"ALICEBLUE\">";

?>
<My_Title>Ledriel's Gaming </My_Title>
<button_Image><img src="../button.png" style="height:4em; width:50em;">  </button_Image>
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
	$display_block .= "<form method=\"POST\" action=\"/home.php\">";
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
?><C><br><br><h3><white_bold2><green><b>CUBE GAME</b></green></white_bold2></h3> </C>

<MapBackground>
<?php
//Set the current user
$user='';
if(isset($_SESSION['user'])) $user=$_SESSION['user'];

$result = mysqli_query($con,"SELECT id,stageName,Author,stageLevel,numberOfCheeses,numberOfBuildings FROM stage WHERE Author='$user' Order By StageName");

//SHOW MAPS

$n = mysqli_num_rows($result);

//check in which page you currently are
if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

//check the remaining records
$n=$n-$page*9;
$nextpageEnable=0;
//if you have more than 9 record then show only 9 and enable the next page button
if($n>9) { $n=9; $nextpageEnable=1;}
//If there is at least 1 record in the database...search it to fill the current page
if($n>0)
{
	mysqli_data_seek($result, $page*9);
	$row = mysqli_fetch_row($result);
}

for($i=0;$i<$n;$i++)
{
	if($i%3==0 and $i!=0) echo"<br><br><br>";
	echo "<img src=\"creationStart.jpg\" style=\"height:14.5em; width:20em;\"> &nbsp ";
}
?>
</MapBackground>

<StagesLoc>
<?php
//show the maps
for($i=0;$i<$n;$i++)
{
	if ($i % 3 == 0) {echo "<br><br><br><br><br><br><br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";}
	else {echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";}
		
	if($row[3]==1){echo "<img src=\"plainsButton.jpg\" ONCLICK=\"window.location.href='/cubeGame/PLAY.php?StageID=$row[0]'\" style=\"cursor: pointer; height:9.5em; width:8em;\">";}
	else if($row[3]==2) {echo "<img src=\"stoneButton.jpg\" ONCLICK=\"window.location.href='/cubeGame/PLAY.php?StageID=$row[0]'\" style=\"cursor: pointer; height:9.5em; width:8em;\">";}
	else if($row[3]==3){echo "<img src=\"woodButton.jpg\" ONCLICK=\"window.location.href='/cubeGame/PLAY.php?StageID=$row[0]'\" style=\"cursor: pointer; height:9.5em; width:8em;\">";}
	else {echo "<img src=\"lava.jpg\" ONCLICK=\"window.location.href='/cubeGame/PLAY.php?StageID=$row[0]'\" style=\"cursor: pointer; height:9.5em; width:8em;\">";}
	$row = mysqli_fetch_row($result);
}
?>
</StagesLoc>
<?php

//SHOW INFORMATION ABOUT EVERY MAP
//If there is at least 1 record in the database...search it to fill the current page
if($n>0)
{
	mysqli_data_seek($result, $page*9);
	$row = mysqli_fetch_row($result);
}
$grammes=0;
$sthles=0;
for($i=0;$i<$n;$i++)
{
	//Load the best score for this current stage of the current logged User
	$result3 = mysqli_query($con,"SELECT MIN(score),user FROM time Where stage='$row[0]' GROUP BY user");
	$n3 = mysqli_num_rows($result3);
	//
	if($sthles==0)
	{	
		if($grammes==0) echo "<MapInfo1>";
		else if($grammes==1) echo "<MapInfo4>";
		else echo "<MapInfo7>";
		
		echo "<white_bold2><b>$row[1]</b></white_bold2>
				<white><br><b>By</b>: $row[2]
				<br><b>Cheeses</b>: $row[4]
				<br><b>Buildings</b>: $row[5] </white>";
				
		//Load the rating of the specific stage
			$result2 = mysqli_query($con,"SELECT stageID,rating,userID FROM rating WHERE stageID='$row[0]'");
			$row2 = mysqli_fetch_row($result2);
			$n2 = mysqli_num_rows($result2);
			$found=0;
			$rat=0; $avg=0;
			for($xxx=0;$xxx<$n2;$xxx++)
			{
				//If the current user has already voted this stage
				if($row2[2]==$user) $found=1;
				//Get the stage's sum rating
				$rat=$rat+$row2[1];
				$row2 = mysqli_fetch_row($result2);
			}
			if($n2!=0) $avg=$rat/$n2;
			//If the user had already voted
			if($found==0 && $user!='')
			{	
				//Avarage Five stars
				if($avg==5) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//Four stars
				else if($avg>=4) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//Three stars
				else if($avg>=3) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//Two stars
				else if($avg>=2) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//One star
				else if($avg>=1) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//No star
				else
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
			}
			//If user didn't vote yet
			else 
			{
				//Avegare Five stars
				if($avg==5) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//Four stars
				else if($avg>=4) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//Three stars
				else if($avg>=3) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//Two stars
				else if($avg>=2) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//One star
				else if($avg>=1) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//No star
				else 
				{
					echo("<br><img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
			}
		//end of rating				
		//echo best Score
		if($n3>0)
		{
				$row3 = mysqli_fetch_row($result3);
				echo "<br> <white_bold><b>Best Time: $row3[0]
						<br> By:  $row3[1] </b></white_bold>";
		}
		//
		$sthles++;
		
		if($grammes==0) echo "</MapInfo1>";
		else if($grammes==1) echo "</MapInfo4>";
		else echo "</MapInfo7>";
	}
	else if($sthles==1)
	{
		if($grammes==0) echo "<MapInfo2>";
		else if($grammes==1) echo "<MapInfo5>";
		else echo "<MapInfo8>";
		
		echo "<white_bold2><b>$row[1]</b></white_bold2>
				<white><br><b>By</b>: $row[2]
				<br><b>Cheeses</b>: $row[4]
				<br><b>Buildings</b>: $row[5] </white>";
				
		//Load the rating of the specific stage
			$result2 = mysqli_query($con,"SELECT stageID,rating,userID FROM rating WHERE stageID='$row[0]'");
			$row2 = mysqli_fetch_row($result2);
			$n2 = mysqli_num_rows($result2);
			$found=0;
			$rat=0; $avg=0;
			for($xxx=0;$xxx<$n2;$xxx++)
			{
				//If the current user has already voted this stage
				if($row2[2]==$user) $found=1;
				//Get the stage's sum rating
				$rat=$rat+$row2[1];
				$row2 = mysqli_fetch_row($result2);
			}
			if($n2!=0) $avg=$rat/$n2;
			//If the user had already voted
			if($found==0 && $user!='') 
			{	
				//Avarage Five stars
				if($avg==5) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//Four stars
				else if($avg>=4) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//Three stars
				else if($avg>=3) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//Two stars
				else if($avg>=2) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//One star
				else if($avg>=1) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//No star
				else
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
			}
			//If user didn't vote yet
			else 
			{
				//Avegare Five stars
				if($avg==5) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//Four stars
				else if($avg>=4) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//Three stars
				else if($avg>=3) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//Two stars
				else if($avg>=2) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//One star
				else if($avg>=1) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//No star
				else 
				{
					echo("<br><img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
			}
		//end of rating	
		//echo best Score
		if($n3>0)
		{
				$row3 = mysqli_fetch_row($result3);
				echo "<br> <white_bold><b>Best Time: $row3[0]
						<br> By:  $row3[1] </b></white_bold>";
		}
		//
		$sthles++;
		if($grammes==0) echo "</MapInfo2>";
		else if($grammes==1) echo "</MapInfo5>";
		else echo "</MapInfo8>";
	}
	else
	{
		if($grammes==0) echo "<MapInfo3>";
		else if($grammes==1) echo "<MapInfo6>";
		else echo "<MapInfo9>";
		
		echo "<white_bold2><b>$row[1]</b></white_bold2>
				<white><br><b>By</b>: $row[2]
				<br><b>Cheeses</b>: $row[4]
				<br><b>Buildings</b>: $row[5] </white>";
				
			
		//Load the rating of the specific stage
			$result2 = mysqli_query($con,"SELECT stageID,rating,userID FROM rating WHERE stageID='$row[0]'");
			$row2 = mysqli_fetch_row($result2);
			$n2 = mysqli_num_rows($result2);
			$found=0;
			$rat=0; $avg=0;
			for($xxx=0;$xxx<$n2;$xxx++)
			{
				//If the current user has already voted this stage
				if($row2[2]==$user) $found=1;
				//Get the stage's sum rating
				$rat=$rat+$row2[1];
				$row2 = mysqli_fetch_row($result2);
			}
			if($n2!=0) $avg=$rat/$n2;
			//If the user had already voted
			if($found==0 && $user!='') 
			{	
				//Avarage Five stars
				if($avg==5) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//Four stars
				else if($avg>=4) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//Three stars
				else if($avg>=3) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//Two stars
				else if($avg>=2) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//One star
				else if($avg>=1) 
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
				//No star
				else
				{
					echo("<br><img name=\"star\" ONCLICK=\"SubmitRating($row[0],1,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],2,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],3,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],4,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
					echo("<img name=\"star\" ONCLICK=\"SubmitRating($row[0],5,'$user')\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em; cursor: pointer;\" onmouseover=\"\">");
				}
			}
			//If user didn't vote yet
			else 
			{
				//Avegare Five stars
				if($avg==5) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//Four stars
				else if($avg>=4) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//Three stars
				else if($avg>=3) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//Two stars
				else if($avg>=2) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//One star
				else if($avg>=1) 
				{
					echo("<br><img name=\"star\" src=\"dragon.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
				//No star
				else 
				{
					echo("<br><img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
					echo("<img name=\"star\" src=\"dragon2.png\" style=\"height:1.8em; width:1.75em;\" onmouseover=\"\">");
				}
			}
		//end of rating	
		//echo best Score
		if($n3>0)
		{
				$row3 = mysqli_fetch_row($result3);
				echo "<br> <white_bold><b>Best Time: $row3[0]
						<br> By:  $row3[1] </b></white_bold>";
		}
		//
		
		if($grammes==0) echo "</MapInfo3>";
		else if($grammes==1) echo "</MapInfo6>";
		else echo "</MapInfo9>";
				
		$sthles=0; $grammes=$grammes+1;
	}
	$row = mysqli_fetch_row($result);
}

if($page>0) echo("<PrevPage> <img name=\"prevPage\" ONCLICK=\"ChangePage(0)\" src=\"prevPage.png\" style=\"height:4em; width:8em; cursor: pointer;\" onmouseover=\"\"></PrevPage>");
if($nextpageEnable==1) echo("<NextPage> <img name=\"nextPage\" ONCLICK=\"ChangePage(1)\" src=\"nextPage.png\" style=\"height:4em; width:8em; cursor: pointer;\" onmouseover=\"\"></NextPage>");

?>

<script type="text/javascript"> 
//go to the previous or next page
function ChangePage(PrevOrNext)
{
	if(PrevOrNext==0)
	{
		var page ="<?php echo $page-1; ?>";
	}
	else if(PrevOrNext==1)
	{
		var page ="<?php echo $page+1; ?>";
	}
	window.location.href = "/cubeGame/myMaps.php?page="+page;
}
</script>

<script type="text/javascript"> 
function SubmitRating(stageID,rating,userID)
{
		//subtim rating
		var dataObject = {stageID: stageID,
							rating: rating,
							userID : userID};
	
		alert("Your vote has been submitted");
		$.ajax({type: "POST",
             url: "insertRatingToDatabase.php",
			 data: dataObject,
			 cache: false
			});
		
		//wait some seconds to redict on self
		setTimeout(
			function() 
			{
				window.location.href=document.URL;
			}, 100);
}
</script>


</script>
</fieldset>
</body>
</html>