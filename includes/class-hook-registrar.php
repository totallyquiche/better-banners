<?php declare( strict_types = 1 );

namespace TotallyQuiche\BetterBanners;

final class Hook_Registrar {
	/**
	 * Contains all of the registered hooks.
	 *
	 * @var array
	 */
	private array $hooks = array();

	/**
	 * Register a hook to the registrar.
	 *
	 * @param string   $hook_type
	 * @param string   $hook_name
	 * @param Callable $callable
	 * @param int      $priority
	 * @param int      $accepted_args
	 *
	 * @return void
	 */
	public function register(
		string       $hook_type,
		string       $hook_name,
		Hook_Handler $hook_handler,
		int          $priority = 10,
		int          $accepted_args = 1
	) : void {
		$callable = array(
			$hook_handler,
			'handle',
		);

		$this->hooks[] = array(
			'type'          => $hook_type,
			'name'          => $hook_name,
			'callable'      => $callable,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);
	}

	/**
	 * Add all hooks in the registry to the application.
	 *
	 * @return void
	 */
	public function add() : void {
		foreach ( $this->hooks as $hook ) {
			switch ( array_shift( $hook ) ) {
				case 'action':
					add_action( ...array_values($hook) );
					break;
				case 'filter':
					add_filter( ...array_values($hook) );
					break;
			}
		}
	}
}