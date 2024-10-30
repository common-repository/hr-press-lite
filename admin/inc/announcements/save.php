<?php
defined( 'ABSPATH' ) || exit;
require_once HRP_PLUGIN_DIR . 'includes/HRP_Helper.php';

$announcements_page_url = HRP_Helper::get_page_url( ANNOUNCEMENTS );
$send_to_list           = HRP_Helper::announcement_type_list();
$employee_list          = HRP_Helper::get_data( HRP_Helper::get_employees() );
$department_list        = HRP_Helper::get_data( HRP_Helper::get_departments() );
$designation_list       = HRP_Helper::get_data( HRP_Helper::get_designations() );

$selected_employee        = array();
$selected_department      = array();
$selected_designation     = array();
$announcement_id          = '';
$announcement_title       = '';
$announcement_description = '';
$announcement_send_to     = '';
if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$announcement_id           = absint( $_GET['id'] );
	$announcement              = HRP_Helper::get_announcement( $announcement_id );
	$announcement_title        = $announcement->title;
	$announcement_description  = $announcement->announcement;
	$announcement_send_to      = $announcement->send_to;
	$announcement_announced_to = unserialize( $announcement->announced_to );

	if ( 'by_employee' === $announcement_send_to ) {
		foreach ( $announcement_announced_to as $value ) {
			array_push( $selected_employee, $value );
		}
	} elseif ( 'by_department' === $announcement_send_to ) {
		foreach ( $announcement_announced_to as $value ) {
			array_push( $selected_department, $value );
		}
	} elseif ( 'by_designation' === $announcement_send_to ) {
		foreach ( $announcement_announced_to as $value ) {
			array_push( $selected_designation, $value );
		}
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
								if ( $announcement_id ) {
									esc_html_e( 'Update Announcement Information', 'hrp' );
								} else {
									esc_html_e( 'Add New Announcement', 'hrp' );
								}
								?>
							</h4>
						</div>
					</div>
				</div>
				<div class="nk-block-head-content">
					<a href="<?php echo esc_url( $announcements_page_url ); ?>" class="btn btn-outline-light bg-white d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em>
						<span><?php esc_html_e( 'Back', 'hrp' ); ?></span></a>
				</div>
			</div>

			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="gy-3 form-validate is-alter hrp-save-form" novalidate="novalidate" id="hrp-save-form">
				<input type="hidden" name="<?php echo esc_attr( 'save-announcement' ); ?>" value="<?php echo esc_attr( wp_create_nonce( 'save-announcement' ) ); ?>">
				<input type="hidden" name="action" value="hrp-save-announcement">
				<input type="hidden" name="announcement_id" value="<?php echo esc_attr( $announcement_id ); ?>">
				<div class="row gy-4">

					<div class="data-head">
						<h6 class="overline-title"><?php esc_html_e( 'Announcement Details', 'hrp' ); ?></h6>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label required" for="announcement-title"><?php esc_html_e( 'Title', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="announcement-title" name="title" placeholder="<?php esc_attr_e( 'Enter Title', 'hrp' ); ?>" value="<?php echo esc_attr( $announcement_title ); ?>" required>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="send_to"><?php esc_html_e( 'Send Announcement To', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<select class="form-select js-select2" id="send_to" name="send_to">
									<?php foreach ( $send_to_list as  $key => $send_to ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, ( $announcement_send_to ) ); ?>><?php echo esc_html( $send_to ); ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-4 announcement-options" style="<?php ( empty( $selected_employee ) ) ? esc_attr_e( 'display: none;' ) : ''; ?>" id="by_employee">
						<div class="form-group">
							<label class="form-label" for="employee"><?php esc_html_e( 'Select Employee', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<select class="form-select js-select2" id="employee" name="employee[]" multiple>
									<?php foreach ( $employee_list as $employee ) : ?>
										<option value="<?php echo esc_attr( $employee->ID ); ?>" <?php selected( true, in_array( $employee->ID, $selected_employee, true ) ); ?>><?php echo esc_html( $employee->name ); ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-4 announcement-options" style="<?php ( empty( $selected_department ) ) ? esc_attr_e( 'display: none;' ) : ''; ?>" id="by_department">
						<div class="form-group">
							<label class="form-label" for="department"><?php esc_html_e( 'Select Department', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<select class="form-select js-select2" id="department" name="department[]" multiple>
									<?php foreach ( $department_list as $department ) : ?>
										<option value="<?php echo esc_attr( $department->ID ); ?>" <?php selected( true, in_array( $department->ID, $selected_department, true ) ); ?>><?php echo esc_html( ucwords( $department->title ) ); ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-4 announcement-options" style="<?php ( empty( $selected_designation ) ) ? esc_attr_e( 'display: none;' ) : ''; ?>" id="by_designation">
						<div class="form-group">
							<label class="form-label" for="designation"><?php esc_html_e( 'Select Designation', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<select class="form-select js-select2" id="designation" name="designation[]" multiple>
									<?php foreach ( $designation_list as $designation ) : ?>
										<option value="<?php echo esc_attr( $designation->ID ); ?>" <?php selected( true, in_array( $designation->ID, $selected_designation, true ) ); ?>><?php echo esc_html( ucwords( $designation->title ) ); ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label class="form-label" for="announcement"><?php esc_html_e( 'Announcement', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<textarea class="form-control " name="announcement" id="announcement" spellcheck="false" data-ms-editor="true" placeholder="<?php esc_attr_e( 'Enter announcement here...', 'hrp' ); ?>"><?php echo esc_attr( $announcement_description ); ?></textarea>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<h6 class="title mb-3 mt-4"><?php esc_html_e( 'Send Notifications', 'hrp' ); ?></h6>
							<ul class="custom-control-group">
								<li>
									<div class="custom-control custom-checkbox custom-control-pro no-control">
										<input type="checkbox" class="custom-control-input" name="email" id="email">
										<label class="custom-control-label" for="email"><?php esc_html_e( 'EMAIL', 'hrp' ); ?></label>
									</div>
								</li>
							</ul>
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
