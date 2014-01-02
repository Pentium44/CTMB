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

//Standard vars for this script //
$date = date("n, j, Y");
$time = date("g:i a");
$date_string = "$date at $time";
$rand = rand(11111, 99999);

// Set user specified theme, else use default
if(isset($_SESSION['ctmb-theme'])){ $theme = $_SESSION['ctmb-theme']; } else { $theme = "default"; }

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
			$catid = $_GET['cid'];
			if (file_exists("db/cat/$catid/post_$id.txt"))
			{
				print <<<EOD
			<div class="text">
			<h2><b>Reply</b></h2>
			<a href="index.php?action=help_bbcode">BBCode Help</a><br>
			<form action="topic.php?action=doreply&id=$id&cid=$catid" method="post">
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
						$catid = $_GET['cid'];
						$getoldcontent = file_get_contents("db/cat/$catid/post_$id.txt");
						$text = htmlentities(stripslashes($_POST["text"]));
						$text2 = nl2br($text);
						include "bb.php";
						$bb = bbcode_format($text2);
						$get_user_color = file_get_contents("db/users/$username.color");
						$get_user_logo = file_get_contents("db/users/$username.rank");
	
						// Write content //
						$loadusercolor = "$('#color_$rand').load('load.php?action=color&name=$username');";
						$loaduserrank = "$('#rank_$rand').load('load.php?action=rank&name=$username');";
						$loadsig = "$('#sig_$rand').load('load.php?action=sig&name=$username');";
						$str1 = "\n<tr><td class='userinfo'><div id='color_$rand'></div>\n";
						$str2 = "<div class='text_small' id='rank_$rand'></div>\n";
						$str3 = "<img style='margin: auto; width: 140px;' src='load.php?action=avatar&name=$username'><br>";
						$str4 = "$date<br />$time</td><td class='userpost'>" . $bb . "\n";
						$str5 = "<div id='sig_$rand' class='sig'></div>\n";
						$str6 = "<script type='text/javascript'>" . $loadusercolor . $loaduserrank . $loadsig . "</script></td></tr>\n";
						$newcontent = $str1 . $str2 . $str3 . $str4 . $str5 . $str6; 
						
						file_put_contents("db/cat/$catid/post_$id.txt", $getoldcontent . $newcontent);
						//Add reply to logs
						$log_posts_string = "<td>$username</td>\n<td>$id</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n</tr><tr>\n\n";
						$log_posts = "db/logs/posts.txt";
						$old_log_content = file_get_contents($log_posts);
						file_put_contents($log_posts, $log_posts_string . $old_log_content);
						
						//add new post to postnumber//
						$postnumber = file_get_contents("db/users/$username.postnumber");
						$postnumber = $postnumber + 1;
						file_put_contents("db/users/$username.postnumber", $postnumber);
						
						$replies = file_get_contents("db/cat/$catid/post_$id.txt_replies");
						$replies = $replies + 1;
						file_put_contents("db/cat/$catid/post_$id.txt_replies", $replies);
						
						//Getting users color//
						$usercolor = file_get_contents("db/users/$username.color");
						file_put_contents("db/cat/$catid/last.txt", "<font color=\"$usercolor\">$username</font>");
						print <<<EOD
						<div class="text">Your reply to topic ID: $id was successful - <a href="view.php?tid=$id&cid=$catid">Topic post</a></div>
EOD;
						//header( "refresh:3;url=view.php?tid=$id&cid=$catid" );
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
		$catid = $_GET['cid'];
		print <<<EOD
	<div class="text">
	<h2><b>Create a New Topic</b></h2>
	<a href="index.php?action=help_bbcode">BBCode Help</a><br>
	<form action="topic.php?action=donewtopic&cid=$catid" method="post">
	Topic Name: <input type="text" name="topic"><br>
EOD;
		//$user_status = file_get_contents("db/users/" . $_SESSION['ctmb-login-user'] . ".status");
		//if($user_status=="admin")
		//{
		//	echo "Sticky: <input type=\"checkbox\" name=\"sticky\" id=\"sticky\"><br>";
		//}

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
			$catid = $_GET['cid'];
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
					$get_user_logo = file_get_contents("db/users/$username.rank");

					$loadusercolor = "$('#color_$rand').load('load.php?action=color&name=$username');";
					$loaduserrank = "$('#rank_$rand').load('load.php?action=rank&name=$username');";
					$loadsig = "$('#sig_$rand').load('load.php?action=sig&name=$username');";
					$str1 = "\n<tr><td class='userinfo'><div id='color_$rand'></div>\n";
					$str2 = "<div class='text_small' id='rank_$rand'></div>\n";
					$str3 = "<img style='margin: auto; width: 140px;' src='load.php?action=avatar&name=$username'><br>";
					$str4 = "$date<br />$time</td><td class='userpost'>" . $bb . "\n";
					$str5 = "<div id='sig_$rand' class='sig'></div>\n";
					$str6 = "<script type='text/javascript'>" . $loadusercolor . $loaduserrank . $loadsig . "</script></td></tr>\n";
					$newcontent = "<h3 style='text-align:center'>$title</h3>" . $str1 . $str2 . $str3 . $str4 . $str5 . $str6; 

					$id = file_get_contents("db/cat/$catid/post.amount");
					$id = $id + 1;
					
					//Add topic creation to logs

					$log_posts_string = "<td>$username</td>\n<td>$topic</td>\n<td>$id</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n</tr><tr>\n\n";
					$log_posts = "db/logs/topics.txt";
					$old_log_content = file_get_contents($log_posts);
					file_put_contents($log_posts, $log_posts_string . $old_log_content);
					
					// Write content to database -- check if sticky //
					if(!isset($_POST['sticky']))
					{
						file_put_contents("db/cat/$catid/post_$id" . ".txt_title", $topic);
						file_put_contents("db/cat/$catid/post_$id.txt", $newcontent);
						file_put_contents("db/cat/$catid/post_$id" . ".txt_by", $username);
						file_put_contents("db/cat/$catid/post_$id" . ".txt_date", $date_string);
						file_put_contents("db/cat/$catid/post_$id" . ".txt_id", $id);
						file_put_contents("db/cat/$catid/post_$id" . ".txt_replies", "1");
					}
			
					//add new post to postnumber//
					$postnumber = file_get_contents("db/users/$username.postnumber");
					$postnumber = $postnumber + 1;
					file_put_contents("db/users/$username.postnumber", $postnumber);
					
					//Getting users color//
					$usercolor = file_get_contents("db/users/$username.color");
					file_put_contents("db/cat/$catid/last.txt", "<font color=\"$usercolor\">$username</font>");
					
					// Update amount of posts in category
					file_put_contents("db/cat/$catid/post.amount", $id);
					
					// Successful!
					print <<<EOD
					<div class="text">The creaation of this topic ($id) was successful - <a href="view.php?tid=$id&cid=$catid">To topic($id)</a></div>
EOD;
					//header( "refresh:3;url=view.php?tid=$id&cid=$catid" );
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
