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
session_start();

if(!file_exists("config.php")) 
{
	header("Location: install.php");
}
else
{
	include "config.php";
}

include "themes/$theme/header.php";

if(isset($_SESSION['ctmb-login-user']) && isset($_SESSION['ctmb-login-pass']))
{
	if(file_exists("db/users/" . $_SESSION['ctmb-login-user'] . ".php"))
	{
		include "db/users/" . $_SESSION['ctmb-login-user'] . ".php";
		if($_SESSION['ctmb-login-pass']==$userpass)
		{
			if (isset($_GET['action']))
			{
				if ($_GET['action']=="upload")
				{
					$username = $_SESSION['ctmb-login-user'];
					$allowedExts = array("gif", "jpeg", "jpg", "png");
					$temp = explode(".", $_FILES["file"]["name"]);
					$extension = end($temp);
					if ((($_FILES["file"]["type"] == "image/gif")
					|| ($_FILES["file"]["type"] == "image/x-gif")
					|| ($_FILES["file"]["type"] == "image/jpeg")
					|| ($_FILES["file"]["type"] == "image/x-jpeg")
					|| ($_FILES["file"]["type"] == "image/x-jpg")
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
							if (file_exists("db/avatars/$username.$extension"))
							{
								unlink("db/avatars/$username.$extension");
								move_uploaded_file($_FILES["file"]["tmp_name"],
								"db/avatars/" . $_FILES["file"]["name"]);
								rename("db/avatars/" . $_FILES["file"]["name"], "db/avatars/$username.$extension");
								file_put_contents("db/avatars/$username.txt", "$username.$extension");
								echo "<div class='text'>Avatar Uploaded, this will be your avatar when you make posts, and topics</div>";
							}
							else
							{
								move_uploaded_file($_FILES["file"]["tmp_name"],
								"db/avatars/" . $_FILES["file"]["name"]);
								rename("db/avatars/" . $_FILES["file"]["name"], "db/avatars/$username.$extension");
								file_put_contents("db/avatars/$username.txt", "$username.$extension");
								echo "<div class='text'>Avatar Uploaded, this will be your avatar when you make posts, and topics</div>";
							}
						}
					}
					else
					{
						echo "<div class='text'>Error: Avatar is too large, or is a invalid filetype</div>";
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
<label for="password">Password:</label>
<input type="password" name="password" id="password"><br>
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Upload">
</form>
</div>
EOD;
			}
		}
		else
		{
			echo "<div class='text'>Error: The password that is set does not seem to match the user you are logged in as.</div>";
		}
	}
	else
	{
		echo "<div class='text'>Error: This user that is set in your browser cache does not exist</div>";
	}
}
else
{
	echo "<div class='text'>Error: You must be logged in to upload a avatar</div>";
}

include "themes/$theme/footer.php";

?>
