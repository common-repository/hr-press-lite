<?php
defined( 'ABSPATH' ) || exit;
$settings           = HRP_Helper::get_general_settings();
$company_name       = $settings['company_name'];
$company_logo_id    = $settings['company_logo'];
$company_email      = $settings['company_email'];
$company_address    = $settings['company_address'];
$employee_id_prefix = $settings['employee_id_prefix'];
?>
<div class="tab-pane active" id="general_settings">

	<!-- head -->
	<div class="nk-block-head nk-block-head-lg">
		<div class="nk-block-between">
			<div class="nk-block-head-content">
				<h6 class="nk-block-title"><?php esc_html_e( 'General Settings', 'hrp' ); ?></h6>
				<span><?php esc_html_e( 'These settings helps you modify your settings.', 'hrp' ); ?></span>
			</div>
			<div class="nk-block-head-content align-self-start d-lg-none">
				<a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
			</div>
		</div>
	</div>

	<!-- form. -->
	<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="gy-3 form-validate is-alter" novalidate="novalidate" id="hrp-general-setting-form">
		<?php
		$nonce_action = 'save-company-details';
		$nonce        = wp_create_nonce( $nonce_action );
		?>
		<input type="hidden" name="nonce" value="<?php echo esc_attr( $nonce ); ?>">
		<input type="hidden" name="action" value="hrp-save-company-details">

		<div class="data-head">
			<h6 class="overline-title"><?php esc_html_e( 'Company Details', 'hrp' ); ?></h6>
		</div>
		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label" for="company-name"><?php esc_html_e( 'Name', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'Specify the company name.', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="form-group">
					<div class="form-control-wrap">
						<input type="text" class="form-control" id="company-name" name="name" value="<?php echo esc_attr( $company_name ); ?>" placeholder="Company Name">
					</div>
				</div>
			</div>
		</div>
		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label" for="company-email"><?php esc_html_e( 'Email', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'Specify the email address of your company.', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="form-control-wrap">
					<div class="form-icon form-icon-right"><em class="icon ni ni-mail"></em></div><input type="text" class="form-control" id="email" name="email" placeholder="Company Email" value="<?php echo esc_attr( $company_email ); ?>">
				</div>
			</div>
		</div>
		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label" for="company-address"><?php esc_html_e( 'Address', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'Specify the address of your company.', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="form-group">
					<div class="form-control-wrap">
						<textarea class="form-control form-control-sm" name="address" placeholder="Enter Company Address."><?php echo esc_html( $company_address ); ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label"><?php esc_html_e( 'Logo', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'Upload your company logo.', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="form-group">
					<div class="form-control-wrap">
						<div class="form-file">
							<input type="text" class="form-file-input file-upload" id="company-logo" name="logo" value="<?php echo esc_attr( $company_logo_id ); ?>">
							<label class="form-file-label file-label" id="file-label" for="company-logo">
								<?php
								if ( $company_logo_id ) {
									echo esc_attr( basename( ( get_attached_file( $company_logo_id ) ) ) );
								} else {
									echo esc_attr( 'Choose file', 'hrp' );
								}
								?>

							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="user-card user-card-s2">
					<div class="user-avatar sq lg">
						<?php if ( $company_logo_id ) : ?>
							<img src="<?php echo esc_url( wp_get_attachment_image_url( $company_logo_id ) ); ?>" alt="company-logo">
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>

		<div class="data-head">
			<h6 class="overline-title"><?php esc_html_e( 'General Settings', 'hrp' ); ?></h6>
		</div>

		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label" for="employee-id-prefix"><?php esc_html_e( 'Employee ID Prefix ', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'Specify the front-end dashboard title.', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="form-group">
					<div class="form-control-wrap">
						<input type="text" class="form-control" id="employee-id-prefix" name="employee_id_prefix" value="<?php echo esc_attr( $employee_id_prefix ); ?>" placeholder="Enter Employee ID Prefix">
					</div>
				</div>
			</div>
		</div>

		<div class="row g-3">
			<div class="col-lg-7">
				<div class="form-group mt-2">
					<button type="button" class="btn btn-lg btn-primary " id="hrp-general-setting-btn">
						<span><?php esc_html_e( 'Update', 'hrp' ); ?></span>
					</button>
				</div>
			</div>
		</div>
	</form>
</div>
