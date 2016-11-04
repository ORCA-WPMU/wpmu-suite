<?php
/**
 * WPMU Suite Core
 *
 * @since NEXT
 * @package WPMU Suite
 */

/**
 * WPMU Suite Core.
 *
 * @since NEXT
 */
class WPMU_Core {
	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Main Menu Slug
	 *
	 * @var   class
	 * @since NEXT
	 */
	public $main_menu_slug = null;

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->main_menu_slug = 'wpmu-suite';
		$this->hooks();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {
		add_action( 'network_admin_menu', array( $this, 'setup_admin_menu' ) );
	}

	/**
	 * Setup network admin menu
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function setup_admin_menu() {
		add_menu_page(
			__( 'WPMU Suite', 'wpmu-suite' ),
			__( 'WPMU Suite', 'wpmu-suite' ),
			'manage_network',
			$this->main_menu_slug,
			array( $this, 'setup_main_page' )
		);
	}
	/**
	 * Setup main page
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function setup_main_page() {
		echo 345678;
	}
}
