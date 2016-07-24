<?php namespace Cyber_Smarties\User_Star_Rating;

use BP_Groups_Member;

/**
 * Rewards logic
 *
 * @package WP_Plugins\Boilerplate
 */
class Rewards extends Component
{
	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init()
	{
		parent::init();
	}

	/**
	 * Update user reward status
	 *
	 * @param int         $user_id
	 * @param string      $reward
	 * @param string|null $update_time
	 *
	 * @return bool
	 */
	public function update_user_reward( $user_id, $reward, $update_time = null )
	{
		$old_reward = get_user_meta( $user_id, 'sr_reward', true );

		if ( empty($old_reward) && $reward === 'none' )
		{
			// check if first use and none is the first option
			return false;
		}

		if ( null === $update_time )
		{
			// getting and setting current time for mysql
			$update_time = current_time( 'mysql' );
		}

		//check to see if new reward.
		if ( $old_reward != $reward )
		{
			// update
			update_user_meta( $user_id, 'sr_reward', $reward );
			update_user_meta( $user_id, 'sr_last_action_date', $update_time );

			return true;
		}

		return false;
	}

	/**
	 * add the notification for newly rewarded users.
	 *
	 * @param int $user_id
	 *
	 * @return void
	 */
	public function send_notification( $user_id )
	{
		//check for notification activeness
		if ( !bp_is_active( 'notifications' ) )
		{
			return;
		}

		// send notification 
		bp_notifications_add_notification( [
			'user_id'           => $user_id,
			'item_id'           => $user_id,
			'secondary_item_id' => get_current_user_id(),
			'component_name'    => 'sr_custom_notify',
			'component_action'  => 'sr_reward',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
			'allow_duplicate'   => true,
		] );
	}

	/**
	 * check if user is teacher of principal
	 *
	 * @param int $user_id
	 *
	 * @return bool
	 */
	public function is_principal_or_teacher( $user_id )
	{
		$current_user_roles = get_userdata( $user_id )->roles;

		return in_array( 'principal', $current_user_roles ) || in_array( 'teacher', $current_user_roles );
	}

	/**
	 * return ids for students in current user groups
	 *
	 * @param int $user_id
	 *
	 * @return array of students ids
	 */
	public function get_user_students( $user_id )
	{
		//getting user groups
		$students    = [ ];
		$user_groups = groups_get_user_groups( $user_id );

		foreach ( $user_groups[ 'groups' ] as $group_id )
		{
			$members = BP_Groups_Member::get_group_member_ids( $group_id );

			foreach ( $members as $member )
			{
				//getting and checking for roles 
				$user_roles = get_userdata( $member )->roles;
				if ( in_array( 'student', $user_roles ) )
				{
					//array of ids
					$students[] = $member;
				}
			}
		}

		return $students;
	}
}
