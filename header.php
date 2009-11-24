<!doctype html>
<html>
	<head>
		<title><?php wp_title(' &mdash; ', true, 'right'); bloginfo('name'); ?></title>
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/reset.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/style.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/shots.css" type="text/css" />
		<?php wp_head() ?>
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
				<div class="post" id="twitter">
					<span>I haven&apos;t been thinking much lately.</span>
					<p class="date"><a href="http://twitter.com/rmccue">Meow?</a></p>
				</div>