<?php declare(strict_types=1);

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

require plugin_dir_path( __FILE__ ) . 'includes/class-better-banners.php';

global $wpdb;

$table_name = $wpdb->prefix . 'posts';
$post_type = TotallyQuiche\BetterBanners\Better_Banners::$custom_post_type_slug;

$wpdb->query("DELETE FROM `$table_name` WHERE `post_type` = \"$post_type\";");