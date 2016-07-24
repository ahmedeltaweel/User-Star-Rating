<?php namespace Cyber_Smarties\User_Star_Rating;


/**
 * AJAX handler
 *
 * @package WP_Plugins\Boilerplate
 */
class Ajax_Handler extends Component
{
	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init()
	{
		parent::init();
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		{
			$action = filter_var( isset( $_REQUEST[ 'action' ] ) ? $_REQUEST[ 'action' ] : '', FILTER_SANITIZE_STRING );
			if ( method_exists( $this, $action ) )
			{
				// hook into action if it's method exists
				add_action( 'wp_ajax_' . $action, [ &$this, $action ] );
			}
		}
	}

	/**
	 * AJAX Debug response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function debug( $data )
	{
		// return dump
		$this->error( $data );
	}

	/**
	 * AJAX Debug response ( dump )
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $args
	 *
	 * @return void
	 */
	public function dump( $args )
	{
		// return dump
		$this->error( print_r( func_num_args() === 1 ? $args : func_get_args(), true ) );
	}

	/**
	 * AJAX Error response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function error( $data )
	{
		wp_send_json_error( $data );
	}

	/**
	 * AJAX success response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function success( $data )
	{
		wp_send_json_success( $data );
	}

	/**
	 * AJAX JSON Response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $response
	 *
	 * @return void
	 */
	public function response( $response )
	{
		// send response
		wp_send_json( $response );
	}

	/**
	 * Handling ajax request and adding rewards to selected users.
	 *
	 * @return void
	 */
	public function sr_update_reward()
	{
		if ( !usr_rewards()->is_principal_or_teacher( bbp_get_current_user_id() ) )
		{
			// error, permission denied
			$this->error( __( 'Have no permission', USR_DOMAIN ) );
		}

		$updates = filter_input( INPUT_POST, 'data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		if ( false === $updates || null === $updates )
		{
			// error for invalid data passed
			$this->error( __( 'Invalid data', USR_DOMAIN ) );
		}

		$updates = array_filter( array_map( function ( $item )
		{
			if ( !is_array( $item ) )
			{
				// skip if not array
				return false;
			}

			if ( !isset( $item[ 'name' ] ) || !isset( $item[ 'value' ] ) )
			{
				// skip if data incorrect
				return false;
			}

			if ( !in_array( $item[ 'value' ], [ 'gold', 'silver', 'none' ] ) )
			{
				// skip for invalid award
				return false;
			}

			$item[ 'name' ] = absint( $item[ 'name' ] );
			if ( 0 === $item[ 'name' ] )
			{
				// skip for invalid user ID
				return false;
			}

			return $item;
		}, $updates ) );

		$current_time = current_time( 'mysql' );
		$new_dates    = [ ];
		$students     = usr_rewards()->get_user_students( bbp_get_current_user_id() );
		foreach ( $updates as $user_update )
		{
			if ( false === get_user_by( 'id', $user_update[ 'name' ] ) )
			{
				//checking for existence of user
				continue;
			}

			if ( !in_array( $user_update[ 'name' ], $students ) )
			{
				//checking for user as student in current groups
				continue;
			}

			// set new reward
			$update_time = usr_rewards()->update_user_reward( $user_update[ 'name' ], $user_update[ 'value' ], $current_time );

			if ( $user_update[ 'value' ] !== 'none' )
			{
				// inserting notification if not none
				usr_rewards()->send_notification( $user_update[ 'name' ] );
			}

			//getting new dates if updated
			if ( $update_time )
			{
				// inserting new dates for updated users
				$new_dates[] = [ 'sr_id' => $user_update[ 'name' ], 'sr_time' => $current_time ];
			}
		}

		//return success with new dates
		$this->success( $new_dates );
	}
}
