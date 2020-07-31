<?php
session_start();
/*
 * CTMB - Crazy Tiny Message Board - 2012-2020
 * CTMB (Crazy Tiny Message Board) is a simple, flatfile database message
 * board that is created by Chris Dorman (cddo.cf), 2012-2020
 * CTMB is released under the Creative Commons - BY-NC-SA 4.0 NonPorted license
 *
 * CTMB is released with NO WARRANTY.
 *
 */

if(!file_exists("config.php")) 
{
	header("Location: install.php");
}
else
{
	include_once("config.php");
}

// Set user specified theme, else use default
if(isset($_SESSION['ctmb-theme'])){ $theme = $_SESSION['ctmb-theme']; } else { $theme = "default"; }

include "themes/$theme/header.php";

if (isset($_GET['action']))
{
	$action = $_GET['action'];
	if ($action=="doregister")
	{
		if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_again']))
		{
			$username = htmlentities(stripslashes($_POST['username']));
			$password = $_POST['password'];
			$password_again = $_POST['password_again'];
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
								$old_users = file_get_contents("db/pendingusers.txt");
								file_put_contents("db/pendingusers.txt", $username . "<br>" . $old_users);
							}
							$password_hash = sha1(md5($password));
							if(file_exists("db/users/$username.php")) { echo "<div class='text'>Error: User Exists!</div>"; } else {
							file_put_contents("db/users/$username.status", "user");
							file_put_contents("db/users/$username.color", $user_color);
							file_put_contents("db/users/$username.rank", "Board User");
							file_put_contents("db/users/$username.postnumber", "0");
							file_put_contents("db/users/$username.txt", "$username");
							file_put_contents("db/users/$username.sig", "User signature");
							file_put_contents("db/users/$username.theme", "default"); // Set users theme (default)
							$pass_string = "<?php \$userpass = \"$password_hash\" ?>";
							file_put_contents("db/users/" . $username . ".php", $pass_string);
							
							// Userlist was removed, now list is loaded dynamically
							//$old_users = file_get_contents("db/userlist.txt");
							//$user = "<a href=\"user.php?action=userpanel&user=$username\">$username</a><br>\n";
							//file_put_contents("db/userlist.txt", $user . $old_users);
							
							// Set owner account as latest created user
							file_put_contents("db/users/latest", "<b style=\"color:$user_color;\">$username</b>");
							
							echo "<div class=\"text\">Your account has been created. <a href='index.php?do=login'>Login</a></div>";
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
	else if($action=="userpanel")
	{
		if(isset($_GET['user']))
		{
			$user = $_GET['user'];
			if(file_exists("db/users/$user.php"))
			{
				include_once("bb.php");
				$usercolor = file_get_contents("db/users/$user.color");
				$userrank = file_get_contents("db/users/$user.rank");
				$userpostnumber = file_get_contents("db/users/$user.postnumber");
				$usersig = nl2br(bbcode_format(stripslashes(htmlentities(file_get_contents("db/users/$user.sig")))));
				$userstatus = file_get_contents("db/users/$user.status");
				echo "\n<div class=\"text\"><h2 style='color:$usercolor;'>$user</h2>\n";
				echo "<table id='tblarge'><tr>\n<td class='userinfo'>Users avatar:<br />\n<img style='margin: auto; max-width: 140px;' src='load.php?action=avatar&name=$user' alt='User Avatar' /></td>\n";
				echo "<td class='userpost'>\n";
				echo "User rank: $userrank<br />\n";
				echo "User is set as admin: \n";
				if($userstatus=="admin") { echo "True"; } else { echo "False"; }
				echo "<br />\n";
				echo "Number of posts: $userpostnumber<br />\n";
				echo "User signature:\n<div style='padding:8px;' class='text_small'>$usersig</div>";
				echo "</td></tr></table>"; // Close table and such
				echo "</div>";
			}
			else
			{
				echo "<div class=\"text\">Error: User does not exist, or was removed.</div>";
			}
		}
		else
		{
			echo "<div class=\"text\">Error: User not specified.</div>";
		}
	}
	else 
	{
		echo "Error: Action not found!";
	}
}

if (!isset($_GET['action']))
{
	header("Location: index.php");
}

include "themes/$theme/footer.php";

?>
