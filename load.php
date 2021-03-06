<?php
/*
 * CTMB - Crazy Tiny Message Board - 2012-2020
 * CTMB (Crazy Tiny Message Board) is a simple, flatfile database message
 * board that is created by Chris Dorman (cddo.cf), 2012-2020
 * CTMB is released under the Creative Commons - BY-NC-SA 4.0 NonPorted license
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
}
?>
