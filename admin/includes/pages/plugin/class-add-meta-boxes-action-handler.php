<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\Admin\Pages\Plugin;

use TotallyQuiche\BetterBanners\Hook_Handler;
use TotallyQuiche\BetterBanners\Better_Banners;

final class Add_Meta_Boxes_Action_Handler implements Hook_Handler {
    /**
     * Handle the add_meta_boxes action.
     *
     * @return void
     */
    public static function handle() : void {
        $banner_post_type_slug = Better_Banners::get_banner_post_type_slug();

        add_meta_box(
            "{$banner_post_type_slug}_meta_box",
            'Banner Settings',
            array(
                self::class,
                'render_settings_meta_box',
            ),
            $banner_post_type_slug
        );
    }

    /**
     * Render the settings meta box.
     *
     * @return void
     */
    public static function render_settings_meta_box() : void {
        $post_id = get_post()->ID;
		$plugin_prefix = Better_Banners::PLUGIN_PREFIX;

		$background_color = esc_attr(
			get_post_meta( $post_id, $plugin_prefix . '_background_color' )[0]
                ?? Better_Banners::DEFAULT_BANNER_BACKGROUND_COLOR
		);

		$custom_inline_css = esc_html (
			get_post_meta( $post_id, $plugin_prefix . '_custom_inline_css' )[0]
		);

        require_once( __DIR__ . '/../../partials/settings-meta-box.php' );
    }
}