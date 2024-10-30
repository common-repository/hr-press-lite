<?php
defined( 'ABSPATH' ) || exit;
$page = sanitize_text_field( wp_unslash( isset( $_GET['page'] ) ) );
?>
<ul class="nav nav-tabs nav-tabs-mb-icon nav-tabs-card">
	<li class="nav-item">
		<a class="nav-link <?php ( 'hr_press_emp' === $page ) ? esc_attr_e( 'active' ) : ''; ?>" href="<?php echo esc_url( HRP_Helper::get_page_url( 'hr_press_emp' ) ); ?>"><em class="icon ni ni-dashboard"></em><span><?php esc_html_e( 'Dashboard', 'hrp' ); ?></span></a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php ( 'emp_profile' === $page ) ? esc_attr_e( 'active' ) : ''; ?>" href="<?php echo esc_url( HRP_Helper::get_page_url( 'emp_profile' ) ); ?>"><em class="icon ni ni-user-circle"></em><span><?php esc_html_e( 'Profile', 'hrp' ); ?></span></a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php ( 'emp_holidays' === $page ) ? esc_attr_e( 'active' ) : ''; ?>" href="<?php echo esc_url( HRP_Helper::get_page_url( 'emp_holidays' ) ); ?>"><em class="icon ni ni-gift"></em><span><?php esc_html_e( 'Holidays', 'hrp' ); ?></span></a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php ( 'emp_attendances' === $page ) ? esc_attr_e( 'active' ) : ''; ?>" href="<?php echo esc_url( HRP_Helper::get_page_url( 'emp_attendances' ) ); ?>"><em class="icon ni ni-Calendar-date"></em><span><?php esc_html_e( 'Attendances', 'hrp' ); ?></span></a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php ( 'emp_announcements' === $page ) ? esc_attr_e( 'active' ) : ''; ?>" href="<?php echo esc_url( HRP_Helper::get_page_url( 'emp_announcements' ) ); ?>"><em class="icon ni ni-bell"></em><span><?php esc_html_e( 'Announcements', 'hrp' ); ?></span></a>
	</li>

	<li class="nav-item nav-item-trigger d-xxl-none">
		<a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-user-list-fill"></em></a>
	</li>
</ul>
