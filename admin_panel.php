<?php
/*
 * CTMB - Crazy Tiny Message Board - (C) CrazyCoder Productions, 2012-2013
 * CTMB (Crazy Tiny Message Board) is a simple, flatfile database message
 * board that is created by Chris Dorman (CrazyCoder Productions), 2012-2013
 * CTMB is released under the Creative Commons - BY - NC 3.0 NonPorted license
 * 
 * Website : http://sourceforge.net/projects/cutils - Maintained By Chris Dorman
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

include "themes/$theme/header.php";

$date = date("n, j, Y");
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
	if ($action=="panel")
	{
		$method = $_GET['method'];
		if ($method=="rmuser")
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
			// Center Table //
			echo "<div class='text'>";
			echo "<h2><b>Board Logs</b></h2>\n";
			echo "<b>Topic Creations</b><br>\n<table id='tblarge'><tr>";
			echo "<td width='20%' style='border: solid 1px #666666;'>Username</td>\n<td width='20%' style='border: solid 1px #666666;'>Topic Name</td>\n<td width='20%' style='border: solid 1px #666666;'>Topic ID</td>\n<td width='20%' style='border: solid 1px #666666;'>Time & Date</td>\n<td width='20%' style='border: solid 1px #666666;'>External IP</td>\n</tr><tr>\n\n";
			echo $log_topics;
			echo "</tr></table>";
			echo "<b>User Posts</b><br>\n<table id='tblarge'><tr>";
			echo "<td width='25%' style='border: solid 1px #666666;'>Username</td>\n<td width='25%' style='border: solid 1px #666666;'>Topic ID</td>\n<td width='25%' style='border: solid 1px #666666;'>Time & Date</td>\n<td width='25%' style='border: solid 1px #666666;'>External IP</td>\n</tr><tr>\n\n";
			echo $log_posts;
			echo "</tr></table>";
			// Close Center //
			echo "</div>";
				
		}
		else if($method=="admin_user")
		{
			print <<<EOD
			<div class="text"><b><h2>Administration Panel</h2></b>
			<b>Remove User</b><br>
			<form action="admin_panel.php?action=admin_user" method="post">
			Username in which you want to Administrate: <input type="text" name="username"><br>
			<input type="submit" value="Administrate User" name="admin_user" id="admin_user">
			</div>	
EOD;
		}
		else if($method=="newcat")
		{
			print <<<EOD
			<div class="text"><b><h2>Administration Panel</h2></b>
			<b>Add Category</b><br>
			<form action="admin_panel.php?action=addcat" method="post">
			Category Title: <input type="text" name="cat_title"><br>
			Category Description:<br>
			<textarea name="cat_desc" cols="28" rows="8"></textarea><br>
			<input type="submit" value="Add" name="add_cat" id="add_cat">
			</div>	
EOD;
		}
		else if($method=="delcat")
		{
			print <<<EOD
			<div class="text"><b><h2>Administration Panel</h2></b>
			<b>Delete a Category</b><br>
			<form action="admin_panel.php?action=delcat" method="post">
			Category ID: <input type="text" name="cat_id"><br>
			<input type="submit" value="Delete" name="del_cat" id="del_cat">
			</div>	
EOD;
		}
		else if($method=="usercolor")
		{
			print <<<EOD
			<div class="text"><b><h2>Administration Panel</h2></b>
			<b>Change a User's Color</b><br>
			<form action="admin_panel.php?action=usercolor" method="post">
			Username: <input type="text" name="username_for_change"><br>
			Color: <select name="usercolor">
					<option value="#ff0000" name="#ff0000">Red</option>
					<option value="orange" name="orange">Orange</option>
					<option value="yellow" name="yellow">Yellow</option>
					<option value="#00ff00" name="#00ff00">Green</option>
					<option value="#0000ff" name="#0000ff">Blue</option>
					<option value="#00ffff" name="#00ffff">Cyan</option>
					<option value="#9900cc" name="#9900cc">Purple</option>
					<option value="#ff00ff" name="#ff00ff">Magenta</option>
					<option value="silver" name="silver">Silver</option>
					<option value="#fefefe" name="#fefefe">White</option>
				   </select><br>
			<input type="submit" value="Change" name="change" id="change">
			</div>	
EOD;
		}
		else if($method=="userrank")
		{
			print <<<EOD
			<div class="text"><b><h2>Administration Panel</h2></b>
			<b>Change a User's Color</b><br>
			<form action="admin_panel.php?action=userrank" method="post">
			Username: <input type="text" name="username_for_change"><br>
			User's Rank: <input type="text" name="userrank"><br>
			<input type="submit" value="Change" name="change" id="change">
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
			if(file_exists("db/users/" . $_POST['username'] . ".php")) { unlink('db/users/' . $_POST['username'] . '.php'); }
			if(file_exists("db/users/" . $_POST['username'] . ".color")) { unlink('db/users/' . $_POST['username'] . '.color'); }
			if(file_exists("db/users/" . $_POST['username'] . ".status")) { unlink('db/users/' . $_POST['username'] . '.status'); }
			if(file_exists("db/users/" . $_POST['username'] . ".logo")) { unlink('db/users/' . $_POST['username'] . '.rank'); }
			if(file_exists("db/users/" . $_POST['username'] . ".validation")) { unlink('db/users/' . $_POST['username'] . '.validation'); }
			if(file_exists("db/users/avatars/" . $_POST['username'] . ".*")) { unlink('db/users/avatars/' . $_POST['username'] . '.*'); }			
			if(file_exists("db/users/" . $_POST['username'] . ".postnumber")) { unlink('db/users/' . $_POST['username'] . '.postnumber'); }
			$userlist = "db/userlist.txt";
			$userlist_data = file_get_contents($userlist);
			$remove_user_from_list = str_replace("<a href=\"user.php?action=userpanel&user=$user_to_replace\">$user_to_replace</a><br>", "", $userlist_data);
			file_put_contents($userlist, $remove_user_from_list);
			echo "<div class=\"text\">User removed: <a href='admin_panel.php'>Back to panel</a></div>";
			//header( "refresh:2;url=admin_panel.php" );
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
					echo "<div class=\"text\">$username declined: <a href='admin_panel.php'>Back to panel</a></div>";
					//header( "refresh:3;url=admin_panel.php" );
				}
				if (isset($_POST['valid_action']))
				{
					file_put_contents("db/users/" . $_POST['username'] . ".validation", "valid");
					file_put_contents("db/pendingusers.txt", $remove_user);
					echo "<div class=\"text\">$username validated: <a href='admin_panel.php'>Back to panel</a></div>";
					//header( "refresh:2;url=admin_panel.php" );
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
				echo "<div class=\"text\">$username is now an administrator: <a href='admin_panel.php'>Back to panel</a></div>";
				//header( "refresh:2;url=admin_panel.php" );
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
	
	if($action=="addcat")
	{
		if(isset($_POST['add_cat']) && $_POST['cat_title']!="" && $_POST['cat_desc']!="")
		{
			$cat_title = $_POST['cat_title'];
			$cat_desc = $_POST['cat_desc'];
			// Mrtux code from here to where it says stop! - from TuXBoard //
			$id = 0;
			foreach (glob("db/cat/" . "*") as $unusedvar)
			{
				$id = $id + 1;
			}
			$id = $id + 1;
			// STOP!! Chris's Code starting!!! //
			
			mkdir("db/cat/$id", 0777);
			file_put_contents("db/cat/$id/title.txt", $cat_title);
			file_put_contents("db/cat/$id/date.txt", $date_string);
			file_put_contents("db/cat/$id/desc.txt", $cat_desc);
			file_put_contents("db/cat/$id/catid.txt", $id);
			file_put_contents("db/cat/$id/last.txt", "Nobody");
			echo "<div class=\"text\">Category Created: <a href='admin_panel.php'>Back to panel</a></div>";
			//header( "refresh:2;url=admin_panel.php" );
		}
		else
		{
			print <<<EOD
			<div class="text">Error: Missing category title, or description.</div>
EOD;
		}
	}
	
	if($action=="delcat")
	{
		if(isset($_POST['cat_id']))
		{
			$cat_id = $_POST['cat_id'];
			if(is_dir("db/cat/$cat_id"))
			{
				foreach(glob("db/cat/$cat_id/" . "*") as $file_to_remove)
				{
					unlink($file_to_remove);
				}
				rmdir("db/cat/$cat_id");
				echo "<div class=\"text\">Category Removed: <a href='admin_panel.php'>Back to panel</a></div>";
				//header( "refresh:2;url=admin_panel.php" );
			}
			else
			{
				echo "<div class=\"text\">Error: Category not found!</div>";
			}
		}
		else
		{
			echo "<div class=\"text\">Error: Category ID not specified!</div>";
		}
	}
	
	if($action=="usercolor")
	{
		if($_POST['usercolor']!="" && $_POST['username_for_change']!="")
		{
			$usercolor = $_POST['usercolor'];
			$username_for_change = $_POST['username_for_change'];
			if(file_exists("db/users/$username_for_change.color"))
			{
				file_put_contents("db/users/$username_for_change.color", $usercolor);
				echo "<div class=\"text\">User's color changed: <a href='admin_panel.php'>Back to panel</a></div>";
				//header( "refresh:2;url=admin_panel.php" );
			}
			else
			{
				echo "<div class=\"text\">Error: User does not exist.</div>";
			}
		}
		else
		{
			echo "<div class=\"text\">Error: Please specify a color, <b>and</b> username</div>";
		}
	}
	
	if($action=="userrank")
	{
		if($_POST['userrank']!="" && $_POST['username_for_change']!="")
		{
			$userlogo = $_POST['userrank'];
			$username_for_change = $_POST['username_for_change'];
			if(file_exists("db/users/$username_for_change.rank"))
			{
				file_put_contents("db/users/$username_for_change.rank", $userlogo);
				echo "<div class=\"text\">User's rank changed: <a href='admin_panel.php'>Back to panel</a></div>";
				//header( "refresh:2;url=admin_panel.php" );
			}
			else
			{
				echo "<div class=\"text\">Error: User does not exist.</div>";
			}
		}
		else
		{
			echo "<div class=\"text\">Error: Please specify a new rank, <b>and</b> username</div>";
		}
	}
	if($action=="clearlogs")
	{
		file_put_contents("db/logs/topics.txt", "");
		file_put_contents("db/logs/posts.txt", "");
		file_put_contents("db/logs/logins.txt", "");
		echo "<div class=\"text\">Logs Cleared - Redirecting in 2 seconds.</div>";
		header( "refresh:2;url=admin_panel.php" );
	}
	if($action=="delpost")
	{
		if(isset($_GET['cid']) && isset($_GET['tid']))
		{
			$catid = $_GET['cid'];
			$topicid = $_GET['tid'];
			if(file_exists("db/cat/$catid/post_$topicid.txt"))
			{
				unlink("db/cat/$catid/post_$topicid.txt");
				unlink("db/cat/$catid/post_$topicid.txt_title");
				unlink("db/cat/$catid/post_$topicid.txt_date");
				unlink("db/cat/$catid/post_$topicid.txt_by");
				unlink("db/cat/$catid/post_$topicid.txt_replies");
				unlink("db/cat/$catid/post_$topicid.txt_id");
				echo "<div class='text'>Success: Post removed: <a href='admin_panel.php'>Back to panel</a></div>";
				//header( "refresh:2;url=index.php" );
			}
			else
			{
				echo "<div class='text'>Error, Post not found!</div>\n";
			}
		}
		else
		{
			echo "<div class='text'>Error, category ID and post ID not set!</div>\n";
		}
	}
}
	

if (!isset($action))
{
print <<<EOD
	<div class="text">
		<h2><b>Administrator Panel</b></h2>
		<b>Managing Users</b><br>
		<a href="admin_panel.php?action=panel&method=rmuser">Remove a User</a><br>
		<a href="admin_panel.php?action=panel&method=puser">Pending Users</a><br>
		<a href="admin_panel.php?action=panel&method=usercolor">Change User Color</a><br>
		<a href="admin_panel.php?action=panel&method=userrank">Change User Rank</a><br>
		<a href="admin_panel.php?action=panel&method=admin_user">Administrate a User</a><br>
		<br><b>Managing the Board</b><br>
		<a href="admin_panel.php?action=panel&method=logs">Board Logs</a><br>
		<a href="admin_panel.php?action=clearlogs">Clear Board Logs</a><br>
		<a href="admin_panel.php?action=panel&method=newcat">Add a Category</a><br>
		<a href="admin_panel.php?action=panel&method=delcat">Delete a Category</a><br>
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