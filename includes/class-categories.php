<?php
/**
 * WPMU Suite Categories
 *
 * @since NEXT
 * @package WPMU Suite
 */

/**
 * WPMU Suite Categories.
 *
 * @since NEXT
 */
class WPMU_Categories {
	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {
		add_action( 'network_admin_menu', array( $this, 'setup_admin_page' ) );
	}

	public function setup_admin_page() {
		add_submenu_page(
	        wpmu_suite()->core->main_menu_slug,
	        __( 'Categories', 'wpmu-suite' ),
	        __( 'Categories', 'wpmu-suite' ),
	        'manage_network',
	        'categories',
	        array( $this, 'setup_page' )
		);
	}

	public function setup_page() {
		include_once( wpmu_suite()->path . 'templates/categories.php' );
	}
}
