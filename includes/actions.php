<?php

function msan_ajax_handler(){
	
	$notice_id = ( !empty( $_GET ) && !empty( $_GET['id'] ) ? (int) $_GET['id'] : 0 );

	check_ajax_referer( 'msan-manage-notices' );
	
	if( !current_user_can( 'manage_network' ) ){
		wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
	}
	
	if( $notice_id && get_post_type( $notice_id ) != MSAN_NOTICE_CPT ){
		wp_send_json_error( array( 'message' => 'Notice not found' ) );
	}
		
	//No notice ID, creating new:
	if( empty( $notice_id ) && 'POST' == $_SERVER['REQUEST_METHOD'] ){
		$payload = json_decode( file_get_contents('php://input'), true );
		$notice_id = msan_insert_notice( $payload );
		$notice = msan_get_notice( $notice_id );
		wp_die( json_encode( $notice ) );

	//Get
	}elseif( !empty( $notice_id ) && 'GET' == $_SERVER['REQUEST_METHOD'] ){
		$notice = msan_get_notice( $notice_id );
		wp_die( json_encode( $notice ) );

	//Delete
	}elseif( !empty( $notice_id ) && 'DELETE' == $_SERVER['REQUEST_METHOD'] ){
		msan_delete_notice( $notice_id );
		wp_send_json_success();
						
	//Update
	}elseif( !empty( $notice_id ) && 'PUT' == $_SERVER['REQUEST_METHOD'] ){
		$payload = json_decode( file_get_contents('php://input'), true );
		$notice_id = msan_update_notice( $notice_id, $payload );
		$notice = msan_get_notice( $notice_id );
		wp_die( json_encode( $notice ) );
		
	}else{
		wp_send_json_error( array( 'message' => 'Unknown error' ) );
	}
	
}
add_action( 'wp_ajax_msan-notice', 'msan_ajax_handler' );
