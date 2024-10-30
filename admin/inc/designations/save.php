<?php
defined( 'ABSPATH' ) || exit;
require_once HRP_PLUGIN_DIR . 'includes/HRP_Helper.php';

$designations_page_url = HRP_Helper::get_page_url( DESIGNATIONS );

$designation_title       = '';
$designation_description = '';
$designation_id          = '';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$designation_id          = absint( $_GET['id'] );
	$designation             = HRP_Helper::get_designation( $designation_id );
	$designation_title       = $designation->title;
	$designation_description = $designation->description;
}
?>
<div class="container-fluid">
	<div class="card">
		<div class="card-inner">
			<div class="nk-block-between g-3">
				<div class="nk-block-head nk-block-head-sm">
					<div class="nk-block-between g-3">
						<div class="nk-block-head-content">
							<h4 class="nk-block-title "><?php ! empty( $designation_id ) ? esc_html_e( 'Update Designation Information', 'hrp' ) : esc_html_e( 'Add New Designation', 'hrp' ); ?>
							</h4>
						</div>
					</div>
				</div>
				<div class="nk-block-head-content">
					<a href="<?php echo esc_url( $designations_page_url ); ?>" class="btn btn-outline-light bg-white d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em>
						<span><?php esc_html_e( 'Back', 'hrp' ); ?></span></a>
				</div>
			</div>

			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="gy-3 form-validate is-alter hrp-save-form" novalidate="novalidate" id="hrp-save-form">
				<input type="hidden" name="<?php echo esc_attr( 'save-designation' ); ?>" value="<?php echo esc_attr( wp_create_nonce( 'save-designation' ) ); ?>">
				<input type="hidden" name="action" value="hrp-save-designation">
				<input type="hidden" name="designation_id" value="<?php echo esc_attr( $designation_id ); ?>">
				<div class="row gy-4">

					<div class="data-head">
						<h6 class="overline-title"><?php esc_html_e( 'Designation Details', 'hrp' ); ?></h6>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label required" for="designation-title"><?php esc_html_e( 'Designation Title', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="designation-title" name="title" placeholder="<?php esc_attr_e( 'Enter Designation Title', 'hrp' ); ?>" value="<?php echo esc_attr( $designation_title ); ?>" required>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label class="form-label" for="designation-description"><?php esc_html_e( 'Designation Description', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<textarea class="form-control no-resize" name="description" id="designation-description" spellcheck="false" data-ms-editor="true" placeholder="<?php esc_attr_e( 'Enter Designation Description', 'hrp' ); ?>"><?php echo esc_attr( $designation_description ); ?></textarea>
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
