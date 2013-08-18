<?php
// Start session //
//session_start();

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

include "themes/$theme/header.php";

if (isset($_GET['action']))
{
	if ($_GET['action']=="userlist")
	{
		$userlist = file_get_contents("db/userlist.txt");
		echo "<div class=\"text\"><ul>";
		echo $userlist;
		echo "</ul></div>";

	}
	
	if ($_GET['action']=="help_bbcode")
	{
		echo "<div class='text'>";
		print <<<EOD
		<center><h2><b>Help: Using BBCode</b></h2></center>
		BBCode is a link to HTML used on wikis, blogs, boards, and forums. Here is a list of BBCode tags available within CTMB.
		<ul>
		<li>[b]Text[b] : Bold Text</li>
		<li>[i]Text[/i] : Italic Text</li>
		<li>[p]Paragraph[/p] : Paragraph</li>
		<li>[color=red]Text[/color] : Text Color</li>
		<li>[url]http://example.com/[/url] OR [url=http://example.com]Link Name[/url] : Links</li>
		<li>[img]http://example.com/image.png[/img] : Images</li>
		<li>[mail]user@email.com[/mail] OR [mail=user@email.com]MY Email[/mail] : Linking email</li>
		<li>[code]Some Code[/code] : Code</li>
		<li>[audio]http://example.com/example.mp3[/audio] : Embed audio player (MP3 only)</li>
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
			header("Location: index.php");
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
					header("Location: index.php");
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
print <<<EOD
<div class="text">
EOD;
	$sticky_postlist = file_get_contents("db/st_list.txt");
	$postlist = file_get_contents("db/list.txt");
	echo $sticky_postlist;
	echo $postlist;
	print <<<EOD
	<br><hr><a href="topic.php?action=newtopic">New Topic</a></div>
EOD;
}

include "themes/$theme/footer.php";

?>
