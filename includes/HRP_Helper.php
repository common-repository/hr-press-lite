<?php
defined( 'ABSPATH' ) || die();
require_once HRP_PLUGIN_DIR . 'includes/constants.php';

class HRP_Helper {

	public static function get_page_url( $page ) {
		return admin_url( 'admin.php?page=' . $page );
	}

	public static function get_dashboard_url( $page ) {
		return admin_url( 'admin.php?page=hr_press&action=' . $page );
	}

	public static function time_format( $data ) {
		if ( $data !== '00:00:00' && ! is_null( $data ) ) {
			return $data = date( 'h:i A', strtotime( $data ) );
		} else {
			return esc_html( '--:--' );
		}
	}

	public static function date_default_format() {
		return 'm/d/Y';
	}

	public static function days_diff( $date_one, $date_two ) {
		$duration = ( new DateTime( $date_two ) )->diff( new DateTime( $date_one ) );
		$days     = self::days_to_text( $duration->days );
		return $days;
	}

	public static function days_to_text( $days ) {
		$days = $days + 1;
		if ( $days === 1 ) {
			return sprintf( esc_html( __( '%s Day', 'hrp' ) ), $days );
		} else {
			return sprintf( esc_html( __( '%s Days', 'hrp' ) ), $days );
		}
	}

	public static function date_format( $date ) {
		if ( $date ) {
			return date_format( date_create( $date ), self::date_default_format() );
		}
		return '';
	}

	public static function display_date( $date ) {
		if ( $date ) {
			$date_format = get_option( 'date_format' );
			return wp_date( $date_format, strtotime( $date ) );
		}
	}

	public static function get_start_to_end_years( $start_year, $end_year ) {
		if ( empty( $start_year ) && empty( $end_year ) ) {
			$years = array();
			$year  = date( 'Y' );
			array_push( $years, $year );
			return $years;
		}
		$start_year = DateTime::createFromFormat( 'Y-m-d', $start_year );
		$start_year = $start_year->format( 'Y' );
		$end_year   = DateTime::createFromFormat( 'Y-m-d', $end_year );
		$end_year   = $end_year->format( 'Y' );
		$years      = array();
		for ( $i = intval( $start_year ); $i <= intval( $end_year ); $i++ ) {
			array_push( $years, $i );
		}
		return $years;
	}

	public static function get_status( $status ) {
		if ( $status === 'active' ) {
			return '<span class="tb-status text-success">' . esc_html( ucwords( $status ) ) . '</span>';
		} elseif ( $status === 'inactive' ) {
			return '<span class="tb-status text-warning">' . esc_html( ucwords( $status ) ) . '</span>';
		} elseif ( $status === 'terminated' ) {
			return '<span class="tb-status text-danger">' . esc_html( ucwords( $status ) ) . '</span>';
		} elseif ( $status === 'deceased' ) {
			return '<span class="tb-status text-gray">' . esc_html( ucwords( $status ) ) . '</span>';
		} elseif ( $status === 'resigned' ) {
			return '<span class="tb-status text-info">' . esc_html( ucwords( $status ) ) . '</span>';
		}
	}

	public static function get_data_count( $data ) {
		global $wpdb;
		$data = $wpdb->get_var( $data );
		return $data;
	}

	public static function get_data( $data ) {
		global $wpdb;
		$data = $wpdb->get_results( $data );
		return $data;
	}

	public static function holidays() {
		 return array(
			 'monday'    => 'Monday',
			 'tuesday'   => 'Tuesday',
			 'wednesday' => 'Wednesday',
			 'thursday'  => 'Thursday',
			 'friday'    => 'Friday',
			 'saturday'  => 'Saturday',
			 'sunday'    => 'Sunday',
		 );
	}

	public static function employee_type_list() {
		return array(
			'full_time'   => 'Full Time',
			'part_time'   => 'Part Time',
			'on_contract' => 'On Contract',
			'temporary'   => 'Temporary',
			'trainee'     => 'Trainee',
		);
	}

	public static function employee_status_list() {
		 return array(
			 'active'     => 'Active',
			 'inactive'   => 'Inactive',
			 'terminated' => 'Terminated',
			 'deceased'   => 'Deceased',
			 'resigned'   => 'Resigned',
		 );
	}

	public static function pay_type_list() {
		return array(
			'hourly'   => 'Hourly',
			'daily'    => 'Daily',
			'weekly'   => 'Weekly',
			'biweekly' => 'Biweekly',
			'monthly'  => 'Monthly',
			'contract' => 'Contract',
		);
	}

