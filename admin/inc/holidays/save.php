<?php
defined( 'ABSPATH' ) || exit;
require_once HRP_PLUGIN_DIR . 'includes/HRP_Helper.php';

$holidays_page_url = HRP_Helper::get_page_url( HOLIDAYS );

$holidays      = HRP_Helper::holidays();
$holiday_title = '';
$start_date    = '';
$end_date      = '';
$description   = '';
$holiday_id    = '';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$holiday_id    = absint( $_GET['id'] );
	$holiday       = HRP_Helper::get_holiday( $holiday_id );
	$holiday_title = $holiday->title;
	$start_date    = HRP_Helper::date_format( $holiday->start_date );
	$end_date      = HRP_Helper::date_format( $holiday->end_date );
	$description   = $holiday->description;
}
?>
<div class="container-fluid">
	<div class="card">
		<div class="card-inner">
			<div class="nk-block-between g-3">
				<div class="nk-block-head nk-block-head-sm">
					<div class="nk-block-between g-3">
						<div class="nk-block-head-content">
							<h4 class="nk-block-title "><?php ! empty( $holiday_id ) ? esc_html_e( 'Update Holiday Information', 'hrp' ) : esc_html_e( 'Add New Holiday', 'hrp' ); ?>
							</h4>
						</div>
					</div>
				</div>
				<div class="nk-block-head-content">
					<a href="<?php echo esc_url( $holidays_page_url ); ?>" class="btn btn-outline-light bg-white d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em>
						<span><?php esc_html_e( 'Back', 'hrp' ); ?></span></a>
				</div>
			</div>

			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="gy-3 form-validate is-alter hrp-save-form" novalidate="novalidate" id="hrp-save-form">
				<input type="hidden" name="<?php echo esc_attr( 'save-holiday' ); ?>" value="<?php echo esc_attr( wp_create_nonce( 'save-holiday' ) ); ?>">
				<input type="hidden" name="action" value="hrp-save-holiday">
				<input type="hidden" name="holiday_id" value="<?php echo esc_attr( $holiday_id ); ?>">
				<div class="row gy-4">

					<div class="data-head">
						<h6 class="overline-title"><?php esc_html_e( 'Holiday Details', 'hrp' ); ?></h6>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label required" for="holiday-title"><?php esc_html_e( 'Holiday Title', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="holiday-title" name="title" placeholder="<?php esc_attr_e( 'Enter Holiday Title', 'hrp' ); ?>" value="<?php echo esc_attr( $holiday_title ); ?>" required>
							</div>
						</div>
					</div>

					<div class="col-md-8">
						<div class="form-group">
							<label class="form-label"><?php esc_html_e( 'Date Range', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<div class="input-daterange date-picker-range input-group">
									<input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo esc_attr( $start_date ); ?>" autocomplete="off" />
									<div class="input-group-addon"><?php esc_html_e( 'TO', 'hrp' ); ?></div>
									<input type="text" class="form-control" id="end_date" name="end_date" value="<?php echo esc_attr( $end_date ); ?>" autocomplete="off" />
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label class="form-label" for="description"><?php esc_html_e( 'Description', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<textarea class="form-control no-resize" name="description" id="description" spellcheck="false" data-ms-editor="true" placeholder="<?php esc_attr_e( 'Enter Description', 'hrp' ); ?>"><?php echo esc_attr( $description ); ?></textarea>
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
