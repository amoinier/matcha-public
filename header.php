<?php session_start(); ?>
<!DOCTYPE html>
<?php include_once('users.php');?>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/font.css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="css/button.css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="css/header.css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="css/login.css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="css/search.css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="css/profile.css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="css/profile-small.css" media="screen and (max-width: 1170px)" title="no title" charset="utf-8">
		<link rel="stylesheet" href="css/edit.css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="css/discover.css" media="screen" title="no title" charset="utf-8">
		<script src="js/jquery.js"></script>
		<script src="js/ajaxi.js"></script>
		<title id='title'><?php echo $title; ?></title>
	</head>
	<body>
		<div id="fond">
		</div>
		<div id="fond2">
			<img id='zoomp'></div>
		</div>
		<div id='header'>
			<a id='titlelink' href="index.php"><span id='title'>Soul Mate</span></a>
		</div>
		<div id='butt'>
			<?php if (!nullif($_SESSION['login'])) { ?>
			<div id="logoutbut" onclick="logoutt();">Logout <img src="resources/logout.png" alt="logout"/></div>
			<a href="index.php?page=discover"><div id="but2">Discover <img src="resources/discover.png" alt="search"/></div></a>
			<a href="index.php?page=search"><div id="but3">Search <img src="resources/search.png" alt="search"/></div></a>
			<a href="index.php?page=profile&login=<?= $_SESSION['login'];?>"><div id="but4">Profile <img src="resources/profile.png" alt="profile"/></div></a>
			<a href="index.php?page=edit"><div id="but5">Edit <img src="resources/edit.png" alt="edit"/></div></a>
			<div id="but6" onclick="seenotif();">Notification <img src="resources/notification.png" alt="edit"/></div><div id='bulnot'></div><div id='seenoti'><div class="notimess">No notification!</div></div>
			<?php }
			else {?>
				<a href="index.php?page=reg"><div id="but1">Register <img src="resources/reg.png" alt="register"/></div></a>
			<?php } ?>
		</div>
