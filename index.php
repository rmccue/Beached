<?php
get_header();
?>
<?php
while(have_posts()): the_post();
?>
				<div class="post shot shot-<?php echo shot_get_post_type() ?>" id="post-<?php the_id() ?>">
					<h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
					<p class="date"><?php the_time('jS \o\f F') ?></p>
					<div class="content">
						<?php the_content() ?>
					</div>
				</div>
<?php
endwhile;
get_footer();
?>