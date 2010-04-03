<?php

register_sidebar(array(
	'before_widget' => '
				<li class="widget" id="widget-%1$s">',
	'after_widget' => '
				</li>',
	'before_title' => '
					<h2>',
	'after_title' => '</h2>
					',
));

// Fire this during init
register_post_type('link', array(
	'label' => __('Links'),
	'singular_label' => __('Link'),
	'public' => true,
	'show_ui' => false,
	'_builtin' => false,
	'_edit_link' => 'post.php?post=%d',
	'capability_type' => 'post',
	'hierarchical' => false,
	'supports' => array('title', 'editor', 'author', 'comments', 'custom-fields'),
	'rewrite' => array('slug' => 'link')
));


function shot_get_posts( $query ) {
	if ( is_home() || is_feed() )
		$query->set( 'post_type', array( 'post', 'link', 'attachment' ) );

	return $query;
}
add_filter( 'pre_get_posts', 'shot_get_posts' );

function shot_link_columns($columns) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => 'Link Title',
		'description' => 'Description',
		'url' => 'URL',
		'comments' => 'Comments',
	);	
	return $columns;
}
add_filter('manage_edit-podcast_columns', 'shot_link_columns');
 
function shot_link_custom_columns($column) {
	global $post;
	if ("ID" == $column) echo $post->ID;
	elseif ("description" == $column) echo $post->post_content;
	elseif ("url" == $column) echo "63:50";
}
add_action('manage_posts_custom_column', 'shot_link_custom_columns');

function shot_post_callback( $post_id ) {
	global $post, $shot_types;

	// Verify
	if ( !wp_verify_nonce( $_POST['shot-nonce'], 'shot-meta-box' ))
		return $post_id;

	// Pages can't have shot data
	if ( 'link' !== $_POST['post_type'] )
		return $post_id;

	// Check permissions
	if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;

	// SHOT LINK TARGET
	$data = $_POST['shot-link-target'];
	$data = htmlspecialchars($data);

	if(get_post_meta($post_id, 'shot_link_target') == "")
		add_post_meta($post_id, 'shot_link_target', $data, true);

	elseif($data != get_post_meta($post_id, 'shot_link_target', true))
		update_post_meta($post_id, 'shot_link_target', $data);
}
add_action('save_post', 'shot_post_callback');

function shot_admin() {
	add_meta_box( 'shot-meta-box', 'Shot', 'shot_meta_box', 'link', 'normal', 'high' );
	global $menu, $submenu;

	// Remove the *other* "Links" menu
	unset($menu[15]);

	$ptype_obj = get_post_type_object('link');

	add_menu_page( '', esc_attr($ptype_obj->label), $ptype_obj->edit_type_cap, "edit.php?post_type=link", '', get_stylesheet_directory_uri() . '/shots/link.png', 6);

	add_submenu_page( "edit.php?post_type=link", 'Edit Links', 'Edit', $ptype_obj->edit_type_cap, "edit.php?post_type=link");
	add_submenu_page( "edit.php?post_type=link", 'Add New Link', 'Add New', $ptype_obj->edit_type_cap, "post-new.php?post_type=link");
}
add_action('admin_menu', 'shot_admin');

function shot_meta_box( $object, $box ) {
	global $shot_types;
	$target = get_post_meta($object->ID, 'shot_link_target', true);
?>
	<p><label for="shot-link-target">Link to:</label>
	<input type="text" name="shot-link-target" id="shot-link-target" value="<?php echo $target ?>" class="code" style="width: 99%" /></p>
	<p>This makes the title of the post link to this address on your blog. It will also change your permalink in your Atom and RSS feeds.</p>
    <input type="hidden" name="shot-nonce" value="<?php echo wp_create_nonce( 'shot-meta-box' ); ?>" />
</p>
<?php
}

function shot_link_target($default = '') {
	global $post;
	$link = get_post_meta($post->ID, 'shot_link_target', true);
	if(empty($link))
		$link = $default;
	return $link;
}

function shot_link_tag() {
	$link = shot_link_target();
	if(empty($link))
		return get_the_title();

	return '<a href="' . $link . '">' . get_the_title() . '</a>';
}

function shot_link_rss_content($content) {
	global $post;
	if(get_post_type($post) == 'link' && shot_link_target(false)) {
		$content .= '<p><a href="' . get_permalink() . '">[Permalink]</a></p>';
		return $content;
	}
	return $content;
}
add_filter('the_content_feed', 'shot_link_rss_content');
add_filter('the_excerpt_rss', 'shot_link_rss_content');

function shot_link_rss_permalink($permalink) {
	global $wp_query, $post;
	if(get_post_type($post) == 'link' && $url = shot_link_target(false)) {
		return $url;
	}
	return $permalink;
}
add_filter('the_permalink_rss', 'shot_link_rss_permalink');