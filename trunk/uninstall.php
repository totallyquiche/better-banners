<?php

declare(strict_types=1);

namespace TotallyQuiche\BetterBanners;

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

require_once plugin_dir_path(__FILE__) . 'autoload.php';

(new Uninstaller)->uninstall();
