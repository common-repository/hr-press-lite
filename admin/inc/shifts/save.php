<?php
defined( 'ABSPATH' ) || exit;
require_once HRP_PLUGIN_DIR . 'includes/HRP_Helper.php';

$shift_id    = '';
$shift_title = '';
$start_time  = '';
$end_time    = '';

$shifts_page_url = HRP_Helper::get_page_url( SHIFTS );
$holidays        = HRP_Helper::holidays();

$selected_days = array();
if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$shift_id       = absint( $_GET['id'] );
	$shift          = HRP_Helper::get_shift( $shift_id );
	$shift_title    = $shift->title;
	$start_time     = HRP_Helper::time_format( $shift->start_time );
	$end_time       = HRP_Helper::time_format( $shift->end_time );
	$shift_holidays = unserialize( $shift->holidays );

	foreach ( $shift_holidays as $value ) {
		array_push( $selected_days, $value );
	}
}
?>
<div class="container-fluid">
	<div class="card">
		<div class="card-inner">
			<div class="nk-block-between g-3">
				<div class="nk-block-head nk-block-head-sm">
					<div class="nk-block-between g-3">
						<div class="nk-block-head-content">
							<h4 class="nk-block-title ">
								<?php
								if ( $shift_id ) {
									esc_html_e( 'Update Shift Information', 'hrp' );
								} else {
									esc_html_e( 'Add New Shift', 'hrp' );
								}
								?>
							</h4>
						</div>
					</div>
				</div>
				<div class="nk-block-head-content">
					<a href="<?php echo esc_url( $shifts_page_url ); ?>" class="btn btn-outline-light bg-white d-sm-inline-flex">
						<em class="icon ni ni-arrow-left"></em><span><?php esc_html_e( 'Back', 'hrp' ); ?></span>
					</a>
				</div>
			</div>

			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="gy-3 form-validate is-alter hrp-save-form" novalidate="novalidate" id="hrp-save-form">
				<input type="hidden" name="<?php echo esc_attr( 'save-shift' ); ?>" value="<?php echo esc_attr( wp_create_nonce( 'save-shift' ) ); ?>">
				<input type="hidden" name="action" value="hrp-save-shift">
				<input type="hidden" name="shift_id" value="<?php echo esc_attr( $shift_id ); ?>">
				<div class="row gy-4">

					<div class="data-head">
						<h6 class="overline-title"><?php esc_html_e( 'Shift Details', 'hrp' ); ?></h6>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label required" for="shift-title"><?php esc_html_e( 'Shift Title', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="shift-title" name="title" placeholder="<?php echo esc_attr( 'Enter Shift Title' ); ?>" value="<?php echo esc_attr( $shift_title ); ?>" required>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label"><?php esc_html_e( 'Shift Start Time', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control time-picker" id="start_time" name="start_time" placeholder="Shift Start Time" value="<?php echo esc_attr( $start_time ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label"><?php esc_html_e( 'Shift End Time', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control time-picker" id="start_time" name="end_time" placeholder="Shift End Time" value="<?php echo esc_attr( $end_time ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="shift_holiday"><?php esc_html_e( 'Holidays', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<select class="form-select js-select2" id="shift_holiday" name="holidays[]" multiple>
									<?php foreach ( $holidays as $key => $holiday ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( true, in_array( $key, $selected_days, true ) ); ?>><?php echo esc_html( $holiday ); ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-sm-12">
						<ul class="preview-list ">
							<li class="preview-item">
								<button type="submit" class="btn btn-primary hrp-save-btn" id="hrp-save-btn">
									<span><?php esc_html_e( 'Submit', 'hrp' ); ?></span>
								</button>
							</li>
						</ul>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
