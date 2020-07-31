<?php
session_start();
/*
 * CTMB - Crazy Tiny Message Board - 2012-2020
 * CTMB (Crazy Tiny Message Board) is a simple, flatfile database message
 * board that is created by Chris Dorman (cddo.cf), 2012-2020
 * CTMB is released under the Creative Commons - BY-NC-SA 4.0 NonPorted license
 *
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
// For signature //
include "bb.php";

// Set user specified theme, else use default
if(isset($_SESSION['ctmb-theme'])){ $theme = $_SESSION['ctmb-theme']; } else { $theme = "default"; }

include "themes/$theme/header.php";

if(isset($_SESSION['ctmb-login-user']) && isset($_SESSION['ctmb-login-pass']))
{
	if(file_exists("db/users/" . $_SESSION['ctmb-login-user'] . ".php"))
	{
		include "db/users/" . $_SESSION['ctmb-login-user'] . ".php";
		if($_SESSION['ctmb-login-pass']==$userpass)
		{
			$username = $_SESSION['ctmb-login-user'];
			if (isset($_GET['action']))
			{
				if ($_GET['action']=="avatar")
				{
					if(isset($_GET['method']) && $_GET['method']=="upload")
					{
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
								if (file_exists("db/users/avatars/$username.$extension"))
								{
									unlink("db/users/avatars/$username.$extension");
									move_uploaded_file($_FILES["file"]["tmp_name"],
									"db/users/avatars/" . $_FILES["file"]["name"]);
									rename("db/users/avatars/" . $_FILES["file"]["name"], "db/users/avatars/$username.$extension");
									file_put_contents("db/users/avatars/$username.txt", "$username.$extension");
									echo "<div class='text'>Avatar Uploaded, this will be your avatar when you make posts, and topics - <a href='up.php'>Back to user panel</a></div>";
								}
								else
								{
									move_uploaded_file($_FILES["file"]["tmp_name"],
									"db/users/avatars/" . $_FILES["file"]["name"]);
									rename("db/users/avatars/" . $_FILES["file"]["name"], "db/users/avatars/$username.$extension");
									file_put_contents("db/users/avatars/$username.txt", "$username.$extension");
									echo "<div class='text'>Avatar Uploaded, this will be your avatar when you make posts, and topics - <a href='up.php'>Back to user panel</a></div>";
								}
							}
						}
						else
						{
							echo "<div class='text'>Error: Avatar is too large, or is a invalid filetype</div>";
						}
					}
					else
					{
						print <<<EOD
<div class="text">
<form action="up.php?action=avatar&method=upload" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Upload">
</form>
</div>
EOD;
					}
				}
				if($_GET['action']=="sig")
				{
					if(isset($_GET['method']) && $_GET['method']=="dosig")
					{
						if($_POST['usersig']!="")
						{
							$sig = $_POST['usersig'];
							file_put_contents("db/users/$username.sig", $sig);
							echo "<div class='text'>Signature set - <a href='up.php'>Back to user panel</a></div>";
							//header( "refresh:2;url=user_panel.php" );
						}
						else
						{
							echo "<div class='text'>Error, No new signature provided.</div>\n";
						}
					}
					else
					{
						$signature = file_get_contents("db/users/$username.sig");
						print <<<EOD
<div class="text">
<h3 style='text-align:center;'>Change your signature</h3>
<a href="index.php?action=help_bbcode">BBCode Help</a><br>
<form action="up.php?action=sig&method=dosig" method="post">
<textarea cols='36' rows='12' name='usersig'>
EOD;
					echo $signature;
print <<<EOD
</textarea><br />
<input type="submit" name="submit" value="Save">
</form>
</div>
EOD;
					}
				}
				if($_GET['action']=="theme")
				{
					if(isset($_GET['method']) && $_GET['method']=="settheme")
					{
						$theme_select = $_POST['theme'];
						file_put_contents("db/users/$username.theme", $theme_select);
						$_SESSION['ctmb-theme'] = $theme_select;
						echo "<div class='text'>Theme set - <a href='up.php'>Back to user panel</a></div>";
						//header( "refresh:2;url=user_panel.php" );
					}
					else
					{
						print <<<EOD
<div class="text">
<h3 style='text-align:center;'>Change your signature</h3>
<a href="index.php?action=help_bbcode">BBCode Help</a><br>
<form action="up.php?action=theme&method=settheme" method="post">
<select name="theme">
EOD;
						$themedir = "themes/";
						$opendir = opendir($themedir);
						while(false != ($theme_name = readdir($opendir)))
						{
							if($theme_name == "." || $theme_name == "..") { continue; }
							$theme_title = file_get_contents("$themedir" . "$theme_name/" . "name");
							echo "<option value='$theme_name'>$theme_title</option>\n";
						}
						print <<<EOD
</select>
<input type="submit" name="submit" value="Save Theme">
</form>
</div>
EOD;
					}
				}
			}

			/* Show Forum topics */
			if (!isset($_GET['action']))
			{
				print <<<EOD
<div class="text">
<h2>User Control Panel</h2>
<a href="up.php?action=avatar">Change your Avatar</a><br />
<a href="up.php?action=sig">Change your Signature</a><br />
<a href="up.php?action=theme">Change your Theme</a><br />
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
