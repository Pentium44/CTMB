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
	Board Owner Username: <input type="text" name="username" id="username"><br>
	Board Owner Password: <input type="password" name="password" id="password"><br>
	Avatar Upload Size (In Bytes!): <input type="text" name="image_upload_size" id="image_upload_size"><br>
	Show User IP's: <input type="checkbox" name="show_ips" value="show_ips"><br>
	User Validation (Validate new users before they can post): <input type="checkbox" name="validation" value="validation"><br>
	Board Theme: <select name="theme">
					<option name="default" value="default">CTMB Default</option>
					<option name="terminal" value="terminal">Mono Terminal</option>
					<option name="bright_white" value="bright_white">Bright n' Simple</option>
					<option name="gothic-purple" value="gothic-purple">Gothic</option>
				 </select><br>
	<input type="submit" name="install" value="Install CTMB">
	</form>
	</body>
	</html>
EOD;
}
else
{
	if(isset($_POST['install']) && $_POST['username']!="" && $_POST['password']!="" && $_POST['title']!="" && $_POST['image_upload_size']!="")
	{
		// Make Config //
		$title = $_POST['title'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$image_upload_size = $_POST['image_upload_size'];
		$config_string1 = "<?php \$title = \"$title\"; \$admin_color = \"#ff00ff\"; \$user_color = \"#00ff00\";  \$image_upload_size = \"$image_upload_size\"; ";
		if(isset($_POST['validation']))
		{
			$config_string2 = "\$validation = \"true\"; ";
		}
		else
		{
			$config_string2 = "\$validation = \"false\"; ";
		}
		
		if(isset($_POST['show_ips']))
		{
			$config_string3 = "\$show_ips = \"true\"; ";
		}
		else
		{
			$config_string3 = "\$show_ips = \"false\"; ";
		}
		
		$theme = $_POST['theme'];
		if($theme=="default")
		{
			$config_string4 = "\$theme = \"default\"; ";
		}
		else if($theme=="terminal")
		{
			$config_string4 = "\$theme = \"terminal\"; ";
		}
		else if($theme=="gothic-purple")
		{
			$config_string4 = "\$theme = \"gothic-purple\"; ";
		}
		else
		{
			$config_string4 = "\$theme = \"bright_white\"; ";
		}
		// Close the php tag //
		$config_string5 = "?>";
		file_put_contents("config.php", $config_string1 . $config_string2 . $config_string3 . $config_string4 . $config_string5);
	
		// Create Owner //
		file_put_contents("db/users/" . $username . ".validation", "valid");
		file_put_contents("db/users/$username.status", "admin");
		file_put_contents("db/users/$username.color", "#ff0000");
		file_put_contents("db/users/$username.logo", "Board Owner");
		file_put_contents("db/users/" . $username . ".php", "<?php \$userpass = \"$password\"; ?>");
		$old_users = file_get_contents("db/userlist.txt");
		$user = "<b>$username</b><br>";
		file_put_contents("db/userlist.txt", $user . $old_users);
		echo "<html>Board Installed!</html>";
	
	}
	else
	{
		echo "<html>Error: Form Not Completely filled out!</html>";
	}
}



?>