<?php declare ( strict_types = 1 );

namespace TotallyQuiche\BetterBanners;

interface Hook_Handler {
    /**
     * Main method responsible for handling the hook.
     *
     * @return void
     */
    public static function handle() : void;
}