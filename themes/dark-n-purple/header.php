<?php 
/* CTMB Theme
 * (C) Chris Dorman, 2013-2014 - CC-BY-NC 3.0
 */

include "config.php"; 

 // Theme name (for stylesheet)
$theme = "dark-n-purple";

// Amount of views on the forum
$forum_views = file_get_contents("db/forum.views");
// Latest user registery
$forum_latest_user = file_get_contents("db/users/latest");
?>

<html>
	<head>
		<title><?php echo $title ?></title>
		<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme ?>/style.css">
	</head>
<body>
	<table class="title" style="width:100%;">
		<tr>
			<td>
				<?php echo $title; ?>
				<div style="font-size:18px;color:silver;"><?php echo $desc; ?></div>
			</td>
			<td>
				<table align="right" id="finfo_t">
					<tr>
						<td id="finfo_h">
							Information
						</td>
					</tr>
					<tr>
						<td id="finfo_b">
							Forum views: <?php echo $forum_views; ?><br />
							Latest registery: <?php echo $forum_latest_user; ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<div class="board">
		<?php
			if(isset($_SESSION['ctmb-login-user']) && isset($_SESSION['ctmb-login-pass']) && file_exists("db/users/" . $_SESSION['ctmb-login-user'] . ".php"))
			{
				include "db/users/" . $_SESSION['ctmb-login-user'] . ".php";
				if($_SESSION['ctmb-login-pass']==$userpass)
				{
					$check_admin = file_get_contents("db/users/" . $_SESSION['ctmb-login-user'] . ".status");
					if($check_admin!="admin")
					{
						print <<<EOD
						<center><div class="menu"><a href="index.php">Index</a>|<a href="index.php?action=logout">Logout</a>|<a href="index.php?action=userlist">Userlist</a>|<a href="user_panel.php">User Panel</a></div></center><br />
EOD;
					}
					else
					{
						print <<<EOD
						<center><div class="menu"><a href="index.php">Index</a>|<a href="index.php?action=logout">Logout</a>|<a href="index.php?action=userlist">Userlist</a>|<a href="user_panel.php">User Panel</a>|<a href="admin_panel.php">Admin Panel</a></div></center><br />
EOD;
					}					
				}
				else
				{
					print <<<EOD
					<center><div class="menu"><a href="index.php">Index</a>|<a href="index.php?action=login">Login</a>|<a href="user.php?action=register">Register</a>|<a href="index.php?action=userlist">Userlist</a></div></center><br />
EOD;
				}
			}
			else
			{
				print <<<EOD
				<center><div class="menu"><a href="index.php">Index</a>|<a href="index.php?action=login">Login</a>|<a href="user.php?action=register">Register</a>|<a href="index.php?action=userlist">Userlist</a></div></center><br />
EOD;
			} ?>
