<?php

declare(strict_types=1);

namespace TotallyQuiche\BetterBanners\Admin;

use TotallyQuiche\BetterBanners\Hook_Handler;

final class Plugin_In_Admin_Footer_Action_Handler implements Hook_Handler
{
    /**
     * Handle the in_admin_footer action.
     *
     * @mixed ...$args
     *
     * @return void
     */
    public static function handle(...$args): void
    {
        require_once(__DIR__ . '/partials/admin-footer.php');

        echo apply_filters('render_admin_footer_message', $footer);
    }
}
