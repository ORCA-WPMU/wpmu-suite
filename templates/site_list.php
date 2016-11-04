<?php
	require_once( wpmu_suite()->path . 'includes/class-wp-ms-sites-list-table.php' );
	//Create an instance of our package class...
    $wp_list_table = new WPMU_MS_Sites_List_Table();
	$pagenum = $wp_list_table->get_pagenum();
    //Fetch, prepare, sort, and filter our data...
    $wp_list_table->prepare_items();
	//print_r( $wp_list_table );
	$msg = '';
?>
<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?>
		<?php if ( current_user_can( 'create_sites') ) : ?>
			<a href="<?php echo network_admin_url('site-new.php'); ?>" class="page-title-action"><?php echo esc_html_x( 'Add New', 'site' ); ?></a>
		<?php endif; ?>
	</h2>
	<?php
	if ( isset( $_REQUEST['s'] ) && strlen( $_REQUEST['s'] ) ) {
		/* translators: %s: search keywords */
		printf( '<span class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' ) . '</span>', esc_html( $s ) );
	} ?>
	</h1>

	<?php echo $msg; ?>
	<form method="get" id="ms-search">
	<?php $wp_list_table->search_box( __( 'Search Sites' ), 'site' ); ?>
	<input type="hidden" name="action" value="blogs" />
	</form>

	<form id="form-site-list" action="sites.php?action=allblogs" method="post">
		<?php $wp_list_table->display(); ?>
	</form>
	</div>
</div>
