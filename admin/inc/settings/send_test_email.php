<?php
defined( 'ABSPATH' ) || die();
?>
<div class="row g-3 align-center">
	<div class="col-lg-5">
		<div class="form-group">
			<label class="form-label" for="announcement_email_message_test"><?php esc_html_e( 'Send Test Email', 'hrp' ); ?></label>
			<span class="form-note"><?php esc_html_e( 'Email notification will be sent to entered email address ', 'hrp' ); ?></span>
		</div>
	</div>
	<div class="col-lg-7">
		<div class="form-control-wrap">
			<div class="input-group">
				<div class="input-group-append">
					<input type="text" class="form-control send-test-email-to" placeholder="Recipient's Email Address">
					<button type="button" class="btn btn-outline-primary btn-dim send-test-email" data-template="<?php echo esc_attr( $email_template ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'send-test-email' ) ); ?>">Send</button>
				</div>
			</div>
		</div>
	</div>
</div>
