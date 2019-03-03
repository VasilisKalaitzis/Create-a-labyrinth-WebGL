<!DOCTYPE html>
<html>
<head>
</head>
 <title> Ledriel's Gaming </title> </head>
<link rel="stylesheet" href="My_Css.css"> 
<body onload="startup()" oncontextmenu="return false;" background="background.jpg" bgcolor=silver>

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


<pic2>    <img src="cubeGame/creationStart.jpg" style="height:20em; width:40em;">         </pic2>
<?php

//sindesou me to phpmyadmin
$con = mysqli_connect('localhost','root') or
die('Could not connect to the database');
//sindesou me to database store_database
mysqli_select_db($con,'ptuxiaki');

$display_block = "<My_Center2><br><b><white_bold2> Registration<white_bold2></b><br>";

//an o xrhsths ekane kapio la8os, min xreiazete na ta 3anasumplhrwsei ola ta pedia apo tin arxh
if(isset($_POST['done']))
{
	$isEverythingFilled=0;
	if($_POST['userText']=="")
	{
		$usernameMissing=1;
		$isEverythingFilled=1;
	}
	if($_POST['passText']=="")
	{
		$passMissing=1;
		$isEverythingFilled=1;
	}
	if($_POST['confirmPass']=="")
	{
		$pass2Missing=1;
		$isEverythingFilled=1;
	}
	if($_POST['email']=="")
	{
		$emailMissing=1;
		$isEverythingFilled=1;
	}
	if($_POST['fAnswer']=="")
	{
		$ansMissing=1;
		$isEverythingFilled=1;
	}
	$par1=$_POST['userText'];
	$par2=$_POST['passText'];
	$par3=$_POST['confirmPass'];
	$par4=$_POST['email'];
	$par5=$_POST['fAnswer'];
	$par6=$_POST['phone'];
	$par7=$_POST['country'];
	$selectOption = $_POST['fQuestion'];
	
	
	if($isEverythingFilled==0)
	{
		//an to email den exei tin swsth morfh
		$emailValid=0;
		$stage=0;
		$i=0;
		while($i<strlen($par4))
		{
			//an exei prohgh8ei @ kai . ta epomena grammata prepei nane com  (p.x ***@***.com)
			if($stage==2)
			{
				if($i+3==strlen($par4))
				{	
					if($par4[$i]=='c' and $par4[$i+1]=='o' and $par4[$i+2]=='m') 
					{
						$emailValid=1;
						$i++;
					}
					else $i=strlen($par4);
				}
				else if($i+2==strlen($par4))
				{	
					if($par4[$i]=='g' and $par4[$i+1]=='r') 
					{
						$emailValid=1;
						$i++;
					}
					else $i=strlen($par4);
				}
				else $i=strlen($par4);
			}
			//an to sigekrimeno gramma den einai sumvolo apla proxwra
			else if(($par4[$i]>='0' and $par4[$i]<='9') or ($par4[$i]>='a' and $par4[$i]<='z') or ($par4[$i]>='A' and $par4[$i]<='Z') and $stage<2)
			{
				$i++;
			}
			//an o xarakthras einai @ tote proxwra alla an 3anadeis to @ tote 8a einai la8os
			else if($par4[$i]=='@' and $stage==0)
			{
				$stage=1;
				$i++;
			}
			//an exei prohgh8ei @ kai tra vre8ike teleia
			else if($par4[$i]=='.' and $stage==1)
			{
				$stage=2;
				$i++;
			}
			//an einai kapio allo sumvolo tote einai la8os
			else
			{
				$i=strlen($par4);
			}
		}
		//elen3e an to username kai to pass exoun mhkos 3 kai panw
		if(strlen($par1)<3 or strlen($par1)>12)
		{
			$display_block .= "<font color=\"ff0000\"> username's length must be between 3 and 12 chars<br> </font>";
		}
		else if(strlen($par2)<3 or strlen($par2)>12)
		{
			$display_block .= "<font color=\"ff0000\"> password's length must be between 3 and 12 chars<br> </font>";
		}
		//an to password kai to password epivevewshs den einai idia
		else if($par2!=$par3)
		{
			$display_block .= "<font color=\"ff0000\"> Confirmation password must be the same with password<br></font>";
		}
		//an to email den exei tin swsth morfh
		else if($emailValid==0)
		{
			$display_block .= "<font color=\"ff0000\"> Please, check if the email you have entered is correct!  (example: roadsw2123@gmail.com)<br> </font>";
		}
		//Elen3e an to username uparxei hdh sthn vash dedomenwn, an den uparxei dhmiourghse to
		else
		{
		//elen3e
			$result = mysqli_query($con,"SELECT * FROM users WHERE username='".$par1."'");
			$row = mysqli_fetch_row($result);
			$n = mysqli_num_rows($result);
			//uparxei hdh sthn vash
			if($n>0) 
			{
				$display_block .= "<font color=\"ff0000\"> USERNAME ALREADY EXISTS<br> </font>";
			}
			//den uparxei hdh sthn vash
			//kataxwrhse sthn vash
			else
			{
				$sql="INSERT INTO users (username,password,email,phone,country,favoriteQ,favoriteA,status) VALUES ('$par1','$par2','$par4','$par6','$par7','$selectOption','$par5','innactive')";
				mysqli_query($con,$sql);
				header("Location: /home.php?New_Account=Account_Has_Been_Created");
			}
		}

	}
}
else if(isset($_POST['toHome']))
{
	header("Location: /home.php");
}
else
{
	$par1="";
	$par2="";
	$par3="";
	$par4="";
	$par5="";
	$par6="";
	$par7="";
}

