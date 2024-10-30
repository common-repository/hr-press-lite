<?php
defined( 'ABSPATH' ) || exit;
require_once HRP_PLUGIN_DIR . 'includes/constants.php';
class HRP_Database {
	public static function activation() {
		global $wpdb;
		add_role(
			'hrp_employee', // System name of the role.
			__( 'HRP Employee' ), // Display name of the role.
			array(
				'read' => true,
			)
		);
		$charset_collate = $wpdb->get_charset_collate();
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Departments table : Create table if not exits.
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hrp_departments`(
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			title varchar(200) DEFAULT NULL,
			`description` text DEFAULT NULL,
			`employee_id` int(11) unsigned DEFAULT NULL,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY (ID)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta( $sql );

		// Designations table : Create table if not exits.
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hrp_designations`(
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			title varchar(200) DEFAULT NULL,
			`description` text DEFAULT NULL,
			`status` tinyint(1) DEFAULT 1,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY (ID)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta( $sql );

		// Employees table : Create table if not exits.
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hrp_employees`(
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`user_id` bigint(20) DEFAULT NULL,
			photo_id bigint(20) DEFAULT NULL,
			employee_id varchar(20) DEFAULT NULL,
			`name` varchar(40) DEFAULT NULL,
			date_of_hire date NULL DEFAULT NULL,
			termination_date date NULL DEFAULT NULL,
			date_of_birth date NULL DEFAULT NULL,
			department bigint(20) DEFAULT NULL,
			designation bigint(20) DEFAULT NULL,
			`location` varchar(50) DEFAULT NULL,
			`source_of_hire` varchar(50) DEFAULT NULL,
			`type` varchar(20) DEFAULT NULL,
			`status` varchar(10) DEFAULT NULL,
			reporting_to bigint(20) DEFAULT NULL,
			pay_rate decimal(20,2) unsigned DEFAULT NULL,
			pay_type varchar(20) DEFAULT NULL,
			mobile varchar(50) DEFAULT NULL,
			shift_id bigint(20) DEFAULT NULL,
			deleted_at datetime DEFAULT NULL,
			PRIMARY KEY (ID),
			KEY user_id (user_id),
			KEY designation (designation),
			KEY department (department),
			KEY `status` (`status`)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta( $sql );

		// Shifts table : Create table if not exits.
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hrp_shifts`(
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			title varchar(191) DEFAULT NULL,
			`start_time` time DEFAULT NULL,
			`end_time` time DEFAULT NULL,
			`holidays` varchar(190) DEFAULT '',
			`status` tinyint(1) NOT NULL DEFAULT '1',
			PRIMARY KEY (ID),
			KEY `title` (`title`),
			KEY `start_time` (`start_time`),
			KEY `end_time` (`end_time`),
			KEY `status` (`status`)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta( $sql );

		// Settings table : Create table if not exits.
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hrp_settings`(
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			setting_key varchar(190) DEFAULT NULL,
			setting_value text DEFAULT NULL,
			PRIMARY KEY (ID),
			KEY setting_key (setting_key),
			UNIQUE (setting_key)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta( $sql );

		// Announcements table : Create table if not exits.
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hrp_announcements`(
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			title varchar(190) DEFAULT NULL,
			send_to varchar(190) DEFAULT NULL,
			announcement text DEFAULT NULL,
			announced_to text DEFAULT NULL,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			`user_id` bigint(20) DEFAULT NULL,
			`status` tinyint(1) DEFAULT 1,
			PRIMARY KEY (ID),
			KEY send_to (send_to)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta( $sql );

		// Holidays table : Create table if not exits.
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hrp_holidays`(
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			title varchar(190) DEFAULT NULL,
			`start_date` date NULL DEFAULT NULL,
			`end_date` date NULL DEFAULT NULL,
			`description` text DEFAULT NULL,
			`added_by_user_id` bigint(20) DEFAULT NULL,
			PRIMARY KEY (ID)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta( $sql );

		// Attendances table : Create table if not exits.
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hrp_attendances`(
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`employee_id` bigint(20) DEFAULT NULL,
			`checkin` time DEFAULT NULL,
			`checkout` time DEFAULT NULL,
			`breakin` time DEFAULT NULL,
			`breakout` time DEFAULT NULL,
			`date` date NULL DEFAULT NULL,
			`comment` text DEFAULT NULL,
			`status` tinyint(1) DEFAULT NULL,
			ip_address text DEFAULT NULL,
			PRIMARY KEY (ID),
			KEY employee_id (employee_id),
			KEY `date` (`date`)
			) ENGINE=InnoDB " . $charset_collate;
		dbDelta( $sql );

	}
	public static function deactivation() {
	}
}
