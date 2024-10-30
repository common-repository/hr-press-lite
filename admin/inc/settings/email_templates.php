<?php
defined( 'ABSPATH' ) || exit;
$settings = HRP_Helper::get_email_template_settings();
?>
<div class="tab-pane" id="email_template">
	<!-- head -->
	<div class="nk-block-head nk-block-head-lg">
		<div class="nk-block-between">
			<div class="nk-block-head-content">
				<h6 class="nk-block-title"><?php esc_html_e( 'Email Templates', 'hrp' ); ?></h6>
				<span><?php esc_html_e( 'These settings helps you modify email templates settings.', 'hrp' ); ?></span>
			</div>
			<div class="nk-block-head-content align-self-start d-lg-none">
				<a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
			</div>
		</div>
	</div>

	<!-- form. -->
	<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="gy-3 form-validate is-alter " novalidate="novalidate" id="hrp-email-template-form">
		<?php
		$nonce_action = 'save-email-template-settings';
		$nonce        = wp_create_nonce( $nonce_action );
		?>
		<input type="hidden" name="nonce" value="<?php echo esc_attr( $nonce ); ?>">
		<input type="hidden" name="action" value="hrp-save-email-template-settings">

		<div class="data-head">
			<h6 class="overline-title"><?php esc_html_e( 'Template Dynamic Keys List(click on button to copy)', 'hrp' ); ?></h6>
		</div>

		<div class="card-inner">
			<ul class="d-flex flex-wrap g-1">
				<?php foreach ( HRP_Helper::dynamic_keys() as $key => $value ) : ?>
					<li>
						<button type="button" class="btn btn-dim btn-outline-primary copy" id="<?php echo esc_attr( $key ); ?>"  data-bs-toggle="tooltip" data-bs-placement="top" title="Click To Copy"><?php echo esc_html( $key ); ?></button>
					</li>
				<?php endforeach ?>
			</ul>
		</div>

		<div class="data-head">
			<h6 class="overline-title"><?php esc_html_e( 'Registration email template', 'hrp' ); ?></h6>
		</div>

		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label" for="registration_email_enable"><?php esc_html_e( 'Enable Email Notification', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'Enable Email Notification for Registrations', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="custom-control custom-switch">
					<input type="checkbox" class="custom-control-input" name="registration_email_enable" id="registration_email_enable" <?php checked( $settings['registration_enable'], 'on', 'on' ); ?>>
					<label class="custom-control-label" for="registration_email_enable"><?php esc_html_e( 'Enable Email Notification', 'hrp' ); ?></label>
				</div>
			</div>
		</div>

		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label" for="registration_email_message"><?php esc_html_e( 'Message', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'Email notification will be sent to all selected employees ', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="form-group">
					<div class="form-control-wrap">
						<textarea class="form-control form-control-sm mb-2" name="registration_email_message" placeholder="Enter Message"><?php echo esc_html( $settings['registration_message'] ); ?></textarea>
					</div>
				</div>
			</div>
		</div>

		<?php
		$email_template = 'registration_message';
		require HRP_PLUGIN_DIR . 'admin/inc/settings/send_test_email.php';
		?>

		<div class="data-head">
			<h6 class="overline-title"><?php esc_html_e( 'holiday email template', 'hrp' ); ?></h6>
		</div>

		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label" for="holiday_email_enable"><?php esc_html_e( 'Enable Email Notification', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'Enable Email Notification for Holidays', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<a class="btn btn-success" href="<?php echo esc_url( 'https://codeclove.com/hrpress' ); ?>"><?php esc_html_e( 'Get Hr-Press Pro', 'hrp' ); ?></a>
			</div>
		</div>

		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label" for="holiday_email_days"><?php esc_html_e( 'Send Before Holiday', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'Email notification will be sent to all selected employees ', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="form-group">
					<div class="form-control-wrap">
						<input type="number" class="form-control form-control-sm mb-2" name="holiday_email_days" placeholder="Enter Days" value="<?php echo esc_html( $settings['holiday_email_days'] ); ?>"></input>
					</div>
				</div>
			</div>
		</div>

		<div class="row g-3">
			<div class="col-lg-7">
				<div class="form-group mt-2">
					<button type="button" class="btn btn-lg btn-primary" id="hrp-email-template-btn">
						<span><?php esc_html_e( 'Update', 'hrp' ); ?></span>
					</button>
				</div>
			</div>
		</div>
	</form>
</div>
