<?php
defined( 'ABSPATH' ) || exit;
require_once HRP_PLUGIN_DIR . 'includes/HRP_Helper.php';

$departments_page_url = HRP_Helper::get_page_url( DEPARTMENTS );
$total_rows           = HRP_Helper::get_data_count( HRP_Helper::get_departments_row_count() );
?>
<div class="container-fluid">
	<div class="card">
		<div class="card-inner">
			<div class="nk-block-head">
				<div class="nk-block-between g-3">
					<div class="nk-block-head-content">
						<h4 class="nk-block-title"><?php esc_html_e( 'Departments', 'hrp' ); ?></h4>
						<div class="nk-block-des text-soft">
							<p> 
							<?php
							/* Translators: %s departments count */
							echo sprintf( esc_html__( 'Have Total %s departments', 'hrp' ), esc_html( $total_rows ) );
							?>
							</p>
						</div>
					</div>
					<div class="nk-block-head-content">
						<ul class="nk-block-tools g-3">
							<li>
								<div class="dropdown">
									<a href="<?php echo esc_url( $departments_page_url . '&action=save' ); ?>" class="btn btn-icon btn-primary"><em class="icon ni ni-plus"></em></a>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<table class="nk-tb-list nk-tb-ulist is-compact" id="departments">
				<thead>
					<tr class="nk-tb-item nk-tb-head">
						<th class="nk-tb-col nk-tb-col-check">
							<div class="custom-control custom-control-sm custom-checkbox notext">
								<input type="checkbox" class="custom-control-input bulk-select" id="bulk-select">
								<label class="custom-control-label" for="bulk-select"></label>
							</div>
						</th>
						<th class="nk-tb-col"><span class="sub-text"><?php esc_html_e( 'Department Title', 'hrp' ); ?></span></th>
						<th class="nk-tb-col "><span class="sub-text"><?php esc_html_e( 'Description', 'hrp' ); ?></span></th>
						<th class="nk-tb-col "><span class="sub-text"><?php esc_html_e( 'Number Of Employees', 'hrp' ); ?></span></th>
						<th class="nk-tb-col "><span class="sub-text"><?php esc_html_e( 'Department Manager', 'hrp' ); ?></span></th>
						<th class="nk-tb-col nk-tb-col-tools">
							<ul class="nk-tb-actions gx-1 my-n1">
								<div class="dropdown">
									<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-filter-alt"></em></a>
									<div class="dropdown-menu dropdown-menu-end">
										<ul class="link-list-opt no-bdr">
											<li><a href="#"><em class="icon ni ni-trash"></em><span><?php esc_html_e( 'Remove Selected', 'hrp' ); ?></span></a></li>
										</ul>
									</div>
								</div>
							</ul>
						</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
