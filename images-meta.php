<?php
/*
Plugin Name: Images Meta
Plugin URI: http://scuba-net.org/blog/plugin/images-meta/
Description: Improved image attachment metabox for wordpress post editor
Version: 1.0
Author: Steve Fraser
Author URI: http://scuba-net.org/
License: GPL2


*/

// Place this code in your theme's functions.php file to configure this plugin
global $_images_post_types, $_all_images;
//$_images_post_types = array( 'page', 'post' ); // Posts types to use for images meta plugin
$_all_images = 1; // Show 'All Images' metabox when using images meta plugin

function images_meta_box() {
	if ( $_GET['post'] ) {
		foreach ( get_post_types() as $post_type ) {
			add_meta_box('images-meta', "Images", 'show_images_meta', $post_type, 'normal', 'high');
			add_meta_box('all-images', "All Images", 'show_all_images', $post_type, 'normal', 'high');	
		}
	}
	add_action( 'admin_head', 'images_meta_style' );
	add_action('save_post', 'save_images_meta');
	wp_enqueue_script( 'images-meta', get_bloginfo( 'template_url' ).'/include/images-meta/images-meta.js' );
}
add_action('admin_init','images_meta_box');

function images_meta_style() { ?>
	<style type="text/css">
		.image-meta {
			border: 1px solid #CFCFCF;
			display: inline-block;
			vertical-align: top;
			width: 150px;
			text-align: center;
		 }
		 .image-meta p {
		 	margin: 0.25em 0;
		 	text-align: left;
		 }
		 .image-meta .img {
		 	height: 100px;
		 	display: table-cell;
		 	vertical-align: middle;
		 }
		 .image-meta .short {
		 	margin: 0 auto;
		 	width: 100px;
		 	border: 1px solid #CFCFCF;
		 	font-size: 90%;
		 }
		 .image-meta img {
			vertical-align: middle;
		}
		.image-meta .caption {
			border: 1px solid #CFCFCF;
			margin: -8px 0 0;
			width: 88px;
			font-size: 80%;
			width: 100%;
		}
		.image-meta .description textarea {
			width: 100%;
		}
		.image-meta .buttons {
			text-align: center;
		}
		.image-meta button {
			padding: 0;
			margin: 0 0.25em;
		}
	</style>
	<?php
}
// Callback function to show fields in meta box
function show_images_meta() {
	global $wpdb;
	$pid = $_GET['post'];
	$thumb = get_post_thumbnail_id($pid);
	mysql_query($q);
	$images =& get_children("post_type=attachment&post_mime_type=image&post_parent=$pid" );
	echo '<p>Products will display in the order of the numbers shown.  <strong>Save page to see newly attached images</strong>.</p>';
    echo '<ul class="images-meta-list">';
	foreach ($images as $image) {
		$tdi++;
		if ( $image->ID == $thumb ) $is_thumb = ' checked="checked"';
		else $is_thumb = '';
		echo "<li class='image-meta' id='image-meta-{$image->ID}'>"
			 ."<p>Title:<input type='text' value='{$image->post_title}' name='image_title[]' size='10'/></p>"
			 ."<div class='short'>"		
			 .'<div class="img">'
			 .'<a href="'.wp_get_attachment_url($image->ID).'" rel="colorbox" title='.$image->post_title.'>'
			 .wp_get_attachment_image($image->ID, $size='post-thumbnail', $icon = false)
			 .'</a></div>'
			 ."<input type='hidden' value='{$image->ID}' name='image_ID[]' />"
			 ."<textarea class='caption' name='image_excerpt[]' size='10'/>{$image->post_excerpt}</textarea></div>"
		   //."<p class='description'>Full Description:<textarea name='image_content[]' rows='5' cols='10'/>{$image->post_content}</textarea></p>"
			."<p>Order:<input type='text' value='{$image->menu_order}' name='image_order[]' size='2'/></p>"
			."<p>Thumb:<input type='radio' name='image_thumb'$is_thumb/ value='{$image->ID}'></p>"			
			."<p class='buttons'><button onclick='detachImage({$image->ID});return false;'>Detach</button>"
			."<button onclick='deleteImage({$image->ID});return false;'>Delete</button></p>"			
			."</li>";
		if ($tdi == 3) {
			echo "</tr><tr>";
			$tdi = 0;
		}
	}
	echo '</ul>';
}
function show_all_images() {
	$images =& get_posts( array( 
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'post_parent' => 0,
		'posts_per_page' => -1
	) );
	echo '<ul class="images-meta-list" id="all-images">';
	foreach( $images as $image ) {
		 echo '<li class="image-meta" id="image-meta-'.$image->ID.'">'
			 ."<p><input type='text' name='new_image_title[]' size='10' value='{$image->post_title}'/></p>"		 
			 .'<div class="img"><a href="'.wp_get_attachment_url($image->ID).'" rel="colorbox" title='.$image->post_title.'>'
		 	.wp_get_attachment_image($image->ID, $size='thumbnail', $icon = false).'</a></div>'
	 	    ."<textarea class='caption' name='new_image_caption[]' size='10'/>{$image->post_excerpt}</textarea>"
	     	."<input type='hidden' value='{$image->ID}' name='image[]' />"
	     	."Attach:<input type='checkbox' value='{$image->ID}' name='new_image[]' />"
	     	."Delete:<input type='checkbox' value='{$image->ID}' name='delete_image[]' />"
	     	.'</li>';
	}
	echo '</ul>';
}
function save_images_meta( $post_id ) {
	/* Update changes to attadchments */
	foreach ( (array)$_POST['image_ID'] as $i=>$ID ) {
		$my_post = array(
			'ID' => $ID,
			'post_title' => $_POST['image_title'][$i],
			'post_excerpt' => $_POST['image_excerpt'][$i],
			'post_content' => $_POST['image_content'][$i],
			'menu_order' => $_POST['image_order'][$i]
		);
		wp_update_post( $my_post );
	}
	
	/* Update changes to unattached images */
	if ( is_array($_POST['image'] ) ) foreach ( $_POST['image'] as $p=>$new_id ) {
		if ( in_array( $new_id, (array)$_POST['new_image'] ) )$post_parent = $post_id; // Attach ?
		else $post_parent = 0;
		$my_post = array(
			'ID' => $new_id,
			'post_title' => $_POST['new_image_title'][$p],
			'post_excerpt' => $_POST['new_image_caption'][$p],
			'post_parent' => $post_parent
		);
		if ( get_post( $new_id ) ) wp_update_post( $my_post );
		else wp_insert_post( $my_post );
	}
	set_post_thumbnail( $post_id, $_POST['image_thumb'] ); // Thumbnail!
	if ( is_array($_POST['delete_image'] ) ) foreach ( $_POST['delete_image'] as $delete ) {
		wp_delete_post( $delete ); // Delete ?
	}
	return $post_id;
}

