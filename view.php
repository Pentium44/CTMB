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

if(!file_exists("config.php")) 
{
	header("Location: install.php");
}
else
{
	include "config.php";
}

include "themes/$theme/header.php";

$tid = $_GET['tid'];
if (isset($tid))
{
	if (file_exists("db/posts/$tid.txt"))
	{
		print <<<EOD
		<div class="text"><center><table border='1'>
EOD;
		$file_content = file_get_contents("db/posts/$tid.txt");
		echo $file_content;
		
		print <<<EOD
		</table></center>
		</div><br><div class="text"><b><a href="topic.php?action=reply&id=$tid">Reply</a></b></div>
EOD;
	}
	else
	{
		print <<<EOD
		<div class="text">Error: Topic Not Found.</div>
EOD;
	}
}
else
{
	print <<<EOD
	<div class="text">Error: No topic ID specified</div>
EOD;
}

include "themes/$theme/footer.php";

?>