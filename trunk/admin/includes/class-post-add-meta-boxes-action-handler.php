<?php

declare(strict_types=1);

namespace TotallyQuiche\BetterBanners\Admin;

use TotallyQuiche\BetterBanners\Hook_Handler;
use TotallyQuiche\BetterBanners\Better_Banners;

final class Post_Add_Meta_Boxes_Action_Handler implements Hook_Handler
{
    /**
     * Handle the add_meta_boxes action.
     *
     * @mixed ...$args
     *
     * @return void
     */
    public static function handle(...$args): void
    {
        $banner_post_type_slug = Better_Banners::get_banner_post_type_slug();

        add_meta_box(
            "{$banner_post_type_slug}_settings_meta_box",
            'Banner Settings',
            [self::class, 'render_settings_meta_box'],
            $banner_post_type_slug
        );
    }

    /**
     * Render the settings meta box.
     *
     * @return void
     */
    public static function render_settings_meta_box(): void
    {
        $post_id = get_post()->ID;
        $plugin_prefix = Better_Banners::PLUGIN_PREFIX;

        $existing_background_color = get_post_meta($post_id, $plugin_prefix . '_background_color');

        $background_color = esc_attr(
            isset($existing_background_color[0]) ? $existing_background_color[0] : Better_Banners::DEFAULT_BANNER_BACKGROUND_COLOR
        );

        $existing_custom_inline_css = get_post_meta($post_id, $plugin_prefix . '_custom_inline_css');

        $custom_inline_css = esc_html(
            isset($existing_custom_inline_css[0]) ? $existing_custom_inline_css[0] : ''
        );

        require_once(__DIR__ . '/partials/settings-meta-box.php');
    }
}
