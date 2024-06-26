<?php

declare(strict_types=1);

namespace TotallyQuiche\BetterBanners;

require_once plugin_dir_path(__FILE__) . 'includes/class-autoloader.php';

spl_autoload_register([Autoloader::class, 'register']);
