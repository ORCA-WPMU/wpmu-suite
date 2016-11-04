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
	 * Categories
	 *
	 * @var   class
	 * @since NEXT
	 */
	public $categories = null;

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->taxonomy = 'site_category';
		//$this->get_categories();
		$this->hooks();
	}

	public function get_categories() {
		$terms = get_terms( array(
			'taxonomy' => $this->taxonomy,
			'hide_empty' => false,
		) );
		// array to store terms;
		$categories = array();
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				// store term id and name
				$categories[ $term->term_id ] = $term->name;
			}
		}
		// set categories.
		$this->categories = $categories;
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
		add_action( 'wp_ajax_wpmu_suite_set_category', array( $this, 'set_category' ), 12, 2 );
		add_filter( 'all_plugins', array( $this, 'alter_site_plugins' ), 12, 1 );
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

		register_taxonomy( $this->taxonomy, 'null', $args );
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

		return $options;
	}
	/**
	 * Setup CMB2 Metabox
	 * @return void
	 */
	public function cmb2_metaboxes() {
		// Start with an underscore to hide fields from custom fields list
		$prefix = '_site_';
		global $pagenow;

		//if ( 'edit-tags.php' != $pagenow ) {
			/**
			 * Initiate the metabox
			 */
			$cmb = new_cmb2_box( array(
				'id'			=> 'wpmu_suite_categories',
				'title'		 => __( 'Categories', 'wpmu-suite' ),
				'object_types'  => array( 'term' ), // Type
				'taxonomies'	   => array( $this->taxonomy ),
				'context'	   => 'normal',
				'priority'	  => 'high',
				'show_names'	=> true, // Show field names on the left
			) );

			// Multicheck field
			$cmb->add_field( array(
				'name'	   => __( 'Plugins allowed', 'wpmu-suite' ),
				'desc'	   => __( 'Plugins allowed per site', 'wpmu-suite' ),
				'id'		 => $prefix . 'allowed_plugins',
				'type'	   => 'multicheck',
				'options' => $this->get_plugin_options(),
			) );

		//}
	}

	public function set_category() {
		$blog_id 	= (int) $_POST['blog_id'];
		$category	= (int) $_POST['category'];
		$term_taxonomy_ids = wp_set_object_terms( $blog_id, $category, $this->taxonomy );
		if ( is_wp_error( $term_taxonomy_ids ) ) {
			wp_send_json_error();
		} else {
			wp_send_json_success();
		}
	}

	public function get_site_category( $blog_id = 0 ) {
		$args = array( 'fields' => 'ids' );
		$site_category = wp_get_object_terms( $blog_id,  wpmu_suite()->categories->taxonomy, $args );
		if ( ! is_wp_error( $site_category ) ) {
			if ( ! empty( $site_category ) ) {
				$site_category = $site_category[0];
			}
		}
		restore_current_blog();
		return $site_category;
	}

	public function get_site_selected_plugins( $site_category_id = 0 ) {
		$plugins = get_term_meta( $site_category_id, '_site_allowed_plugins' );
		print_r( $plugins );
		print_r( $site_category_id );
	}
	public function alter_site_plugins( $plugins = array() ) {
		if ( is_main_site() ) {
			return $plugins;
		}
		$blog_id 		= get_current_blog_id();
		global $current_site;
		switch_to_blog( $current_site->blog_id );
		$site_category 	= $this->get_site_category( $blog_id );
		if ( $site_category ) {
			$plugins 	= $this->get_site_selected_plugins( $site_category );
		}
		restore_current_blog();
		return $plugins;
	}
}
