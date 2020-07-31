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
	include "config.php";
}

//Standard vars for this script //
$date = date("n, j, Y");
$time = date("g:i a");
$date_string = "$date at $time";
$rand = rand(11111, 99999);

// Set user specified theme, else use default
if(isset($_SESSION['ctmb-theme'])){ $theme = $_SESSION['ctmb-theme']; } else { $theme = "default"; }

// The forum was viewed, record it
$forum_views = file_get_contents("db/forum.views");
$forum_views = $forum_views + 1;
file_put_contents("db/forum.views", $forum_views);

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
			if (file_exists("db/cat/$catid/$id/title"))
			{
				print <<<EOD
			<div class="text">
			<h2><b>Reply</b></h2>
			<a href="index.php?action=help_bbcode">BBCode Help</a><br>
			<form action="topic.php?action=doreply&id=$id&cid=$catid" method="post" enctype="multipart/form-data">
			<textarea name="text" cols="35" rows="8">Post Body</textarea><br>
			Attach image (Max 1MB) : <input type="file" id="file" name="file" /><br />
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
				// Some variables
				$username = $_SESSION['ctmb-login-user'];
				$id = $_GET['id'];
				$catid = $_GET['cid'];
				$text = htmlentities(stripslashes($_POST["text"]));
				
				// Update number of replies		
				$replies = file_get_contents("db/cat/$catid/$id/replies");
				$replies = $replies + 1;
				file_put_contents("db/cat/$catid/$id/replies", $replies);		
				
				// Add attachment - do first to make sure everything goes smoothly
				$rand_name = substr(md5(microtime()),rand(0,26),5);
				$allowedExts = array("gif", "jpeg", "jpg", "png");
				$temp = explode(".", $_FILES["file"]["name"]);
				$extension = end($temp);
				if ((($_FILES["file"]["type"] == "image/gif")
				|| ($_FILES["file"]["type"] == "image/x-gif")
				|| ($_FILES["file"]["type"] == "image/jpeg")
				|| ($_FILES["file"]["type"] == "image/x-jpeg")
				|| ($_FILES["file"]["type"] == "image/x-jpg")
				|| ($_FILES["file"]["type"] == "image/jpg")
				|| ($_FILES["file"]["type"] == "image/pjpeg")
				|| ($_FILES["file"]["type"] == "image/x-png")
				|| ($_FILES["file"]["type"] == "image/png"))
				&& ($_FILES["file"]["size"] < 1000000)
				&& in_array($extension, $allowedExts))
				{
					if ($_FILES["file"]["error"] > 0 || $_FILES["file"]["size"] == 0)
					{
						echo "<!-- file error -->\n\n";
					}	
					else
					{
						move_uploaded_file($_FILES["file"]["tmp_name"],	"db/attachment/" . $_FILES["file"]["name"]);
						if(file_exists("db/attachment/" . $_FILES["file"]["name"]))
						{
							// Randomize attachment name
							rename("db/attachment/" . $_FILES["file"]["name"], "db/attachment/$rand_name.$extension");
							// add attachment to post
							file_put_contents("db/cat/$catid/$id/$replies.txt_a", "db/attachment/$rand_name.$extension");
						}
					}	
				}
				else
				{
					echo "<!-- file not found -->\n\n";
				}	
				
				/*
					Do some work with database
				*/
				
				// Write post
				file_put_contents("db/cat/$catid/$id/$replies.txt", $text);
				// Write post owner
				file_put_contents("db/cat/$catid/$id/$replies.txt_u", $username);
				// Write post date
				file_put_contents("db/cat/$catid/$id/$replies.txt_d", "$time<br />$date\n");
				
				//Add reply to logs
				$log_posts_string = "<td>$username</td>\n<td>$id</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n</tr><tr>\n\n";
				$log_posts = "db/logs/posts.txt";
				$old_log_content = file_get_contents($log_posts);
				file_put_contents($log_posts, $log_posts_string . $old_log_content);
						
				//add new post to postnumber//
				$postnumber = file_get_contents("db/users/$username.postnumber");
				$postnumber = $postnumber + 1;
				file_put_contents("db/users/$username.postnumber", $postnumber);
						
				//Getting users color//
				$usercolor = file_get_contents("db/users/$username.color");
				file_put_contents("db/cat/$catid/last.txt", "<font color=\"$usercolor\">$username</font>");
				print <<<EOD
			<div class="text">Your reply to topic ID: $id was successful - <a href="view.php?tid=$id&cid=$catid">Topic post</a></div>
EOD;
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
	<form action="topic.php?action=donewtopic&cid=$catid" method="post" enctype="multipart/form-data">
	Topic Name: <input type="text" name="topic" /><br />
EOD;
		//$user_status = file_get_contents("db/users/" . $_SESSION['ctmb-login-user'] . ".status");
		//if($user_status=="admin")
		//{
		//	echo "Sticky: <input type=\"checkbox\" name=\"sticky\" id=\"sticky\"><br>";
		//}

		print <<<EOD
	<textarea name="text" cols="35" rows="8">Post Body</textarea><br />
	Attach image (Max 1MB) : <input type="file" id="file" name="file" /><br />
	<input type="submit" value="Submit" />
	</form>
	</div>
EOD;
	}
	if ($action=="donewtopic")
	{
		if (file_exists("db/users/" . $_SESSION['ctmb-login-user'] . ".php"))
		{
			// Some variables
			$username = $_SESSION['ctmb-login-user'];
			$replies = "0"; // I have no idea why im using this variable
			$catid = $_GET['cid'];
			$id = file_get_contents("db/cat/$catid/post.amount");
			$id = $id + 1;
			$text = htmlentities(stripslashes($_POST["text"]));
			$topic = htmlentities(stripcslashes($_POST['topic']));
			
			// Make post directory
			file_put_contents("db/cat/$catid/$id.post", "$id");
			mkdir("db/cat/$catid/$id", 0777);	
			
			// Add attachment - do first to make sure everything goes smoothly
			$rand_name = substr(md5(microtime()),rand(0,26),5);
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			$temp = explode(".", $_FILES["file"]["name"]);
			$extension = end($temp);
			if ((($_FILES["file"]["type"] == "image/gif")
			|| ($_FILES["file"]["type"] == "image/x-gif")
			|| ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/x-jpeg")
			|| ($_FILES["file"]["type"] == "image/x-jpg")
			|| ($_FILES["file"]["type"] == "image/jpg")
			|| ($_FILES["file"]["type"] == "image/pjpeg")
			|| ($_FILES["file"]["type"] == "image/x-png")
			|| ($_FILES["file"]["type"] == "image/png"))
			&& ($_FILES["file"]["size"] < 1000000)
			&& in_array($extension, $allowedExts))
			{
				if ($_FILES["file"]["error"] > 0 || $_FILES["file"]["size"] == 0)
				{
					echo "<!-- file error -->\n\n";
				}
				else
				{
					move_uploaded_file($_FILES["file"]["tmp_name"],	"db/attachment/" . $_FILES["file"]["name"]);
					if(file_exists("db/attachment/" . $_FILES["file"]["name"]))
					{
						// Randomize attachment name
						rename("db/attachment/" . $_FILES["file"]["name"], "db/attachment/$rand_name.$extension");
						// add attachment to post
						file_put_contents("db/cat/$catid/$id/$replies.txt_a", "db/attachment/$rand_name.$extension");
					}
				}	
			}
			else
			{
				echo "<!-- file not found -->\n\n";
			}
			
			/*
				Do some work with database
			*/
			
			// Write post title
			file_put_contents("db/cat/$catid/$id/title", $topic);
			// Write post date
			file_put_contents("db/cat/$catid/$id/date", $date_string);
			// Write post
			file_put_contents("db/cat/$catid/$id/$replies.txt", $text);
			// Write post owner
			file_put_contents("db/cat/$catid/$id/$replies.txt_u", $username);
			file_put_contents("db/cat/$catid/$id/owner", $username);
			// Write post date
			file_put_contents("db/cat/$catid/$id/$replies.txt_d", "$time<br />$date\n");
			// Write post views
			file_put_contents("db/cat/$catid/$id/views", "1");
			// Write post date
			file_put_contents("db/cat/$catid/$id/replies", "0");
			
			//Add reply to logs
			$log_posts_string = "<td>$username</td>\n<td>$id</td>\n<td>$date_string</td>\n<td>" . $_SERVER['REMOTE_ADDR'] . "</td>\n</tr><tr>\n\n";
			$log_posts = "db/logs/posts.txt";
			$old_log_content = file_get_contents($log_posts);
			file_put_contents($log_posts, $log_posts_string . $old_log_content);
					
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
			<div class="text">The creaation of this topic ($id) was successful - <a href="view.php?tid=$id&cid=$catid">To topic (ID: $id)</a></div>
EOD;
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