add_filter("manage_upload_columns", 'upload_columns');
add_action("manage_media_custom_column", 'media_custom_columns', 0, 2);

function upload_columns($columns) {

	unset($columns['parent']);
	$columns['better_parent'] = "Parent";

	return $columns;

}
function media_custom_columns($column_name, $id) {

	$post = get_post($id);

	if($column_name != 'better_parent')
		return;

		if ( $post->post_parent > 0 ) {
			if ( get_post($post->post_parent) ) {
				$title =_draft_or_post_title($post->post_parent);
			}
			?>
			<strong><a href="<?php echo get_edit_post_link( $post->post_parent ); ?>"><?php echo $title ?></a></strong>, <?php echo get_the_time(__('Y/m/d')); ?>
			<br />
			<a class="hide-if-no-js" onclick="findPosts.open('media[]','<?php echo $post->ID ?>');return false;" href="#the-list"><?php _e('Re-Attach'); ?></a>
			<br />
			<a class="hide-if-no-js" href="" onclick="detachImage(<?php echo $post->ID; ?>,1); return false;">Detach</a>

			<?php
		} else {
			?>
			<?php _e('(Unattached)'); ?><br />
			<a class="hide-if-no-js" onclick="findPosts.open('media[]','<?php echo $post->ID ?>');return false;" href="#the-list"><?php _e('Attach'); ?></a>
			<?php
		}

}

?>
