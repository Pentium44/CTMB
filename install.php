<?php

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
	User Validation (Validate new users before they can post): <input type="checkbox" name="validation" value="validation"><br>
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
		$config_string1 = "<?php\n //CTMB Config generation \n\n \$title = \"$title\";\n \$desc = \"$desc\";\n \$admin_color = \"#ff00ff\";\n \$user_color = \"#00ff00\";\n  \$image_upload_size = \"$image_upload_size\";\n ";
		if(isset($_POST['validation']))
		{
			$config_string2 = "\$validation = \"true\";\n ";
		}
		else
		{
			$config_string2 = "\$validation = \"false\";\n ";
		}
		
		$config_string4 = "\$theme = \"default\";\n ";
		
		// Close the php tag //
		$config_string5 = "?>\n";
		file_put_contents("config.php", $config_string1 . $config_string2 . $config_string3 . $config_string4 . $config_string5);
	
		// Create Owner //
		file_put_contents("db/users/" . $username . ".validation", "valid");
		file_put_contents("db/users/$username.status", "admin");
		file_put_contents("db/users/$username.color", "#ff0000");
		file_put_contents("db/users/$username.rank", "Board Owner");
		file_put_contents("db/users/$username.postnumber", "0");
		file_put_contents("db/users/" . $username . ".php", "<?php \$userpass = \"$password\"; ?>");
		$old_users = file_get_contents("db/userlist.txt");
		$user = "<a href=\"user.php?action=userpanel&user=$username\">$username</a><br>\n";
		file_put_contents("db/userlist.txt", $user . $old_users);
		echo "<html>Board Installed! : : <a href='index.php'>To board</a></html>";
	
	}
	else
	{
		echo "<html>Error: Form Not Completely filled out!</html>";
	}
}



?>