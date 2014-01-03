<?php
session_start();
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

// Set user specified theme, else use default
if(isset($_SESSION['ctmb-theme'])){ $theme = $_SESSION['ctmb-theme']; } else { $theme = "default"; }

include "themes/$theme/header.php";

if (isset($_GET['tid']) && isset($_GET['cid']))
{
	$tid = $_GET['tid'];
	$catid = $_GET['cid'];
	if (file_exists("db/cat/$catid/post_$tid.txt"))
	{
		print <<<EOD
		<script src='data/jquery-1.9.1.js' type='text/javascript'></script>
		<script src='data/spoiler.js' type='text/javascript'></script>
		<div class="text">
EOD;
		$views = file_get_contents("db/cat/$catid/post_$tid.txt_views");
		$views = $views + 1;
		file_put_contents("db/cat/$catid/post_$tid.txt_views", $views);
		
		echo "<table id='tblarge'>\n";
		$file_content = file_get_contents("db/cat/$catid/post_$tid.txt");
		echo $file_content;
		
		print <<<EOD
		</table><br />
		<a href="topic.php?action=reply&id=$tid&cid=$catid">Reply</a> | 
EOD;
		if(isset($_SESSION['ctmb-login-user']))
		{
			$user_status = file_get_contents("db/users/" . $_SESSION['ctmb-login-user'] . ".status");
			if($user_status=="admin")
			{
				echo "<a href='admin_panel.php?action=delpost&cid=$catid&tid=$tid'>Delete this Post</a></div>\n";
			}
			else
			{
				echo "</div>\n";
			}
		}
	}
	else
	{
		print <<<EOD
		<div class="text">Error: Topic Not Found. This topic could have been deleted!</div>
EOD;
	}
}
else if(isset($_GET['cid']))
{
	$catid = $_GET['cid'];
	echo "<div class=\"text\">\n";
	echo "<table id='tblarge'>\n";
		echo "<tr>\n";
		echo "<td id='ptitle_t'>Post Title</td>\n";
		echo "<td id='preplies_t'>Replies</td>\n";
		//echo "</tr><tr>\n";
		echo "<td id='pviews_t'>Views</td>\n";
		echo "<td id='powner_t'>Post Owner</td>\n";
		echo "<td id='pdate_t'>Date</td>\n";
		echo "</tr>\n";
	foreach(array_reverse(glob("db/cat/$catid/post_" . "*" . ".txt" . "")) as $post)
	{
		$post_title = file_get_contents("$post" . "_title");
		$post_date = file_get_contents("$post" . "_date");
		$post_by = file_get_contents("$post" . "_by");
		$post_id = file_get_contents("$post" . "_id");
		$post_replies = file_get_contents("$post" . "_replies");
		$post_views = file_get_contents("$post" . "_views");
	
		echo "<tr>\n";
		echo "<td id='ptitle'><a href=\"view.php?cid=$catid&tid=$post_id\">$post_title</a></td>\n";
		echo "<td id='preplies'>$post_replies</td>\n";
		//echo "</tr><tr>\n";
		echo "<td id='pviews'>$post_views</td>\n";
		echo "<td id='powner'>$post_by</td>\n";
		echo "<td id='pdate'>$post_date</td>\n";
		echo "</tr>\n";
		
	}
	echo "</table>\n";
	echo "<a href=\"topic.php?action=newtopic&cid=$catid\">New Topic</a>\n</div>";
}
else
{
	print <<<EOD
	<div class="text">Error: No category or topic ID specified.</div>
EOD;
}

include "themes/$theme/footer.php";

?>
