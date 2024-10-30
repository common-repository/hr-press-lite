<?php
defined( 'ABSPATH' ) || die();
require_once HRP_PLUGIN_DIR . 'admin/inc/menu.php';
require_once HRP_PLUGIN_DIR . 'includes/HRP_Action.php';

add_action( 'admin_menu', array( 'HRP_Menu', 'menu' ) );

// Departments.
add_action( 'wp_ajax_hrp-save-department', array( 'HRP_Action', 'save_department' ) );
add_action( 'wp_ajax_hrp-fetch-departments', array( 'HRP_Action', 'fetch_departments' ) );
add_action( 'wp_ajax_hrp-delete-department', array( 'HRP_Action', 'delete_department' ) );

// Designations.
add_action( 'wp_ajax_hrp-save-designation', array( 'HRP_Action', 'save_designation' ) );
add_action( 'wp_ajax_hrp-fetch-designations', array( 'HRP_Action', 'fetch_designation' ) );
add_action( 'wp_ajax_hrp-delete-designation', array( 'HRP_Action', 'delete_designation' ) );

// Shifts.
add_action( 'wp_ajax_hrp-save-shift', array( 'HRP_Action', 'save_shift' ) );
add_action( 'wp_ajax_hrp-fetch-shifts', array( 'HRP_Action', 'fetch_shift' ) );
add_action( 'wp_ajax_hrp-delete-shift', array( 'HRP_Action', 'delete_shift' ) );

// Attendances.
add_action( 'wp_ajax_hrp-save-attendance', array( 'HRP_Action', 'save_attendance' ) );
add_action( 'wp_ajax_hrp-fetch-attendances', array( 'HRP_Action', 'fetch_attendances' ) );
add_action( 'wp_ajax_hrp-delete-attendance', array( 'HRP_Action', 'delete_attendance' ) );
add_action( 'wp_ajax_hrp-fetch-attendances-employees', array( 'HRP_Action', 'fetch_attendance_employees' ) );

// Holidays.
add_action( 'wp_ajax_hrp-save-holiday', array( 'HRP_Action', 'save_holiday' ) );
add_action( 'wp_ajax_hrp-fetch-holidays', array( 'HRP_Action', 'fetch_holiday' ) );
add_action( 'wp_ajax_hrp-delete-holiday', array( 'HRP_Action', 'delete_holiday' ) );

// Employees.
add_action( 'wp_ajax_hrp-save-employee', array( 'HRP_Action', 'save_employee' ) );
add_action( 'wp_ajax_hrp-fetch-employees', array( 'HRP_Action', 'fetch_employee' ) );
add_action( 'wp_ajax_hrp-delete-employee', array( 'HRP_Action', 'delete_employee' ) );

// Announcements.
add_action( 'wp_ajax_hrp-save-announcement', array( 'HRP_Action', 'save_announcement' ) );
add_action( 'wp_ajax_hrp-fetch-announcements', array( 'HRP_Action', 'fetch_announcement' ) );
add_action( 'wp_ajax_hrp-delete-announcement', array( 'HRP_Action', 'delete_announcement' ) );

// Settings.
add_action( 'wp_ajax_hrp-save-company-details', array( 'HRP_Action', 'save_company_details' ) );
add_action( 'wp_ajax_hrp-save-notification-settings', array( 'HRP_Action', 'save_notification_settings' ) );
add_action( 'wp_ajax_hrp-save-attendance-settings', array( 'HRP_Action', 'save_attendance_settings' ) );
add_action( 'wp_ajax_hrp-save-email-template-settings', array( 'HRP_Action', 'save_email_template_settings' ) );

// Employee checkin, checkout.
add_action( 'wp_ajax_hrp-save-checkin', array( 'HRP_Action', 'employee_checkin' ) );
add_action( 'wp_ajax_hrp-save-checkout', array( 'HRP_Action', 'employee_checkout' ) );
add_action( 'wp_ajax_hrp-save-breakin', array( 'HRP_Action', 'employee_breakin' ) );
add_action( 'wp_ajax_hrp-save-breakout', array( 'HRP_Action', 'employee_breakout' ) );

add_action( 'wp_ajax_hrp-fetch-emp-announcements', array( 'HRP_Action', 'emp_fetch_announcements' ) );

add_action( 'wp_ajax_hrp-fetch-reports', array( 'HRP_Action', 'fetch_reports' ) );

// Send email.
add_action( 'wp_ajax_send-test-email', array( 'HRP_Action', 'send_test_email' ) );
