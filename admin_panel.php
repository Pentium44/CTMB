<?php
/*
 * CTMB - Crazy Tiny Message Board - (C) CrazyCoder Productions, 2012-2013
 * CTMB (Crazy Tiny Message Board) is a simple, flatfile database message
 * board that is created by Chris Dorman (CrazyCoder Productions), 2012-2013
 * CTMB is released under the Creative Commons - BY - NC 3.0 NonPorted license
 * 
 * Website : http://cdrom.co.nf/cutils.php - Maintained By Chris Dorman
 * CTMB is released with NO WARRANTY.
 * 
 *//

include "config.php";
print <<<EOD
<html>
	<head>
		<title>$title</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
<body>
	<div class="title">$title</div>
EOD;

/* Menu List for Login/out, index, and admin panel */
	print <<<EOD
	<center><span class="menu"><a href="index.php">Forum Index</a><a href="signup.php">Register</a><a href="index.php?action=userlist">Userlist</a><a href="admin_panel.php">Administration Panel</a></span></center><br>
EOD;

$action = $_GET['action'];

if (isset($action))
{
	$username = $_POST['username'];
	if ($action=="login")
	{
		if ($username==$admin_account)
		{
			$password = $_POST['password'];
			$userpass = file_get_contents("db/users/" . $username);
			$decrypt_pass = base64_decode($userpass);
			if ($password==$decrypt_pass)
			{
				print <<<EOD
				<div class="text"><b><h2>Administration Panel</h2></b>
				<b>Remove User</b><br>
				<form action="admin_panel.php?action=rmuser" method="post">
				Username: <input type="text" name="username"><br>
				<input type="submit" value="Remove User" name="remove_user" id="remove_user">
				</div>	
EOD;
			}
			else
			{
				print <<<EOD
				<div class="text">Error: Administrator password incorrect</div>
EOD;
			}
			
		}
		else
		{
			print <<<EOD
			<div class="text">Error: User is not marked as administrator</div>
EOD;
		}
	}
	if ($action=="rmuser")
	{
		if (isset($_POST['username']) && isset($_POST['remove_user']))
		{
			$user_to_replace = $_POST['username'];
			unlink('db/users/' . $_POST['username']);
			$userlist = "db/userlist.txt";
			$userlist_data = file_get_contents($userlist);
			$remove_user_from_list = str_replace("<b>$user_to_replace</b><br>", "", $userlist_data);
			file_put_contents($userlist, $remove_user_from_list);
			print <<<EOD
			<div class="text">Removed $username</div>
EOD;
		}
		else
		{
			print <<<EOD
			<div class="text">Error : Username not specified</div>
EOD;
		}
	}
}

if (!isset($action))
{
print <<<EOD
	<div class="text"><h2><b>Administration Panel</b></h2>
		<form action="admin_panel.php?action=login" method="post">
		Admin Username: <input type="text" name="username"><br>
		Admin Password: <input type="password" name="password"><br>
		<input type="submit" value="Login">
	</div>
EOD;
}

print <<<EOD
<br><div class="footer">&copy; CTMB - CrazyCoder Productions, 2012-2013</div>
</body>
</html>
EOD;
?>