	public static function blood_group_list() {
		 return array(
			 'nan' => 'Not Known',
			 'A+'  => 'A+',
			 'A-'  => 'A-',
			 'B+'  => 'B+',
			 'B-'  => 'B-',
			 'O+'  => 'O+',
			 'O-'  => 'O-',
			 'AB+' => 'AB+',
			 'AB-' => 'AB-',
		 );
	}

	public static function gender_list() {
		return array(
			'male'   => 'Male',
			'female' => 'Female',
			'other'  => 'Other',
		);
	}

	public static function announcement_type_list() {
		return array(
			'all_employees'  => 'All Employees',
			'by_employee'    => 'Employee',
			'by_department'  => 'Department',
			'by_designation' => 'Designation',
		);
	}

	public static function email_send_method_list() {
		return array(
			'wp_mail' => 'WP Mail',
			'smtp'    => 'SMTP',
		);
	}

	public static function months() {
		return array(
			'01' => esc_html__( 'January', 'hrp' ),
			'02' => esc_html__( 'February', 'hrp' ),
			'03' => esc_html__( 'March', 'hrp' ),
			'04' => esc_html__( 'April', 'hrp' ),
			'05' => esc_html__( 'May', 'hrp' ),
			'06' => esc_html__( 'June', 'hrp' ),
			'07' => esc_html__( 'July', 'hrp' ),
			'08' => esc_html__( 'August', 'hrp' ),
			'09' => esc_html__( 'September', 'hrp' ),
			'10' => esc_html__( 'October', 'hrp' ),
			'11' => esc_html__( 'November', 'hrp' ),
			'12' => esc_html__( 'December', 'hrp' ),
		);
	}

	public static function get_employment_type( $type ) {
		$employee_type_list = self::employee_type_list();
		foreach ( $employee_type_list as $key => $value ) {
			if ( ( $type ) == $key ) {
				return $value;
			}
		}
	}

	public static function get_attendance_status( $status ) {
		if ( is_null( $status ) ) {
			return '<span class="badge badge-dim bg-danger"> ' . esc_html__( 'Absent', 'hrp' ) . ' </span>';
		} else {
			return '<span class="badge badge-dim bg-success"> ' . esc_html__( 'Present', 'hrp' ) . ' </span>';
		}
	}

	public static function get_holiday_status( $holidays, $day, $status ) {
		foreach ( $holidays as $holiday ) {
			if ( ( $day >= $holiday->start_date ) && ( $day <= $holiday->end_date ) ) {
				return '<span class="badge badge-dim bg-warning"> ' . sprintf( esc_html( __( '%s | ( Holiday )', 'hrp' ) ), $holiday->title ) . ' </span>';
			}
		}
		return self::get_attendance_status( $status );
	}

	// Function to get all the dates in given range
	public static function getDatesFromRange( $start, $end, $format = 'Y-m-d' ) {
		// Declare an empty array
		$array = array();

		// Variable that store the date interval
		// of period 1 day
		$interval = new DateInterval( 'P1D' );

		$realEnd = new DateTime( $end );
		$realEnd->add( $interval );

		$period = new DatePeriod( new DateTime( $start ), $interval, $realEnd );

		// Use loop to store date into array
		foreach ( $period as $date ) {
			$array[] = $date->format( $format );
		}

		// Return the array elements
		return $array;
	}

	public static function seconds_to_hour_minute( $sec, $padHours = false, $asArray = false ) {
		if ( empty( $sec ) ) {
			return esc_html( '--:--' );
		}
		$hour_minute_second  = '';
		$hours               = intval( intval( $sec ) / 3600 );
		$hour_minute_second .= ( $padHours )
			? str_pad( $hours, 2, '0', STR_PAD_LEFT ) . ':'
			: $hours . ':';
		$minutes             = intval( ( $sec / 60 ) % 60 );
		$hour_minute_second .= str_pad( $minutes, 2, '0', STR_PAD_LEFT );
		return esc_html( ( $asArray ) ? explode( ':', $hour_minute_second ) : $hour_minute_second );
	}

	// ----- General Settings -----

	public static function get_general_settings() {
		// Default Data
		$company_name       = '';
		$company_logo       = null;
		$company_email      = '';
		$company_address    = '';
		$employee_id_prefix = 'EMP-';

		global $wpdb;
		$settings = $wpdb->get_row( $wpdb->prepare( 'SELECT ID, setting_value FROM ' . SETTINGS . ' WHERE setting_key = %s', 'company_detail' ) );
		if ( $settings ) {
			$settings           = unserialize( $settings->setting_value );
			$company_name       = ! empty( $settings['company_name'] ) ? $settings['company_name'] : $company_name;
			$company_logo       = ! empty( $settings['company_logo'] ) ? $settings['company_logo'] : $company_logo;
			$company_email      = ! empty( $settings['company_email'] ) ? $settings['company_email'] : $company_email;
			$company_address    = ! empty( $settings['company_address'] ) ? $settings['company_address'] : $company_address;
			$employee_id_prefix = ! empty( $settings['employee_id_prefix'] ) ? $settings['employee_id_prefix'] : $employee_id_prefix;
		}

		return array(
			'company_name'       => $company_name,
			'company_logo'       => $company_logo,
			'company_email'      => $company_email,
			'company_address'    => $company_address,
			'employee_id_prefix' => $employee_id_prefix,
		);
	}

