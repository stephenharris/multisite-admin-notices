<?php 

function msan_display_admin_notices(){
	
	/*$notice_id = msan_insert_notice( array(
		'message' => 'hello world!',
	));
	var_dump( $notice_id );*/
	
	$notices = msan_get_notices();

	
	if( $notices ){
		$notice_handler = Multsite_Admin_Notice_Handler::get_instance();
		foreach( $notices as $notice ){
			$notice_handler->add_notice( $notice['id'], false, $notice['message'] );
		}
	}
	
	
	
}
add_action( 'admin_init', 'msan_display_admin_notices' );
