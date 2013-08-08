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
	
	$list = "db/list.txt";
	$get_list = file_get_contents("db/list.txt", true);
	if (file_exists("$list"))
	{
		echo $get_list;
	}
	else
	{
		echo "ERROR: Post list not found";
	}
	echo "<hr><a href='addpost.php'>New Topic</a>";
	
echo "
</div>
<div class=\"footer\">&copy; CTMB, 2012-2013 GPLv2+</div>
</body>
</html>
";
?>
	