<?php
/**
 * Created by Nabeel
 * Date: 2016-01-22
 * Time: 2:38 AM
 *
 * @package WP_Plugins\Boilerplate
 */

use Cyber_Smarties\User_Star_Rating\Plugin;
use Cyber_Smarties\User_Star_Rating\Rewards;

if ( !function_exists('user_star_rating') ):
	/**
	 * Get plugin instance
	 *
	 * @return Plugin
	 */
	function user_star_rating()
	{
		return Plugin::get_instance();
	}
endif;

if ( !function_exists('usr_rewards') ):
	/**
	 * Get Rewards instance
	 *
	 * @return Rewards
	 */
	function usr_rewards()
	{
		return user_star_rating()->rewards;
	}
endif;

if ( !function_exists( 'usr_view' ) ):
	/**
	 * Load view
	 *
	 * @param string $view_name
	 * @param array  $args
	 *
	 * @return void
	 */
	function usr_view( $view_name, $args = null )
	{
		user_star_rating()->load_view( $view_name, $args );
	}
endif;

if ( !function_exists( 'usr_version' ) ):
	/**
	 * Get plugin version
	 *
	 * @return string
	 */
	function usr_version()
	{
		return user_star_rating()->version;
	}
endif;