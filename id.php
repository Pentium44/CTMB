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

	$post = "db/posts/$id.txt";
	$get_post = file_get_contents("db/posts/$id.txt");
	if (file_exists("$post"))
	{
		echo $get_post;
		echo "<hr>Reply<br>
<form action=\"reply.php?id=$id\" method=\"post\">
Username: <input type=\"text\" name=\"username\"><br>
<textarea name=\"body\" cols=\"35\" rows=\"8\">Post Body</textarea><br>
<input type=\"submit\" value=\"Submit\">";
	}
	else
	{
		echo "ERROR: Post ID Invalid";
	}
	
echo "
<br><hr><a href=\"index.php\">Back to Post Index</a>
</div>
<div class=\"footer\">&copy; CTMB, 2012-2013 GPLv2+</div>
</body>
</html>
";
?>
