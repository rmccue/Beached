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


/* Shot code */

function shot_init() {
	global $shot_types;
	$shot_types = array(
		'text' => array(
			'name' => 'Text',
			'callback' => 'shot_text_callback',
		),
		'photo' => array(
			'name' => 'Photo',
			'callback' => 'shot_photo_callback',
		),
		'quote' => array(
			'name' => 'Quote',
			'callback' => 'shot_quote_callback',
		),
		'link' => array(
			'name' => 'Link',
			'callback' => 'shot_link_callback',
		),
		'chat' => array(
			'name' => 'Chat',
			'callback' => 'shot_chat_callback',
		),
		'audio' => array(
			'name' => 'Audio',
			'callback' => 'shot_audio_callback',
		),
		'video' => array(
			'name' => 'Video',
			'callback' => 'shot_video_callback',
		),
	);
}
add_action('init', 'shot_init');

function shot_admin() {
	add_meta_box( 'shot-meta-box', 'Shot', 'shot_meta_box', 'post', 'side', 'high' );
}
add_action('admin_menu', 'shot_admin'); 

function shot_meta_box( $object, $box ) {
	global $shot_types;
?>
<p>
    <label for="shot-post-type">
        <strong>Post Type</strong>
    </label>
	<select name="shot-post-type" id="shot-post-type">
<?php
	foreach($shot_types as $type => $detail) {
		$selected = ($type == get_post_meta($object->ID, 'shot_post_type', true)) ? ' selected="selected"' : '';
?>
		<option value="<?php echo $type ?>"<?php echo $selected ?>><?php echo $detail['name'] ?></option>
<?php
	}
?>
	</select>
<?php
	$target = get_post_meta($object->ID, 'shot_link_target', true);
?>
	<label for="shot-link-target">
		<strong>Link Target</strong>
	</label>
	<input type="text" name="shot-link-target" id="shot-link-target" value="<?php echo $target ?>" />
    <input type="hidden" name="shot-nonce" value="<?php echo wp_create_nonce( 'shot-meta-box' ); ?>" />
</p>
<?php
}

function shot_post_callback( $post_id ) {
	global $post, $shot_types;

	// Verify
	if ( !wp_verify_nonce( $_POST['shot-nonce'], 'shot-meta-box' ))
		return $post_id;

	// Pages can't have shot data
	if ( 'page' == $_POST['post_type'] )
		return $post_id;

	// Check permissions
	if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;

	// SHOT TYPE
	$types = array_keys($shot_types);
	$data = $_POST['shot-post-type'];
	if(!in_array($data, $types))
		$data = $types[0];

	if(get_post_meta($post_id, 'shot_post_type') == "")
		add_post_meta($post_id, 'shot_post_type', $data, true);

	elseif($data != get_post_meta($post_id, 'shot_post_type', true))
		update_post_meta($post_id, 'shot_post_type', $data);

	// SHOT LINK TARGET
	$data = $_POST['shot-link-target'];
	$data = htmlspecialchars($data);

	if(get_post_meta($post_id, 'shot_link_target') == "")
		add_post_meta($post_id, 'shot_link_target', $data, true);

	elseif($data != get_post_meta($post_id, 'shot_link_target', true))
		update_post_meta($post_id, 'shot_link_target', $data);
}
add_action('save_post', 'shot_post_callback');  

/**
 * Retrieves the Shot post type for the current post
 *
 * @param string $default Default type if post does not have one
 * @return string Post type
 */
function shot_get_post_type($default = 'text') {
	global $post;
	$type = get_post_meta($post->ID, 'shot_post_type', true);
	if(empty($type))
		$type = $default;
	return $type;
}

/**
 * Register a Shot post type
 *
 * @param string $id Unique identifier for the post type
 * @param string $name Display name
 * @param callback $callback Function/Method to call for the create/edit page
 */
function shot_register_post_type($id, $name, $callback) {
	global $shot_types;
	$shot_types[$id] = array(
		'name' => $name,
		'callback' => $callback
	);
}

/**
 * Retrieve all registered Shot post types
 *
 * @return array
 */
function shot_list_types() {
	global $shot_types;
	return $shot_types;
}


function shot_rewrite_rules( $wp_rewrite ) {
	// add rewrite tokens
	$keytag_token = '%add%';
	$wp_rewrite->add_rewrite_tag($keytag_token, '(.+)', 'shot_add=');
	
	$keywords_structure = $wp_rewrite->root . "add/$keytag_token";
	$keywords_rewrite = $wp_rewrite->generate_rewrite_rules($keywords_structure);
	
	return ( $rewrite + $keywords_rewrite );
}
//add_action('generate_rewrite_rules', 'shot_rewrite_rules');

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
	if(shot_get_post_type() == 'link' && shot_link_target(false)) {
		$content .= '<p><a href="' . get_permalink() . '">[Permalink]</a></p>';
		return $content;
	}
	return $content;
}
add_filter('the_content_feed', 'shot_link_rss_content');
add_filter('the_excerpt_rss', 'shot_link_rss_content');

function shot_link_rss_permalink($permalink) {
	global $wp_query;
	if(shot_get_post_type() == 'link' && $url = shot_link_target(false)) {
		return $url;
	}
	return $permalink;
}
add_filter('the_permalink_rss', 'shot_link_rss_permalink');