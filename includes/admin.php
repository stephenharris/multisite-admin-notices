<?php
class MSAN_Admin_Page{

	var $hook;
	var $title;
	var $menu;
	var $permissions;
	var $slug;
	var $page;

	static $instance;

	/**
	 * Singleton model
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Construct the controller and listen for form submission
	 */
	public function __construct() {

		//Singletons!
		if ( !is_null( self::$instance ) )
			trigger_error( "Tried to construct a second instance of class \"$class\"", E_USER_WARNING );
	
		$this->hooks();
	}
	
	function hooks(){
		add_action( 'network_admin_menu', array( $this, 'add_page'   ) );
		add_action( 'init',               array( $this, 'set_values' ) );
	}

	function set_values(){

		$this->title       = "Admin Notices";
		$this->menu        = "Admin Notices";
		$this->permissions = "manage_network";
		$this->slug        = "msan-admin";
	}

	function add_page(){
		$this->page = add_submenu_page( 'settings.php', $this->title, $this->menu, $this->permissions, $this->slug, array( $this,'render_page' ), false, 3 );
		add_action( 'load-'.$this->page, array( $this, 'page_actions' ),   9  );
	}


	function page_actions(){
		
		_msan_update_notices_cache();
		wp_localize_script( 'msan-notice-manager', 'msan', array(
			'notices' => array_values( msan_get_notices() ),
			'url'     => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'msan-manage-notices' ),
		));
		wp_enqueue_script( 'msan-notice-manager' );
		wp_enqueue_style( 'msan-notice-manager' );
		
		?>
		<script type="text/template" id="tmpl-msan-notice">
			<div class="msan-notice-message"> <%- message %></div>
			<div class="msan-notice-toolbar">
				<span class="msan-notice-updated"> <%- last_update %> </span>
				<% if( typeof id != 'undefined' ){ %><span class="msan-notice-id"> #<%- id %> </span><% } %>
				<a href="#" class="msan-delete-notice"><?php esc_html_e( 'Delete', 'multisite-admin-notices' ) ; ?></a>
				<a href="#" class="msan-edit-notice"><?php esc_html_e( 'Edit', 'multisite-admin-notices' ) ; ?></a>
			</div>
		</script>
		
		<script type="text/template" id="tmpl-msan-notice-edit">
			<div class="msan-notice-message">
				<textarea><%- message %></textarea>
			</div>
			<div class="msan-notice-toolbar">
				<span class="msan-notice-updated"> <%- last_update %> </span>
				<% if( typeof id != 'undefined' ){ %><span class="msan-notice-id"> #<%- id %> </span><% } %>
				<a href="#" class="msan-cancel-update"><?php esc_html_e( 'Cancel', 'multisite-admin-notices' ) ; ?></a>
				<a href="#" class="msan-update-notice"><?php esc_html_e( 'Update', 'multisite-admin-notices' ) ; ?></a>
			</div>
		</script><?php 
	}

	function render_page(){
	
		$post_type_object = get_post_type_object( MSAN_NOTICE_CPT );
		?>
		
		<div class="wrap">
		
			<h2><?php echo esc_html( $post_type_object->labels->all_items ); ?></h2>
		
			<div id="msan-notices" class="msan-wrap">	
				<div class="msan-publish-new-notice">
					<textarea placeholder="<?php esc_attr_e( 'Enter your message here...', 'multisite-admin-notices' );?>"></textarea>
					<?php submit_button( __( 'Publish notice', 'multisite-admin-notices' ), 'primary', 'submit', false ); ?>
				</div>
			
				<div style="clear:both"></div>
				
				<ul class="msan-notices">
			
				</ul>
				
			</div>
			
		</div>
		<?php
	}
	
}
$msan_admin_page = new MSAN_Admin_Page();