	public static function get_notification_settings() {
		$email             = '';
		$sender_name       = '';
		$sender_address    = '';
		$email_send_method = 'wp_mail';
		$smtp_host         = '';
		$smtp_username     = '';
		$smtp_password     = '';
		$smtp_encryption   = '';
		$smtp_port         = '';

		global $wpdb;
		$settings = $wpdb->get_row( $wpdb->prepare( 'SELECT ID, setting_value FROM ' . SETTINGS . ' WHERE setting_key = %s', 'notification_settings' ) );
		if ( $settings ) {
			$settings          = unserialize( $settings->setting_value );
			$email             = isset( $settings['email_enable'] ) ? $settings['email_enable'] : '';
			$sender_name       = isset( $settings['sender_name'] ) ? $settings['sender_name'] : '';
			$sender_address    = isset( $settings['sender_address'] ) ? $settings['sender_address'] : '';
			$email_send_method = isset( $settings['email_send_method'] ) ? $settings['email_send_method'] : '';
			$smtp_host         = isset( $settings['smtp_host'] ) ? $settings['smtp_host'] : '';
			$smtp_username     = isset( $settings['smtp_username'] ) ? $settings['smtp_username'] : '';
			$smtp_password     = isset( $settings['smtp_password'] ) ? $settings['smtp_password'] : '';
			$smtp_encryption   = isset( $settings['smtp_encryption'] ) ? $settings['smtp_encryption'] : '';
			$smtp_port         = isset( $settings['smtp_port'] ) ? $settings['smtp_port'] : '';
		}

		return array(
			'email_enable'      => $email,
			'sender_name'       => $sender_name,
			'sender_address'    => $sender_address,
			'email_send_method' => $email_send_method,
			'smtp_host'         => $smtp_host,
			'smtp_username'     => $smtp_username,
			'smtp_password'     => $smtp_password,
			'smtp_encryption'   => $smtp_encryption,
			'smtp_port'         => $smtp_port,
		);
	}

	public static function get_attendance_settings() {
		// Default Data
		$grace_before_checkin  = '';
		$attendance_threshold  = '';
		$grace_before_checkout = '';

		global $wpdb;
		$settings = $wpdb->get_row( $wpdb->prepare( 'SELECT ID, setting_value FROM ' . SETTINGS . ' WHERE setting_key = %s', 'attendance_settings' ) );
		if ( $settings ) {
			$settings              = unserialize( $settings->setting_value );
			$grace_before_checkin  = isset( $settings['grace_before_checkin'] ) ? $settings['grace_before_checkin'] : '';
			$attendance_threshold  = isset( $settings['attendance_threshold'] ) ? $settings['attendance_threshold'] : '';
			$grace_before_checkout = isset( $settings['grace_before_checkout'] ) ? $settings['grace_before_checkout'] : '';
		}

		return array(
			'grace_before_checkin'  => $grace_before_checkin,
			'attendance_threshold'  => $attendance_threshold,
			'grace_before_checkout' => $grace_before_checkout,
		);
	}

	// Email template settings

	public static function get_email_template_settings() {
		// Default Data
		$registration_enable  = '';
		$registration_message = '';
		$holiday_email_days   = '';

		global $wpdb;
		$settings = $wpdb->get_row( $wpdb->prepare( 'SELECT ID, setting_value FROM ' . SETTINGS . ' WHERE setting_key = %s', 'email_template_setting' ) );
		if ( $settings ) {
			$settings             = unserialize( $settings->setting_value );
			$registration_enable  = ! empty( $settings['registration_enable'] ) ? $settings['registration_enable'] : '';
			$registration_message = ! empty( $settings['registration_message'] ) ? $settings['registration_message'] : '';
			$holiday_email_days   = ! empty( $settings['holiday_email_days'] ) ? $settings['holiday_email_days'] : '';
		}

		return array(
			'registration_enable'  => $registration_enable,
			'registration_message' => $registration_message,
			'holiday_email_days'   => $holiday_email_days,
		);
	}

	// ----- Departments -----

	public static function get_department( $department_id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT d.ID, d.title, d.description, d.employee_id  FROM ' . DEPARTMENTS . ' as d WHERE d.ID = %d', $department_id ) );
	}

