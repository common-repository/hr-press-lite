<?php
defined( 'ABSPATH' ) || exit;
require_once HRP_PLUGIN_DIR . 'includes/constants.php';
require_once HRP_PLUGIN_DIR . 'includes/HRP_Helper.php';
class HRP_Menu {
	public static function menu() {
		$menu = add_menu_page( esc_html__( 'Hr Press Lite', 'hrp' ), esc_html__( 'Hr Press Lite', 'hrp' ), ADMIN, HR_PRESS, array( 'HRP_Menu', 'dashboard' ), 'dashicons-businessman', 3 );
		add_action( 'admin_print_styles-' . $menu, array( 'HRP_Menu', 'assets' ) );

		$dash = add_submenu_page( HR_PRESS, esc_html__( 'Dashboard', 'hrp' ), esc_html__( 'Dashboard', 'hrp' ), ADMIN, HR_PRESS, array( 'HRP_Menu', 'dashboard' ) );
		add_action( 'admin_print_styles-' . $dash, array( 'HRP_Menu', 'assets' ) );

		$employees = add_submenu_page( HR_PRESS, esc_html__( 'Employees', 'hrp' ), esc_html__( 'Employees', 'hrp' ), ADMIN, EMPLOYEES, array( 'HRP_Menu', 'employees' ) );
		add_action( 'admin_print_styles-' . $employees, array( 'HRP_Menu', 'assets' ) );

		$attendance = add_submenu_page( HR_PRESS, esc_html__( 'Attendance', 'hrp' ), esc_html__( 'Attendance', 'hrp' ), ADMIN, ATTENDANCES, array( 'HRP_Menu', 'attendance' ) );
		add_action( 'admin_print_styles-' . $attendance, array( 'HRP_Menu', 'assets' ) );

		$departments = add_submenu_page( HR_PRESS, esc_html__( 'Departments', 'hrp' ), esc_html__( 'Departments', 'hrp' ), ADMIN, DEPARTMENTS, array( 'HRP_Menu', 'departments' ) );
		add_action( 'admin_print_styles-' . $departments, array( 'HRP_Menu', 'assets' ) );

		$designations = add_submenu_page( HR_PRESS, esc_html__( 'Designations', 'hrp' ), esc_html__( 'Designations', 'hrp' ), ADMIN, DESIGNATIONS, array( 'HRP_Menu', 'designations' ) );
		add_action( 'admin_print_styles-' . $designations, array( 'HRP_Menu', 'assets' ) );

		$announcements = add_submenu_page( HR_PRESS, esc_html__( 'Announcements', 'hrp' ), esc_html__( 'Announcements', 'hrp' ), ADMIN, ANNOUNCEMENTS, array( 'HRP_Menu', 'announcements' ) );
		add_action( 'admin_print_styles-' . $announcements, array( 'HRP_Menu', 'assets' ) );

		$shifts = add_submenu_page( HR_PRESS, esc_html__( 'Shifts', 'hrp' ), esc_html__( 'Shifts', 'hrp' ), ADMIN, SHIFTS, array( 'HRP_Menu', 'shifts' ) );
		add_action( 'admin_print_styles-' . $shifts, array( 'HRP_Menu', 'assets' ) );

		$holidays = add_submenu_page( HR_PRESS, esc_html__( 'Holidays', 'hrp' ), esc_html__( 'Holidays', 'hrp' ), ADMIN, HOLIDAYS, array( 'HRP_Menu', 'holidays' ) );
		add_action( 'admin_print_styles-' . $holidays, array( 'HRP_Menu', 'assets' ) );

		$settings = add_submenu_page( HR_PRESS, esc_html__( 'Settings', 'hrp' ), esc_html__( 'Settings', 'hrp' ), ADMIN, SETTINGS, array( 'HRP_Menu', 'settings' ) );
		add_action( 'admin_print_styles-' . $settings, array( 'HRP_Menu', 'assets' ) );

		// Create Employee menu if user has the `hrp_employee` role.
		if ( is_user_logged_in() ) {
			global $current_user;
			if ( in_array( 'hrp_employee', $current_user->roles, true ) ) {

				// Remove WordPress default dashboard and profile page menu.
				remove_menu_page( 'index.php' );
				remove_menu_page( 'profile.php' );

				$emp_menu = add_menu_page( esc_html__( 'Dashboard', 'hrp' ), esc_html__( 'Dashboard', 'hrp' ), 'hrp_employee', HR_PRESS_EMP, array( 'HRP_Menu', 'emp_dash' ), 'dashicons-dashboard' );
				add_action( 'admin_print_styles-' . $emp_menu, array( 'HRP_Menu', 'assets' ) );

				$emp_profile = add_menu_page( esc_html__( 'Profile', 'hrp' ), esc_html__( 'Profile', 'hrp' ), 'hrp_employee', 'emp_profile', array( 'HRP_Menu', 'emp_dash' ), 'dashicons-businessman' );
				add_action( 'admin_print_styles-' . $emp_profile, array( 'HRP_Menu', 'assets' ) );

				$emp_holidays = add_menu_page( esc_html__( 'Holidays', 'hrp' ), esc_html__( 'Holidays', 'hrp' ), 'hrp_employee', 'emp_holidays', array( 'HRP_Menu', 'emp_dash' ), 'dashicons-palmtree' );
				add_action( 'admin_print_styles-' . $emp_holidays, array( 'HRP_Menu', 'assets' ) );

				$emp_attendance = add_menu_page( esc_html__( 'Attendances', 'hrp' ), esc_html__( 'Attendances', 'hrp' ), 'hrp_employee', 'emp_attendances', array( 'HRP_Menu', 'emp_dash' ), 'dashicons-calendar-alt' );
				add_action( 'admin_print_styles-' . $emp_attendance, array( 'HRP_Menu', 'assets' ) );

				$emp_announcement = add_menu_page( esc_html__( 'Announcements', 'hrp' ), esc_html__( 'Announcements', 'hrp' ), 'hrp_employee', 'emp_announcements', array( 'HRP_Menu', 'emp_dash' ), 'dashicons-bell' );
				add_action( 'admin_print_styles-' . $emp_announcement, array( 'HRP_Menu', 'assets' ) );
			}
		}
	}

