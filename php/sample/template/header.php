<?php
$current_page = basename($_SERVER['PHP_SELF']);
?><!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="template/layout.css">
		<link rel="stylesheet" type="text/css" href="template/sample.css">
	</head>
	<body>
		<div id="broadcast-nav">
			<div<?php echo $current_page == 'upcoming_broadcasts.php' ? ' class="broadcast-current-list"' : ''; ?>>
				<a href="upcoming_broadcasts.php">Upcoming Broadcasts</a>
			</div>
			<div<?php echo $current_page == 'now_playing.php' ? ' class="broadcast-current-list"' : ''; ?>>
				<a href="now_playing.php">Now Playing</a>
			</div>
			<div<?php echo $current_page == 'archived_broadcasts.php' ? ' class="broadcast-current-list"' : ''; ?>>
				<a href="archived_broadcasts.php">Archived Broadcasts</a>
			</div>
			<div style="width:auto; clear:both; float:none; "></div>
		</div>
		<div id="main-container">