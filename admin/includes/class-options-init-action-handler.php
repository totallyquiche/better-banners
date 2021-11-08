<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\Admin;

use TotallyQuiche\BetterBanners\Hook_Handler;
use TotallyQuiche\BetterBanners\Better_Banners;

final class Options_Init_Action_Handler implements Hook_Handler {
	/**
	 * Handle the init action.
	 *
	 * @mixed ...$args
	 *
	 * @return void
	 */
	public static function handle( ...$args ) : void {
		self::add_options();
		self::handle_post();
	}

	/**
	 * Add all options.
	 *
	 * @return void
	 */
	private static function add_options() : void {
		// Add "Display Banners using JavaScript" option

		$option_name = Better_Banners::get_display_banners_using_javascript_option_name();

		if ( get_option( $option_name ) === false ) {
			add_option(
				$option_name,
				true
			);
		}

		// Add "Custom Inline CSS" option

		$option_name = Better_Banners::get_custom_inline_css_all_banners_option_name();

		if ( get_option( $option_name ) === false ) {
			add_option( $option_name );
		}
	}

	/**
	 * Handle form posts.
	 *
	 * @return void
	 */
	private static function handle_post() : void {
		$plugin_prefix = Better_Banners::PLUGIN_PREFIX;
		$options_form_submit_button_name = $plugin_prefix . '_options_form_submit_button';
		$get_display_banners_using_javascript_option_name = Better_Banners::get_display_banners_using_javascript_option_name();

		// Options page Display Banners using JavaScript checkbox
		if (
			isset( $_POST[ $options_form_submit_button_name ] ) &&
			isset( $_POST[ $get_display_banners_using_javascript_option_name ] )
		) {
			update_option(
				$get_display_banners_using_javascript_option_name,
				true
			);
		} elseif ( isset( $_POST[ $options_form_submit_button_name ] ) ) {
			update_option(
				$get_display_banners_using_javascript_option_name,
				false
			);
		}

		// Options page Custom Inline CSS (all banners) textarea
		if (
			isset( $_POST[ $options_form_submit_button_name ] ) &&
			isset( $_POST[ Better_Banners::get_custom_inline_css_all_banners_option_name() ] )
		) {
			update_option(
				Better_Banners::get_custom_inline_css_all_banners_option_name(),
				sanitize_option(
					Better_Banners::get_custom_inline_css_all_banners_option_name(),
					$_POST[ Better_Banners::get_custom_inline_css_all_banners_option_name() ]
				)
			);
		}
	}
}