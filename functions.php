<?php

register_sidebar(array(
	'before_widget' => '
				<li class="widget" id="%1$s">',
	'after_widget' => '
				</li>',
	'before_title' => '
					<h2>',
	'after_title' => '</h2>
					',
));