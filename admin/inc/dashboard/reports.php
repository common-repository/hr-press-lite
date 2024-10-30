<?php
defined( 'ABSPATH' ) || exit;
require HRP_PLUGIN_DIR . 'includes/Month_Calendar.php';
$employees_page_url = HRP_Helper::get_page_url( EMPLOYEES );
?>
<div class="nk-content ">
	<div class="container-fluid">
		<div class="nk-content-inner">
			<div class="nk-content-body">
				<div class="nk-block-head nk-block-head-sm">
					<div class="nk-block-between">
						<div class="nk-block-head-content">
							<h3 class="nk-block-title page-title"><?php esc_html_e( 'Employees Reports [ Checkin / Checkout ]', 'hrp' ); ?></h3>
						</div>
						<div class="nk-block-head-content">
							<div class="toggle-wrap nk-block-tools-toggle">
								<a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
								<div class="toggle-expand-content" data-content="pageMenu">
									<ul class="nk-block-tools g-3">
										<li class="nk-block-tools-opt"><a href="<?php echo esc_url( HRP_Helper::get_dashboard_url( 'dashboard' ) ); ?>" class="btn btn-primary"><em class="icon ni ni-reports"></em><span><?php esc_html_e( 'Dashboard', 'hrp' ); ?></span></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="nk-block">
					<div class="row g-gs">
						<div class="col-xxl-12">
							<div class="card card-full card-inner">
								<table class="nk-tb-list nk-tb-ulist is-compact" id="reports">
									<thead>
										<tr class="nk-tb-item nk-tb-head">
											<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Employee ID', 'hrp' ); ?></span></th>
											<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Name', 'hrp' ); ?></span></th>
											<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Checkin', 'hrp' ); ?></span></th>
											<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Checkout', 'hrp' ); ?></span></th>
											<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Hours', 'hrp' ); ?></span></th>
											<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Break Start', 'hrp' ); ?></span></th>
											<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Break End', 'hrp' ); ?></span></th>
											<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'IP Address', 'hrp' ); ?></span></th>
											<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Status', 'hrp' ); ?></span></th>
											<th class="nk-tb-col nk-tb-col-tools">
												<ul class="nk-tb-actions gx-1 my-n1">
													<span class="sub-text"><?php esc_attr_e( 'Action', 'hrp' ); ?></span>
												</ul>
											</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
