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
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
<body>
	<div class="title">$title</div>
EOD;

/* Menu List for Login/out, index, and admin panel */
	print <<<EOD
	<center><span class="menu"><a href="index.php">Forum Index</a><a href="signup.php">Register</a><a href="index.php?action=userlist">Userlist</a><a href="admin_panel.php">Administration Panel</a></span></center><br>
EOD;

$action = $_GET['action'];
if (isset($action))
{
	if ($action=="view")
	{
		$id = $_GET['id'];
		if (isset($id))
		{
			if (file_exists("db/posts/$id.txt"))
			{
				print <<<EOD
				<div class="text">
EOD;
				$file_content = file_get_contents("db/posts/$id.txt");
				echo $file_content;
				
				print <<<EOD
				</div><br><div class="text"><b><a href="topic.php?action=reply&id=$id">Reply</a></b></div>
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
	}
	if ($action=="reply")
	{
		if (isset($_GET['id']))
		{
			$id = $_GET['id'];
			if (file_exists("db/posts/$id.txt"))
			{
				print <<<EOD
			<div class="text">
			<h2><b>Reply</b></h2>
			<form action="topic.php?action=doreply&id=$id" method="post">
			Username: <input type="text" name="username"><br>
			Password: <input type="password" name="password"><br>
			<textarea name="text" cols="35" rows="8">Post Body</textarea><br>
			<input type="submit" value="Submit">
			</div>
EOD;
			}
			else
			{
				print <<<EOD
			<div class="text">Error: Topic Not Found.</div>
EOD;
			}
		}
	}
	if ($action=="doreply")
	{
		if (isset($_GET['id']))
		{
			if (file_exists("db/users/" . $_POST['username']))
			{
				$username = $_POST['username'];
				$password = $_POST['password'];
				$userpass = file_get_contents("db/users/" . $username);
				$decrypt_pass = base64_decode($userpass);
				if ($password==$decrypt_pass)
				{
					$validation = file_get_contents("db/users/" . $username . ".validation");
					if (file_exists("db/users/" . $username . ".validation"))
					{
						if ($validation=="valid")
						{
							$id = $_GET['id'];
							$getoldcontent = file_get_contents("db/posts/$id.txt");
							$text = htmlentities(stripslashes($_POST["text"]));
							$text2 = nl2br($text);
							include "bb.php";
							$bb = bbcode_format($text2);
							$newcontent = "<center><b>" . $username . "</b></center><hr><br>" . $bb . "<br><hr>"; 
							file_put_contents("db/posts/$id.txt", $getoldcontent . $newcontent);
							print <<<EOD
							<div class="text">Your reply to topic id $id was successful.</div>
EOD;
						}
						else
						{
							print <<<EOD
							<div class="text">Error: Your account has not been validated, or you have been declined by the board administrator, in which you cannot reply or post.</div>
EOD;
						}
					}
					else
					{
						print <<<EOD
						<div class="text">Error: Your account has not been validated, or you have been declined by the board administrator, in which you cannot reply or post.</div>
EOD;
					}
				}
				else
				{
					print <<<EOD
					<div class="text">Error: Wrong Password.</div>
EOD;
				}
			}
			else
			{
				print <<<EOD
				<div class="text">Error: User Not Found</div>
EOD;
			}
		}
	}
	if ($action=="newtopic")
	{
		print <<<EOD
	<div class="text">
	<h2><b>Create a New Topic</b></h2>
	<form action="topic.php?action=donewtopic" method="post">
	Username: <input type="text" name="username"><br>
	Password: <input type="password" name="password"><br>
	Topic Name: <input type="text" name="topic"><br>
	<textarea name="text" cols="35" rows="8">Post Body</textarea><br>
	<input type="submit" value="Submit">
	</div>
EOD;
	}
	if ($action=="donewtopic")
	{
		if (file_exists("db/users/" . $_POST['username']))
		{
			$username = $_POST['username'];
			$password = $_POST['password'];
			$topic = $_POST['topic'];
			$userpass = file_get_contents("db/users/" . $username);
			$decrypt_pass = base64_decode($userpass);
			if ($password==$decrypt_pass)
			{
				$validation = file_get_contents("db/users/" . $username . ".validation");
				if (file_exists("db/users/" . $username . ".validation"))
				{
					if ($validation=="valid")
					{
						$text = htmlentities(stripslashes($_POST["text"]));
						$text2 = nl2br($text);
						include "bb.php";
						$bb = bbcode_format($text2);
						$newcontent = "<center><b>" . $username . "</b></center><hr><br>" . $bb . "<br><hr>"; 
						$randomid = rand(1,99999);
						file_put_contents("db/posts/$randomid.txt", $newcontent);
						$list = "<li><b><a href=\"topic.php?action=view&id=$randomid\">$topic</a></b> -by $username<br></li>";
						$list .= file_get_contents('db/list.txt', true);
						file_put_contents("db/list.txt", $list);
						print <<<EOD
						<div class="text">Your Topic has been created</div>
EOD;
					}
					else
					{
						print <<<EOD
						<div class="text">Error: Your account has not been validated, or you have been declined by the board administrator, in which you cannot reply or post.</div>
EOD;
					}
				}
				else
				{
					print <<<EOD
					<div class="text">Error: Your account has not been validated, or you have been declined by the board administrator, in which you cannot reply or post.</div>
EOD;
				}
			}
			else
			{
				print <<<EOD
				<div class="text">Error: Wrong Password.</div>
EOD;
			}
		}
		else
		{
			print <<<EOD
			<div class="text">Error: User Not Found</div>
EOD;
		}	
	}
}


print <<<EOD
<br><div class="footer">&copy; CTMB - CrazyCoder Productions, 2012-2013</div>
</body>
</html>
EOD;

?>