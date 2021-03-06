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

if(file_exists("config.php"))
{ 
	echo "<html>Error: This CTMB installation is complete, if you would like to reinstall, please delete config.php</html>"; 
	exit();
}

if(!isset($_GET['do']))
{
	print <<<EOD
	<html>
	<body>
	<form action="install.php?do=install" method="post">
	Board Title: <input type="text" name="title" id="title"><br>
	Board Desc: <input type="text" name="desc" id="desc"><br>
	Board Owner Username: <input type="text" name="username" id="username"><br>
	Board Owner Password: <input type="password" name="password" id="password"><br>
	<input type="submit" name="install" value="Install CTMB">
	</form>
	</body>
	</html>
EOD;
}
else
{
	if(isset($_POST['install']) && $_POST['username']!="" && $_POST['password']!="" && $_POST['title']!="" && $_POST['desc']!="")
	{
		// Make Config //
		$title = stripslashes(htmlentities($_POST['title']));
		$username = stripslashes(htmlentities($_POST['username']));
		$password = $_POST['password'];
		$desc = stripslashes(htmlentities($_POST['desc']));
		$image_upload_size = "300000";
		$config_string1 = "<?php\n //CTMB Config generation \n\n \$title = \"$title\";\n \$desc = \"$desc\";\n \$admin_color = \"#ff00ff\";\n \$user_color = \"#00ff00\";\n  \$image_upload_size = \"$image_upload_size\";\n \$version = \"3.0.2\";\n";

		if(!file_exists("db/users")) { mkdir("db/users", 0777); }

		// Close the php tag //
		$config_string4 = "?>\n";
		file_put_contents("config.php", $config_string1 . $config_string4);
	
		// Create Owner //
		$password_hash = sha1(md5($password));
		file_put_contents("db/users/" . $username . ".validation", "valid");
		file_put_contents("db/users/$username.status", "admin");
		file_put_contents("db/users/$username.color", "#ff0000");
		file_put_contents("db/users/$username.rank", "Board Owner");
		file_put_contents("db/users/$username.postnumber", "0");
		file_put_contents("db/users/$username.sig", "Board Owner");
		file_put_contents("db/users/$username.theme", "default"); // Set users theme (default)
		file_put_contents("db/users/" . $username . ".php", "<?php \$userpass = \"$password_hash\"; ?>");
		file_put_contents("db/users/$username.txt", "$username");
		
		if(!file_exists("db/cat")) { mkdir("db/cat", 0777); }
		
		// Set up forum views database
		file_put_contents("db/forum.views", "0");
		
		// There are no categories, set it
		file_put_contents("db/cat.amount", "0");
		
		// Set owner account as latest created user
		file_put_contents("db/users/latest", "<b style=\"color:#ff0000;\">$username</b>");
		
		echo "<html>Board Installed! : : <a href='index.php'>To board</a></html>";
	
	}
	else
	{
		echo "<html>Error: Form Not Completely filled out!</html>";
	}
}



?>
