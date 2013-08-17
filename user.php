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

if(!file_exists("config.php")) 
{
	header("Location: install.php");
}
else
{
	include "config.php";
}

$username = htmlentities(stripslashes($_POST['username']));
$password = $_POST['password'];
$password_again = $_POST['password_again'];

include "themes/$theme/header.php";

$action = $_GET['action'];
if (isset($action))
{
	if ($action=="doregister")
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
							
							if(file_exists("db/users/$username.php")) { echo "<div class='text'>Error: User Exists!</div>"; } else {
							file_put_contents("db/users/$username.status", "user");
							file_put_contents("db/users/$username.color", $user_color);
							file_put_contents("db/users/$username.logo", "Board User");
							$pass_string = "<?php \$userpass = \"$password\" ?>";
							file_put_contents("db/users/" . $username . ".php", $pass_string);
							$old_users = file_get_contents("db/userlist.txt");
							$user = "<b>$username</b><br>";
							file_put_contents("db/userlist.txt", $user . $old_users);
							echo "<div class=\"text\">Your account has been created. You can now login.</div>";
							}
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
	else if($action=="register")
	{
		print <<<EOD
	<div class="text">
	<h2><b>Create An Account</b></h2><br>
	<form action='user.php?action=doregister' method='post'>
	Username: <input type='text' name='username'><br>
	Password: <input type='password' name='password'><br>
	Password Again: <input type="password" name="password_again"><br>
	<input type='submit' value='Create Account' name="enter" id="enter">
	</div>
EOD;
	}
	else 
	{
		echo "Error: Action not found!";
	}
}

if (!isset($action))
{
	header("Location: index.php");
}

include "themes/$theme/footer.php";

?>
