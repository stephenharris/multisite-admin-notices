<?php
/**
 * Returns an array of notices, indexed by notice (post) ID. 
 * Each notice is an array of the form:
 * * id      - (int)    (post ID) ID of the notice (in main blog post table)
 * * message - (string) (post content) The notice content   
 */
function msan_get_notices(){
	
	$notices = get_site_transient( 'msan_notices' );
	
	if( false === $notices ){		
		$notices = _msan_update_notices_cache();
	}
	
	apply_filters( 'msan_get_notices', $notices );
	
	return $notices;
}

/**
 * Create a notice, currently the only support attribute in $args is 'message'    
 */
function msan_insert_notice( $args ){
	
	$original_blog_id = get_current_blog_id();
	$main_blog_id = BLOG_ID_CURRENT_SITE;
	
	//Maybe switch to blog
	if( $original_blog_id != $main_blog_id ){
		switch_to_blog( $main_blog_id );
	}
	
	//Set the 'post_content' value
	if( isset( $args['message'] ) ){
		$args['post_content'] = $args['message'];
		unset( $args['message'] );
	}
	
	//Pre-set values
	$args = wp_parse_args( array(
		'post_type'   => MSAN_NOTICE_CPT,
		'post_status' => 'publish'
	), $args );
	
	
	//insert
	$notice_id = wp_insert_post( $args );
	_msan_update_notices_cache();
	
	//Make sure original blog is restored
	if( $original_blog_id != $main_blog_id ){
		restore_current_blog();
	}
	
	return $notice_id;
}


/**
 * Create a notice, currently the only support attribute in $args is 'message'    
 */
function msan_update_notice( $notice_id, $args ){
	
	$original_blog_id = get_current_blog_id();
	$main_blog_id = BLOG_ID_CURRENT_SITE;
	
	//Maybe switch to blog
	if( $original_blog_id != $main_blog_id ){
		switch_to_blog( $main_blog_id );
	}
	
	//Set the 'post_content' value
	if( isset( $args['message'] ) ){
		$args['post_content'] = $args['message'];
		unset( $args['message'] );
	}
	
	//Pre-set values
	$args = wp_parse_args( array(
		'ID'          => $notice_id,
		'post_type'   => MSAN_NOTICE_CPT,
		'post_status' => 'publish'
	), $args );
	
	//Update
	$notice_id = wp_update_post( $args );
	_msan_update_notices_cache();
	
	//Make sure original blog is restored
	if( $original_blog_id != $main_blog_id ){
		restore_current_blog();
	}
	
	return $notice_id;
}


/**
 * Create a notice, currently the only support attribute in $args is 'message'    
 */
function msan_delete_notice( $notice_id ){
	
	$original_blog_id = get_current_blog_id();
	$main_blog_id = BLOG_ID_CURRENT_SITE;
	
	if( get_post_type( $notice_id ) != MSAN_NOTICE_CPT ){
		return false;
	}
	
	//Maybe switch to blog
	if( $original_blog_id != $main_blog_id ){
		switch_to_blog( $main_blog_id );
	}
	
	wp_delete_post( $notice_id, true ); // Force delete
	_msan_update_notices_cache();
	
	//Make sure original blog is restored
	if( $original_blog_id != $main_blog_id ){
		restore_current_blog();
	}
	
	return true;
}

function msan_get_notice( $notice_id ){
	
	$original_blog_id = get_current_blog_id();
	$main_blog_id = BLOG_ID_CURRENT_SITE;
	
	//Maybe switch to blog
	if( $original_blog_id != $main_blog_id ){
		switch_to_blog( $main_blog_id );
	}
	
	if( is_a( $notice_id, 'WP_Post' ) ){
		$notice = $notice_id;
		$notice_id = $notice->ID;
	}else{
		$notice = get_post( $notice_id );
	}
		
	if( get_post_type( $notice_id ) != MSAN_NOTICE_CPT ){
		$_notice = false;
	}else{
		$_notice = array(
			'id'           => $notice->ID,
			'message'      => $notice->post_content,
			'last_updated' => $notice->post_modified_gmt,
		);
	}

	//Make sure original blog is restored
	if( $original_blog_id != $main_blog_id ){
		restore_current_blog();
	}
	
	return $_notice;
}



function _msan_update_notices_cache(){
	
	$original_blog_id = get_current_blog_id();
	$main_blog_id = BLOG_ID_CURRENT_SITE;
	
	if( $original_blog_id != $main_blog_id ){
		switch_to_blog( $main_blog_id );
	}
	
	$notice_posts = get_posts( array(
		'post_type' => MSAN_NOTICE_CPT,
		'orderby'   => 'modified',
		'order'     => 'asc',
	));
	
	$notices = array();
	
	if( $notice_posts ){
		foreach( $notice_posts as $notice_post ){
			$notices[$notice_post->ID] = msan_get_notice( $notice_post ); 
		}
	}
	
	set_site_transient( 'msan_notices', $notices );
	
	if( $original_blog_id != $main_blog_id ){
		restore_current_blog();
	}
	
	return $notices;
}