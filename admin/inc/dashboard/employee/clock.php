<?php
defined( 'ABSPATH' ) || exit;
require HRP_PLUGIN_DIR . 'includes/Month_Calendar.php';
$calendar   = new Month_Calendar();
$attendance = HRP_Helper::get_attendance_by_date( date( 'Y-m-d' ), $employee_id );
if ( $attendance ) {
	$checkin  = $attendance->checkin;
	$checkout = $attendance->checkout;
	$breakin  = $attendance->breakin;
	$breakout = $attendance->breakout;
} else {
	$checkin  = '';
	$checkout = '';
	$breakin  = '';
	$breakout = '';
}

$shift = HRP_Helper::get_shift( $employee->shift_id );
if ( ! empty( $shift ) ) {
	$checkin_time_diff  = abs( ( strtotime( $shift->start_time ) - strtotime( $checkin ) ) / 60 );
	$checkout_time_diff = abs( ( strtotime( $shift->end_time ) - strtotime( $checkout ) ) / 60 );
}


$settings              = HRP_Helper::get_attendance_settings();
$grace_before_checkin  = $settings['grace_before_checkin'];
$grace_before_checkout = $settings['grace_before_checkout'];

$ip_address = HRP_Helper::get_user_ip_address();
?>
<div class="card-inner">
	<div class="row g-3 align-center mt-1">
		<div class="col-lg-4">

			<div class="clock h1">
				<div class="time"></div>
			</div>
			<div class="date-time text-soft"></div>

		</div>
		<div class="col-md-8 d-flex flex-wrap justify-content-end">

			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="form-validate" novalidate="novalidate" id="hrp-checkin-form">
				<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'save-checkin' ) ); ?>">
				<input type="hidden" name="action" value="hrp-save-checkin">
				<input type="hidden" name="employee_id" value="<?php echo esc_attr( $employee_id ); ?>">
				<input type="hidden" name="ip_address" value="<?php echo esc_attr( $ip_address ); ?>">
				<button id="hrp-checkin-btn" type="button" class="btn btn-lg btn-primary m-1" <?php ( $checkin ) ? esc_attr_e( 'disabled' ) : ''; ?>><em class="icon ni ni-clock-fill"></em><span><?php esc_html_e( 'Check In', 'hrp' ); ?></span></button>
			</form>

			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="form-validate" novalidate="novalidate" id="hrp-checkout-form">
				<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'save-checkout' ) ); ?>">
				<input type="hidden" name="action" value="hrp-save-checkout">
				<input type="hidden" name="employee_id" value="<?php echo esc_attr( $employee_id ); ?>">
				<button id="hrp-checkout-btn" type="button" class="btn btn-lg btn-primary m-1" <?php ( $checkout ) ? esc_attr_e( 'disabled' ) : ''; ?>><em class="icon ni ni-clock-fill"></em><span><?php esc_html_e( 'Check Out', 'hrp' ); ?></span></button>
			</form>

			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="form-validate" novalidate="novalidate" id="hrp-breakin-form">
				<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'save-breakin' ) ); ?>">
				<input type="hidden" name="action" value="hrp-save-breakin">
				<input type="hidden" name="employee_id" value="<?php echo esc_attr( $employee_id ); ?>">
				<button id="hrp-breakin-btn" type="button" class="btn btn-lg btn-primary m-1" <?php ( $breakin ) ? esc_attr_e( 'disabled' ) : ''; ?>><em class="icon ni ni-coffee-fill"></em><span><?php esc_html_e( 'Break In', 'hrp' ); ?></span></button>
			</form>

			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="form-validate" novalidate="novalidate" id="hrp-breakout-form">
				<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'save-breakout' ) ); ?>">
				<input type="hidden" name="action" value="hrp-save-breakout">
				<input type="hidden" name="employee_id" value="<?php echo esc_attr( $employee_id ); ?>">
				<button id="hrp-breakout-btn" type="button" class="btn btn-lg btn-primary m-1" <?php ( $breakout ) ? esc_attr_e( 'disabled' ) : ''; ?>><em class="icon ni ni-coffee-fill"></em><span><?php esc_html_e( 'Break Out', 'hrp' ); ?></span></button>
			</form>

		</div>
	</div>
</div>

<?php if ( $attendance ) : ?>

	<div class="card-inner row g-3">
		<?php if ( $checkin ) : ?>
			<div class="example-alert col-md-3">
				<div class="alert alert-icon <?php ( $checkin_time_diff >= $grace_before_checkin ) ? esc_attr_e( 'alert-danger' ) : esc_attr_e( 'alert-success' ); ?>">
					<em class="icon ni ni-check-circle"></em> <strong></strong><?php esc_html_e( 'Check-in at', 'hrp' ); ?> <?php echo esc_html( HRP_Helper::time_format( $checkin ) ); ?>
				</div>
			</div>
		<?php endif ?>

		<?php if ( $checkout ) : ?>
			<div class="example-alert col-md-3">
				<div class="alert alert-icon <?php ( $checkout_time_diff >= $grace_before_checkout ) ? esc_attr_e( 'alert-danger' ) : esc_attr_e( 'alert-success' ); ?>">
					<em class="icon ni ni-check-circle"></em> <strong><?php esc_html_e( 'Checked-out at', 'hrp' ); ?></strong> <?php echo esc_html( HRP_Helper::time_format( $checkout ) ); ?>
				</div>
			</div>
		<?php endif ?>

		<?php if ( $breakin ) : ?>
			<div class="example-alert col-md-3">
				<div class="alert alert-primary alert-icon">
					<em class="icon ni ni-check-circle"></em> <strong><?php esc_html_e( 'Break Started at', 'hrp' ); ?></strong> <?php echo esc_html( HRP_Helper::time_format( $breakin ) ); ?>
				</div>
			</div>
		<?php endif ?>

		<?php if ( $breakout ) : ?>
			<div class="example-alert col-md-3">
				<div class="alert alert-primary alert-icon">
					<em class="icon ni ni-check-circle"></em> <strong><?php esc_html_e( 'Break Ended at', 'hrp' ); ?></strong> <?php echo esc_html( HRP_Helper::time_format( $attendance->breakout ) ); ?>
				</div>
			</div>
		<?php endif ?>
	</div>

<?php endif ?>

<div class="card-inner">
	<?php
	$attendances = HRP_Helper::get_attendance_by_employee_id( $employee_id );
	$holidays    = HRP_Helper::get_holidays_list();

	foreach ( $holidays as $holiday ) {
		$dates = HRP_Helper::getDatesFromRange( $holiday->start_date, $holiday->end_date );

		foreach ( $dates as $date ) {
			$calendar->add_event( esc_html__( 'HOLIDAY', 'hrp' ), $date, 1, 'badge bg-warning' );
		}
	}

	foreach ( $attendances as $attendance ) {
		$checkin  = HRP_Helper::time_format( $attendance->checkin );
		$checkout = HRP_Helper::time_format( $attendance->checkout );
		$present  = ( $attendance->status ) ? esc_html__( 'PRESENT', 'hrp' ) : '';

		$calendar->add_event( $present, $attendance->date, 1, 'badge bg-success' );
	}
	echo wp_kses_post( $calendar );
	?>
</div>
