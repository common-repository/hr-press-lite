<?php defined( 'ABSPATH' ) || exit;
$months        = HRP_Helper::months();
$years         = HRP_Helper::get_years_from_employee( $employee_id );
$years         = HRP_Helper::get_start_to_end_years( $years->MinDate, $years->MaxDate );
$current_year  = date( 'Y' );
$current_month = date( 'm' );
?>
<div class="card-inner">
	<div class="nk-block-head nk-block-head-sm">
		<div class="nk-block-between">

			<div class="nk-block-head-content">
				<h4 class="nk-block-title"><?php esc_html_e( 'Attendance', 'hrp' ); ?></h4>
			</div>

			<div class="nk-block-head-content position-relative card-tools-toggle">
				<ul class="nk-block-tools g-3">
					<li>
						<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="gy-3 form-validate is-alter hrp-attendance-form" novalidate="novalidate">
							<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'attendances' ) ); ?>">
							<input type="hidden" name="action" value="hrp-fetch-attendances">
							<input type="hidden" name="employee_id" value="<?php echo esc_attr( $employee_id ); ?>">
							<div class="dropdown">
								<a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-bs-toggle="dropdown">
									<div class="dot dot-primary"></div>
									<em class="icon ni ni-filter-alt"></em>
								</a>
								<div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-end">
									<div class="dropdown-head">
										<span class="sub-title dropdown-title">Filter</span>
										<div class="dropdown">
											<a href="#" class="btn btn-sm btn-icon">
												<em class="icon ni ni-more-h"></em>
											</a>
										</div>
									</div>
									<div class="dropdown-body dropdown-body-rg">
										<div class="row gx-6 gy-3">
											<div class="col-12">
												<div class="form-group">
													<label class="overline-title overline-title-alt">Month</label>
													<select class="form-select js-select2 js-select2-sm" name="month">
														<?php foreach ( $months as $key => $month ) : ?>
															<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $current_month ); ?>><?php echo esc_html( $month ); ?></option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
											<div class="col-12">
												<div class="form-group">
													<label class="overline-title overline-title-alt">Year</label>
													<select class="form-select js-select2 js-select2-sm" name="year">
														<?php foreach ( $years as $year ) : ?>
															<option value="<?php echo esc_attr( $year ); ?>" <?php selected( $year, $current_year ); ?>><?php echo esc_html( $year ); ?></option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
											<div class="col-12">
												<div class="form-group">
													<button type="button" class="btn btn-secondary" id="hrp-filter-attendance-btn">Filter</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<table class="nk-tb-list nk-tb-ulist is-compact" id="attendances">
		<thead>
			<tr class="nk-tb-item nk-tb-head">
				<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Date', 'hrp' ); ?></span></th>
				<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Checkin', 'hrp' ); ?></span></th>
				<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Checkout', 'hrp' ); ?></span></th>
				<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Hours', 'hrp' ); ?></span></th>
				<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Break Start Time', 'hrp' ); ?></span></th>
				<th class="nk-tb-col"><span class="sub-text"><?php esc_attr_e( 'Break End Time', 'hrp' ); ?></span></th>
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
