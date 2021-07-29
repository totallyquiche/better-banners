<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners;

final class Autoloader {
    /**
     * Register the autoloader method.
     *
     * @param string $class_name
     *
     * @return void
     */
    public static function register( string $class_name ) : void {
        $plugin_dir_path = plugin_dir_path( __FILE__ );

        $class_map = array(
            'TotallyQuiche\BetterBanners\Better_Banners'                               => $plugin_dir_path . 'includes/class-better-banners.php',
            'TotallyQuiche\BetterBanners\ActionHandlers\Action_Handler'                => $plugin_dir_path . 'includes/action_handlers/interface-action-handler.php',
			'TotallyQuiche\BetterBanners\ActionHandlers\Init_Handler'                  => $plugin_dir_path . 'includes/action_handlers/class-init-handler.php',
			'TotallyQuiche\BetterBanners\ActionHandlers\Admin_Enqueue_Scripts_Handler' => $plugin_dir_path . 'includes/action_handlers/class-admin-enqueue-scripts-handler.php',
			'TotallyQuiche\BetterBanners\ActionHandlers\Wp_Enqueue_Scripts_Handler'    => $plugin_dir_path . 'includes/action_handlers/class-wp-enqueue-scripts-handler.php',
			'TotallyQuiche\BetterBanners\ActionHandlers\Admin_Menu_Handler'            => $plugin_dir_path . 'includes/action_handlers/class-admin-menu-handler.php',
			'TotallyQuiche\BetterBanners\ActionHandlers\Wp_Body_Open_Handler'          => $plugin_dir_path . 'includes/action_handlers/class-wp-body-open-handler.php',
			'TotallyQuiche\BetterBanners\ActionHandlers\Add_Meta_Boxes_Handler'        => $plugin_dir_path . 'includes/action_handlers/class-add-meta-boxes-handler.php',
			'TotallyQuiche\BetterBanners\ActionHandlers\In_Admin_Footer_Handler'       => $plugin_dir_path . 'includes/action_handlers/class-in-admin-footer-handler.php',
        );

        if ( array_key_exists( $class_name, $class_map ) ) {
            require_once $class_map[ $class_name ];
        }
    }
}