$display_block .= "<white> <form method=\"POST\" action=\"/register.php\">";
$display_block .= "<br>";
$display_block .= "username*: <input type=\"text\" size=16 name=\"userText\" value=".$par1.">";
if(isset($usernameMissing))
{
	$display_block .= " <font color=\"ff0000\"> username is missing </font>";
}
$display_block .= "<br>";
$display_block .= "password*: <input type=\"password\" size=16 name=\"passText\" value=".$par2."> </b>";
if(isset($passMissing))
{
	$display_block .= "<font color=\"ff0000\"> password is missing </font>";
}
$display_block .= "<br>";
$display_block .= "confirm password*: <input type=\"password\" size=16 name=\"confirmPass\" value=".$par3."> </b>";
if(isset($pass2Missing))
{
	$display_block .= "<font color=\"ff0000\"> confirm for password is missing </font>";
}
$display_block .= "<br>";
$display_block .= "email*: <input type=\"text\" size=16 name=\"email\" value=".$par4."> </b>";
if(isset($emailMissing))
{
	$display_block .= "<font color=\"ff0000\"> email is missing </font>";
}
$display_block .= "<br>";
$display_block .= "phone: <input type=\"text\" size=16 name=\"phone\" value=".$par6."> </b>";
$display_block .= "<br>";
$display_block .= "country: <input type=\"text\" size=16 name=\"country\" value=".$par7."> </b>";
$display_block .= "<br>";
$display_block .= "Favorite question*: 	<select name=\"fQuestion\">
											<option value=\"0\">What's your favorite food</option>
											<option value=\"1\">What's your favorite animal</option>
											<option value=\"2\">Why dinosaurs eats chips</option>
											<option value=\"3\">Poropo</option>
										</select> ";
$display_block .= "<br>";
$display_block .= "Favorite answer*: <input type=\"text\" size=16 name=\"fAnswer\" value=".$par5."> </b>";
if(isset($ansMissing))
{
	$display_block .= "<font color=\"ff0000\"> favorite answer is missing is missing </font>";
}
$display_block .= "<br>";
$display_block .= "<input type=\"Submit\" name=\"done\" value=\"Submit\"/>";
$display_block .= "<input type=\"Submit\" name=\"reset\" value=\"Reset\"/>";
$display_block .= "<input type=\"Submit\" name=\"toHome\" value=\"Home\"/>";
$display_block .= "</Form></white></My_Center2>";
echo "$display_block <br>";

?><white_bold2><My_TopRight><fieldset>  <legend>Log in </legend> <?php

	//an dn einai kaneis logarismenos tote na mporei na kanei o anonymos user logIn
	$display_block = "<form method=\"POST\" action=\"/home.php\">";
	$display_block .= "<br>";
	$display_block .= "<b>username: <input type=\"text\" size=16 name=\"userText\">";
	$display_block .= "<br>";
	$display_block .= "password: <input type=\"password\" size=16 name=\"passText\"> </b>";
	$display_block .= "<br>";
	$display_block .= "<input type=\"Submit\" name=\"login\" value=\"Log In\"/>";
	$display_block .= "<input type=\"Submit\" name=\"register\" value=\"Register\">";
	$display_block .= "</Form>";

	
	
	
	
	
echo "$display_block <br>";
?></fieldset></My_TopRight></white_bold2> 
</fieldset>
</body>

</html>