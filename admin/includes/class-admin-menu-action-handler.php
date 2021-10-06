<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\Admin;

use TotallyQuiche\BetterBanners\Hook_Handler;
use TotallyQuiche\BetterBanners\Better_Banners;

final class Admin_Menu_Action_Handler implements Hook_Handler {
    /**
     * Handle the admin_menu action.
     *
     * @mixed ...$args
     *
     * @return void
     */
    public static function handle( ...$args ) : void {
		add_submenu_page(
			'edit.php?post_type=' . Better_Banners::get_banner_post_type_slug(),
			'Better Banners Options',
			'Options',
			'manage_options',
			Better_Banners::PLUGIN_PREFIX . '_options',
            array(
                self::class,
                'render_options_submenu_page'
            )
		);
    }

    /**
     * Render the submenu page.
     *
     * @return void
     */
    public static function render_options_submenu_page() : void {
        $plugin_prefix = Better_Banners::PLUGIN_PREFIX;
        $checked = get_option( Better_Banners::get_display_banners_using_javascript_option_name() ) ? 'checked="checked"' : '';
        $checkbox_name = Better_Banners::get_display_banners_using_javascript_option_name();
        $textarea_name = Better_Banners::get_custom_inline_css_all_banners_option_name();
        $submit_button_name = $plugin_prefix . '_options_form_submit_button';
        $custom_inline_css_all_banners = esc_textarea( stripslashes( get_option( Better_Banners::get_custom_inline_css_all_banners_option_name() ) ) );

        require_once( __DIR__ . '/partials/options-submenu-page.php' );
    }
}