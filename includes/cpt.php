<?php
/**
 * Registers the admin notice custom post type
 * Hooked onto init
 *
 * @ignore
 * @access private
 * @since 1.0
 */
function msan_register_admin_notices_cpt() {

  	$labels = array(
		'name'               => __( 'Admin Notices', 'multisite-admin-notices' ),
		'singular_name'      => __( 'Admin notice', 'multisite-admin-notices' ),
		'add_new'            => _x('Add New', 'post', 'multisite-admin-notices' ),
		'add_new_item'       => __( 'Add New Admin Notice', 'multisite-admin-notices' ),
		'edit_item'          => __( 'Edit Admin Notice', 'multisite-admin-notices' ),
		'new_item'           => __( 'New Admin Notice', 'multisite-admin-notices' ),
		'all_items'          => __( 'Admin Notices', 'multisite-admin-notices' ),
		'view_item'          => __( 'View Admin Notice', 'multisite-admin-notices' ),
		'search_items'       => __( 'Search admin notices', 'multisite-admin-notices' ),
		'not_found'          => __( 'No admin notices found', 'multisite-admin-notices' ),
		'not_found_in_trash' => __( 'No admin notices found in Trash', 'multisite-admin-notices' ),
		'parent_item_colon'  => '',
		'menu_name'          => __( 'Admin Notices', 'multisite-admin-notices' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => false,
		'publicly_queryable'  => false,
		'exclude_from_search' => true,
		'show_ui'             => false, 
		'show_in_menu'        => false, 
		'query_var'           => false,
		'capabilities'        => array(
			'publish_posts'       => 'manage_network',
			'edit_posts'          => 'manage_network',
			'edit_others_posts'   => 'manage_network',
			'delete_posts'        => 'manage_network',
			'delete_others_posts' => 'manage_network',
			'read_private_posts'  => 'manage_network',
			'edit_post'           => 'manage_network',
			'delete_post'         => 'manage_network',
			'read_post'           => 'manage_network',
		),
		'has_archive'         => false, 
		'hierarchical'        => false,
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
  	); 

	$args = apply_filters( 'msan_admin_notices_cpt', $args );
	register_post_type( MSAN_NOTICE_CPT, $args );
}
add_action( 'init', 'msan_register_admin_notices_cpt', 5 );
