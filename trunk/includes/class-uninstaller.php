<?php

declare(strict_types=1);

namespace TotallyQuiche\BetterBanners;

final class Uninstaller
{
    /**
     * Handle plugin uninstallation.
     *
     * @return void
     */
    public function uninstall(): void
    {
        $this->delete_posts();
        $this->delete_posts_meta();
        $this->delete_options();
    }

    /**
     * Delete all Better Banners posts.
     *
     * @return void
     */
    private function delete_posts(): void
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'posts';
        $post_type = Better_Banners::get_banner_post_type_slug();

        $wpdb->query("DELETE FROM `$table_name` WHERE `post_type` = \"$post_type\";");
    }

    /**
     * Delete all Better Banners post meta data.
     *
     * @return void
     */
    private function delete_posts_meta(): void
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'postmeta';
        $plugin_prefix = Better_Banners::PLUGIN_PREFIX;

        $meta_keys = [
            $plugin_prefix . '_background_color',
            $plugin_prefix . '_custom_inline_css',
        ];

        foreach ($meta_keys as $meta_key) {
            $wpdb->query("DELETE FROM `$table_name` WHERE `meta_key` = \"$meta_key\";");
        }
    }

    /**
     * Delete all Better Banners options.
     *
     * @return void
     */
    private function delete_options(): void
    {
        $option_names = [
            Better_Banners::get_display_banners_using_javascript_option_name(),
            Better_Banners::get_custom_inline_css_all_banners_option_name(),
        ];

        foreach ($option_names as $option_name) {
            delete_option($option_name);
        }
    }
}
