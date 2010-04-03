<?php
get_header();

if (  $wp_query->max_num_pages > 1 ) :
?>
				<div id="nav-above" class="navigation">
					<div class="nav-previous"><?php next_posts_link('&larr; Older posts'); ?></div>
					<div class="nav-next"><?php previous_posts_link('Newer posts &rarr;'); ?></div>
				</div><!-- #nav-aobe -->
<?php
endif;

while(have_posts()): the_post();
	$post_type = get_post_type($post);
	if($post_type == 'link'):
?>
				<div class="post shot shot-<?php echo $post_type ?>" id="post-<?php the_id() ?>">
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
				<div class="post shot shot-<?php echo $post_type ?>" id="post-<?php the_id() ?>">
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
?>

<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link('&larr; Older posts'); ?></div>
					<div class="nav-next"><?php previous_posts_link('Newer posts &rarr;'); ?></div>
				</div><!-- #nav-below -->
<?php
endif;
get_footer();
?>