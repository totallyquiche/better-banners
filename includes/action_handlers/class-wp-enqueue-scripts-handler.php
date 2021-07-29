<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\ActionHandlers;

use TotallyQuiche\BetterBanners\Better_Banners;
use TotallyQuiche\BetterBanners\ActionHandlers\Action_Handler;

final class Wp_Enqueue_Scripts_Handler implements Action_Handler {
	/**
	 * Handle the action.
	 *
	 * @return void
	 */
	public function handle() : void {
		$this->enqueue_styles();
		$this->enqueue_scripts();
	}

	/**
	 * Enqueue the public scripts.
	 *
	 * @return void
	 */
	private function enqueue_scripts() : void {
		wp_enqueue_script(
			'better_banners_js',
			plugin_dir_url( __FILE__ ) . '../../assets/js/public.js',
			array( 'jquery' )
		);

		wp_localize_script(
			'better_banners_js',
			'localizations',
			array(
				'displayBannersUsingJavaScript' =>
					get_option( Better_Banners::get_display_banners_using_javascript_option_slug(), true ),
				'bannersHtml' =>
					Better_Banners::get_banners_html(),
			)
		);
	}

	/**
	 * Enqueue the public styles.
	 *
	 * @return void
	 */
	private function enqueue_styles() : void {
		wp_enqueue_style(
			'better_banners_styles',
			plugin_dir_url( __FILE__ ) . '../../assets/css/public.css'
		);
	}
}