	public static function get_departments() {
		global $wpdb;
		return ( 'SELECT d.ID, d.title, d.description, d.employee_id  FROM ' . DEPARTMENTS . ' as d' );
	}

	public static function get_departments_list() {
		global $wpdb;
		return $wpdb->get_results( 'SELECT d.ID, d.title  FROM ' . DEPARTMENTS . ' as d' );
	}

	public static function get_departments_row_count() {
		return 'SELECT COUNT(d.ID) FROM ' . DEPARTMENTS . ' as d';
	}

	public static function get_department_by_title( $title ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( 'SELECT d.ID, d.title, d.description  FROM ' . DEPARTMENTS . ' as d WHERE d.title = %s', $title ) );
	}

	// ----- Designations -----

	public static function get_designation( $designation_id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT dg.ID, dg.title, dg.description  FROM ' . DESIGNATIONS . ' as dg WHERE dg.ID = %d', $designation_id ) );
	}

	public static function get_designation_by_title( $title ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( 'SELECT dg.ID, dg.title, dg.description  FROM ' . DESIGNATIONS . ' as dg WHERE dg.title = %s', $title ) );
	}

	public static function get_designations() {
		global $wpdb;
		return ( 'SELECT d.ID, d.title, d.description  FROM ' . DESIGNATIONS . ' as d' );
	}

	public static function get_designations_row_count() {
		return 'SELECT COUNT(d.ID) FROM ' . DESIGNATIONS . ' as d';
	}

	public static function get_designations_list() {
		global $wpdb;
		return $wpdb->get_results( 'SELECT ds.ID, ds.title  FROM ' . DESIGNATIONS . ' as ds' );
	}

	// ----- Shifts -----

	public static function get_shift( $shift_id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT sf.ID, sf.title, sf.start_time, sf.end_time, sf.holidays, sf.status  FROM ' . SHIFTS . ' as sf WHERE sf.ID = %d', $shift_id ) );
	}

	public static function get_shift_by_title( $title ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT sf.ID, sf.title  FROM ' . SHIFTS . ' as sf WHERE sf.title = %s', $title ) );
	}

	public static function get_shifts() {
		global $wpdb;
		return ( 'SELECT sf.ID, sf.title, sf.start_time, sf.end_time, sf.holidays, sf.status  FROM ' . SHIFTS . ' as sf' );
	}

	public static function get_shifts_list() {
		global $wpdb;
		return $wpdb->get_results( 'SELECT sf.ID, sf.title, sf.start_time, sf.end_time, sf.holidays, sf.status  FROM ' . SHIFTS . ' as sf' );
	}

	public static function get_shifts_row_count() {
		return 'SELECT COUNT(sf.ID) FROM ' . SHIFTS . ' as sf';
	}

	// ----- Holidays -----

	public static function get_holiday( $holiday_id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT h.ID, h.title, h.start_date, h.end_date, h.description, h.added_by_user_id  FROM ' . HOLIDAYS . ' as h WHERE h.ID = %d', $holiday_id ) );
	}

	public static function get_holiday_by_title( $title ) {
		 global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT h.ID, h.title  FROM ' . HOLIDAYS . ' as h WHERE h.title = %s', $title ) );
	}

	public static function get_holidays() {
		return ( 'SELECT h.ID, h.title, h.start_date, h.end_date, h.description, h.added_by_user_id  FROM ' . HOLIDAYS . ' as h' );
	}

	public static function get_holidays_list() {
		global $wpdb;
		return $wpdb->get_results( 'SELECT h.ID, h.title, h.start_date, h.end_date, h.description  FROM ' . HOLIDAYS . ' as h' );
	}

	public static function get_current_year_holidays() {
		global $wpdb;
		return $wpdb->get_results(
			'SELECT h.title, h.start_date, h.end_date, h.description  FROM ' . HOLIDAYS . ' as h
			WHERE YEAR(h.start_date) = YEAR(CURDATE()) order by h.start_date'
		);
	}

	public static function get_current_month_holidays() {
		global $wpdb;
		return $wpdb->get_results(
			'SELECT h.title, h.start_date, h.end_date, h.description  FROM ' . HOLIDAYS . ' as h
			WHERE MONTH(h.start_date) = MONTH(CURDATE()) order by h.start_date'
		);
	}

	public static function get_holidays_row_count() {
		return 'SELECT COUNT(h.ID) FROM ' . HOLIDAYS . ' as h';
	}

	public static function upcoming_employees_birthdays() {
		global $wpdb;
		return $wpdb->get_results(
			'SELECT emp.name, emp.date_of_birth, dp.title as department, ds.title as designation FROM   ' . EMPLOYEES . ' as emp
			LEFT OUTER JOIN ' . DEPARTMENTS . ' as dp on dp.ID = emp.department
			LEFT OUTER JOIN ' . DESIGNATIONS . ' as ds on ds.ID = emp.designation
			WHERE  DATE_ADD(date_of_birth, INTERVAL YEAR(CURDATE())-YEAR(date_of_birth) + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(date_of_birth),1,0) YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY);'
		);
	}

	// ----- Employees -----

	public static function get_employee( $employee_id ) {
		global $wpdb;
		return $wpdb->get_row(
			$wpdb->prepare(
				'SELECT emp.ID, emp.name, emp.user_id, emp.employee_id, emp.designation, emp.date_of_birth, emp.shift_id, emp.type, emp.status, emp.photo_id, emp.termination_date, emp.pay_rate, emp.pay_type, emp.mobile, emp.deleted_at, emp.source_of_hire, emp.date_of_hire, emp.location, emp.user_id, u.user_email, dp.title as department, ds.title as designation, sf.title as shift FROM ' . EMPLOYEES . ' as emp
				LEFT OUTER JOIN ' . DEPARTMENTS . ' as dp on dp.ID = emp.department
				LEFT OUTER JOIN ' . DESIGNATIONS . ' as ds on ds.ID = emp.designation
				LEFT OUTER JOIN ' . USERS . ' as u on emp.user_id = u.ID
				LEFT OUTER JOIN ' . SHIFTS . ' as sf on sf.ID = emp.shift_id
				WHERE emp.ID = %d',
				$employee_id
			)
		);
	}

	public static function get_employee_by_user_id( $user_id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . EMPLOYEES . ' as emp WHERE emp.user_id = %d', $user_id ) );
	}

	public static function get_employee_count_by_department( $department_id ) {
		 global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(emp.ID) FROM ' . EMPLOYEES . ' as emp WHERE emp.department = %s', $department_id ) );
	}

	public static function get_employee_by_department( $department_id ) {
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . EMPLOYEES . ' as emp WHERE emp.department = %s', $department_id ) );
	}

	public static function get_employee_by_designation( $designation_id ) {
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . EMPLOYEES . ' as emp WHERE emp.designation = %s', $designation_id ) );
	}

	public static function get_employee_count_by_designation( $designation_id ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(emp.ID) FROM ' . EMPLOYEES . ' as emp WHERE emp.designation = %s', $designation_id ) );
	}

	public static function get_employees() {
		global $wpdb;
		return ( 'SELECT emp.ID, emp.name, emp.user_id, emp.employee_id, emp.designation, emp.type, emp.status, emp.photo_id, emp.termination_date, emp.pay_rate, emp.pay_type, emp.mobile, emp.deleted_at, emp.source_of_hire, emp.date_of_hire, emp.location, u.user_email, dp.title as department, ds.title as designation, sf.title as shift  FROM ' . EMPLOYEES . ' as emp
			LEFT OUTER JOIN ' . DEPARTMENTS . ' as dp on dp.ID = emp.department
			LEFT OUTER JOIN ' . DESIGNATIONS . ' as ds on ds.ID = emp.designation
			LEFT OUTER JOIN ' . USERS . ' as u on emp.user_id = u.ID
			LEFT OUTER JOIN ' . SHIFTS . ' as sf on sf.ID = emp.shift_id'
		);
	}

	public static function get_employees_list() {
		global $wpdb;
		return $wpdb->get_results(
			'SELECT emp.ID, emp.name, emp.employee_id FROM ' . EMPLOYEES . ' as emp WHERE deleted_at IS NULL'
		);
	}

	public static function get_employees_row_count() {
		return 'SELECT COUNT(emp.ID) FROM ' . EMPLOYEES . ' as emp';
	}

	public static function get_employee_id_number() {
		global $wpdb;
		$id_number = $wpdb->get_var(
			'SELECT emp.employee_id FROM ' . EMPLOYEES . ' as emp order by employee_id desc limit 0,1'
		);
		return intval( $id_number ) + 1;
	}

	public static function employee_id_prefix( $id ) {
		$settings           = self::get_general_settings();
		$employee_id_prefix = $settings['employee_id_prefix'];
		return $employee_id_prefix . $id;
	}

	// ----- Announcements -----

	public static function get_announcement( $announcement_id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT an.ID, an.title, an.announcement, an.send_to, an.announced_to, an.user_id, an.created_at, an.updated_at, an.status FROM ' . ANNOUNCEMENTS . ' as an WHERE an.ID = %d', $announcement_id ) );
	}

	public static function get_announcement_by_title( $title ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( 'SELECT an.ID, an.title FROM ' . ANNOUNCEMENTS . ' as an WHERE an.title = %s AND status = 1', $title ) );
	}

	public static function get_announcement_by_time( $time ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( 'SELECT an.ID, an.title FROM ' . ANNOUNCEMENTS . ' as an WHERE an.created_at = %s', $time ) );
	}

	public static function get_announcements() {
		return ( 'SELECT an.ID, an.title, an.announcement, an.send_to, an.user_id, an.created_at, an.updated_at, an.status, an.announced_to FROM ' . ANNOUNCEMENTS . ' as an' );
	}

	public static function get_announcements_row_count() {
		return 'SELECT COUNT(an.ID) FROM ' . ANNOUNCEMENTS . ' as an';
	}

	// ----- Attendances -----

	public static function get_years_from_employee( $employee_id ) {
		global $wpdb;
		$year = $wpdb->get_row( $wpdb->prepare( 'SELECT MIN(`date`) AS MinDate, MAX(`date`) AS MaxDate  FROM ' . ATTENDANCES . ' as a WHERE a.employee_id = %d', $employee_id ) );
		return $year;
	}

	public static function get_employees_attendance() {
		global $wpdb;
		return ( 'SELECT emp.ID, emp.name, emp.user_id, emp.employee_id, emp.designation, emp.type, emp.photo_id, emp.termination_date, emp.pay_rate, emp.pay_type, emp.mobile, emp.deleted_at, emp.source_of_hire, emp.date_of_hire, emp.location, u.user_email, dp.title as department, ds.title as designation, sf.title as shift, a.checkin, a.checkout, a.breakin, a.breakout, a.ip_address, a.status  FROM ' . EMPLOYEES . ' as emp
			LEFT OUTER JOIN ' . DEPARTMENTS . ' as dp on dp.ID = emp.department
			LEFT OUTER JOIN ' . DESIGNATIONS . ' as ds on ds.ID = emp.designation
			LEFT OUTER JOIN ' . USERS . ' as u on emp.user_id = u.ID
			LEFT OUTER JOIN ' . SHIFTS . ' as sf on sf.ID = emp.shift_id
			LEFT OUTER JOIN ' . ATTENDANCES . ' as a on a.employee_id = emp.ID'
		);
	}

	public static function get_attendance( $attendance_id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT a.ID, a.checkin, a.checkout, a.date, a.employee_id, a.comment  FROM ' . ATTENDANCES . ' as a WHERE a.ID = %d', $attendance_id ) );
	}

	public static function get_attendance_by_employee_id( $employee_id ) {
		global $wpdb;
		return $wpdb->get_results(
			$wpdb->prepare(
				'SELECT a.ID, a.checkin, a.checkout, a.date, a.employee_id, a.comment, a.ip_address, a.status  FROM ' . ATTENDANCES . ' as a
				JOIN ' . EMPLOYEES . ' as emp on emp.ID = a.employee_id
				WHERE a.employee_id = %d ORDER BY a.date',
				$employee_id
			)
		);
	}

	public static function get_attendance_by_date( $date, $employee_id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( 'SELECT a.ID, a.date, a.checkout, a.checkin, a.ip_address, a.status, a.comment, a.breakin, a.breakout  FROM ' . ATTENDANCES . ' as a WHERE a.date = %s AND employee_id = %s', $date, $employee_id ) );
	}

	public static function get_attendances_with_employee_id( $employee_id, $month = null, $year = null ) {
		global $wpdb;

		// if $month and $year is not given get the current $month, $year
		$month = ( ! $month ) ? date( 'm' ) : $month;
		$year  = ( ! $year ) ? date( 'Y' ) : $year;

		$count = cal_days_in_month( CAL_GREGORIAN, $month, $year );
		$date  = date( "$year-$month-$count" );
		if ( $month && $year ) {
			return $wpdb->prepare(
				'SELECT IF(date IS NULL, 0, date) AS mdate,
				b.Days AS Days, att.employee_id, att.checkin, att.checkout, att.breakin, att.breakout, att.comment, att.status, att.ip_address, att.ID, att.status
				FROM
				(SELECT a.Days
				FROM (
					SELECT %s - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS Days
					FROM       (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a
					CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b
					CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS c
				) a
				WHERE a.Days > %s - INTERVAL %d DAY) b
				LEFT JOIN ' . ATTENDANCES . ' AS att
				ON date = b.Days AND
				employee_id=%d ORDER BY b.Days asc',
				$date,
				$date,
				$count,
				$employee_id
			);
		}
	}

	public static function get_attendances_list() {
		 global $wpdb;
		return $wpdb->get_results( 'SELECT a.ID, a.title, a.start_time, a.end_time, a.holidays, a.status  FROM ' . ATTENDANCES . ' as a' );
	}

	public static function get_attendances_row_count( $employee_id = null, $month = null, $year = null ) {
		global $wpdb;
		// if $month and $year is not given get the current $month, $year
		$month = ( ! $month ) ? date( 'm' ) : $month;
		$year  = ( ! $year ) ? date( 'Y' ) : $year;

		$date  = date( "$year-$month-30" );
		$count = date( "$year-$month-t" );
		if ( $month && $year ) {
			return $wpdb->prepare( 'SELECT COUNT(att.ID) FROM ' . ATTENDANCES . ' as att WHERE employee_id=%d', $employee_id, $month, $year );
		}
	}

	public static function get_user_ip_address() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			// ip from share internet
			$ip = rest_is_ip_address( $_SERVER['HTTP_CLIENT_IP'] );
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			// ip pass from proxy
			$ip = rest_is_ip_address( $_SERVER['HTTP_X_FORWARDED_FOR'] );
		} else {
			$ip = rest_is_ip_address( $_SERVER['REMOTE_ADDR'] );
		}
		return $ip;
	}

	public static function days_in_current_month() {
		return cal_days_in_month( CAL_GREGORIAN, date( 'm' ), date( 'Y' ) );
	}

	public static function presents_in_month( $employee_id ) {
		global $wpdb;
		$presents = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(a.status) FROM ' . ATTENDANCES . ' as a WHERE a.employee_id = %d AND MONTH(date) = MONTH(CURRENT_DATE())', $employee_id ) );

		// get current month holidays count
		$holidays_in_current_month = self::get_current_month_holidays();

		$holidays_count = 0;
		foreach ( $holidays_in_current_month as $holiday ) {
			$start_date      = $holiday->start_date;
			$end_date        = $holiday->end_date;
			$interval        = ( new DateTime( $start_date ) )->diff( new DateTime( $end_date ) );
			$days            = ( $interval->days == 0 ) ? 1 : $interval->days + 1;
			$holidays_count += $days;
		}
		$presents = $presents + $holidays_count;
		return $presents;
	}

	public static function absents_in_month( $employee_id ) {
		global $wpdb;

		$days_in_month = cal_days_in_month( CAL_GREGORIAN, date( 'm' ), date( 'Y' ) );
		$presents      = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(a.status) FROM ' . ATTENDANCES . ' as a WHERE a.employee_id = %d AND MONTH(date) = MONTH(CURRENT_DATE())', $employee_id ) );

		// get current month holidays count
		$holidays_in_current_month = self::get_current_month_holidays();

		$holidays_count = 0;
		foreach ( $holidays_in_current_month as $holiday ) {
			$start_date      = $holiday->start_date;
			$end_date        = $holiday->end_date;
			$interval        = ( new DateTime( $start_date ) )->diff( new DateTime( $end_date ) );
			$days            = ( $interval->days == 0 ) ? 1 : $interval->days + 1;
			$holidays_count += $days;
		}
		$absents = $days_in_month - ( $presents + $holidays_count );
		return $absents;
	}

	public static function send_announcement_email( $send_to, $title, $announcement, $announced_to ) {
		if ( $send_to === 'all_employees' ) {
			$employees_list = self::get_data( self::get_employees() );
			foreach ( $employees_list as $employee ) {
				self::send_email_to_employee( $employee->ID, $title, $announcement );
			}
		} elseif ( $send_to === 'by_employee' ) {
			foreach ( $announced_to as $to ) {
				self::send_email_to_employee( $to, $title, $announcement );
			}
		} elseif ( $send_to === 'by_department' ) {
			foreach ( $announced_to as $to ) {
				$employees = self::get_employee_by_department( $to );
				foreach ( $employees as $employee ) {
					self::send_email_to_employee( $employee->ID, $title, $announcement );
				}
			}
		} elseif ( $send_to === 'by_designation' ) {
			foreach ( $announced_to as $to ) {
				$employees = self::get_employee_by_designation( $to );
				foreach ( $employees as $employee ) {
					self::send_email_to_employee( $employee->ID, $title, $announcement );
				}
			}
		}
	}

	public static function send_email( $to, $subject, $body, $placeholders = array() ) {
		$settings       = self::get_notification_settings();
		$send_method    = $settings['email_send_method'];
		$sender_name    = $settings['sender_name'];
		$sender_address = $settings['sender_address'];
		$name           = array();

		if ( ! empty( $placeholders ) ) {
			foreach ( $placeholders as $key => $value ) {
				if ( in_array( $key, array_keys( self::dynamic_keys() ) ) ) {
					$subject = str_replace( $key, $value, $subject );
					$body    = str_replace( $key, $value, $body );
				}
			}
		}

		if ( 'wp_mail' === $send_method ) {
			$from_name  = $sender_name;
			$from_email = $sender_address;

			if ( is_array( $to ) ) {
				foreach ( $to as $key => $value ) {
					$to[ $key ] = $name[ $key ] . ' <' . $value . '>';
				}
			} else {
				if ( ! empty( $name ) ) {
					$to = "$name <$to>";
				}
			}

			$headers = array();
			array_push( $headers, 'Content-Type: text/html; charset=UTF-8' );
			if ( ! empty( $from_name ) ) {
				array_push( $headers, "From: $from_name <$from_email>" );
			}
			$body = self::email_body( $body );

			$status = wp_mail( $to, html_entity_decode( $subject ), $body, $headers, array() );
			return $status;
		} elseif ( 'smtp' === $send_method ) {

			$host       = $settings['smtp_host'];
			$username   = $settings['smtp_username'];
			$password   = $settings['smtp_password'];
			$encryption = $settings['smtp_encryption'];
			$port       = $settings['smtp_port'];

			global $wp_version;

			require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
			require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
			require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
			$mail = new PHPMailer\PHPMailer\PHPMailer( true );

			try {
				$mail->CharSet  = 'UTF-8';
				$mail->Encoding = 'base64';

				if ( $host && $port ) {
					$mail->IsSMTP();
					$mail->Host = $host;
					if ( ! empty( $username ) && ! empty( $password ) ) {
						$mail->SMTPAuth = true;
						$mail->Password = $password;
					} else {
						$mail->SMTPAuth = false;
					}
					if ( ! empty( $encryption ) ) {
						$mail->SMTPSecure = $encryption;
					} else {
						$mail->SMTPSecure = null;
					}
					$mail->Port = $port;
				}

				$mail->Username = $username;

				$mail->setFrom( $mail->Username, $from_name );

				$mail->Subject = html_entity_decode( $subject );
				$mail->Body    = $body;

				$mail->IsHTML( true );

				if ( is_array( $to ) ) {
					foreach ( $to as $key => $value ) {
						$mail->AddAddress( $value, $name[ $key ] );
					}
				} else {
					$mail->AddAddress( $to, $name );
				}

				$status = $mail->Send();
				return $status;
			} catch ( Exception $e ) {
			}

			return false;
		}
	}

	public static function email_body( $body ) {
		$settings     = self::get_general_settings();
		$company_name = $settings['company_name'];
		return '<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f5f6fa;">
					<center style="width: 100%; background-color: #f5f6fa;">
						<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f5f6fa">
							<tr>
								<td style="padding: 40px 0;">
										<table style="width:100%;max-width:620px;margin:0 auto;">
											<tbody>
												<tr>
													<td style="text-align: center; padding-bottom:25px">
														<a href="#"><img style="height: 40px" src="' . wp_get_attachment_image_url( $settings['company_logo'] ) . '" alt="logo"></a>
														<p style="font-size: 14px; color: #6576ff; padding-top: 12px;">' . $company_name . '</p>
													</td>
												</tr>
											</tbody>
										</table>
										<table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
											<tbody>
												<tr>
													<td style="padding: 30px 30px 20px">
														<pre>' . $body . '<pre/>
													</td>
												</tr>
											</tbody>
										</table>
										<table style="width:100%;max-width:620px;margin:0 auto;">
											<tbody>
												<tr>
													<td style="text-align: center; padding:25px 20px 0;">
														<p style="font-size: 13px;">Copyright © ' . date( 'Y' ) . ' ' . $company_name . '. All rights reserved. <br> Template Made By <a style="color: #6576ff; text-decoration:none;" href="#">CodeClove</a>.</p>
													</td>
												</tr>
											</tbody>
										</table>
								</td>
							</tr>
						</table>
					</center>
				</body>';
	}

	public static function dynamic_keys() {
		return array(
			'{{employee_name}}'        => 'name',
			'{{employee_email}}'       => 'email',
			'{{employee_department}}'  => 'department',
			'{{employee_designation}}' => 'designation',
			'{{employee_shift}}'       => 'shift',
		);
	}

	public static function send_email_to_employee( $employee_id, $subject, $body ) {
		$employee     = self::get_employee( $employee_id );
		$placeholders = array(
			'{{employee_name}}'        => $employee->name,
			'{{employee_email}}'       => $employee->user_email,
			'{{employee_department}}'  => $employee->department,
			'{{employee_designation}}' => $employee->designation,
			'{{employee_shift}}'       => $employee->shift,
		);
		self::send_email( $employee->user_email, $subject, $body, $placeholders );
	}
}
