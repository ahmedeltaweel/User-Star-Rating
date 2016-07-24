<?php namespace Cyber_Smarties\User_Star_Rating;

/**
 * Base Component
 *
 * @package WP_Plugins\Boilerplate
 */
class Component extends Singular
{
	/**
	 * Plugin Main Component
	 *
	 * @var Plugin
	 */
	protected $plugin;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init()
	{
		// vars
		$this->plugin = Plugin::get_instance();
	}
}
