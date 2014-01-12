<?php 
/* CTMB Theme
 * (C) Chris Dorman, 2013-2014 - CC-BY-NC 3.0
 */

include "config.php"; 

$theme = "default"; // Theme name (for stylesheet)
?>

<html>
	<head>
		<title><?php echo $title ?></title>
		<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme ?>/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
<body>
	<div class="title"><?php echo $title; ?><div style="font-size:18px;color:silver;"><?php echo $desc; ?></div></div>
	<div class="board">
		<?php
			if(isset($_SESSION['ctmb-login-user']) && isset($_SESSION['ctmb-login-pass']) && file_exists("db/users/" . $_SESSION['ctmb-login-user'] . ".php"))
			{
				include "db/users/" . $_SESSION['ctmb-login-user'] . ".php";
				if($_SESSION['ctmb-login-pass']==$userpass)
				{
					$check_admin = file_get_contents("db/users/" . $_SESSION['ctmb-login-user'] . ".status");
					if($check_admin!="admin")
					{
						print <<<EOD
						<!--<center>--><div class="menu"><a href="index.php">Forum Index</a><a href="index.php?action=logout">Logout</a><a href="index.php?action=userlist">Userlist</a><a href="up.php">Control Panel</a></div><!--</center>--><br />
EOD;
					}
					else
					{
						print <<<EOD
						<!--<center>--><div class="menu"><a href="index.php">Forum Index</a><a href="index.php?action=logout">Logout</a><a href="index.php?action=userlist">Userlist</a><a href="up.php">Control Panel</a><a href="ap.php">Administration</a></div><!--</center>--><br />
EOD;
					}					
				}
				else
				{
					print <<<EOD
					<!--<center>--><div class="menu"><a href="index.php">Forum Index</a><a href="user.php?action=register">Register</a><a href="index.php?action=login">Login</a><a href="index.php?action=userlist">Userlist</a></div><!--</center>--><br />
EOD;
				}
			}
			else
			{
				print <<<EOD
				<!--<center>--><div class="menu"><a href="index.php">Forum Index</a><a href="user.php?action=register">Register</a><a href="index.php?action=login">Login</a><a href="index.php?action=userlist">Userlist</a></div><!--</center>--><br />
EOD;
			} ?>
