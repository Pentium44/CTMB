<?php session_start(); include "config.php"; ?>

<html>
	<head>
		<title><?php echo $title ?></title>
		<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme ?>/style.css">
	</head>
<body>
	<div class="title"><?php echo $title ?></div>
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
						<center><span class="menu"><a href="index.php">Forum Index</a><a href="index.php?action=logout">Logout</a><a href="index.php?action=userlist">Userlist</a><a href="avatar.php">Avatars</a></span></center><br><br>
EOD;
					}
					else
					{
						print <<<EOD
						<center><span class="menu"><a href="index.php">Forum Index</a><a href="index.php?action=logout">Logout</a><a href="index.php?action=userlist">Userlist</a><a href="avatar.php">Avatars</a><a href="admin_panel.php">Administration</a></span></center><br><br>
EOD;
					}					
				}
				else
				{
					print <<<EOD
					<center><span class="menu"><a href="index.php">Forum Index</a><a href="user.php?action=register">Register</a><a href="index.php?action=login">Login</a><a href="index.php?action=userlist">Userlist</a></span></center><br><br>
EOD;
				}
			}
			else
			{
				print <<<EOD
				<center><span class="menu"><a href="index.php">Forum Index</a><a href="user.php?action=register">Register</a><a href="index.php?action=login">Login</a><a href="index.php?action=userlist">Userlist</a></span></center><br><br>
EOD;
			} ?>