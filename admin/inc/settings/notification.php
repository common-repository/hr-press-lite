<?php
defined( 'ABSPATH' ) || exit;
require_once HRP_PLUGIN_DIR . 'includes/HRP_Helper.php';
$email_send_method_list = HRP_Helper::email_send_method_list();

$settings        = HRP_Helper::get_notification_settings();
$email           = $settings['email_enable'];
$send_method     = $settings['email_send_method'];
$sender_name     = $settings['sender_name'];
$sender_address  = $settings['sender_address'];
$smtp_host       = $settings['smtp_host'];
$smtp_username   = $settings['smtp_username'];
$smtp_password   = $settings['smtp_password'];
$smtp_encryption = $settings['smtp_encryption'];
$smtp_port       = $settings['smtp_port'];
?>
<div class="tab-pane" id="notification">

	<!-- head -->
	<div class="nk-block-head nk-block-head-lg">
		<div class="nk-block-between">
			<div class="nk-block-head-content">
				<h6 class="nk-block-title"><?php esc_html_e( 'Notification', 'hrp' ); ?></h6>
				<span><?php esc_html_e( 'These settings helps you modify notification settings.', 'hrp' ); ?></span>
			</div>
			<div class="nk-block-head-content align-self-start d-lg-none">
				<a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
			</div>
		</div>
	</div>

	<!-- form. -->
	<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="gy-3 form-validate is-alter " novalidate="novalidate" id="hrp-notification-form">
		<?php
		$nonce_action = 'save-notification-settings';
		$nonce        = wp_create_nonce( $nonce_action );
		?>
		<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">
		<input type="hidden" name="action" value="hrp-save-notification-settings">

		<div class="data-head">
			<h6 class="overline-title"><?php esc_html_e( 'Email', 'hrp' ); ?></h6>
		</div>

		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label"><?php esc_html_e( 'Email', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'Specify hr press email notifications', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="custom-control custom-switch">
					<input type="checkbox" class="custom-control-input" name="email_enable" id="email_notification" <?php checked( $email, 'on', 'on' ); ?>>
					<label class="custom-control-label" for="email_notification"><?php esc_html_e( 'Email', 'hrp' ); ?></label>
				</div>
			</div>
		</div>

		<div class="row g-3 align-center">
			<div class="col-lg-5">
				<div class="form-group">
					<label class="form-label"><?php esc_html_e( 'Email Type', 'hrp' ); ?></label>
					<span class="form-note"><?php esc_html_e( 'Specify email sending option', 'hrp' ); ?></span>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="form-group">
					<div class="form-control-wrap">
						<select class="form-select js-select2" id="mail-send-method" name="email_send_method">
							<?php foreach ( $email_send_method_list as  $key => $email_send_method ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, ( $send_method ) ); ?>><?php echo esc_html( $email_send_method ); ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
			</div>
		</div>

		<div class="wp-mail" style="<?php ( 'wp_mail' !== $send_method ) ? esc_attr_e( 'display: none;' ) : ''; ?>" id="sender-name">
			<div class="row g-3 align-center">
				<div class="col-lg-5">
					<div class="form-group">
						<label class="form-label" for="sender-name"><?php esc_html_e( 'Sender Name', 'hrp' ); ?></label>
						<span class="form-note"><?php esc_html_e( 'Specify email sender name.', 'hrp' ); ?></span>
					</div>
				</div>
				<div class="col-lg-7">
					<div class="form-group">
						<div class="form-control-wrap">
							<input type="text" class="form-control" id="sender-name" name="sender_name" value="<?php echo esc_attr( $sender_name ); ?>" placeholder="Email sender name">
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="wp-mail" style="<?php ( 'wp_mail' !== $send_method ) ? esc_attr_e( 'display: none;' ) : ''; ?>" id="sender-address">
			<div class="row g-3 align-center">
				<div class="col-lg-5">
					<div class="form-group">
						<label class="form-label" for="sender-address"><?php esc_html_e( 'Sender Address', 'hrp' ); ?></label>
						<span class="form-note"><?php esc_html_e( 'Specify email sender address.', 'hrp' ); ?></span>
					</div>
				</div>
				<div class="col-lg-7">
					<div class="form-group">
						<div class="form-control-wrap">
							<input type="text" class="form-control" id="sender-address" name="sender_address" value="<?php echo esc_attr( $sender_address ); ?>" placeholder="Email sender address">
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="smtp-mail" style="<?php ( 'smtp' !== $send_method ) ? esc_attr_e( 'display: none;' ) : ''; ?>" id="smtp_host">
			<div class="row g-3 align-center">
				<div class="col-lg-5">
					<div class="form-group">
						<label class="form-label" for="smtp_host"><?php esc_html_e( 'Smtp Host', 'hrp' ); ?></label>
						<span class="form-note"><?php esc_html_e( 'Specify smtp smtp host.', 'hrp' ); ?></span>
					</div>
				</div>
				<div class="col-lg-7">
					<div class="form-group">
						<div class="form-control-wrap">
							<input type="text" class="form-control" id="smtp_host" name="smtp_host" value="<?php echo esc_attr( $smtp_host ); ?>" placeholder="Enter smtp host">
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="smtp-mail" style="<?php ( 'smtp' !== $send_method ) ? esc_attr_e( 'display: none;' ) : ''; ?>" id="smtp_username">
			<div class="row g-3 align-center">
				<div class="col-lg-5">
					<div class="form-group">
						<label class="form-label" for="smtp_username"><?php esc_html_e( 'Smtp Username', 'hrp' ); ?></label>
						<span class="form-note"><?php esc_html_e( 'Specify smtp username.', 'hrp' ); ?></span>
					</div>
				</div>
				<div class="col-lg-7">
					<div class="form-group">
						<div class="form-control-wrap">
							<input type="text" class="form-control" id="smtp_username" name="smtp_username" value="<?php echo esc_attr( $smtp_username ); ?>" placeholder="Enter smtp username">
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="smtp-mail" style="<?php ( 'smtp' !== $send_method ) ? esc_attr_e( 'display: none;' ) : ''; ?>" id="smtp_password">
			<div class="row g-3 align-center">
				<div class="col-lg-5">
					<div class="form-group">
						<label class="form-label" for="smtp_password"><?php esc_html_e( 'Smtp Username', 'hrp' ); ?></label>
						<span class="form-note"><?php esc_html_e( 'Specify smtp username.', 'hrp' ); ?></span>
					</div>
				</div>
				<div class="col-lg-7">
					<div class="form-group">
						<div class="form-control-wrap">
							<input type="text" class="form-control" id="smtp_password" name="smtp_password" value="<?php echo esc_attr( $smtp_password ); ?>" placeholder="Enter smtp username">
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="smtp-mail" style="<?php ( 'smtp' !== $send_method ) ? esc_attr_e( 'display: none;' ) : ''; ?>" id="smtp_encryption">
			<div class="row g-3 align-center">
				<div class="col-lg-5">
					<div class="form-group">
						<label class="form-label" for="smtp_encryption"><?php esc_html_e( 'Smtp Username', 'hrp' ); ?></label>
						<span class="form-note"><?php esc_html_e( 'Specify smtp username.', 'hrp' ); ?></span>
					</div>
				</div>
				<div class="col-lg-7">
					<div class="form-group">
						<div class="form-control-wrap">
							<input type="text" class="form-control" id="smtp_encryption" name="smtp_encryption" value="<?php echo esc_attr( $smtp_encryption ); ?>" placeholder="Smtp smtp username">
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="smtp-mail" style="<?php ( 'smtp' !== $send_method ) ? esc_attr_e( 'display: none;' ) : ''; ?>" id="smtp_port">
			<div class="row g-3 align-center">
				<div class="col-lg-5">
					<div class="form-group">
						<label class="form-label" for="smtp_port"><?php esc_html_e( 'Smtp Username', 'hrp' ); ?></label>
						<span class="form-note"><?php esc_html_e( 'Specify smtp username.', 'hrp' ); ?></span>
					</div>
				</div>
				<div class="col-lg-7">
					<div class="form-group">
						<div class="form-control-wrap">
							<input type="text" class="form-control" id="smtp_port" name="smtp_port" value="<?php echo esc_attr( $smtp_port ); ?>" placeholder="Enter smtp username">
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row g-3">
			<div class="col-lg-7">
				<div class="form-group mt-2">
					<button type="button" class="btn btn-lg btn-primary " id="hrp-notification-btn">
						<span><?php esc_html_e( 'Update', 'hrp' ); ?></span>
					</button>
				</div>
			</div>
		</div>
	</form>
</div>
