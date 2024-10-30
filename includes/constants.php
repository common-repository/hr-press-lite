<?php defined( 'ABSPATH' ) || exit;
global $wpdb;

const ADMIN        = 'manage_options';
const HR_PRESS     = 'hr_press';
const HR_PRESS_EMP = 'hr_press_emp';

define( 'USERS', $wpdb->base_prefix . 'users' );
define( 'EMPLOYEES', $wpdb->prefix . 'hrp_employees' );
define( 'EMPLOYEES_WORK_DETAIL', $wpdb->prefix . 'hrp_employees_work_detail' );
define( 'DEPARTMENTS', $wpdb->prefix . 'hrp_departments' );
define( 'DESIGNATIONS', $wpdb->prefix . 'hrp_designations' );
define( 'ANNOUNCEMENTS', $wpdb->prefix . 'hrp_announcements' );
define( 'ATTENDANCES', $wpdb->prefix . 'hrp_attendances' );
define( 'SHIFTS', $wpdb->prefix . 'hrp_shifts' );
define( 'SETTINGS', $wpdb->prefix . 'hrp_settings' );
define( 'HOLIDAYS', $wpdb->prefix . 'hrp_holidays' );
define( 'PAYROLL', $wpdb->prefix . 'hrp_payroll' );
define( 'REPORTS', $wpdb->prefix . 'hrp_reports' );
