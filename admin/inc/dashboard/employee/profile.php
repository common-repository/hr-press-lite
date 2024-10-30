<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="card-inner">

	<div class="nk-block">
		<div class="nk-block-head">
			<h5 class="title"><?php esc_html_e( 'Employee Details', 'hrp' ); ?></h5>
		</div>
		<div class="data-head">
			<h6 class="overline-title"><?php esc_html_e( 'Personal', 'hrp' ); ?></h6>
		</div>
		<div class="profile-ud-list">
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Full Name', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( ucwords( $employee->name ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Middle Name', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( ucwords( $user_info->middle_name ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Surname', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( ucwords( $user_info->last_name ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Date of Birth', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( date( 'd M Y', strtotime( $employee->date_of_birth ) ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Mobile Number', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( $employee->mobile ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Email Address', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( $user_info->user_email ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Father\'s Name', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( ucwords( $user_info->father_name ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Mother\'s Name', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( ucwords( $user_info->mother_name ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Spouse\'s Name', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( ucwords( $user_info->spouse_name ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Blood Group', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( $user_info->blood_group ); ?></span>
				</div>
			</div>
		</div>
	</div>

	<div class="nk-block">
		<div class="data-head">
			<h6 class="overline-title"><?php esc_html_e( 'Work Details', 'hrp' ); ?></h6>
		</div>
		<div class="profile-ud-list">
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Employee ID', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( HRP_Helper::employee_id_prefix( $employee->employee_id ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Joining Date', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( date( 'd M Y', strtotime( $employee->date_of_hire ) ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Employment Type', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( HRP_Helper::get_employment_type( $employee->type ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Employment End Date', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( date( 'd M Y', strtotime( $employee->termination_date ) ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Employment End Date', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( date( 'd M Y', strtotime( $employee->termination_date ) ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Department', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( HRP_Helper::get_department( $employee->department )->title ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Designation', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( HRP_Helper::get_designation( $employee->designation )->title ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Shift', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( ucwords( HRP_Helper::get_shift( $employee->shift_id )->title ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Source Of Hire', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( ucwords( $employee->source_of_hire ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Pay Type', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( ucwords( $employee->pay_type ) ); ?></span>
				</div>
			</div>
		</div>
	</div>

	<div class="nk-block">
		<div class="data-head">
			<h6 class="overline-title"><?php esc_html_e( 'Additional Information', 'hrp' ); ?></h6>
		</div>
		<div class="profile-ud-list">
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Driving License', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( $user_info->driving_license ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Nationality', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( ucwords( $user_info->nationality ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Country', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( ucwords( $user_info->country ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'State', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( ucwords( $user_info->state ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'City', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( ucwords( $user_info->city ) ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Zip Code', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( $user_info->zip_code ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Address 1', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( $user_info->address_1 ); ?></span>
				</div>
			</div>
			<div class="profile-ud-item">
				<div class="profile-ud wider">
					<span class="profile-ud-label"><?php esc_html_e( 'Address 2', 'hrp' ); ?></span>
					<span class="profile-ud-value"><?php echo esc_html( $user_info->address_2 ); ?></span>
				</div>
			</div>

		</div>
		<div class="mt-2">
			<span class="profile-ud-label"><?php esc_html_e( 'bio', 'hrp' ); ?></span>
			<span class="profile-ud-value"><?php echo esc_html( $user_info->bio ); ?></span>
		</div>
	</div>
	<div class="nk-divider divider md"></div>
</div>
