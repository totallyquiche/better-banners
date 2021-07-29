<?php declare ( strict_types = 1 );

namespace TotallyQuiche\BetterBanners\ActionHandlers;

interface Action_Handler {
    /**
     * Handle the action.
     *
     * @return void
     */
    public function handle() : void;
}