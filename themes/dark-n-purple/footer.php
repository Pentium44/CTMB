<?php 
// Amount of views on the forum
$forum_views = file_get_contents("db/forum.views");
// Latest user registery
$forum_latest_user = file_get_contents("db/users/latest");

?>

</div>
<br><div class="footer">
	Forum views: <?php echo $forum_views; ?> - Latest registery: <?php echo $forum_latest_user; ?><br />
	&copy; CTMB <?php echo $version; ?> - 2012, 2013, 2014 - Chris Dorman
</div>

</body>
</html>
