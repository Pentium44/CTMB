<?php

/*
 * CTMB - Crazy Tiny Message Board - (C) CrazyCoder Productions, 2012-2013
 * CTMB (Crazy Tiny Message Board) is a simple, flatfile database message
 * board that is created by Chris Dorman (CrazyCoder Productions), 2012-2013
 * CTMB is released under the Creative Commons - BY - NC 3.0 NonPorted license
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
	include "config.php";
}

include "themes/$theme/header.php";

if (isset($_GET['action']))
{
	if ($_GET['action']=="userlist")
	{
		$userlist = file_get_contents("db/userlist.txt");
		echo "<div class=\"text\"><h2>Board Users</h2>";
		echo $userlist;
		echo "</div>";

	}
	
	if ($_GET['action']=="help_bbcode")
	{
		echo "<div class='text'>";
		print <<<EOD
		<center><h2><b>Help: Using BBCode</b></h2></center>
		BBCode is a link to HTML used on blogs, and forums. Here is a list of BBCode tags available within CTMB.
		<ul>
		<li>[b]Text[b] : Bold Text</li>
		<li>[i]Text[/i] : Italic Text</li>
		<li>[p]Paragraph[/p] : Paragraph</li>
		<li>[color=red]Text[/color] : Text Color</li>
		<li>[url]http://example.com/[/url] OR [url=http://example.com]Link Name[/url] : Links</li>
		<li>[img]http://example.com/image.png[/img] : Images</li>
		<li>[mail]user@email.com[/mail] OR [mail=user@email.com]MY Email[/mail] : Linking email</li>
		<li>[code]Some Code[/code] : Code</li>
		<li>[spoiler]Content for spoiler[/spoiler] : Spoilers</li>
		</ul>
		
EOD;
		echo "</div>";
	}
	
	if($_GET['action']=="login")
	{
		print <<<EOD
		<div class="text">
		<form action="index.php?action=dologin" method="post">
		Username: <input type="text" name="username" id="username"><br>
		Password: <input type="password" name="password" id="password"><br>
		<input type="submit" name="login" value="Login">
		</form>
		</div>
EOD;
	}
	
	if($_GET['action']=="logout")
	{
		if(isset($_SESSION['ctmb-login-user']) && isset($_SESSION['ctmb-login-pass']))
		{
			$_SESSION['ctmb-login-user'] = null;
			$_SESSION['ctmb-login-pass'] = null;
			echo "<div class='text'>Logged out - <a href='index.php'>Back to index</a></div>\n";
			//header("Location: index.php");
		}
		else
		{
			echo "<div class='text'>Error: You are not logged in as a user</div>";
		}
	}
	
	if($_GET['action']=="dologin")
	{
		if($_POST['username']!="" && $_POST['password']!="" && isset($_POST['login']))
		{
			if(file_exists("db/users/" . $_POST['username'] . ".php"))
			{
				include "db/users/" . $_POST['username'] . ".php";
				if($_POST['password']==$userpass)
				{
					$_SESSION['ctmb-login-user'] = $_POST['username'];
					$_SESSION['ctmb-login-pass'] = $_POST['password'];
					
					// Mark in log success logging in //
					$date = date("F j, Y");
					$time = date("g:i a");
					$date_string = "$date at $time";
					$username = $_POST['username'];
					$log_file = "db/logs/logins.txt";
					$log_content_old = file_get_contents($log_file);
					$log_content_string = "<td>$username</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n<td><font color=\"#00ff00\">Successful</font></td>\n</tr><tr>\n\n";
					file_put_contents($log_file, $log_content_string . $log_content_old);	
					echo "<div class='text'>Logged in - <a href='index.php'>Back to index</a></div>\n";
					//header("Location: index.php");
				}
				else 
				{ 
					echo "<div class='text'>Error: Wrong password</div>"; 
				}
			}
			else
			{
				echo "<div class='text'>Error: User does not exist</div>";
			}
		}
		else
		{
			echo "<div class='text'>Error: login form is not completely filled out</div>";
		}
	}
}

/* Show Forum topics */
if (!isset($_GET['action']))
{
	echo "<div class=\"text\">\n";
	echo "<table id='tblarge'>\n";
		echo "<tr>\n";
		echo "<td id='ctitle_t'>Title / Description</td>\n";
		echo "<td id='cpost_t'>Last Post By</td>\n";
		echo "<td id='cdate_t'>Date</td>\n";
		echo "</tr>\n";
	foreach(glob("db/cat/" . "*") as $categories)
	{
		$cat_title = file_get_contents("$categories/title.txt");
		$cat_date = file_get_contents("$categories/date.txt");
		$cat_desc = file_get_contents("$categories/desc.txt");
		$cat_id = file_get_contents("$categories/catid.txt");
		$cat_lastpost = file_get_contents("$categories/last.txt");
		echo "<tr>\n";
		echo "<td id='ctitle'><a href=\"view.php?cid=$cat_id\" id='title_text'>$cat_title</a><br />$cat_desc</td>\n";
		echo "<td id='cpost'>$cat_lastpost</td>\n";
		echo "<td id='cdate'>$cat_date</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
	echo "</div>\n";
}

include "themes/$theme/footer.php";

?>