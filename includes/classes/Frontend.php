<?php namespace Cyber_Smarties\User_Star_Rating;

use CyberSmarties\USER_STAR_RATING\Helpers;

/**
 * Frontend logic
 *
 * @package WP_Plugins\Boilerplate
 */
class Frontend extends Component
{
	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init()
	{
		parent::init();
		add_filter( 'body_class', [ $this, 'sr_multisite_body_classes' ] );
		add_action( 'bp_setup_nav', [ $this, 'sr_setup_nav_reward' ] );
		add_filter( 'bp_notifications_get_notifications_for_user', [ $this, 'sr_custom_notify_format', 10, 5 ] );
		add_filter( 'bp_notifications_get_registered_components', [ $this, 'sr_register_custom_notification_component' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		add_filter( 'bp_before_member_header_meta', [ $this, 'sr_reward_icon_profile' ] );

	}

	/**
	 * enqueue scripts.
	 *
	 * @return void
	 */
	public function enqueue()
	{
		if ( !bp_is_current_component( 'rewards' ) )
		{
			//return if not reward page
			return;
		}

		$load_path = USR_URI . ( Helpers::is_script_debugging() ? 'assets/src/' : 'assets/dist/' );

		wp_enqueue_script( 'sr_ajax-script', $load_path . 'js/sr_save_reward_hendler.js', [ 'jquery' ], usr_version(), true ); // jQuery will be included automatically
		wp_localize_script( 'sr_ajax-script', 'sr_ajax_object', [
			'sr_ajaxurl' => admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
		] ); // setting ajaxurl
	}

	/**
	 * setting up nav by adding new tab 'reward'
	 *
	 * @return void
	 */
	public function sr_setup_nav_reward()
	{

		if ( !bp_has_members() )
		{
			// return if no members exists
			return;
		}

		if ( !usr_rewards()->is_principal_or_teacher( bbp_get_current_user_id() ) )
		{
			// return if not teacher or principal
			return;
		}

		// adding reward tab to nav menu
		bp_core_new_nav_item(
			[
				'name'                    => __( 'Reward', USR_DOMAIN ),
				'slug'                    => 'rewards',
				'position'                => 300,
				'show_for_displayed_user' => false,
				'screen_function'         => [ $this, 'sr_add_reward' ],
				'default_subnav_slug'     => 'reward',
				'item_css_id'             => 'reward',
			]
		);
	}

	/**
	 * adding the views of tab content
	 *
	 * @return void
	 */
	public function sr_add_reward()
	{
		add_action( 'bp_template_title', [ $this, 'sr_rewards_title' ] );
		add_action( 'bp_template_content', [ $this, 'sr_rewards_content' ] );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	/**
	 * adding custom class to body
	 *
	 * @param $classes
	 *
	 * @return array
	 */
	public function sr_multisite_body_classes( $classes )
	{
		if ( bp_is_current_component( 'rewards' ) )
		{
			$classes[] = 'notifications ';
		}

		return $classes;
	}

	/**
	 * show the content of new tab
	 *
	 * @return void
	 */
	public function sr_rewards_content()
	{
		$students          = usr_rewards()->get_user_students( bbp_get_current_user_id() );
		$students_per_page = 10;
		$page_args         = 'bpage';
		$this->plugin->load_view( 'userRating/starRatingview', [
			'sr_bp_hasmembers' => bp_has_members( bp_ajax_querystring( 'members' ) . "page_arg=$page_args&per_page=$students_per_page&include=" . implode( ',', $students ) ),
		] );
	}

	/**
	 * show the title of new tab
	 *
	 * @return void
	 */
	public function sr_rewards_title()
	{
		$this->plugin->load_view( 'userRating/starRatingTitle' );
	}

	/**
	 * register custom notification component
	 *
	 * @param array $component_names
	 *
	 * @return array
	 */
	public function sr_register_custom_notification_component( $component_names = [ ] )
	{
		if ( !is_array( $component_names ) )
		{
			$component_names = [ ];
		}
		// Add 'sr_custom_notify' component to registered components array
		array_push( $component_names, 'sr_custom_notify' );

		return $component_names;
	}

	/**
	 * custom buddypress notification format
	 *
	 * @param        $action
	 * @param        $item_id
	 * @param        $secondary_item_id
	 * @param        $total_items
	 * @param string $format
	 *
	 * @return void | string custom text of notification
	 */
	public function sr_custom_notify_format( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' )
	{
		// New custom notifications
		$custom_text = '';
		if ( 'sr_reward' !== $action )
		{
			return;
		}
		if ( get_user_meta( $item_id, 'sr_reward', true ) == 'gold' )
		{
			$custom_text = get_option( 'sr_gold_message_option' );
		}
		if ( get_user_meta( $item_id, 'sr_reward', true ) == 'silver' )
		{
			$custom_text = get_option( 'sr_silver_message_option' );
		}

		//echo $custom_text;

		return $custom_text;
	}

	/**
	 * adding view for showing icon to profile page if user has a star reward
	 *
	 * @return void
	 */
	function sr_reward_icon_profile()
	{
		$this->plugin->load_view( 'userRating/starRatingProfile', [
			'sr_user_reward_image' => get_user_meta( bp_displayed_user_id(), 'sr_reward', true ),
			'sr_gold_image'        => get_option( 'sr_gold_image_option' ),
			'sr_silver_image'      => get_option( 'sr_silver_image_option' ),
		] );
	}
}
