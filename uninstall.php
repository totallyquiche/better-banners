<?php declare(strict_types=1);

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

require plugin_dir_path( __FILE__ ) . 'includes/class-better-banners.php';

global $wpdb;

$post_table_name = $wpdb->prefix . 'posts';
$post_type = \TotallyQuiche\BetterBanners\Better_Banners::getBannerPostTypeSlug();

$wpdb->query( "DELETE FROM `$post_table_name` WHERE `post_type` = \"$post_type\";" );

$post_meta_table_name = $wpdb->prefix . 'postmeta';

$wpdb->query( "DELETE FROM `$post_meta_table_name` WHERE `meta_key` = \"tqbb01_background_color\";" );