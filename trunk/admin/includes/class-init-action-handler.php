<?php

declare(strict_types=1);

namespace TotallyQuiche\BetterBanners\Admin;

use TotallyQuiche\BetterBanners\Hook_Handler;
use TotallyQuiche\BetterBanners\Better_Banners;

final class Init_Action_Handler implements Hook_Handler
{
    /**
     * Handle the init action.
     *
     * @mixed ...$args
     *
     * @return void
     */
    public static function handle(...$args): void
    {
        self::handle_register_post_type();
        self::handle_post();
    }

    /**
     * Register the Banner post type.
     *
     * @return void
     */
    private static function handle_register_post_type(): void
    {
        register_post_type(
            Better_Banners::get_banner_post_type_slug(),
            [
                'description' => 'A Better Banners banner.',
                'public'      => true,
                'menu_icon'   => Better_Banners_Admin::get_logo_image_url(),
                'rewrite'     => false,
                'label'       => 'Better Banners',
                'labels'      => [
                    'name'                  => 'Better Banners',
                    'singular_name'         => 'Banner',
                    'add_new'               => 'Add New Banner',
                    'edit_item'             => 'Edit Banner',
                    'new_item'              => 'New Banner',
                    'view_item'             => 'View Banner',
                    'view_items'            => 'View Banners',
                    'search_items'          => 'Search Banners',
                    'not_found'             => 'No Banners found',
                    'not_found_in_trash'    => 'No Banners found in Trash',
                    'all_items'             => 'All Banners',
                    'insert_into_item'      => 'Insert into Banner',
                    'uploaded_to_this_item' => 'Uploaded to this Banner',
                    'filter_items_list'     => 'Filter Banners list',
                    'items_list_navigation' => 'Banners list navigation',
                    'items_list'            => 'Banners list',
                    'item_published'        => 'Banner published',
                    'item_updated'          => 'Banner updated',
                    'item_link'             => 'Banner link',
                    'item_link_description' => 'A link to a Banner',
                ],
            ]
        );
    }

    /**
     * Handle form posts.
     *
     * @return void
     */
    private static function handle_post(): void
    {
        $plugin_prefix = Better_Banners::PLUGIN_PREFIX;

        // Banner post Background Color
        if (
            isset($_POST['post_ID']) &&
            isset($_POST[$plugin_prefix . '-background-color'])
        ) {
            $post_id = intval($_POST['post_ID']);
            $background_color = $_POST[$plugin_prefix . '-background-color'];

            wp_update_post(
                [
                    'ID'         => $post_id,
                    'meta_input' => [$plugin_prefix . '_background_color' => sanitize_hex_color($background_color)],
                ]
            );
        }

        // Banner post Custom Inline CSS
        if (
            isset($_POST['post_ID']) &&
            isset($_POST[$plugin_prefix . '-custom-inline-css'])
        ) {
            $post_id = intval($_POST['post_ID']);
            $custom_inline_css = $_POST[$plugin_prefix . '-custom-inline-css'];

            wp_update_post(
                [
                    'ID'         => $post_id,
                    'meta_input' => [$plugin_prefix . '_custom_inline_css' => esc_html($custom_inline_css)],
                ]
            );
        }
    }
}
