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


include "config.php";

include "themes/$theme/header.php";

if (isset($_GET['action']))
{
	if ($_GET['action']=="userlist")
	{
		$userlist = file_get_contents("db/userlist.txt");
		echo "<div class=\"text\"><ul>";
		echo $userlist;
		echo "</ul></div>";

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

include "themes/$theme/footer.php";

?>
