<?php
defined( 'ABSPATH' ) || exit;
require_once HRP_PLUGIN_DIR . 'includes/HRP_Helper.php';
?>
<div class="container-fluid">
	<div class="nk-content-inner">
		<div class="nk-content-body">
			<div class="nk-block">
				<div class="card card-bordered">
					<div class="card-aside-wrap">
						<div class="card-inner card-inner-lg">
							<div class="tab-content">
								<?php require_once HRP_PLUGIN_DIR . 'admin/inc/settings/general_settings.php'; ?>
								<?php require_once HRP_PLUGIN_DIR . 'admin/inc/settings/attendance.php'; ?>
								<?php require_once HRP_PLUGIN_DIR . 'admin/inc/settings/notification.php'; ?>
								<?php require_once HRP_PLUGIN_DIR . 'admin/inc/settings/email_templates.php'; ?>

							</div>
						</div>
						<div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg" data-toggle-body="true" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
							<div class="card-inner-group" data-simplebar>
								<div class="card-inner">
									<h3 class="nk-block-title page-title"><?php esc_html_e( 'Settings', 'hrp' ); ?></h3>
									<div class="nk-block-des text-soft">
										<p><?php esc_html_e( 'Here you can change and edit your needs', 'hrp' ); ?></p>
									</div>
								</div>
								<div class="card-inner">
									<ul class="nav link-list-menu">
										<li class="nav-item"> <a class="active" data-bs-toggle="tab" href="#general_settings"><em class="icon ni ni-setting"></em><span><?php esc_html_e( 'General', 'hrp' ); ?></span></a></li>
										<li class="nav-item"> <a data-bs-toggle="tab" href="#attendance"><em class="icon ni ni-users"></em><span><?php esc_html_e( 'Attendance', 'hrp' ); ?></span></a></li>
										<li class="nav-item"> <a data-bs-toggle="tab" href="#notification"><em class="icon ni ni-bell"></em></em><span><?php esc_html_e( 'Notification', 'hrp' ); ?></span></a></li>
										<li class="nav-item"> <a data-bs-toggle="tab" href="#email_template"><em class="icon ni ni-mail"></em><span><?php esc_html_e( 'Email Template', 'hrp' ); ?></span></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
