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

print <<<EOD
<html>
	<head>
		<title>$title</title>
		<link rel="stylesheet" type="text/css" href="themes/$theme/style.css">
	</head>
<body>
	<div class="title">$title</div>
EOD;

/* Menu List for Login/out, index, and admin panel */
	print <<<EOD
	<center><span class="menu"><a href="index.php">Forum Index</a><a href="signup.php">Register</a><a href="index.php?action=userlist">Userlist</a><a href="admin_panel.php">Administration Panel</a><a href="avatar.php">Avatars</a></span></center><br>
EOD;

if (isset($_GET['action']))
{
	if ($_GET['action']=="upload")
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$userpass = file_get_contents("db/users/" . $username);
		$decrypt_pass = base64_decode($userpass);
		if (!file_exists("db/users/" . $username)) { echo "<div class='text'>Error: User not found</div>"; exit(); }
		if ($password!=$decrypt_pass) { echo "<div class='text'>Error: Wrong Password</div>"; exit(); }
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);
		if ((($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/x-png")
		|| ($_FILES["file"]["type"] == "image/png"))
		&& ($_FILES["file"]["size"] < $image_upload_size)
		&& in_array($extension, $allowedExts))
		{
			if ($_FILES["file"]["error"] > 0)
			{
				echo "<div class='text'>Return Code: " . $_FILES["file"]["error"] . "<br></div>";
			}
			else
			{
				echo "<div class='text'>Upload: " . $_FILES["file"]["name"] . "<br>";
				echo "Type: " . $_FILES["file"]["type"] . "<br>";
				echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

				if (file_exists("db/avatars/" . $_FILES["file"]["name"]))
				{
					echo $_FILES["file"]["name"] . " already exists. ";
				}
				else
				{
					move_uploaded_file($_FILES["file"]["tmp_name"],
					"db/avatars/" . $_FILES["file"]["name"]);
					echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br></div>";
				}
			}
		}
		else
		{
			echo "<div class='text'>Error: Invalid filetype</div>";
		}
	}
}

/* Show Forum topics */
if (!isset($_GET['action']))
{
print <<<EOD
<div class="text">
<form action="avatar.php?action=upload" method="post"
enctype="multipart/form-data">
<label for="username">Username:</label>
<input type="text" name="username" id="username"><br>
<label for="password">Username:</label>
<input type="password" name="password" id="password"><br>
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Upload">
</form>
</div>
EOD;
}

print <<<EOD
<br><div class="footer">&copy; CTMB - CrazyCoder Productions, 2012-2013</div>
</body>
</html>
EOD;

?>
