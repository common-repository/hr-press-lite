<?php
defined( 'ABSPATH' ) || exit;
$upcoming_birthdays    = HRP_Helper::upcoming_employees_birthdays();
$working_days_of_month = HRP_Helper::days_in_current_month();
$present_days_of_month = HRP_Helper::presents_in_month( $employee_id );
$absents_days_of_month = HRP_Helper::absents_in_month( $employee_id );
?>
<div class="card-aside card-aside-right user-aside toggle-slide toggle-slide-right toggle-break-xxl" data-content="userAside" data-toggle-screen="xxl" data-toggle-overlay="true" data-toggle-body="true">
	<div class="card-inner-group" data-simplebar>
		<div class="card-inner">
			<div class="user-card user-card-s2">
				<div class="user-avatar lg bg-primary">
					<?php if ( ! empty( $employee_photo ) ) : ?>
						<img src="<?php echo esc_url( $employee_photo ); ?>" alt="employee_photo">
					<?php endif ?>
					<?php if ( empty( $employee_photo ) ) : ?>
						<img src="<?php echo esc_url( HRP_PLUGIN_URL . '/assets/images/person.png' ); ?>" alt="employee_photo">
					<?php endif ?>
					<div class="status dot dot-lg dot-success"></div>
				</div>
				<div class="user-info">
					<div class="badge bg-outline-primary rounded-pill ucap"><?php echo esc_html( HRP_Helper::get_designation( $employee->designation )->title ); ?></div>
					<h5 class="text-dark"><?php echo esc_html( ucwords( $employee->name ) ); ?></h5>
					<span class="sub-text"><?php echo esc_html( $user_info->user_email ); ?></span>
				</div>
			</div>
		</div>
		<div class="card-inner">
			<h6 class="overline-title-alt mb-2"><?php esc_html_e( 'Current Month Attendance', 'hrp' ); ?></h6>
			<div class="row text-center">
				<div class="col-4">
					<div class="profile-stats">
						<span class="amount"><?php echo esc_html( $working_days_of_month ); ?></span> <?php esc_html_e( 'Days', 'hrp' ); ?>
						<span class="sub-text"><?php esc_html_e( 'Total Count', 'hrp' ); ?></span>
					</div>
				</div>
				<div class="col-4">
					<div class="profile-stats">
						<span class="amount"><?php echo esc_html( $present_days_of_month ); ?></span> <?php esc_html_e( 'Days', 'hrp' ); ?>
						<span class="sub-text"><?php esc_html_e( 'Total Present', 'hrp' ); ?></span>
					</div>
				</div>
				<div class="col-4">
					<div class="profile-stats">
						<span class="amount"><?php echo esc_html( $absents_days_of_month ); ?></span> <?php esc_html_e( 'Days', 'hrp' ); ?>
						<span class="sub-text"><?php esc_html_e( 'Total Absent', 'hrp' ); ?></span>
					</div>
				</div>

			</div>
		</div>
		<div class="card-inner">
			<h6 class="overline-title-alt mb-2"><?php esc_html_e( 'Additional Details', 'hrp' ); ?></h6>
			<div class="row g-3">
				<div class="col-6">
					<span class="sub-text"><?php esc_html_e( 'Employee ID', 'hrp' ); ?>:</span>
					<span><?php echo esc_html( HRP_Helper::employee_id_prefix( $employee->employee_id ) ); ?></span>
				</div>
				<div class="col-6">
					<span class="sub-text"><?php esc_html_e( 'Date Of Birth', 'hrp' ); ?>:</span>
					<span><?php echo esc_html( HRP_Helper::display_date( $employee->date_of_birth ) ); ?></span>
				</div>
				<div class="col-6">
					<span class="sub-text"><?php esc_html_e( 'Joined At', 'hrp' ); ?>:</span>
					<span><?php echo esc_html( HRP_Helper::display_date( $employee->date_of_hire ) ); ?></span>
				</div>
				<div class="col-6">
					<span class="sub-text"><?php esc_html_e( 'Employment Type', 'hrp' ); ?>:</span>
					<span><?php echo esc_html( ucwords( HRP_Helper::get_employment_type( $employee->type ) ) ); ?></span>
				</div>
				<div class="col-6">
					<span class="sub-text"><?php esc_html_e( 'Department', 'hrp' ); ?>:</span>
					<span><?php echo esc_html( ucwords( HRP_Helper::get_department( $employee->department )->title ) ); ?></span>
				</div>
				<div class="col-6">
					<span class="sub-text"><?php esc_html_e( 'Designation', 'hrp' ); ?>:</span>
					<span><?php echo esc_html( ucwords( HRP_Helper::get_designation( $employee->designation )->title ) ); ?></span>
				</div>
				<div class="col-6">
					<span class="sub-text"><?php esc_html_e( 'Pay Type', 'hrp' ); ?>:</span>
					<span><?php echo esc_html( ucwords( $employee->pay_type ) ); ?></span>
				</div>
				<div class="col-6">
					<span class="sub-text"><?php esc_html_e( 'Shift Type', 'hrp' ); ?>:</span>
					<span><?php echo esc_html( ucwords( HRP_Helper::get_shift( $employee->shift_id )->title ) ); ?></span>
				</div>
			</div>
		</div>
		<div class="card-inner">
			<h6 class="overline-title-alt mb-3"><?php esc_html_e( 'Upcoming employees Birthdays', 'hrp' ); ?></h6>
			<ul class="g-1">
				<?php foreach ( $upcoming_birthdays as $birthday ) : ?>
					<li class="btn-group">
						<a class="btn btn-sm btn-light btn-dim" href="#">
							<?php
							/* translators: 1: Name, 2: Birthday Date */
							echo sprintf( esc_html__( '%1$s [%2$s]', 'hrp' ), esc_html( ucwords( $birthday->name ) ), esc_html( HRP_Helper::display_date( $birthday->date_of_birth ) ) );
							?>
						</a>
					</li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
</div>
