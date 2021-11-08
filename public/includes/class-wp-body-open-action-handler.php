<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\Wp;

use TotallyQuiche\BetterBanners\Hook_Handler;
use TotallyQuiche\BetterBanners\Better_Banners;

final class Wp_Body_Open_Action_Handler implements Hook_Handler {
	/**
	 * Handle the wp_body_open action.
	 *
	 * @mixed ...$args
	 *
	 * @return void
	 */
	public static function handle( ...$args ) : void {
		self::display_banners();
	}

	/**
	 * Echo out the HTML string representing all of the banners.
	 *
	 * @return void
	 */
	private static function display_banners() : void {
		$display_banners_using_javascript = get_option(
			Better_Banners::get_display_banners_using_javascript_option_name(),
			true
		);

		if ( $pagenow !== 'wp-login.php' && ! $display_banners_using_javascript ) {
			require_once( __DIR__ . '/partials/banners.php' );
		}
	}
}