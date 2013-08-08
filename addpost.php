<?php
/* This script is part of CTMB. CTMB is released under the CC BY-NC, and does not include
 * Warranty of any kind. View the Copy of the CC BY-NC license located in the 
 * CTMB archive. CTMB was created by:
 * (c) Chris Dorman, 2012-2013 <cdorm245@gmail.com>
 */
if (file_exists("config.php"))
{
	include "config.php";
}
else
{
	echo "ERROR: Config error";
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
		
		echo "<h2>Post new topic</h2><br>
<form action='post.php' method='post'>
Username: <input type='text' name='username'><br>
Topic Name: <input type='text' name='topic'><br>
<textarea name='body' cols='35' rows='8'>Post Body</textarea><br>
<input type='submit' value='Submit'>";
	
echo "
<br><hr><a href=\"index.php\">Back to Post Index</a>
</div>
<div class=\"footer\">&copy; CTMB, 2012-2013 GPLv2+</div>
</body>
</html>
";
?>