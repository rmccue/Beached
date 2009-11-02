<!doctype html>
<html>
	<head>
		<title><?php wp_title(' &mdash; ', true, 'right'); bloginfo('name'); ?></title>
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/reset.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/style.css" type="text/css" />
	</head>
	<body>
		<div id="sidebar">

			<h1><?php bloginfo('name') ?></h1>
			<p><?php bloginfo('description') ?></p>
			<ul>
				<?php dynamic_sidebar() ?>
			</ul>
		</div>
		<div id="content">

			<div class="wrapper">