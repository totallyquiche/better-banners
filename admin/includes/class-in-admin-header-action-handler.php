<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\Admin;

use TotallyQuiche\BetterBanners\Hook_Handler;
use TotallyQuiche\BetterBanners\Better_Banners;

final class In_Admin_Header_Action_Handler implements Hook_Handler {
    /**
     * Handle the admin_menu action.
     *
     * @mixed ...$args
     *
     * @return void
     */
    public static function handle( ...$args ) : void {
        $plugin_prefix = Better_Banners::PLUGIN_PREFIX;
        $logo_image_url = Better_Banners_Admin::get_logo_image_url();

        require_once( __DIR__ . '/partials/logo-banner.php' );
    }
}