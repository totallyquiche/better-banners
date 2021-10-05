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
        $class_map = array(
            // Common classes
            'TotallyQuiche\BetterBanners\Better_Banners'
                => 'class-better-banners.php',
            'TotallyQuiche\BetterBanners\Hook_Registrar'
                => 'class-hook-registrar.php',
            'TotallyQuiche\BetterBanners\Hook_Handler'
                => 'interface-hook-handler.php',
            // Public classes
            'TotallyQuiche\BetterBanners\Wp\Better_Banners_Public'
                => '../public/includes/class-better-banners-public.php',
            'TotallyQuiche\BetterBanners\Wp\Wp_Body_Open_Action_Handler'
                => '../public/includes/class-wp-body-open-action-handler.php',
            // Admin classes
            'TotallyQuiche\BetterBanners\Admin\Better_Banners_Admin'
                => '../admin/includes/class-better-banners-admin.php',
            'TotallyQuiche\BetterBanners\Admin\Init_Action_Handler'
                => '../admin/includes/class-init-action-handler.php',
            'TotallyQuiche\BetterBanners\Admin\Admin_Menu_Action_Handler'
                => '../admin/includes/class-admin-menu-action-handler.php',
            'TotallyQuiche\BetterBanners\Admin\Pages\Plugin\Add_Meta_Boxes_Action_Handler'
                => '../admin/includes/pages/plugin/class-add-meta-boxes-action-handler.php',
            'TotallyQuiche\BetterBanners\Admin\Pages\Plugin\In_Admin_Footer_Action_Handler'
                => '../admin/includes/pages/plugin/class-in-admin-footer-action-handler.php',
            'TotallyQuiche\BetterBanners\Admin\Pages\Options\Init_Action_Handler'
                => '../admin/includes/pages/options/class-init-action-handler.php',
        );

        if ( array_key_exists( $class_name, $class_map ) ) {
            require_once plugin_dir_path( __FILE__ ) . $class_map[ $class_name ];
        }
    }
}