<?php declare(strict_types=1);

namespace TotallyQuiche\BetterBanners;

final class Better_Banners
{
    /**
     * Run the plugin.
     *
     * @return void
     */
    public function run() : void {
        $this->init();
        $this->wp_enqueue_scripts();

        if (
            ($post_type = $_GET['post_type']) === 'better_banners_post' ||
            get_post_type($_GET['post']) === 'better_banners_post'
        ) {
            $this->admin_enqueue_scripts();
        }

        $this->wp_body_open();
    }

    /**
     * Add init actions.
     *
     * @return void
     */
    private function init() : void {
        add_action(
            'init',
            array(
                $this,
                'register_post_type',
            )
        );
    }

    /**
     * Add wp_body_open actions.
     *
     * @return void
     */
    private function wp_body_open() : void {
        add_action(
            'wp_body_open',
            array(
                $this,
                'display_banners',
            )
        );
    }

    /**
     * Display the banners.
     *
     * @return void
     */
    public function display_banners() : void {
        $posts = get_posts(
            array(
                'post_type' => 'better_banners_post',
                'post_status' => 'publish',
                'numberposts' => -1,
            )
        );

        echo '<div class="better-banners">';

        foreach ( $posts as $post ) {
            echo '<div class="better-banners-banner">';
            echo $post->post_content;
            echo '</div>';
        }

        echo '</div>';
    }

    /**
     * Add the wp_enqueue_scripts actions.
     *
     * @return void
     */
    public function wp_enqueue_scripts() : void {
        add_action(
            'wp_enqueue_scripts',
            array(
                $this,
                'enqueue_styles'
            )
        );
    }

    /**
     * Enqueue the styles.
     *
     * @return void
     */
    public function enqueue_styles() : void {
        wp_enqueue_style(
            'better_banners_styles',
           plugin_dir_url(__FILE__) . '../assets/css/public.css'
        );
    }

    /**
     * Enqueue admin scripts/styles.
     *
     * @return void
     */
    public function admin_enqueue_scripts() : void {
        add_action(
            'admin_enqueue_scripts',
            array(
                $this,
                'enqueue_admin_styles',
            )
        );
    }

    /**
     * Enqueue admin styles.
     *
     * @return void
     */
    public function enqueue_admin_styles() : void {
        wp_enqueue_style(
            'better_banners_admin_styles',
            plugin_dir_url(__FILE__) . '../assets/css/admin.css'
        );
    }

    /**
     * Register the custom post type.
     *
     * @return void
     */
    public function register_post_type() : void {
        register_post_type(
            'better_banners_post',
            array(
                'description' => 'A Better Banners banner.',
                'public' => true,
                'menu_icon' => 'dashicons-megaphone',
                'rewrite' => false,
                'labels' => array(
                    'name' => 'Better Banners',
                    'singular_name' => 'Better Banner',
                    'add_new_item' => 'Add New Better Banner',
                ),
            )
        );
    }
}