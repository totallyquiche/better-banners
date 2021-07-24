<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners;

/**
 * Plugin Name: Better Banners
 * Plugin URI: https://better-banners.briandady.com
 * Description: Create and customize banners to display at the top of your website.
 * Version: 1.0.0
 * Requires at least: 4
 * Requires PHP: 7.4
 * Author: Brian N. Dady <bndady@gmail.com>
 * Author URI: https://briandady.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: better-banners
 * Domain Path: /languages
 */

require_once plugin_dir_path( __FILE__ ) . 'includes/class-better-banners.php';

( new Better_Banners )->run();