	public static function assets() {
		// Enqueue Style.
		wp_enqueue_style( 'style', HRP_PLUGIN_URL . 'assets/css/style.css', array(), HRP_PLUGIN_VERSION );
		wp_enqueue_style( 'Main', HRP_PLUGIN_URL . 'assets/css/main.css', array(), HRP_PLUGIN_VERSION );

		// Enqueue Scripts.
		wp_enqueue_script( 'bootstrap', HRP_PLUGIN_URL . 'assets/js/libraries/bootstrap.bundle.min.js', array(), HRP_PLUGIN_VERSION, true );
		wp_enqueue_script( 'app', HRP_PLUGIN_URL . 'assets/js/nioapp.min.js', array(), HRP_PLUGIN_VERSION, true );
		wp_enqueue_script( 'select2', HRP_PLUGIN_URL . 'assets/js/libraries/select2.full.min.js', array(), HRP_PLUGIN_VERSION, true );
		wp_enqueue_script( 'sweetalert2', HRP_PLUGIN_URL . 'assets/js/libraries/sweetalert2.min.js', array(), HRP_PLUGIN_VERSION, true );
		wp_enqueue_script( 'toastr', HRP_PLUGIN_URL . 'assets/js/libraries/toastr.min.js', array(), HRP_PLUGIN_VERSION, true );
		wp_enqueue_script( 'validate', HRP_PLUGIN_URL . 'assets/js/libraries/jquery.validate.min.js', array(), HRP_PLUGIN_VERSION, true );
		wp_enqueue_script( 'dataTables', HRP_PLUGIN_URL . 'assets/js/datatable/jquery.dataTables.js', array(), HRP_PLUGIN_VERSION, true );
		wp_enqueue_script( 'dataTables-responsive', HRP_PLUGIN_URL . 'assets/js/datatable/dataTables.responsive.min.js', array(), HRP_PLUGIN_VERSION, true );
		wp_enqueue_script( 'dataTables-bootstrap4', HRP_PLUGIN_URL . 'assets/js/datatable/dataTables.bootstrap4.min.js', array(), HRP_PLUGIN_VERSION, true );
		wp_enqueue_script( 'responsive-bootstrap4', HRP_PLUGIN_URL . 'assets/js/datatable/responsive.bootstrap4.min.js', array(), HRP_PLUGIN_VERSION, true );
		wp_enqueue_script( 'datepicker', HRP_PLUGIN_URL . 'assets/js/libraries/bootstrap-datepicker.min.js', array(), HRP_PLUGIN_VERSION, true );
		wp_enqueue_script( 'timepicker', HRP_PLUGIN_URL . 'assets/js/libraries/jquery.timepicker.js', array(), HRP_PLUGIN_VERSION, true );
		wp_enqueue_script( 'scripts', HRP_PLUGIN_URL . 'assets/js/scripts.js', array(), HRP_PLUGIN_VERSION, true );
		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}
		wp_enqueue_script( 'main', HRP_PLUGIN_URL . 'assets/js/main.js', array( 'jquery', 'jquery-form' ), HRP_PLUGIN_VERSION, true );
		wp_localize_script( 'main', 'hrpsecurity', array( wp_create_nonce( 'hrp-security' ) ) );
		wp_localize_script( 'main', 'hrpadminurl', array( admin_url() ) );
	}

	public static function dashboard() {
		require_once HRP_PLUGIN_DIR . 'admin/inc/dashboard/route.php';
	}

	public static function employees() {
		require_once HRP_PLUGIN_DIR . 'admin/inc/employees/route.php';
	}

	public static function departments() {
		require_once HRP_PLUGIN_DIR . 'admin/inc/departments/route.php';
	}

	public static function designations() {
		require_once HRP_PLUGIN_DIR . 'admin/inc/designations/route.php';
	}

	public static function announcements() {
		require_once HRP_PLUGIN_DIR . 'admin/inc/announcements/route.php';
	}

	public static function attendance() {
		require_once HRP_PLUGIN_DIR . 'admin/inc/attendances/route.php';
	}

	public static function shifts() {
		require_once HRP_PLUGIN_DIR . 'admin/inc/shifts/route.php';
	}

	public static function settings() {
		require_once HRP_PLUGIN_DIR . 'admin/inc/settings/settings.php';
	}

	public static function holidays() {
		require_once HRP_PLUGIN_DIR . 'admin/inc/holidays/route.php';
	}

	public static function payroll() {
		require_once HRP_PLUGIN_DIR . 'admin/inc/payroll/payroll.php';
	}

	public static function emp_dash() {
		require_once HRP_PLUGIN_DIR . 'admin/inc/dashboard/employee/index.php';
	}

}
