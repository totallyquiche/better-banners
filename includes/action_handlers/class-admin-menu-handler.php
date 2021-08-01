<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\ActionHandlers;

use TotallyQuiche\BetterBanners\Better_Banners;
use TotallyQuiche\BetterBanners\ActionHandlers\Action_Handler;

final class Admin_Menu_Handler implements Action_Handler {
	/**
	 * Handle the action.
	 *
	 * @return void
	 */
	public function handle() : void {
		add_submenu_page(
			'edit.php?post_type=' . Better_Banners::get_banner_post_type_slug(),
			'Better Banners Options',
			'Options',
			'manage_options',
			Better_Banners::$plugin_prefix . '_options',
			function () {
				$plugin_prefix = Better_Banners::$plugin_prefix;
				$checked = get_option( Better_Banners::get_display_banners_using_javascript_option_slug() ) ? 'checked="checked"' : '';
				$checkbox_name = Better_Banners::get_display_banners_using_javascript_option_slug();
				$textarea_name = Better_Banners::get_custom_inline_css_all_banners_option_slug();
				$submit_button_name = Better_Banners::$plugin_prefix . '_options_form_submit_button';
				$custom_inline_css_all_banners = esc_textarea( stripslashes( get_option( Better_Banners::get_custom_inline_css_all_banners_option_slug() ) ) );

				echo <<<HTML
<h1>Better Banners</h1>
<hr />
<h2>Options</h2>
<form method="post" action="{$_SERVER['REQUEST_URI']}" id="{$plugin_prefix}-options-form">
	<label for="{$checkbox_name}"><b>Display banners using JavaScript</b></label>
	<input type="checkbox" name="{$checkbox_name}" {$checked} />
	<br /><br />
	<span>
		<i>
			Some plugins or themes may prevent Better Banners from displaying properly.
			If your banners are not showing up, try toggling this option.
		</i>
	</span>
	<br /><br />
	<label for="{$textarea_name}"><b>Custom Inline CSS</b></label>
	<br />
	<textarea cols="40" rows="5" id="{$plugin_prefix}-custom-inline-css-all-banners" name="{$textarea_name}" form="{$plugin_prefix}-options-form">{$custom_inline_css_all_banners}</textarea>
	<br /><br />
	<label for="{$plugin_prefix}-custom-inline-css-all-banners-example"><b>Example</b></label>
	<br />
	<textarea cols="40" rows="5" id="{$plugin_prefix}-custom-inline-css-all-banners-example" disabled="disabled">border: 1px solid black;\nfont-style: italic;\nfont-weight: 700;</textarea>
	<br /><br />
	<span>
		<i>
			CSS declarations entered above will be applied to all banners.
		</i>
	</span>
	<br /><br />
	<button type="submit" name="{$submit_button_name}" />Save</button>
</form>
HTML;
			}
		);
	}
}