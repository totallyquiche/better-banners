<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\ActionHandlers;

use TotallyQuiche\BetterBanners\Better_Banners;
use TotallyQuiche\BetterBanners\ActionHandlers\Action_Handler;

final class Add_Meta_Boxes_Handler implements Action_Handler {
	/**
	 * Handle the action.
	 *
	 * @return void
	 */
	public function handle() : void {
		if ( Better_Banners::is_current_page_banner_page() ) {
			$this->add_settings_meta_box();
		}
	}

	/**
	 * Add the Settings meta boxes.
	 *
	 * @return void
	 */
	private function add_settings_meta_box() : void {
		$custom_post_type_slug = Better_Banners::get_banner_post_type_slug();

		add_meta_box(
			"{$custom_post_type_slug}_meta_box",
			'Better Banners Settings',
			array(
				$this,
				'render_settings_meta_box',
			),
			$custom_post_type_slug
		);
	}

	/**
	 * Render the Settings meta box on the custom post type page.
	 *
	 * @return void
	 */
	public function render_settings_meta_box() : void {
		$post_id = get_post()->ID;
		$plugin_prefix = Better_Banners::$plugin_prefix;

		$background_color = esc_attr(
			get_post_meta( $post_id, $plugin_prefix . '_background_color' )[0] ?? Better_Banners::$default_banner_background_color
		);

		$custom_inline_css = esc_html (
			get_post_meta( $post_id, $plugin_prefix . '_custom_inline_css' )[0]
		);

		echo <<<HTML
<div id="{$plugin_prefix}-color-picker-container">
	<label for="{$plugin_prefix}-background-color">Background Color</label>
	<input role="button" id="{$plugin_prefix}-background-color" class="{$plugin_prefix}-color-picker" type="text" value="{$background_color}" />
</div>
<br />
<div id="{$plugin_prefix}-custom-inline-css-container">
	<label for="{$plugin_prefix}-custom-inline-css">Custom Inline CSS</label>
	<br />
	<textarea rows="5" id="{$plugin_prefix}-custom-inline-css" name="{$plugin_prefix}-custom-inline-css" form="post">{$custom_inline_css}</textarea>
</div>
HTML;
	}
}