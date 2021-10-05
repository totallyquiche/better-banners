<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\Wp;

use TotallyQuiche\BetterBanners\Better_Banners;
use TotallyQuiche\BetterBanners\Hook_Registrar;

final class Better_Banners_Public {
    /**
     * The registrar which handles all hooks (actions & filters).
     *
     * @var Hook_Registrar
     */
    private Hook_Registrar $hook_registrar;

    /**
     * Enqueue styles and scripts on object instantiation.
     *
     * @param Hook_Registrar $hook_registrar
     *
     * @return void
     */
    public function __construct( Hook_Registrar $hook_registrar ) {
        $this->hook_registrar = $hook_registrar;

        $this->enqueue_styles();
        $this->enqueue_scripts();
        $this->register_hooks();

        $this->hook_registrar->add();
    }

    /**
     * Enqueue public styles.
     *
     * @return void
     */
    private function enqueue_styles() : void {
        wp_enqueue_style(
            Better_Banners::PLUGIN_PREFIX . '_public_css',
            plugin_dir_url( __FILE__ ) . '../assets/public.css'
        );
    }

    /**
     * Enqueue public scripts.
     *
     * @return void
     */
    private function enqueue_scripts() : void {
        wp_enqueue_script(
            Better_Banners::PLUGIN_PREFIX . '_public_js',
            plugin_dir_url( __FILE__ ) . '../assets/public.js',
            array('jquery')
        );

        $display_banners_using_javascript = get_option(
            Better_Banners::get_display_banners_using_javascript_option_name(),
            true
        );

        global $pagenow;

        if ( $pagenow !== 'wp-login.php' && $display_banners_using_javascript ) {
            wp_localize_script(
                Better_Banners::PLUGIN_PREFIX . '_public_js',
                'localizations',
                array(
                    'bannersHtml' => self::get_banners_html(),
                )
            );
        }
    }

    /**
     * Register public hooks with the registrar.
     *
     * @return void
     */
    private function register_hooks() : void {
        $this->hook_registrar->register(
            'action',
            'wp_body_open',
            new Wp_Body_Open_Action_Handler
        );
    }

    /**
     * Returns the HTML for all banners.
     *
     * @return string
     */
    public static function get_banners_html() : string {
        $posts = get_posts(
            array(
                'post_type'   => Better_Banners::get_banner_post_type_slug(),
                'post_status' => 'publish',
                'numberposts' => -1,
            )
        );

        $posts = apply_filters( Better_Banners::PLUGIN_PREFIX . '_display_banners_posts', $posts );

        $plugin_prefix = Better_Banners::PLUGIN_PREFIX;

        $html = '<div class="' . $plugin_prefix . '-banners-container">';

        foreach ( $posts as $post ) {
            $background_color = esc_attr(
                get_post_meta( $post->ID, $plugin_prefix . '_background_color' )[0] ?? Better_Banners::DEFAULT_BANNER_BACKGROUND_COLOR
            );

            $custom_inline_css = esc_attr(
                get_post_meta( $post->ID, $plugin_prefix . '_custom_inline_css' )[0]
            );

            $custom_inline_css_properties = explode(PHP_EOL, $custom_inline_css);

            foreach ($custom_inline_css_properties as &$custom_inline_css_property) {
                $custom_inline_css_property = rtrim(trim($custom_inline_css_property), ';');

                if ( $custom_inline_css_property ) {
                    $custom_inline_css_property = rtrim(trim($custom_inline_css_property), ';') . ' !important;';
                }
            }

            $custom_inline_css_all_banners = esc_attr(
                get_option( Better_Banners::get_custom_inline_css_all_banners_option_name() )
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