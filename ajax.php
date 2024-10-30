<?php
include "../../../wp_load.php";
if ( $_GET['detachImage'] ) {
	if ( current_user_can( 'edit_post', $_GET['id']  ) ) {
		$my_post = array( 
			'ID' => $_GET['id'],
			'post_parent' => 0
		);
		if ( wp_update_post( $my_post ) ) echo '1';
	}
}
if ( $_GET['deleteImage'] ) {
	if ( current_user_can( 'delete_post', $_GET['id'] ) ) {
		if ( wp_delete_attachment( $_GET['id'] ) ) echo '1';
	}
}
?>
