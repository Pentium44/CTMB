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

// Set user specified theme, else use default
if(isset($_SESSION['ctmb-theme'])){ $theme = $_SESSION['ctmb-theme']; } else { $theme = "default"; }

include "themes/$theme/header.php";

if (isset($_GET['tid']) && isset($_GET['cid']))
{
	$tid = $_GET['tid'];
	$catid = $_GET['cid'];
	if (file_exists("db/cat/$catid/$tid/title"))
	{
		echo "\n<div class='text'>\n"; // Open text div
		
		// Someone is viewing it!
		$views = file_get_contents("db/cat/$catid/$tid/views");
		$views = $views + 1;
		file_put_contents("db/cat/$catid/$tid/views", $views);
		
		// Print the title
		$topic = file_get_contents("db/cat/$catid/$tid/title");
		echo "<table id='tblarge'>\n<div id='topalign'>$topic</div>\n";
		
		foreach(glob("db/cat/$catid/$tid/" . "*" . ".txt" . "") as $postid)
		{
			include_once("bb.php");
			$user = file_get_contents($postid . "_u");
			$ucolor = file_get_contents("db/users/$user.color");
			$urank = file_get_contents("db/users/$user.rank");
			$usig = nl2br(bbcode_format(stripslashes(htmlentities(file_get_contents("db/users/$user.sig")))));
			$pdate = file_get_contents($postid . "_d");
			$upost = nl2br(bbcode_format(stripslashes(htmlentities(file_get_contents($postid)))));
			print <<<EOD
			<tr><td class='userinfo'>
				<div><a style="color:$ucolor;" href="user.php?action=userpanel&user=$user">$user</a></div>
				<div class='text_small'>$urank</div>
				<img style='margin: auto; max-width: 140px;' src='load.php?action=avatar&name=$user'><br>
				<div>$pdate</div>
			</td><td class='userpost'>
				$upost
EOD;
			if(file_exists($postid . "_a"))
			{
				$uattachment = file_get_contents($postid . "_a");
				print <<<EOD
				<div class="attachment">
					<a href="$uattachment"><img src="$uattachment" alt="Attachment" title="$user attachment" /></a>
				</div>
EOD;
			}
			print <<<EOD
			<div class='sig'>
				<div class='text_small'>
					$usig
				</div>
			</div>	
			</td>
EOD;
		}
		print <<<EOD
		</table><br />
		<a href="topic.php?action=reply&id=$tid&cid=$catid">Reply</a>
EOD;
		if(isset($_SESSION['ctmb-login-user']))
		{
			$user_status = file_get_contents("db/users/" . $_SESSION['ctmb-login-user'] . ".status");
			if($user_status=="admin")
			{
				echo "| <a href='ap.php?action=delpost&cid=$catid&tid=$tid'>Delete this Post</a></div>\n";
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
	foreach(array_reverse(glob("db/cat/$catid/" . "*" . ".post")) as $post)
	{
		if(is_dir($post)) { continue; }
		$postid = file_get_contents($post);
		$post_title = file_get_contents("db/cat/$catid/$postid/title");
		$post_date = file_get_contents("db/cat/$catid/$postid/date");
		$post_by = file_get_contents("db/cat/$catid/$postid/owner");
		$post_replies = file_get_contents("db/cat/$catid/$postid/replies");
		$post_views = file_get_contents("db/cat/$catid/$postid/views");
	
		echo "<tr>\n";
		echo "<td id='ptitle'><a href=\"view.php?cid=$catid&tid=$postid\">$post_title</a></td>\n";
		echo "<td id='preplies'>$post_replies</td>\n";
		//echo "</tr><tr>\n";
		echo "<td id='pviews'>$post_views</td>\n";
		echo "<td id='powner'>$post_by</td>\n";
		echo "<td id='pdate'>$post_date</td>\n";
		echo "</tr>\n";
		
	}
	echo "</table>\n";

	$check_admin = file_get_contents("db/users/" . $_SESSION['ctmb-login-user'] . ".status");
        if($check_admin!="admin" && $catid=="1") {
		echo "<!-- not an admin -->";
	} else {
		echo "<a href=\"topic.php?action=newtopic&cid=$catid\">New Topic</a>\n</div>";
	}
}
else
{
	print <<<EOD
	<div class="text">Error: No category or topic ID specified.</div>
EOD;
}

include "themes/$theme/footer.php";

?>
