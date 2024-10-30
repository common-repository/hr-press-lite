<?php
defined( 'ABSPATH' ) || exit;
require_once HRP_PLUGIN_DIR . 'includes/HRP_Helper.php';

$attendances_page_url = HRP_Helper::get_page_url( ATTENDANCES );
$employee_list        = HRP_Helper::get_employees_list();

$attendance_date    = '';
$checkout           = '';
$checkin            = '';
$attendance_comment = '';
$employee_id        = '';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$attendance_id      = absint( wp_unslash( $_GET['id'] ) );
	$attendance         = HRP_Helper::get_attendance( $attendance_id );
	$attendance_date    = $attendance->date;
	$checkin            = HRP_Helper::time_format( $attendance->checkin );
	$checkout           = HRP_Helper::time_format( $attendance->checkout );
	$attendance_comment = $attendance->comment;
}
?>
<div class="container-fluid">
	<div class="card">
		<div class="card-inner">
			<div class="nk-block-between g-3">
				<div class="nk-block-head nk-block-head-sm">
					<div class="nk-block-between g-3">
						<div class="nk-block-head-content">
							<h4 class="nk-block-title "><?php ! empty( $attendance_id ) ? esc_html_e( 'Update Attendance Information', 'hrp' ) : esc_html_e( 'Add New Attendance', 'hrp' ); ?>
							</h4>
						</div>
					</div>
				</div>
				<div class="nk-block-head-content">
					<a href="<?php echo esc_url( $attendances_page_url ); ?>" class="btn btn-outline-light bg-white d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em>
						<span><?php esc_html_e( 'Back', 'hrp' ); ?></span></a>
				</div>
			</div>

			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="gy-3 form-validate is-alter hrp-save-form" novalidate="novalidate" id="hrp-save-form">
				<input type="hidden" name="<?php echo esc_attr( 'save-attendance' ); ?>" value="<?php echo esc_attr( wp_create_nonce( 'save-attendance' ) ); ?>">
				<input type="hidden" name="action" value="hrp-save-attendance">
				<input type="hidden" name="attendance_id" value="<?php echo esc_attr( $attendance_id ); ?>">
				<div class="row gy-4">

					<div class="data-head">
						<h6 class="overline-title"><?php esc_html_e( 'Attendance Details', 'hrp' ); ?></h6>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label class="form-label required" for="date"><?php esc_html_e( 'Date', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<div class="form-icon form-icon-left"><em class="icon ni ni-calendar"></em></div>
								<input type="text" class="form-control date-picker" id="date" name="date" placeholder="<?php esc_attr_e( 'Enter Date', 'hrp' ); ?>" value="<?php echo esc_attr( HRP_Helper::date_format( $attendance_date ) ); ?>" required autocomplete="off" data-date-format="mm/dd/yyyy">
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label class="form-label required" for="employee_id"><?php esc_html_e( 'Employee', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<select class="form-select js-select2" id="employee_id" name="employee_id" data-search="on">
									<?php foreach ( $employee_list as $employee ) : ?>
										<option value="<?php echo esc_attr( $employee->ID ); ?>" <?php selected( $employee->ID, $employee_id ); ?>> <?php echo esc_html( ucwords( $employee->name ) ); ?> </option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label class="form-label"><?php esc_html_e( 'Checkin', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control time-picker" id="checkin" name="checkin" placeholder="Enter checkin time" value="<?php echo esc_attr( $checkin ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label class="form-label"><?php esc_html_e( 'Checkout', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control time-picker" id="checkout" name="checkout" placeholder="Enter checkout time" value="<?php echo esc_attr( $checkout ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label class="form-label" for="comment"><?php esc_html_e( 'Comment', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<textarea class="form-control no-resize" name="comment" id="comment" spellcheck="false" data-ms-editor="true" placeholder="<?php esc_attr_e( 'Enter Comment', 'hrp' ); ?>"><?php echo esc_attr( $attendance_comment ); ?></textarea>
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
