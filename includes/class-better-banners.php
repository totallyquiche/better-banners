<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners;

final class Better_Banners {
	/**
	 * The universal prefix for this plugin.
	 *
	 * @var string
	 */
	public static string $plugin_prefix = 'tqbb01';

	/**
	 * The default background color for banners.
	 *
	 * @var string
	 */
	public static string $default_banner_background_color = '#00c9c2';

	/**
	 * Returns the slug for the banner post type.
	 *
	 * @return string
	 */
	public static function get_banner_post_type_slug() : string {
		return self::$plugin_prefix . '_banner';
	}

	/**
	 * Returns the slug for the option to display banners using JavaScript.
	 *
	 * @return string
	 */
	public static function get_display_banners_using_javascript_option_slug() : string {
		return self::$plugin_prefix . '_display_banners_using_javascript';
	}

	/**
	 * Returns the slug for the option to add inline CSS for all banners.
	 *
	 * @return string
	 */
	public static function get_custom_inline_css_all_banners_option_slug() : string {
		return self::$plugin_prefix . '_custom_inline_css_all_banners';
	}

	/**
	 * Run the plugin.
	 *
	 * @return void
	 */
	public function run() : void {
        add_action(
            'init',
            array(
                ( new \TotallyQuiche\BetterBanners\ActionHandlers\Init_Handler ),
                'handle'
            )
        );

        add_action(
            'admin_enqueue_scripts',
            array(
                ( new \TotallyQuiche\BetterBanners\ActionHandlers\Admin_Enqueue_Scripts_Handler ),
                'handle'
            )
        );

        add_action(
            'wp_enqueue_scripts',
            array(
                ( new \TotallyQuiche\BetterBanners\ActionHandlers\Wp_Enqueue_Scripts_Handler ),
                'handle'
            )
        );

        add_action(
            'admin_menu',
            array(
                ( new \TotallyQuiche\BetterBanners\ActionHandlers\Admin_Menu_Handler ),
                'handle'
            )
        );

        add_action(
            'wp_body_open',
            array(
                ( new \TotallyQuiche\BetterBanners\ActionHandlers\Wp_Body_Open_Handler ),
                'handle'
            )
        );

        add_action(
            'add_meta_boxes',
            array(
                ( new \TotallyQuiche\BetterBanners\ActionHandlers\Add_Meta_Boxes_Handler ),
                'handle'
            )
        );

        add_action(
            'in_admin_footer',
            array(
                ( new \TotallyQuiche\BetterBanners\ActionHandlers\In_Admin_Footer_Handler ),
                'handle'
            )
        );
	}

	/**
	 * Returns a boolean indicating whether the current page is a banner post page.
	 *
	 * @return bool
	 */
	public static function is_current_page_banner_page() : bool {
		static $is_current_page_banner_page;

		if ( ! isset( $is_current_page_banner_page ) ) {
			$is_current_page_banner_page = $_GET['post_type'] === self::get_banner_post_type_slug() ||
				get_post_type( $_GET['post'] ) === self::get_banner_post_type_slug();
		}

		return $is_current_page_banner_page;
	}

	/**
	 * Return the banners HTML.
	 *
	 * @return string
	 */
	public static function get_banners_html() : string {
		$posts = get_posts(
			array(
				'post_type'   => self::get_banner_post_type_slug(),
				'post_status' => 'publish',
				'numberposts' => -1,
			)
		);

		$plugin_prefix = self::$plugin_prefix;

		$posts = apply_filters(
			'better_banners_display_banners_posts',
			$posts
		);

		$html = '<div class="' . $plugin_prefix . '-banners-container">';

		foreach ( $posts as $post ) {
			$background_color = esc_attr(
				get_post_meta( $post->ID, self::$plugin_prefix . '_background_color' )[0] ?? self::$default_banner_background_color
			);

			$custom_inline_css = esc_attr(
				get_post_meta( $post->ID, self::$plugin_prefix . '_custom_inline_css' )[0]
			);

			$custom_inline_css_properties = explode(PHP_EOL, $custom_inline_css);

			foreach ($custom_inline_css_properties as &$custom_inline_css_property) {
				$custom_inline_css_property = rtrim(trim($custom_inline_css_property), ';');

				if ( $custom_inline_css_property ) {
					$custom_inline_css_property = rtrim(trim($custom_inline_css_property), ';') . ' !important;';
				}
			}

			$custom_inline_css_all_banners = esc_attr(
				get_option( self::get_custom_inline_css_all_banners_option_slug() )
			);

			$custom_inline_css_all_banners_properties = explode(PHP_EOL, $custom_inline_css_all_banners);

			foreach ($custom_inline_css_all_banners_properties as &$custom_inline_css_all_banners_property) {
				$custom_inline_css_all_banners_property = rtrim(trim($custom_inline_css_all_banners_property), ';');

				if ( $custom_inline_css_all_banners_property ) {
					$custom_inline_css_all_banners_property = rtrim(trim($custom_inline_css_all_banners_property), ';') . ' !important;';
				}
			}

			$style = 'background-color: ' . $background_color . ' !important;' . implode('', $custom_inline_css_properties) . implode('', $custom_inline_css_all_banners_properties);

			$html .= <<<HTML
<div class="{$plugin_prefix}-banner" style="{$style}" role="banner">
	{$post->post_content}
</div>
HTML;
		}

		$html .= '</div>';

		return $html;
	}
}