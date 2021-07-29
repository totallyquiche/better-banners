<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\ActionHandlers;

use TotallyQuiche\BetterBanners\ActionHandlers\Action_Handler;
use TotallyQuiche\BetterBanners\Better_Banners;

final class Init_Handler implements Action_Handler {
    /**
     * Handle the action.
     *
     * @return void
     */
    public function handle() : void {
		$this->add_options();
		$this->register_post_type();
		$this->handle_post();
    }

	/**
	 * Add all options.
	 *
	 * @return void
	 */
	private function add_options() : void {
		$option_name = Better_Banners::get_display_banners_using_javascript_option_slug();

		if ( get_option( $option_name ) === false ) {
			add_option(
				$option_name,
				true
			);
		}

		$option_name = Better_Banners::get_custom_inline_css_all_banners_option_slug();

		if ( get_option( $option_name ) === false ) {
			add_option(
				$option_name
			);
		}
	}

	/**
	 * Register the custom post type.
	 *
	 * @return void
	 */
	private function register_post_type() : void {
		register_post_type(
			Better_Banners::get_banner_post_type_slug(),
			array(
				'description' => 'A Better Banners banner.',
				'public'      => true,
				'menu_icon'   => 'dashicons-megaphone',
				'rewrite'     => false,
				'labels'      => array(
					'name'                  => 'Better Banners',
					'singular_name'         => 'Better Banner',
					'add_new_item'          => 'Add New Better Banner',
					'edit_item'             => 'Edit Better Banner',
					'new_item'              => 'New Better Banner',
					'view_item'             => 'View Better Banner',
					'view_items'            => 'View Better Banners',
					'search_items'          => 'Search Better Banners',
					'not_found'             => 'No Better Banners found',
					'not_found_in_trash'    => 'No Better Banners found in Trash',
					'all_items'             => 'All Better Banners',
					'insert_into_item'      => 'Insert into Better Banner',
					'uploaded_to_this_item' => 'Uploaded to this Better Banner',
					'filter_items_list'     => 'Filter Better Banners list',
					'items_list_navigation' => 'Better Banners list navigation',
					'items_list'            => 'Better Banners list',
					'item_published'        => 'Better Banner published',
					'item_updated'          => 'Better Banner updated',
					'item_link'             => 'Better Banner Link',
					'item_link_description' => 'A link to a Better Banner',
				),
			)
		);
	}

	/**
	 * Handle form posts.
	 *
	 * @return void
	 */
	private function handle_post() : void {
		if ( isset( $_POST['post_ID'] ) && isset( $_POST[Better_Banners::$plugin_prefix . '-background-color'] ) ) {
			wp_update_post(
				array(
					'ID'         => intval( $_POST['post_ID'] ),
					'meta_input' => array(
						Better_Banners::$plugin_prefix . '_background_color' => sanitize_hex_color( $_POST[Better_Banners::$plugin_prefix . '-background-color'] ),
					),
				)
			);
		}

		if (
			isset( $_POST[ Better_Banners::$plugin_prefix . '_options_form_submit_button' ] ) &&
			isset( $_POST[ Better_Banners::get_display_banners_using_javascript_option_slug() ] )
		) {
			update_option(
				Better_Banners::get_display_banners_using_javascript_option_slug(),
				true
			);
		} elseif ( isset( $_POST[ Better_Banners::$plugin_prefix . '_options_form_submit_button' ] ) ) {
			update_option(
				Better_Banners::get_display_banners_using_javascript_option_slug(),
				false
			);
		}

		if (
			isset( $_POST[ Better_Banners::$plugin_prefix . '_options_form_submit_button' ] ) &&
			isset( $_POST[ Better_Banners::get_custom_inline_css_all_banners_option_slug() ] )
		) {
			update_option(
				Better_Banners::get_custom_inline_css_all_banners_option_slug(),
				sanitize_option(
					Better_Banners::get_custom_inline_css_all_banners_option_slug(),
					$_POST[ Better_Banners::get_custom_inline_css_all_banners_option_slug() ]
				)
			);
		}

		if ( isset( $_POST['post_ID'] ) && isset( $_POST[ Better_Banners::$plugin_prefix . '-custom-inline-css' ] ) ) {
			wp_update_post(
				array(
					'ID'         => intval( $_POST['post_ID'] ),
					'meta_input' => array(
						Better_Banners::$plugin_prefix . '_custom_inline_css' => esc_html( $_POST[ Better_Banners::$plugin_prefix . '-custom-inline-css' ] ),
					),
				)
			);
		}
	}
}