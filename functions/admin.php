<?php
/*==================================================
	Add Sub Menu Page
================================================== */
add_action( 'admin_menu', 'cfs_p2p_menu' );
function cfs_p2p_menu() {
	// $page_hook_suffix = add_options_page('CFS Post 2 Post', 'CFS Post 2 Post', 8, 'cfs_p2p_menu', 'cfs_p2p_options_page');
	if ( false === apply_filters( 'cfs_disable_admin', false ) ) {
		$page_hook_suffix = add_submenu_page( 'edit.php?post_type=cfs', 'CFS Post 2 Post', 'CFS Post 2 Post', 'manage_options', 'cfs_p2p_menu', 'cfs_p2p_options_page' );
	}
	add_action( 'admin_print_styles-' . $page_hook_suffix, 'cfs_p2p_admin_styles' );
	add_action( 'admin_print_scripts-' . $page_hook_suffix, 'cfs_p2p_admin_scripts' );
	add_action( 'admin_init', 'register_cfs_p2p_settings' );
}

/**
 * admin_menu
 */
function admin_menu() {
	if ( false === apply_filters( 'cfs_disable_admin', false ) ) {
		add_submenu_page( 'edit.php?post_type=cfs', __( 'Tools', 'cfs' ), __( 'Tools', 'cfs' ), 'manage_options', 'cfs-tools', array( $this, 'page_tools' ) );
	}

	// Remove the "Add New" menu link
	global $submenu;
	unset( $submenu['edit.php?post_type=cfs'][10] );
}


function cfs_p2p_admin_styles() {
	wp_enqueue_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css', array() );
	wp_enqueue_style( 'admin-options', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/css/admin-options.css', array() );
}
function cfs_p2p_admin_scripts() {
	wp_enqueue_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'script', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/script.js', array( 'select2' ) );
}
function register_cfs_p2p_settings() {
	register_setting( 'cfs_p2p-settings-group', 'cfs_p2p_overwrite_type' );
}
function cfs_p2p_options_page() {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/index.php';
}

/**
 * Order the sub-menu
 */
function cfs_p2p_submenu_order( $menu_ord ) {
	global $submenu;

	foreach ( $submenu as $key => $menus ) {
		if ( 'edit.php?post_type=cfs' === $key ) {
			// var_dump($menus);
			foreach ( $menus as $subkey => $menu ) {
				if ( 11 === $subkey ) {
					$cfs_post2post_menu = array( $subkey => $menu );
				}
			}
			unset( $menus[11] );
			$menus      += $cfs_post2post_menu;
			$orderedmenu = $menus;
		}
	}
	$submenu['edit.php?post_type=cfs'] = $orderedmenu;
	// var_dump($submenu);

	return $submenu;
}
add_filter( 'custom_menu_order', 'cfs_p2p_submenu_order' );

/*==================================================
	Add CSS to edit.php
================================================== */
// if (is_admin()) {
//     global $pagenow;
//     if (is_admin() && $pagenow == 'edit.php') {
//         wp_enqueue_style('admin-edit', plugin_dir_url(dirname(__FILE__)) . 'admin/css/admin-edit.css', array());
//     }
// }
