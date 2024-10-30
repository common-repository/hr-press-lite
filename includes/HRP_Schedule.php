<?php
defined( 'ABSPATH' ) || die();

require_once HRP_PLUGIN_DIR . 'includes/HRP_Helper.php';

class HRP_Schedule {
	public static function holiday_notification() {
		self::send_holiday_notification();
	}

	public static function send_holiday_notification() {
		$holidays    = HRP_Helper::get_holidays_list();
		$send_before = HRP_Helper::get_email_template_settings()['holiday_email_days'];
		$today_date  = date( 'Y-m-d' );

		foreach ( $holidays as $holiday ) {
			$start_date       = $holiday->start_date;
			$subject          = $holiday->title;
			$body             = $holiday->description;
			$send_before_date = date( 'Y-m-d', strtotime( $start_date . " + $send_before days" ) );

			if ( $send_before_date === $today_date ) {
				$employees = HRP_Helper::get_data( HRP_Helper::get_employees() );

				foreach ( $employees as $employee ) {
					HRP_Helper::send_email_to_employee( $employee->ID, $subject, $body );
				}
			}
		}
	}
}
