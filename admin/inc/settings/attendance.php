<?php
defined( 'ABSPATH' ) || exit;
$settings              = HRP_Helper::get_attendance_settings();
$grace_before_checkin  = $settings['grace_before_checkin'];
$attendance_threshold  = $settings['attendance_threshold'];
$grace_before_checkout = $settings['grace_before_checkout'];
?>
<div class="tab-pane" id="attendance">
	<!-- head -->
	<div class="nk-block-head nk-block-head-lg">
		<div class="nk-block-between">
			<div class="nk-block-head-content">
				<h6 class="nk-block-title"><?php esc_html_e( 'Attendance Settings', 'hrp' ); ?></h6>
				<span><?php esc_html_e( 'These settings helps you modify attendance settings.', 'hrp' ); ?></span>
			</div>
			<div class="nk-block-head-content align-self-start d-lg-none">
				<a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
			</div>
		</div>
	</div>

	<!-- form. -->
	<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="gy-3 form-validate is-alter hrp-save-form" novalidate="novalidate">
		<?php
		$nonce_action = 'save-attendance-settings';
		$nonce        = wp_create_nonce( $nonce_action );
		?>
		<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">
		<input type="hidden" name="action" value="hrp-save-attendance-settings">

		<div class="data-head">
			<h6 class="overline-title"><?php esc_html_e( 'Attendance Timing Configurations', 'hrp' ); ?></h6>
		</div>

		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label" for="grace_before_checkin"><?php esc_html_e( 'Grace Before Time Checkin', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'This time will not counted as overtime. (in minute)', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="form-group">
					<div class="form-control-wrap">
						<input type="text" class="form-control" id="grace_before_checkin" name="grace_before_checkin" value="<?php echo esc_attr( $grace_before_checkin ); ?>" placeholder="Enter grace time">
					</div>
				</div>
			</div>
		</div>

		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label" for="attendance_threshold"><?php esc_html_e( 'Threshold between checkin & checkout', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'This time will prevent quick checkin after making a checkout. (in second)', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="form-group">
					<div class="form-control-wrap">
						<input type="text" class="form-control" id="attendance_threshold" name="attendance_threshold" value="<?php echo esc_attr( $attendance_threshold ); ?>" placeholder="Enter Threshold grace time">
					</div>
				</div>
			</div>
		</div>

		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label" for="grace_before_checkout"><?php esc_html_e( 'Grace Before Time Checkout', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'this time will not counted as early left. (in minute)', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="form-group">
					<div class="form-control-wrap">
						<input type="text" class="form-control" id="grace_before_checkout" name="grace_before_checkout" value="<?php echo esc_attr( $grace_before_checkout ); ?>" placeholder="Enter grace time">
					</div>
				</div>
			</div>
		</div>

		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label" for="ip_restriction"><?php esc_html_e( 'IP Restriction', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'Enable IP restriction for checkin/checkout', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<a class="btn btn-success" href="<?php echo esc_url( 'https://codeclove.com/hrpress' ); ?>"><?php esc_html_e( 'Get Hr-Press Pro', 'hrp' ); ?></a>
			</div>
		</div>

		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label" for="whitelisted_ip"><?php esc_html_e( 'Whitelisted IP\'s', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'Employees from this IP addresses will be able to self check-in. Put one IP in each line.', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<a class="btn btn-success" href="<?php echo esc_url( 'https://codeclove.com/hrpress' ); ?>"><?php esc_html_e( 'Get Hr-Press Pro', 'hrp' ); ?></a>
			</div>
		</div>

		<div class="row g-3">
			<div class="col-lg-7">
				<div class="form-group mt-2">
					<button type="button" class="btn btn-lg btn-primary hrp-save-btn">
						<span><?php esc_html_e( 'Update', 'hrp' ); ?></span>
					</button>
				</div>
			</div>
		</div>
	</form>
</div>
