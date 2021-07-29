<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\ActionHandlers;

use TotallyQuiche\BetterBanners\Better_Banners;
use TotallyQuiche\BetterBanners\ActionHandlers\Action_Handler;

final class In_Admin_Footer_Handler implements Action_Handler {
	/**
	 * Handle the action.
	 *
	 * @return void
	 */
	public function handle() : void {
		if ( Better_Banners::is_current_page_banner_page() ) {
			$this->render_admin_footer();
		}
	}

	/**
	 * Render the admin footer content.
	 *
	 * @return void
	 */
	private function render_admin_footer() : void {
		$message = <<<HTML
<span>
	Betters Banners was created with love by <a href="https://briandady.com" target="_BLANK" title="Brian Dady's website">Brian Dady</a>.
	&#129505;
</span>

<hr />
HTML;

		echo apply_filters( 'render_admin_footer_message', $message );
	}
}