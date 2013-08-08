<?php
/* This script is part of CTMB. CTMB is released under the CC BY-NC, and does not include
 * Warranty of any kind. View the Copy of the CC BY-NC license located in the 
 * CTMB archive. CTMB was created by:
 * (c) Chris Dorman, 2012-2013 <cdorm245@gmail.com>
 */
$id = $_GET['id'];
if (file_exists("config.php"))
{
	include "config.php";
}
else
{
	echo "ERROR: Config error";
	exit();
}

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<html>
<head>
<title>$title</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"$stylesheet\">
</head>
<body>
<div class=\"title\">$title</div>
<div class=\"desc\">$desc</div>
<div class=\"text\">";

	$postid = "db/posts/$id.txt";
	$user = htmlentities(stripslashes($_POST["username"]));
	$body = htmlentities(stripslashes($_POST["body"]));
	include "bb.php";

	$text = bbcode_format($body);
	$placement = "<hr><center>$user</center><hr>$text<br><br>";
	
	if (isset($user) && isset($text))
	{
		$old_posts = file_get_contents($postid);
		file_put_contents($postid, $old_posts . $placement);
		echo "Reply complete. Post here: <a href=\"id.php?id=$id\">Link to post</a><br><hr><a href=\"index.php\">Back to Post Index</a>";
	}

echo "
</div>
<div class=\"footer\">&copy; CTMB, 2012-2013 GPLv2+</div>
</body>
</html>
";
?>