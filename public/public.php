<?php
defined( 'ABSPATH' ) || die();
require_once HRP_PLUGIN_DIR . 'includes/constants.php';
require_once HRP_PLUGIN_DIR . 'public/inc/hrp_shortcode.php';
require_once HRP_PLUGIN_DIR . 'includes/HRP_Schedule.php';

// Load translation.
add_action( 'plugins_loaded', array( 'HRP_Shortcode', 'load_translation' ) );

if ( ! wp_next_scheduled( 'hrp_holiday_notification' ) ) {
	wp_schedule_event( time(), 'daily', 'hrp_holiday_notification' );
}
add_action( 'hrp_holiday_notification', array( 'HRP_Schedule', 'holiday_notification' ), 10, 3 );
