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

if(isset($_SESSION['ctmb-login-user']) && isset($_SESSION['ctmb-login-pass']))
{
	if(file_exists("db/users/" . $_SESSION['ctmb-login-user'] . ".php"))
	{
		include "db/users/" . $_SESSION['ctmb-login-user'] . ".php";
		if($_SESSION['ctmb-login-pass']==$userpass)
		{
$action = $_GET['action'];
if (isset($action))
{	
	if ($action=="reply")
	{
		if (isset($_GET['id']))
		{
			$id = $_GET['id'];
			if (file_exists("db/posts/$id.txt"))
			{
				print <<<EOD
			<div class="text">
			<h2><b>Reply</b></h2>
			<a href="index.php?action=help_bbcode">BBCode Help</a><br>
			<form action="topic.php?action=doreply&id=$id" method="post">
			<textarea name="text" cols="35" rows="8">Post Body</textarea><br>
			<input type="submit" value="Submit">
			</div>
EOD;
			}
			else
			{
				print <<<EOD
			<div class="text">Error: Topic Not Found.</div>
EOD;
			}
		}
	}
	if ($action=="doreply")
	{
		if (isset($_GET['id']))
		{
			if (file_exists("db/users/" . $_SESSION['ctmb-login-user'] . ".php"))
			{
				$username = $_SESSION['ctmb-login-user'];
				$validation = file_get_contents("db/users/" . $username . ".validation");
				if (file_exists("db/users/" . $username . ".validation"))
				{
					if ($validation=="valid")
					{
						$id = $_GET['id'];
						$getoldcontent = file_get_contents("db/posts/$id.txt");
						$text = htmlentities(stripslashes($_POST["text"]));
						$text2 = nl2br($text);
						include "bb.php";
						$bb = bbcode_format($text2);
						$get_user_color = file_get_contents("db/users/$username.color");
						$get_user_logo = file_get_contents("db/users/$username.logo");
						if ($show_ips=="true")
						{
							if (file_exists("db/avatars/$username.txt"))
							{
								$user_avatar = file_get_contents("db/avatars/$username.txt");
								$newcontent = "\n<tr><td class='userinfo'><b><font color='" . $get_user_color . "'>" . $username . "</font></b>\n<br><div class='text_small'>" . $get_user_logo . "</div>\n<img style='margin: auto; width: 140px;' src='db/avatars/$user_avatar'><br>" . $_SERVER['REMOTE_ADDR'] . "</td><td class='userpost'>" . $bb . "</td></tr>"; 								
							}
							else
							{
								$newcontent = "\n<tr><td class='userinfo'><b><font color='" . $get_user_color . "'>" . $username . "</font></b>\n<br><div class='text_small'>" . $get_user_logo . "</div>\n<img style='margin: auto; width: 140px;' src='db/avatars/default.jpg'><br>" . $_SERVER['REMOTE_ADDR'] . "</td><td class='userpost'>" . $bb . "</td></tr>"; 	
							}
						}
						else
						{
							if (file_exists("db/avatars/$username.txt"))
							{
								$user_avatar = file_get_contents("db/avatars/$username.txt");
								$newcontent = "\n<tr><td class='userinfo'><b><font color='" . $get_user_color . "'>" . $username . "</font></b><br>\n<div class='text_small'>" . $get_user_logo . "</div>\n<img style='margin: auto; width: 140px;' src='db/avatars/$user_avatar'></td><td class='userpost'>" . $bb . "</td></tr>"; 								
							}
							else
							{
								$newcontent = "\n<tr><td class='userinfo'><b><font color='" . $get_user_color . "'>" . $username . "</font></b><br>\n<div class='text_small'>" . $get_user_logo . "</div>\n<img style='margin: auto; width: 140px;' src='db/avatars/default.jpg'></td><td class='userpost'>" . $bb . "</td></tr>"; 	
							}
						}
						file_put_contents("db/posts/$id.txt", $getoldcontent . $newcontent);
						//Add reply to logs
						
						$date = date("F j, Y");
						$time = date("g:i a");
						$date_string = "$date at $time";
						$log_posts_string = "<td>$username</td>\n<td>$id</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n</tr><tr>\n\n";
						$log_posts = "db/logs/posts.txt";
						$old_log_content = file_get_contents($log_posts);
						file_put_contents($log_posts, $log_posts_string . $old_log_content);
						print <<<EOD
						<div class="text">Your reply to topic id $id was successful. redirecting in 3 seconds. If redirect fails, <a href="view.php?tid=$id">Click Here</a></div>
EOD;
						header( "refresh:3;url=view.php?tid=$id" );
					}
					else
					{
						print <<<EOD
						<div class="text">Error: Your account has not been validated, or you have been declined by the board administrator, in which you cannot reply or post.</div>
EOD;
					}
				}
				else
				{
					print <<<EOD
					<div class="text">Error: Your account has not been validated, or you have been declined by the board administrator, in which you cannot reply or post.</div>
EOD;
				}
			}
			else
			{
				print <<<EOD
				<div class="text">Error: User Not Found</div>
EOD;
			}
		}
	}
	
	if ($action=="newtopic")
	{
		print <<<EOD
	<div class="text">
	<h2><b>Create a New Topic</b></h2>
	<a href="index.php?action=help_bbcode">BBCode Help</a><br>
	<form action="topic.php?action=donewtopic" method="post">
	Topic Name: <input type="text" name="topic"><br>
EOD;
		$user_status = file_get_contents("db/users/" . $_SESSION['ctmb-login-user'] . ".status");
		if($user_status=="admin")
		{
			echo "Sticky: <input type=\"checkbox\" name=\"sticky\" id=\"sticky\"><br>";
		}

		print <<<EOD
	<textarea name="text" cols="35" rows="8">Post Body</textarea><br>
	<input type="submit" value="Submit">
	</div>
EOD;
	}
	if ($action=="donewtopic")
	{
		if (file_exists("db/users/" . $_SESSION['ctmb-login-user'] . ".php"))
		{
			$username = $_SESSION['ctmb-login-user'];
			$topic = $_POST['topic'];
			$validation = file_get_contents("db/users/" . $username . ".validation");
			if (file_exists("db/users/" . $username . ".validation"))
			{
				if ($validation=="valid")
				{
					$text = htmlentities(stripslashes($_POST["text"]));
					$text2 = nl2br($text);
					include "bb.php";
					$bb = bbcode_format($text2);
					$get_user_color = file_get_contents("db/users/$username.color");
					$get_user_logo = file_get_contents("db/users/$username.logo");
					if ($show_ips=="true")
					{
						if (file_exists("db/avatars/$username.txt"))
						{
							$user_avatar = file_get_contents("db/avatars/$username.txt");
							$newcontent = "\n<center><h3>$topic</h3></center><tr><td class='userinfo'><b><font color='" . $get_user_color . "'>" . $username . "</font></b><br>\n<div class='text_small'>" . $get_user_logo . "</div>\n<img style='margin: auto; width: 140px;' src='db/avatars/$user_avatar'><br>" . $_SERVER['REMOTE_ADDR'] . "</td><td class='userpost'>" . $bb . "</td></tr>\n"; 								
						}
						else
						{
							$newcontent = "\n<center><h3>$topic</h3></center><tr><td class='userinfo'><b><font color='" . $get_user_color . "'>" . $username . "</font></b><br>\n<div class='text_small'>" . $get_user_logo . "</div>\n<img style='margin: auto; width: 140px;' src='db/avatars/default.jpg'><br>" . $_SERVER['REMOTE_ADDR'] . "</td><td class='userpost'>" . $bb . "</td></tr>\n"; 	
						}
					}
					else
					{
						if (file_exists("db/avatars/$username.txt"))
						{
							$user_avatar = file_get_contents("db/avatars/$username.txt");
							$newcontent = "\n<center><h3>$topic</h3></center><tr><td class='userinfo'><b><font color='" . $get_user_color . "'>" . $username . "</font></b><br>\n<div class='text_small'>" . $get_user_logo . "</div>\n<img style='margin: auto; width: 140px;' src='db/avatars/$user_avatar'></td><td class='userpost'>" . $bb . "</td></tr>\n"; 								
						}
						else
						{
							$newcontent = "\n<center><h3>$topic</h3></center><tr><td class='userinfo'><b><font color='" . $get_user_color . "'>" . $username . "</font></b><br>\n<div class='text_small'>" . $get_user_logo . "</div>\n<img style='margin: auto; width: 140px;' src='db/avatars/default.jpg'></td><td class='userpost'>" . $bb . "</td></tr>\n"; 	
						}
					}
					$randomid = rand(1,99999);
					$date = date("n, j, Y");
					$date2 = date("F j, Y");
					$time = date("g:i a");
					//Add topic creation to logs
					
					$date_string = "$date2 at $time";
					$log_posts_string = "<td>$username</td>\n<td>$topic</td>\n<td>$randomid</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n</tr><tr>\n\n";
					$log_posts = "db/logs/topics.txt";
					$old_log_content = file_get_contents($log_posts);
					file_put_contents($log_posts, $log_posts_string . $old_log_content);
					
					file_put_contents("db/posts/$randomid.txt", $newcontent);
					$list = "<li><b><a href=\"view.php?tid=$randomid\">$topic</a></b><div class=\"date_float\">Posted by: $username | Posted : $date at $time</div><br></li>\n";
					$list .= file_get_contents('db/list.txt');
					$list_sticky = "<li><b><img style=\"float:left;\" src=\"data/sticky.png\"><a href=\"view.php?tid=$randomid\">$topic</a></b><div class=\"date_float\">Posted by: $username | Posted : $date at $time</div><br></li>\n";
					$list_sticky .= file_get_contents('db/st_list.txt');
					if(!isset($_POST['sticky']))
					{
						file_put_contents("db/list.txt", $list);
					}
					else
					{
						file_put_contents("db/st_list.txt", $list_sticky);
					}
					print <<<EOD
					<div class="text">Topic $id post was successful. redirecting in 3 seconds. If redirect fails, <a href="view.php?tid=$randomid">Click Here</a></div>
EOD;
					header( "refresh:3;url=view.php?tid=$randomid" );
				}
				else
				{
					print <<<EOD
					<div class="text">Error: Your account has not been validated, or you have been declined by the board administrator, in which you cannot reply or post.</div>
EOD;
				}
			}
			else
			{
				print <<<EOD
				<div class="text">Error: Your account has not been validated, or you have been declined by the board administrator, in which you cannot reply or post.</div>
EOD;
			}
		}
		else
		{
			print <<<EOD
			<div class="text">Error: User Not Found</div>
EOD;
		}	
	}
}
else
{
	echo "<div class='text'>Error: Action not set</div>";
}

// Code for session check, not topic //
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
	echo "<div class='text'>Error: You must be logged in to make posts or reply to topics</div>";
}

include "themes/$theme/footer.php";

?>
