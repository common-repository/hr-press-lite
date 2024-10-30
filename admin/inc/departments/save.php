<?php
defined( 'ABSPATH' ) || exit;
require_once HRP_PLUGIN_DIR . 'includes/HRP_Helper.php';

$departments_page_url = HRP_Helper::get_page_url( DEPARTMENTS );
$manager_list         = HRP_Helper::get_employees_list();

$department_title       = '';
$department_description = '';
$manager                = '';
$manager_id             = '';
$department_id          = '';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$department_id          = absint( $_GET['id'] );
	$department             = HRP_Helper::get_department( $department_id );
	$department_title       = $department->title;
	$department_description = $department->description;
	$manager_id             = $department->employee_id;
}
?>
<div class="container-fluid">
	<div class="card">
		<div class="card-inner">
			<div class="nk-block-between g-3">
				<div class="nk-block-head nk-block-head-sm">
					<div class="nk-block-between g-3">
						<div class="nk-block-head-content">
							<h4 class="nk-block-title "><?php ! empty( $department_id ) ? esc_html_e( 'Update Department Information', 'hrp' ) : esc_html_e( 'Add New Department', 'hrp' ); ?>
							</h4>
						</div>
					</div>
				</div>
				<div class="nk-block-head-content">
					<a href="<?php echo esc_url( $departments_page_url ); ?>" class="btn btn-outline-light bg-white d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em>
						<span><?php esc_html_e( 'Back', 'hrp' ); ?></span></a>
				</div>
			</div>

			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="gy-3 form-validate is-alter hrp-save-form" novalidate="novalidate" id="hrp-save-form">
				<input type="hidden" name="<?php echo esc_attr( 'save-department' ); ?>" value="<?php echo esc_attr( wp_create_nonce( 'save-department' ) ); ?>">
				<input type="hidden" name="action" value="hrp-save-department">
				<input type="hidden" name="department_id" value="<?php echo esc_attr( $department_id ); ?>">
				<div class="row gy-4">

					<div class="data-head">
						<h6 class="overline-title"><?php esc_html_e( 'Department Details', 'hrp' ); ?></h6>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label required" for="department-title"><?php esc_html_e( 'Department Title', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="department-title" name="title" placeholder="<?php esc_attr_e( 'Enter Department Title', 'hrp' ); ?>" value="<?php echo esc_attr( $department_title ); ?>" required>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="employee_id"><?php esc_html_e( 'Department Manager', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<select class="form-select js-select2" id="employee_id" name="employee_id">
									<option value="0"><?php esc_html_e( 'Select Department Manager', 'hrp' ); ?></option>
									<?php foreach ( $manager_list as $manager ) : ?>
										<option value="<?php echo esc_attr( $manager->ID ); ?>" <?php selected( $manager->ID, ( $manager_id ) ); ?>><?php echo esc_html( ucwords( $manager->name ) ); ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label class="form-label" for="department-description"><?php esc_html_e( 'Department Description', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<textarea class="form-control no-resize" name="description" id="department-description" spellcheck="false" data-ms-editor="true" placeholder="<?php esc_attr_e( 'Enter Department Description', 'hrp' ); ?>"><?php echo esc_attr( $department_description ); ?></textarea>
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
