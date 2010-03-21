<?php
get_header();
?>
<?php
while(have_posts()): the_post();
	$post_type = shot_get_post_type();
	if($post_type == 'link'):
?>
				<div class="post shot shot-<?php echo shot_get_post_type() ?>" id="post-<?php the_id() ?>">
					<h2><?php echo shot_link_tag() ?></h2>
					<p class="date"><?php the_time('jS \o\f F') ?></p>
					<div class="content">
						<?php the_content() ?>
					</div>
					<p class="comments"><a href="<?php the_permalink() ?>">Permalink</a> | <?php comments_popup_link( 'Start the conversation', '1 comment', '% comments'); ?></p>
				</div>
<?php
	else:
?>
				<div class="post shot shot-<?php echo shot_get_post_type() ?>" id="post-<?php the_id() ?>">
					<h2><?php the_title() ?></h2>
					<p class="date"><?php the_time('jS \o\f F') ?></p>
					<div class="content">
						<?php the_content() ?>
					</div>
					<p class="comments"><a href="<?php the_permalink() ?>">Permalink</a> | <?php comments_popup_link( 'Start the conversation', '1 comment', '% comments'); ?></p>
				</div>
<?php
	endif;
endwhile;
get_footer();
?>