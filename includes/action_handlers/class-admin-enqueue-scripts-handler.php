<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\ActionHandlers;

use TotallyQuiche\BetterBanners\Better_Banners;
use TotallyQuiche\BetterBanners\ActionHandlers\Action_Handler;

final class Admin_Enqueue_Scripts_Handler implements Action_Handler {
	/**
	 * Handle the action.
	 *
	 * @return void
	 */
	public function handle() : void {
		if ( Better_Banners::is_current_page_banner_page() ) {
			$this->enqueue_admin_styles();
			$this->enqueue_admin_scripts();
		}
	}

	/**
	 * Enqueue the admin scripts.
	 *
	 * @return void
	 */
	private function enqueue_admin_scripts() : void {
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_script(
			'better_banners_admin_js',
			plugin_dir_url( __FILE__ ) . '../../assets/js/admin.js',
			array(
				'jquery',
				'iris',
			)
		);
	}

	/**
	 * Enqueue the admin styles.
	 *
	 * @return void
	 */
	private function enqueue_admin_styles() : void {
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style(
			'better_banners_admin_css',
			plugin_dir_url( __FILE__ ) . '../../assets/css/admin.css'
		);
	}
}