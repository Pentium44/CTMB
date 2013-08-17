<?php session_start(); include "config.php"; ?>

<html>
	<head>
		<title><?php echo $title ?></title>
		<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme ?>/style.css">
	</head>
<body>
	<div class="title"><?php echo $title ?></div><br>
	<div class="board">
	<table><tr><td style="width:170px;vertical-align:top;">
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
		<span class="menu">
			<a href="index.php">Forum Index</a><br>
			<a href="index.php?action=logout">Logout</a><br>
			<a href="index.php?action=userlist">Userlist</a><br>
			<a href="avatar.php">Avatars</a><br>
		</span></td>
EOD;
					}
					else
					{
						print <<<EOD
		<span class="menu">
			<a href="index.php">Forum Index</a><br>
			<a href="index.php?action=logout">Logout</a><br>
			<a href="index.php?action=userlist">Userlist</a><br>
			<a href="avatar.php">Avatars</a><br>
			<a href="admin_panel.php">Administration</a><br>
		</span></td>
EOD;
					}					
				}
				else
				{
					print <<<EOD
		<span class="menu">
			<a href="index.php">Forum Index</a><br>
			<a href="user.php?action=register">Register</a><br>
			<a href="index.php?action=login">Login</a><br>
			<a href="index.php?action=userlist">Userlist</a><br>
		</span></td>
EOD;
				}
			}
			else
			{
				print <<<EOD
		<span class="menu">
			<a href="index.php">Forum Index</a><br>
			<a href="user.php?action=register">Register</a><br>
			<a href="index.php?action=login">Login</a><br>
			<a href="index.php?action=userlist">Userlist</a><br>
		</span></td>
EOD;
			} ?>
	<td style="width:730px;vertical-align:top;">