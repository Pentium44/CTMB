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
 */

include "config.php";

$user = $_SESSION['user'];
$username = htmlentities(stripslashes($_POST['username']));
$password = $_POST['password'];
$password_again = $_POST['password_again'];
$email = htmlentities(stripslashes($_POST['email']));

print <<<EOD
<html>
	<head>
		<title>$title</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
<body>
	<div class="title">$title</div>
EOD;

	print <<<EOD
	<center><span class="menu"><a href="index.php">Forum Index</a><a href="signup.php">Register</a><a href="index.php?action=userlist">Userlist</a><a href="admin_panel.php">Administration Panel</a></span></center><br>
EOD;


$action = $_GET['action'];
if (isset($action))
{
	if ($action=="go")
	{
		if (isset($username) && isset($password) && isset($password_again))
		{
			if ($username=="")
			{
				echo "<div class=\"text\">Error: Username not provided.</div>";
			}
			else
			{
				if ($password=="")
				{
					echo "<div class=\"text\">Error: password not provided.</div>";

				}
				else
				{
					if ($password_again=="")
					{
						echo "<div class=\"text\">Error: password in both textboxes not provided.</div>";

					}
					else
					{
						if ($password==$password_again)
						{
							if ($validation!="true")
							{
								file_put_contents("db/users/" . $username . ".validation", "valid");
							}
							else
							{
								file_put_contents("db/pendingusers.txt", $username . "<br>");
							}
							$encrypt_pass = base64_encode($password);
							file_put_contents("db/users/" . $username, $encrypt_pass);
							$old_users = file_get_contents("db/userlist.txt");
							$user = "<b>$username</b><br>";
							file_put_contents("db/userlist.txt", $user . $old_users);
							echo "<div class=\"text\">Your account has been created. You can now login.</div>";

						}
						else
						{
							echo "<div class=\"text\">Error: password do not match.</div>";
							
						}
					}
				}
			}
		}
	}
	else 
	{
		echo "Error: Action not found!";
	}
}

if (!isset($action))
{
	print <<<EOD
	<div class="text">
	<h2><b>Create An Account</b></h2><br>
	<form action='signup.php?action=go' method='post'>
	Username: <input type='text' name='username'><br>
	Email (Not Published): <input type="text" name="email"><br>
	Password: <input type='password' name='password'><br>
	Password Again: <input type="password" name="password_again"><br>
	<input type='submit' value='Create Account' name="enter" id="enter">
	</div>
EOD;
}

print <<<EOD
<br><div class="footer">&copy; CTMB - CrazyCoder Productions, 2012-2013</div>
</body>
</html>
EOD;

?>