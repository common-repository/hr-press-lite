<?php
defined( 'ABSPATH' ) || exit;
require_once HRP_PLUGIN_DIR . 'includes/HRP_Helper.php';
$current_user_id = get_current_user_id();
$employee        = HRP_Helper::get_employee_by_user_id( $current_user_id );
$employee_id     = $employee->ID;
$employee_photo  = ( ! empty( wp_get_attachment_url( $employee->photo_id ) ) ) ? wp_get_attachment_url( $employee->photo_id ) : '';

$user_info           = get_userdata( $current_user_id );
$employee_registered = $user_info->user_registered;
?>
<div class="container-fluid">
	<div class="card">
		<div class="card-aside-wrap">
			<div class="card-content">
				<?php
				require HRP_PLUGIN_DIR . 'admin/inc/dashboard/employee/navigation.php';

				if ( 'emp_announcements' === $page ) {
					require_once HRP_PLUGIN_DIR . 'admin/inc/dashboard/employee/emp-announcement.php';
				} elseif ( 'emp_profile' === $page ) {
					require_once HRP_PLUGIN_DIR . 'admin/inc/dashboard/employee/profile.php';
				} elseif ( 'emp_holidays' === $page ) {
					require_once HRP_PLUGIN_DIR . 'admin/inc/dashboard/employee/emp-holidays.php';
				} elseif ( 'emp_attendances' === $page ) {
					require_once HRP_PLUGIN_DIR . 'admin/inc/dashboard/employee/emp-attendance.php';
				} elseif ( 'hr_press_emp' === $page ) {
					require_once HRP_PLUGIN_DIR . 'admin/inc/dashboard/employee/clock.php';
				}
				?>
			</div>
			<?php require HRP_PLUGIN_DIR . 'admin/inc/dashboard/employee/emp-details.php'; ?>
		</div>
	</div>
</div>
