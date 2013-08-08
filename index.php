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
 *//


include "config.php";

print <<<EOD
<html>
	<head>
		<title>$title</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
<body>
	<div class="title">$title</div>
EOD;

/* Menu List for Login/out, index, and admin panel */
	print <<<EOD
	<center><span class="menu"><a href="index.php">Forum Index</a><a href="signup.php">Register</a><a href="index.php?action=userlist">Userlist</a><a href="admin_panel.php">Administration Panel</a></span></center><br>
EOD;

if (isset($_GET['action']))
{
	if ($_GET['action']=="userlist")
	{
		$userlist = file_get_contents("db/userlist.txt");
		echo "<div class=\"text\">";
		echo $userlist;
		echo "</div>";

	}
}

/* Show Forum topics */
if (!isset($_GET['action']))
{
print <<<EOD
<div class="text">
EOD;
	$postlist = file_get_contents("db/list.txt");
	echo $postlist;
	print <<<EOD
	<br><hr><a href="topic.php?action=newtopic">New Topic</a></div>
EOD;
}

print <<<EOD
<br><div class="footer">&copy; CTMB - CrazyCoder Productions, 2012-2013</div>
</body>
</html>
EOD;

?>