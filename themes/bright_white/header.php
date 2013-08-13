<?php include "config.php"; ?>

<html>
	<head>
		<title><?php echo $title ?></title>
		<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme ?>/style.css">
	</head>
<body>
	<div class="title"><?php echo $title ?></div><br>
	<div class="board">
	<table><tr><td style="width:170px;vertical-align:top;">
	<span class="menu">
		<a href="index.php">Forum Index</a><br>
		<a href="signup.php">Register</a><br>
		<a href="index.php?action=userlist">Userlist</a><br>
		<a href="admin_panel.php">Administration</a><br>
		<a href="avatar.php">Avatars</a>
	</span></td>
	<td style="width:730px;vertical-align:top;">