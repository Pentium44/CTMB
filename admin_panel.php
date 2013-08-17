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
session_start();

if(!file_exists("config.php")) 
{
	header("Location: install.php");
}
else
{
	include "config.php";
}

include "themes/$theme/header.php";

$date = date("F j, Y");
$time = date("g:i a");
$date_string = "$date at $time";
if(isset($_SESSION['ctmb-login-user']) && isset($_SESSION['ctmb-login-pass']))
{
	if(file_exists("db/users/" . $_SESSION['ctmb-login-user'] . ".php"))
	{
		include "db/users/" . $_SESSION['ctmb-login-user'] . ".php";
		if($_SESSION['ctmb-login-pass']==$userpass)
		{
			$user_status = file_get_contents("db/users/" . $_SESSION['ctmb-login-user'] . ".status");
			if($user_status=="admin")
			{
if (isset($_GET['action']))
{
	$action = $_GET['action'];
	$username = $_SESSION['ctmb-login-user'];
	if ($action=="panel")
	{
		$method = $_GET['method'];
		if ($method=="rmuser")
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
		else if ($method=="puser")
		{
			// Mark in log success logging in //
			$log_file = "db/logs/logins.txt";
			$log_content_old = file_get_contents($log_file);
			$log_content_string = "<td>$username</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n<td><font color=\"#00ff00\">Successful</font></td>\n</tr><tr>\n\n";
			file_put_contents($log_file, $log_content_string . $log_content_old);				

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
		else if($method=="logs")
		{
			// Mark in log success logging in //
			$log_file = "db/logs/logins.txt";
			$log_content_old = file_get_contents($log_file);
			$log_content_string = "<td>$username</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n<td><font color=\"#00ff00\">Successful</font></td>\n</tr><tr>\n\n";
			file_put_contents($log_file, $log_content_string . $log_content_old);				
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
		else if($method=="admin_user")
		{
			// Mark in log success logging in //
			$log_file = "db/logs/logins.txt";
			$log_content_old = file_get_contents($log_file);
			$log_content_string = "<td>$username</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n<td><font color=\"#00ff00\">Successful</font></td>\n</tr><tr>\n\n";
			file_put_contents($log_file, $log_content_string . $log_content_old);
				
			print <<<EOD
			<div class="text"><b><h2>Administration Panel</h2></b>
			<b>Remove User</b><br>
			<form action="admin_panel.php?action=admin_user" method="post">
			Username in which you want to Administrate: <input type="text" name="username"><br>
			<input type="submit" value="Administrate User" name="admin_user" id="admin_user">
			</div>	
EOD;
		}
		else
		{
			print <<<EOD
			<div class="text">Error: Unknown method</div>
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
		if ($_POST['username']!="")
		{
			if (file_exists("db/users/" . $_POST['username'] . ".php"))
			{
				$username = $_POST['username'];
				$pendinguserslist = file_get_contents("db/pendingusers.txt");
				$remove_user = str_replace($_POST['username'] . "<br>", "", $pendinguserslist);
				if (!isset($_POST['valid_action']))
				{
					file_put_contents("db/users/" . $_POST['username'] . ".validation", "invalid");
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
	
	if($action=="admin_user")
	{
		if ($_POST['username']!="")
		{
			if (file_exists("db/users/" . $_POST['username'] . ".php"))
			{
				$username = $_POST['username'];
				file_put_contents("db/users/$username.status", "admin");
				file_put_contents("db/users/$username.logo", "Board Administrator");
				file_put_contents("db/users/$username.color", $admin_color);
				echo "<div class=\"text\">$username set as administrator</div>";
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
}
	

if (!isset($action))
{
print <<<EOD
	<div class="text">
		<h2><b>Administrator Panel</b></h2>
		<a href="admin_panel.php?action=panel&method=rmuser">Remove a User</a><br>
		<a href="admin_panel.php?action=panel&method=puser">Pending Users</a><br>
		<a href="admin_panel.php?action=panel&method=logs">Board Logs</a><br>
		<a href="admin_panel.php?action=panel&method=admin_user">Administrate a User</a><br>
	</div>
EOD;
}
			}
			else
			{
				echo "<div class='text'>Error: You are not an administrator</div>";
			}
		}
		else
		{
			echo "<div class='text'>Error: The password that is set does not seem to match the user you are logged in as.</div>";
		}
	}
	else
	{
		echo "<div class='text'>Error: This user that is set in your browser cache does not exist</div>";
	}
}
else
{
	echo "<div class='text'>Error: You must be logged in</div>";
}

include "themes/$theme/footer.php";

?>
