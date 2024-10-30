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
							<h3 class="nk-block-title page-title"><?php esc_html_e( 'Dashboard', 'hrp' ); ?></h3>
						</div>
						<div class="nk-block-head-content">
							<div class="toggle-wrap nk-block-tools-toggle">
								<a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
								<div class="toggle-expand-content" data-content="pageMenu">
									<ul class="nk-block-tools g-3">
										<li class="nk-block-tools-opt"><a href="<?php echo esc_url( HRP_Helper::get_dashboard_url( 'reports' ) ); ?>" class="btn btn-primary"><em class="icon ni ni-reports"></em><span><?php esc_html_e( 'Reports', 'hrp' ); ?></span></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="nk-block">
					<div class="row g-gs">

						<div class="col-xxl-3">
							<div class="card">
								<div class="card-inner">
									<ul class="nk-store-statistics">
										<li class="item">
											<div class="info">
												<div class="title" style="font-size: 1.5rem;"><?php esc_html_e( 'Employees', 'hrp' ); ?></div>
												<div class="count"><?php echo esc_html( HRP_Helper::get_data_count( HRP_Helper::get_employees_row_count() ) ); ?></div>
											</div>
											<em class="icon bg-info-dim ni ni-users-fill" style="font-size: 2.5rem; height: 80px; width: 80px;"></em>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-xxl-3">
							<div class="card">
								<div class="card-inner">
									<ul class="nk-store-statistics">
										<li class="item">
											<div class="info">
												<div class="title" style="font-size: 1.5rem;"><?php esc_html_e( 'Departments', 'hrp' ); ?></div>
												<div class="count"><?php echo esc_html( HRP_Helper::get_data_count( HRP_Helper::get_departments_row_count() ) ); ?></div>
											</div>
											<em class="icon bg-teal-dim ni ni-network" style="font-size: 2.5rem; height: 80px; width: 80px;"></em>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-xxl-3">
							<div class="card">
								<div class="card-inner">
									<ul class="nk-store-statistics">
										<li class="item">
											<div class="info">
												<div class="title" style="font-size: 1.5rem;"><?php esc_html_e( 'Designations', 'hrp' ); ?></div>
												<div class="count"><?php echo esc_html( HRP_Helper::get_data_count( HRP_Helper::get_designations_row_count() ) ); ?></div>
											</div>
											<em class="icon bg-orange-dim ni ni-user-list-fill" style="font-size: 2.5rem; height: 80px; width: 80px;"></em>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-xxl-3">
							<div class="card">
								<div class="card-inner">
									<ul class="nk-store-statistics">
										<li class="item">
											<div class="info">
												<div class="title" style="font-size: 1.5rem;"><?php esc_html_e( 'Shifts', 'hrp' ); ?></div>
												<div class="count"><?php echo esc_html( HRP_Helper::get_data_count( HRP_Helper::get_shifts_row_count() ) ); ?></div>
											</div>
											<em class="icon bg-pink-dim ni ni-server-fill" style="font-size: 2.5rem; height: 80px; width: 80px;"></em>
										</li>
									</ul>
								</div>
							</div>
						</div>

						<div class="col-xxl-6">
							<div class="card h-100">
								<div class="card-inner">
									<div class="card-title-group">
										<div class="card-title">
											<h6 class="title"><?php echo date( 'F, Y' ); ?></h6>
										</div>

									</div>
								</div>
								<div class="card-inner">
									<?php
									$calendar = new Month_Calendar();
									$calendar->add_event( 'Today', date( 'Y-m-d' ), 1, 'badge bg-purple' );
									echo wp_kses_post( $calendar );
									?>
								</div>
							</div>
						</div>

						<div class="col-xxl-3 col-md-6">
							<div class="card h-100">
								<div class="card-inner">
									<div class="card-title-group mb-2">
										<div class="card-title">
											<h6 class="title"><?php esc_html_e( 'Statistics', 'hrp' ); ?></h6>
										</div>
									</div>
									<ul class="nk-store-statistics">
										<li class="item">
											<div class="info">
												<div class="title"><?php esc_html_e( 'Total Employees', 'hrp' ); ?></div>
												<div class="count"><?php echo esc_html( HRP_Helper::get_data_count( HRP_Helper::get_employees_row_count() ) ); ?></div>
											</div>
											<em class="icon bg-primary-dim ni ni-users-fill"></em>
										</li>
										<li class="item">
											<div class="info">
												<div class="title"><?php esc_html_e( 'Total Departments', 'hrp' ); ?></div>
												<div class="count"><?php echo esc_html( HRP_Helper::get_data_count( HRP_Helper::get_departments_row_count() ) ); ?></div>
											</div>
											<em class="icon bg-info-dim ni ni-network"></em>
										</li>
										<li class="item">
											<div class="info">
												<div class="title"><?php esc_html_e( 'Total Designations', 'hrp' ); ?></div>
												<div class="count"><?php echo esc_html( HRP_Helper::get_data_count( HRP_Helper::get_designations_row_count() ) ); ?></div>
											</div>
											<em class="icon bg-teal-dim ni ni-user-list-fill"></em>
										</li>
										<li class="item">
											<div class="info">
												<div class="title"><?php esc_html_e( 'Total Shifts', 'hrp' ); ?></div>
												<div class="count"><?php echo esc_html( HRP_Helper::get_data_count( HRP_Helper::get_shifts_row_count() ) ); ?></div>
											</div>
											<em class="icon bg-orange-dim ni ni-server-fill"></em>
										</li>
										<li class="item">
											<div class="info">
												<div class="title"><?php esc_html_e( 'Total Holidays', 'hrp' ); ?></div>
												<div class="count"><?php echo esc_html( HRP_Helper::get_data_count( HRP_Helper::get_holidays_row_count() ) ); ?></div>
											</div>
											<em class="icon bg-pink-dim ni ni-star-fill"></em>
										</li>
									</ul>
								</div>
							</div>
						</div>

						<div class="col-xxl-3 col-md-6">
							<div class="card h-100">
								<div class="card-inner border-bottom">
									<div class="card-title-group">
										<div class="card-title">
											<h6 class="title"><?php esc_html_e( 'Upcoming Birthdays', 'hrp' ); ?></h6>
										</div>
									</div>
								</div>
								<div class="card-inner">
									<div class="timeline">
										<h6 class="timeline-head"><?php echo esc_html( wp_date( 'F Y', strtotime( date( 'Y-m-d' ) ) ) ); ?></h6>
										<ul class="timeline-list">
											<?php $upcoming_birthdays = HRP_Helper::upcoming_employees_birthdays(); ?>
											<?php foreach ( $upcoming_birthdays as $birthday ) : ?>
												<li class="timeline-item">
													<div class="timeline-status bg-primary is-outline"></div>
													<div class="timeline-date"><?php echo esc_html( wp_date( 'M j', strtotime( $birthday->date_of_birth ) ) ); ?> <em class="icon ni ni-alarm-alt"></em></div>
													<div class="timeline-data">
														<h6 class="timeline-title"><?php echo esc_html( ucwords( $birthday->name ) ); ?></h6>
														<div class="timeline-des">
															<p>
															<?php
															/* Translators: 1: department, 2: department name */
															echo sprintf( esc_html__( '%1$s %2$s', 'hrp' ), esc_html_e( 'Department:', 'hrp' ), esc_html( $birthday->department ) );
															?>
															</p>
															<p>
															<?php
															/*  Translators: 1: designation, 2: designation name */
															echo sprintf( esc_html__( '%1$s %2$s', 'hrp' ), esc_html_e( 'Designation:' ), esc_html( $birthday->designation ) );
															?>
															</p>
														</div>
													</div>
												</li>
											<?php endforeach ?>
										</ul>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6 col-xxl-12">
							<div class="card card-full">
								<div class="card-inner-group">
									<div class="card-inner">
										<div class="card-title-group">
											<div class="card-title">
												<h6 class="title"><?php esc_html_e( 'New Employees', 'hrp' ); ?></h6>
											</div>
											<div class="card-tools">
												<a href="<?php echo ( esc_url( $employees_page_url ) ); ?>" class="link"><?php esc_html_e( 'View All', 'hrp' ); ?></a>
											</div>
										</div>
									</div>
									<?php
									global $wpdb;
									$employees = $wpdb->get_results( HRP_Helper::get_employees() . ' ORDER BY emp.ID DESC LIMIT 5' );
									?>
									<?php foreach ( $employees as $employee ) : ?>
										<div class="card-inner card-inner-md">
											<div class="user-card">
												<div class="user-avatar bg-primary-dim">
													<?php
													if ( ! empty( $employee->photo_id ) ) {
														echo '<img class="thumb" src="' . esc_url( wp_get_attachment_url( $employee->photo_id ) ) . '" alt="photo">';
													} else {
														echo '<span>' . esc_html( strtoupper( substr( $employee->name, 0, 2 ) ) ) . '</span>';
													}
													?>
												</div>
												<div class="user-info">
													<span class="lead-text"><?php echo esc_html( $employee->name ); ?></span>
													<span class="sub-text"><?php echo esc_html( $employee->user_email ); ?></span>
												</div>
												<div class="user-action">
													<div class="drodown">
														<a href="#" class="dropdown-toggle btn btn-icon btn-trigger me-n1" data-bs-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
														<div class="dropdown-menu dropdown-menu-end">
															<ul class="link-list-opt no-bdr">
																<li><a href="<?php echo esc_url( $employees_page_url . '&action=save&id=' . $employee->ID ); ?>"><em class="icon ni ni-setting"></em><span><?php esc_html_e( 'View All', 'hrp' ); ?></span></a></li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach ?>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
