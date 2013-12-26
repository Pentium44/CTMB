<?php
/*
 * CTMB - Crazy Tiny Message Board - (C) CrazyCoder Productions, 2012-2013
 * CTMB (Crazy Tiny Message Board) is a simple, flatfile database message
 * board that is created by Chris Dorman (CrazyCoder Productions), 2012-2013
 * CTMB is released under the Creative Commons - BY - NC 3.0 NonPorted license
 * 
 * CTMB is released with NO WARRANTY.
 * 
 */


// Check if action is available, for windows reasoning //
if(isset($_GET['action']))
{
	// Check if action is set for getting avatar, or signature //
	$action = $_GET['action'];
	if($action=="avatar")
	{
		if(isset($_GET['name']))
		{
			$user = $_GET['name'];
			if(file_exists("db/users/avatars/$user.txt"))
			{
				$username = $user;
				$user_avatar = file_get_contents("db/users/avatars/$username.txt");
				header("Location: db/users/avatars/$user_avatar");
			}
			else
			{
				header("Location: db/users/avatars/default.png");
			}
		}
	}
	else if($action=="sig")
	{
		if(isset($_GET['name']))
		{
			$user = $_GET['name'];
			if(file_exists("db/users/$user.sig"))
			{ 
			 	$sig = file_get_contents("db/users/$user.sig");
			 	echo "<div style='font-size:11px;'>$sig</div>\n";
			}
			else
			{
				echo "<div style='font-size:11px;'>No signature</div>\n";
			}
		}
	}
	else if($action=="color")
	{
		if(isset($_GET['name']))
		{
			$user = $_GET['name'];
			$color = file_get_contents("db/users/$user.color");
			echo "<b style='color:$color;'>$user</b>\n";
		}		
	}
	else if($action=="rank")
	{
		if(isset($_GET['name']))
		{
			$user = $_GET['name'];
			$rank = file_get_contents("db/users/$user.rank");
			echo "$rank";
		}		
	}
}

//
//EOF//

?>
