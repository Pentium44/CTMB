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

include "themes/$theme/header.php";

$date = date("F j, Y");
$time = date("g:i a");
$date_string = "$date at $time";

if (isset($_GET['action']))
{
	$action = $_GET['action'];
	$username = $_POST['username'];
	if ($action=="login")
	{
		if ($username==$admin_account)
		{
			$method = $_POST['method'];
			if ($method=="Remove User")
			{
				$password = $_POST['password'];
				$userpass = file_get_contents("db/users/" . $username);
				$decrypt_pass = base64_decode($userpass);
				if ($password==$decrypt_pass)
				{
					// Mark in log success logging in //
					$log_file = "db/logs/logins.txt";
					$log_content_old = file_get_contents($log_file);
					$log_content_string = "<td>$username</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n<td><font color=\"#00ff00\">Successful</font></td>\n</tr><tr>\n\n";
					file_put_contents($log_file, $log_content_string . $log_content_old);
					
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
					// Mark in log error logging in //

					$log_file = "db/logs/logins.txt";
					$log_content_old = file_get_contents($log_file);
					$log_content_string = "<td>$username</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n<td><font color=\"red\">Failure</font></td>\n</tr><tr>\n\n";
					file_put_contents($log_file, $log_content_string . $log_content_old);	
					
					print <<<EOD
					<div class="text">Error: Administrator password incorrect</div>
EOD;
				}	
			}
			else if ($method=="Pending Users")
			{
				// Mark in log success logging in //
				$log_file = "db/logs/logins.txt";
				$log_content_old = file_get_contents($log_file);
				$log_content_string = "<td>$username</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n<td><font color=\"#00ff00\">Successful</font></td>\n</tr><tr>\n\n";
				file_put_contents($log_file, $log_content_string . $log_content_old);				

				$password = $_POST['password'];
				$userpass = file_get_contents("db/users/" . $username);
				$decrypt_pass = base64_decode($userpass);
				if ($password==$decrypt_pass)
				{
					$pendingusers = file_get_contents("db/pendingusers.txt");
					print <<<EOD
					<div class="text"><b><h2>Pending Users</h2></b>
EOD;
					echo $pendingusers;
					print <<<EOD
					<br>
					<form action="admin_panel.php?action=validate_user" method="post">
					Username: <input type="text" name="username"><br>
					Action (Check box to accept user): <input type="checkbox" name="valid_action" value="accept">Accept<br>
					<input type="submit" value="Validate User" name="validate_user" id="validate_user">
					</div>	
EOD;
				}
				else
				{
					// Mark in log error logging in //

					$log_file = "db/logs/logins.txt";
					$log_content_old = file_get_contents($log_file);
					$log_content_string = "<td>$username</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n<td><font color=\"red\">Failure</font></td>\n</tr><tr>\n\n";
					file_put_contents($log_file, $log_content_string . $log_content_old);	
					
					print <<<EOD
					<div class="text">Error: Administrator password incorrect</div>
EOD;
				}
			}
			else if($method=="Logs")
			{
				// Mark in log success logging in //
				$log_file = "db/logs/logins.txt";
				$log_content_old = file_get_contents($log_file);
				$log_content_string = "<td>$username</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n<td><font color=\"#00ff00\">Successful</font></td>\n</tr><tr>\n\n";
				file_put_contents($log_file, $log_content_string . $log_content_old);				
	
				$password = $_POST['password'];
				$userpass = file_get_contents("db/users/" . $username);
				$decrypt_pass = base64_decode($userpass);
				if ($password==$decrypt_pass)
				{
					$log_posts = file_get_contents("db/logs/posts.txt");
					$log_topics = file_get_contents("db/logs/topics.txt");
					$log_logins = file_get_contents("db/logs/logins.txt");
					// Center Table //
					echo "<center><div class='text'>";
					echo "<h2><b>Board Logs</b></h2><br>\n<b>Administrator Logins</b><br>\n<table border='1'><tr>";
					echo "<td>Username</td>\n<td>Time & Date</td>\n<td>External IP</td>\n<td>Status</td>\n</tr><tr>\n\n";
					echo $log_logins;
					echo "</tr></table>";
					echo "<b>Topic Creations</b><br>\n<table border='1'><tr>";
					echo "<td>Username</td>\n<td>Topic Name</td>\n<td>Topic ID</td>\n<td>Time & Date</td>\n<td>External IP</td>\n</tr><tr>\n\n";
					echo $log_topics;
					echo "</tr></table>";
					echo "<b>User Posts</b><br>\n<table border='1'><tr>";
					echo "<td>Username</td>\n<td>Topic ID</td>\n<td>Time & Date</td>\n<td>External IP</td>\n</tr><tr>\n\n";
					echo $log_posts;
					echo "</tr></table>";
					// Close Center //
					echo "</div></center>";
				}
				else
				{
					// Mark in log error logging in //

					$log_file = "db/logs/logins.txt";
					$log_content_old = file_get_contents($log_file);
					$log_content_string = "<td>$username</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n<td><font color=\"red\">Failure</font></td>\n</tr><tr>\n\n";
					file_put_contents($log_file, $log_content_string . $log_content_old);
					
					echo "<div class='text'>Error: Administrator password incorrect</div>";
				}
			}
			else
			{
				print <<<EOD
				<div class="text">Error: Unknown method</div>
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
			<div class="text">Error: Username not specified</div>
EOD;
		}
	}
	
	if ($action=="validate_user")
	{
		if (isset($_POST['username']))
		{
			if ($_POST['username']!="")
			{
				if (file_exists("db/users/" . $_POST['username']))
				{
					$username = $_POST['username'];
					$pendinguserslist = file_get_contents("db/pendingusers.txt");
					$remove_user = str_replace($_POST['username'] . "<br>", "", $pendinguserslist);
					if (!isset($_POST['valid_action']))
					{
						file_put_contents("db/users/" . $_POST['username'] . ".validation", "ibvalid");
						file_put_contents("db/pendingusers.txt", $remove_user);
						echo "<div class=\"text\">$username declined</div>";
					}
					if (isset($_POST['valid_action']))
					{
						file_put_contents("db/users/" . $_POST['username'] . ".validation", "valid");
						file_put_contents("db/pendingusers.txt", $remove_user);
						print <<<EOD
						<div class="text">$username validated</div>
EOD;
					}
				}
				else
				{
					print <<<EOD
					<div class="text">Error: User was not found</div>
EOD;
				}
			}
			else
			{
				print <<<EOD
				<div class="text">Error: No username specified</div>
EOD;
			}
		}
		else
		{
			print <<<EOD
			<div class="text">Error: Someone is trying to validate themselves!</div>
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
		Action: <select name="method">
				<option>Remove User</option>
				<option>Pending Users</option>
				<option>Logs</option>
				</select><br>
		<input type="submit" value="Login">
	</div>
EOD;
}

include "themes/$theme/footer.php";

?>
