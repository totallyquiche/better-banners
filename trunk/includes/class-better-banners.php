<?php

declare(strict_types=1);

namespace TotallyQuiche\BetterBanners;

use TotallyQuiche\BetterBanners\Admin\Better_Banners_Admin;
use TotallyQuiche\BetterBanners\Public\Better_Banners_Public;

final class Better_Banners
{
    /**
     * The global prefix for this plugin.
     *
     * @const string
     */
    public const PLUGIN_PREFIX = 'tqbb01';

    /**
     * The default background color to use for new banners.
     *
     * @var string
     */
    public const DEFAULT_BANNER_BACKGROUND_COLOR = '#00c9c2';

    /**
     * Run the plugin.
     *
     * @return void
     */
    public function run(): void
    {
        $hook_registrar = new Hook_Registrar;

        is_admin()
            ? (new Better_Banners_Admin($hook_registrar))
            : (new Better_Banners_Public($hook_registrar));
    }

    /**
     * Get the Banner post type slug.
     *
     * @return string
     */
    public static function get_banner_post_type_slug(): string
    {
        return self::PLUGIN_PREFIX . '_banner';
    }

    /**
     * Returns the slug for the "Display Banners using JavaScript" option.
     *
     * @return string
     */
    public static function get_display_banners_using_javascript_option_name(): string
    {
        return Better_Banners::PLUGIN_PREFIX . '_display_banners_using_javascript';
    }

    /**
     * Returns the slug for the "Custom Inline CSS" option.
     *
     * @return string
     */
    public static function get_custom_inline_css_all_banners_option_name(): string
    {
        return Better_Banners::PLUGIN_PREFIX . '_custom_inline_css_all_banners';
    }
}
