<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\ActionHandlers;

use TotallyQuiche\BetterBanners\Better_Banners;
use TotallyQuiche\BetterBanners\ActionHandlers\Action_Handler;

final class Wp_Body_Open_Handler implements Action_Handler {
    /**
     * Handle the action.
     *
     * @return void
     */
    public function handle() : void {
		if ( ! get_option( Better_Banners::get_display_banners_using_javascript_option_slug(), true ) ) {
			$this->display_banners();
		}
    }

	/**
	 * Display the banners via wp_body_open.
	 *
	 * @return void
	 */
	private function display_banners() : void {
		echo Better_Banners::get_banners_html();
	}
}