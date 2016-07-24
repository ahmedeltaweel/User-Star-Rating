<?php namespace Cyber_Smarties\User_Star_Rating;
use CyberSmarties\USER_STAR_RATING\Helpers;

/**
 * Backend logic
 *
 * @package CyberSmarties\US_STAR_RATING
 */
class Backend extends Component
{
	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init()
	{
		parent::init();
		add_action('admin_init', array($this, 'sr_general_section'));
		add_action('admin_enqueue_scripts', array($this, 'enqueues'));
	}

	/**
	 * adding plugin settings section in general settings page
	 * @return void
	 */
	public function sr_general_section()
	{
		add_settings_section(
			'starRating_options_section',
			'Star Rating Options',
			array($this, 'sr_section_function'),
			'general'
		);

		add_settings_field(
			'sr_gold_message_option',
			'Gold Star Notification',
			array($this, 'sr_gold_options_text_function'),
			'general',
			'starRating_options_section'
		);

		add_settings_field(
			'sr_gold_image_option',
			'Gold Image',
			array($this, 'sr_options_gold_image_function'),
			'general',
			'starRating_options_section'
		);

		add_settings_field(
			'sr_silver_message_option',
			'Silver Star Notification',
			array($this, 'sr_silver_options_text_function'),
			'general',
			'starRating_options_section'
		);

		add_settings_field(
			'sr_silver_image_option',
			'Silver Image',
			array($this, 'sr_options_silver_image_function'),
			'general',
			'starRating_options_section'
		);

		register_setting('general', 'sr_gold_message_option', 'sanitize_text_field');
		register_setting('general', 'sr_silver_message_option', 'sanitize_text_field');
		register_setting('general', 'sr_gold_image_option', 'esc_url_raw');
		register_setting('general', 'sr_silver_image_option', 'esc_url_raw');
	}

	/**
	 * adding title for settings section
	 * @return void
	 */
	public function sr_section_function()
	{
		echo '<p>Add the message and images</p>';
	}

	/**
	 * adding field of gold image url
	 * @return void
	 */
	public function sr_gold_options_text_function()
	{
		echo '<input type="text" id="sr_gold_message_option" name="sr_gold_message_option" class="large-text ltr" value="' . esc_attr(get_option('sr_gold_message_option')) . '" />';
	}

	/**
	 * adding field of silver image url
	 * @return void
	 */
	public function sr_silver_options_text_function()
	{
		echo '<input type="text" id="sr_silver_message_option" name="sr_silver_message_option" class="large-text ltr" value="' . esc_attr(get_option('sr_silver_message_option')) . '" />';
	}

	/**
	 * adding button for choosing gold image from media library
	 * @return void
	 */
	public function sr_options_gold_image_function()
	{
		echo '<div>';
		echo '<input type="text" name="sr_gold_image_option" id="sr_gold_image_option" class="regular-text ltr" value="' . esc_attr(get_option('sr_gold_image_option')) . '" />';
		echo '<input type="button" name="gold-upload-btn" id="gold-upload-btn" class="button" value="Upload Image"/>';
		echo '</div>';
	}

	/**
	 * adding button for choosing silver image from media library
	 * @return void
	 */
	public function sr_options_silver_image_function()
	{
		echo '<div>';
		echo '<input type="text" name="sr_silver_image_option" id="sr_silver_image_option" class="regular-text ltr" value="' . esc_attr(get_option('sr_silver_image_option')) . '" />';
		echo '<input type="button" name="silver-upload-btn" id="silver-upload-btn" class="button" value="Upload Image"/>';
		echo '</div>';
	}

	/**
	 * Load JS & CSS assets
	 *
	 * @return void
	 */
	function enqueues( $current_page )
	{
		if ( 'options-general.php' !== $current_page )
		{
            // skip non-related pages
            return;
		}

        // load path prefix
        $load_path = USR_URI . (Helpers::is_script_debugging() ? 'assets/src/' : 'assets/dist/');

        // media dependencies
        wp_enqueue_media();
        wp_enqueue_script('script_media', $load_path . 'js/sr_media_script.js', array('jquery'), usr_version(), true);
	}
}
