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
		add_action( 'admin_menu', array( $this, 'setup_admin_page' ) );
		add_action( 'init', array( $this, 'register_category_taxonomy' ), 12 );
		add_action( 'cmb2_admin_init', array( $this, 'cmb2_metaboxes' ) );
	}

	public function setup_admin_page() {
		add_submenu_page(
			wpmu_suite()->core->main_menu_slug,
			__( 'Categories', 'wpmu-suite' ),
			__( 'Categories', 'wpmu-suite' ),
			'manage_network',
			'edit-tags.php?taxonomy=site_category'
		);

	}

	public function register_category_taxonomy() {
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'			  => _x( 'Categories', 'taxonomy general name', 'textdomain' ),
			'singular_name'	 => _x( 'Category', 'taxonomy singular name', 'textdomain' ),
			'search_items'	  => __( 'Search Categories', 'textdomain' ),
			'all_items'		 => __( 'All Categories', 'textdomain' ),
			'parent_item'	   => __( 'Parent Category', 'textdomain' ),
			'parent_item_colon' => __( 'Parent Category:', 'textdomain' ),
			'edit_item'		 => __( 'Edit Category', 'textdomain' ),
			'update_item'	   => __( 'Update Category', 'textdomain' ),
			'add_new_item'	  => __( 'Add New Category', 'textdomain' ),
			'new_item_name'	 => __( 'New Category Name', 'textdomain' ),
			'menu_name'		 => __( 'Category', 'textdomain' ),
		);

		$args = array(
			'hierarchical'	  => true,
			'labels'			=> $labels,
			'show_ui'		   => true,
			'show_admin_column' => true,
			'query_var'		 => true,
			'public' => false,
			'rewrite' => false,
		);

		register_taxonomy( 'site_category', 'null', $args );
	}
	/**
	 * Get Site Plugins
	 * @return array
	 */
	public function get_plugin_options() {
		$options = array();
		// Check if function exists.
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins = get_plugins();
		if ( ! empty( $all_plugins ) ) {
			foreach ( $all_plugins as $plugin_path => $plugin ) {
				// if plugin is wpmu suite then skip it.
				if ( 'wpmu-suite/wpmu-suite.php' == $plugin_path  ) {
					continue;
				}
				$options[ $plugin_path ] = $plugin['Name'];
			}
		}

		//error_log( print_r( $options, true ) );

		return $options;
	}
	/**
	 * Setup CMB2 Metabox
	 * @return void
	 */
	public function cmb2_metaboxes() {
		// Start with an underscore to hide fields from custom fields list
	    $prefix = '_yourprefix_';

	    /**
	     * Initiate the metabox
	     */
	    $cmb = new_cmb2_box( array(
	        'id'            => 'test_metabox',
	        'title'         => __( 'Test Metabox', 'cmb2' ),
	        'object_types'  => array( 'term' ), // Post type
			'taxonomies'       => array( 'site_category' ),
	        'context'       => 'normal',
	        'priority'      => 'high',
	        'show_names'    => true, // Show field names on the left
	        // 'cmb_styles' => false, // false to disable the CMB stylesheet
	        // 'closed'     => true, // Keep the metabox closed by default
	    ) );

	    // Regular text field
	    $cmb->add_field( array(
	        'name'       => __( 'Plugins allowed', 'cmb2' ),
	        'desc'       => __( 'Plugins allowed per site', 'cmb2' ),
	        'id'         => $prefix . 'allowed_plugins',
	        'type'       => 'multicheck',
			'options' => $this->get_plugin_options(),
	    ) );

	    // Add other metaboxes as needed
	}
}
