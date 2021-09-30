<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\Admin\Pages\Plugin;

use TotallyQuiche\BetterBanners\Hook_Handler;

final class In_Admin_Footer_Action_Handler implements Hook_Handler {
    /**
     * Handle the in_admin_footer action.
     *
     * @return void
     */
    public static function handle() : void {
        require_once( __DIR__ . '/../../partials/admin-footer.php' );
    }
}