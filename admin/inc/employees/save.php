<?php
defined( 'ABSPATH' ) || exit;
require_once HRP_PLUGIN_DIR . 'includes/HRP_Helper.php';

$employees_page_url = HRP_Helper::get_page_url( EMPLOYEES );
$employee_type_list = HRP_Helper::employee_type_list();
$status_list        = HRP_Helper::employee_status_list();
$pay_type_list      = HRP_Helper::pay_type_list();
$blood_group_list   = HRP_Helper::blood_group_list();
$gender_list        = HRP_Helper::gender_list();

$department_list  = HRP_Helper::get_departments_list();
$designation_list = HRP_Helper::get_designations_list();
$shift_list       = HRP_Helper::get_shifts_list();

$first_name           = '';
$middle_name          = '';
$last_name            = '';
$employee_id          = '';
$email                = '';
$date_of_hire         = '';
$date_of_birth        = '';
$termination_date     = '';
$department_id        = '';
$designation_id       = '';
$shift_id             = '';
$location             = '';
$source_of_hire       = '';
$mobile               = '';
$pay_rate             = '';
$pay_type_id          = '';
$user_info            = '';
$father_name          = '';
$mother_name          = '';
$spouse_name          = '';
$marital_status       = '';
$driving_license      = '';
$employee_blood_group = '';
$nationality          = '';
$employee_gender      = '';
$address_1            = '';
$address_2            = '';
$city                 = '';
$country              = '';
$state                = '';
$zip_code             = '';
$bio                  = '';
$employee_status      = '';
$photo_id             = '';
$employee_id_number   = '';
$employee_id          = '';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$employee_id        = absint( $_GET['id'] );
	$employee           = HRP_Helper::get_employee( $employee_id );
	$user_id            = $employee->user_id;
	$date_of_hire       = HRP_Helper::date_format( $employee->date_of_hire );
	$termination_date   = HRP_Helper::date_format( $employee->termination_date );
	$date_of_birth      = HRP_Helper::date_format( $employee->date_of_birth );
	$department_id      = $employee->department;
	$shift_id           = $employee->shift_id;
	$location           = $employee->location;
	$source_of_hire     = $employee->source_of_hire;
	$pay_rate           = $employee->pay_rate;
	$pay_type_id        = $employee->pay_type;
	$mobile             = $employee->mobile;
	$employee_status    = $employee->status;
	$employee_id_number = $employee->employee_id;
	$photo_id           = $employee->photo_id;

	$user_info            = get_userdata( $user_id );
	$email                = $user_info->user_email;
	$first_name           = get_user_meta( $user_id, 'first_name', true );
	$middle_name          = get_user_meta( $user_id, 'middle_name', true );
	$last_name            = get_user_meta( $user_id, 'last_name', true );
	$father_name          = get_user_meta( $user_id, 'father_name', true );
	$mother_name          = get_user_meta( $user_id, 'mother_name', true );
	$spouse_name          = get_user_meta( $user_id, 'spouse_name', true );
	$marital_status       = get_user_meta( $user_id, 'marital_status', true );
	$driving_license      = get_user_meta( $user_id, 'driving_license', true );
	$employee_blood_group = get_user_meta( $user_id, 'blood_group', true );
	$nationality          = get_user_meta( $user_id, 'nationality', true );
	$employee_gender      = get_user_meta( $user_id, 'gender', true );
	$address_1            = get_user_meta( $user_id, 'address_1', true );
	$address_2            = get_user_meta( $user_id, 'address_2', true );
	$city                 = get_user_meta( $user_id, 'city', true );
	$country              = get_user_meta( $user_id, 'country', true );
	$state                = get_user_meta( $user_id, 'state', true );
	$zip_code             = get_user_meta( $user_id, 'zip_code', true );
	$bio                  = get_user_meta( $user_id, 'bio', true );
}
?>
<div class="container-fluid">
	<div class="card">
		<div class="card-inner">
			<div class="nk-block-between g-3">
				<div class="nk-block-head nk-block-head-sm">
					<div class="nk-block-between g-3">
						<div class="nk-block-head-content">
							<h4 class="nk-block-title "><?php ! empty( $employee_id ) ? esc_html_e( 'Update Employee Information', 'hrp' ) : esc_html_e( 'Add New Employee', 'hrp' ); ?>
							</h4>
						</div>
					</div>
				</div>
				<div class="nk-block-head-content">
					<a href="<?php echo esc_url( $employees_page_url ); ?>" class="btn btn-outline-light bg-white d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em>
						<span><?php esc_html_e( 'Back', 'hrp' ); ?></span></a>
				</div>
			</div>

			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" class="gy-3 form-validate is-alter hrp-save-form" novalidate="novalidate" id="hrp-save-form">
				<input type="hidden" name="<?php echo esc_attr( 'save-employee' ); ?>" value="<?php echo esc_attr( wp_create_nonce( 'save-employee' ) ); ?>">
				<input type="hidden" name="action" value="hrp-save-employee">
				<input type="hidden" name="employee_id" value="<?php echo esc_attr( $employee_id ); ?>">
				<div class="row gy-4">

					<div class="data-head">
						<h6 class="overline-title"><?php esc_html_e( 'Employee Basic Details', 'hrp' ); ?></h6>
					</div>

					<div class="col-lg-2 ">
						<div class="user-card user-card-s2">
							<div class="user-avatar  xl bg-primary-dim">
								<?php if ( $photo_id ) : ?>
									<img height="150" width="150" class="border border-light " src="<?php echo esc_url( wp_get_attachment_url( $photo_id ) ); ?>" alt="photo">
								<?php endif ?>
								<?php if ( empty( $photo_id ) ) : ?>
									<em class="icon ni ni-user-alt"></em>
								<?php endif ?>
							</div>
							<input hidden type="text" class="form-file-input file-upload" id="photo_id" name="photo_id" value="<?php echo esc_attr( $photo_id ); ?>">
							<a href="#" class="btn btn-primary mt-1 file-upload"><em class="icon ni ni-upload-cloud pe-1"></em></em><?php esc_html_e( 'Upload Photo', 'hrp' ); ?></a>
						</div>
					</div>

					<div class="col-lg-10">
						<div class="row gy-4">
							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label required" for="first_name"><?php esc_html_e( 'First Name', 'hrp' ); ?></label>
									<div class="form-control-wrap">
										<input type="text" class="form-control" id="first_name" name="first_name" placeholder="<?php esc_attr_e( 'Enter First Name', 'hrp' ); ?>" value="<?php echo esc_attr( $first_name ); ?>" required>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="middle_name"><?php esc_html_e( 'Middle Name', 'hrp' ); ?></label>
									<div class="form-control-wrap">
										<input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="<?php esc_attr_e( 'Enter Middle Name', 'hrp' ); ?>" value="<?php echo esc_attr( $middle_name ); ?>">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="last_name"><?php esc_html_e( 'Last Name', 'hrp' ); ?></label>
									<div class="form-control-wrap">
										<input type="text" class="form-control" id="last_name" name="last_name" placeholder="<?php esc_attr_e( 'Enter Last Name', 'hrp' ); ?>" value="<?php echo esc_attr( $last_name ); ?>">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="employee_id_number"><?php esc_html_e( 'Employee ID', 'hrp' ); ?></label>
									<div class="form-control-wrap">
										<input type="text" class="form-control" id="employee_id_number" name="employee_id_number" placeholder="<?php esc_attr_e( 'Enter Employee ID', 'hrp' ); ?>" value="<?php echo esc_attr( $employee_id_number ); ?>">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label required" for="email"><?php esc_html_e( 'Email', 'hrp' ); ?></label>
									<div class="form-control-wrap">
										<input type="text" class="form-control" id="email" name="email" placeholder="<?php esc_attr_e( 'Enter Email', 'hrp' ); ?>" value="<?php echo esc_attr( $email ); ?>" required>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="employee_type"><?php esc_html_e( 'Employee Type', 'hrp' ); ?></label>
									<div class="form-control-wrap">
										<select class="form-select js-select2" id="employee_type" name="employee_type">
											<?php foreach ( $employee_type_list as $key => $employee_type ) : ?>
												<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $employee_type ); ?>><?php echo esc_html( $employee_type ); ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="status"><?php esc_html_e( 'Employee Status', 'hrp' ); ?></label>
									<div class="form-control-wrap">
										<select class="form-select js-select2" id="status" name="employee_status">
											<?php foreach ( $status_list as $key => $status ) : ?>
												<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $employee_status ); ?>><?php echo esc_html( $status ); ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="date_of_hire"><?php esc_html_e( 'Date Of Hire', 'hrp' ); ?></label>
									<div class="form-control-wrap">
										<div class="form-icon form-icon-left"><em class="icon ni ni-calendar"></em></div>
										<input type="text" class="form-control date-picker" id="date_of_hire" name="date_of_hire" placeholder="<?php esc_attr_e( 'Enter Date Of Hire', 'hrp' ); ?>" value="<?php echo esc_attr( $date_of_hire ); ?>" data-date-format="mm/dd/yyyy">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="termination_date"><?php esc_html_e( 'Employee End Date', 'hrp' ); ?></label>
									<div class="form-control-wrap">
										<div class="form-icon form-icon-left"><em class="icon ni ni-calendar"></em></div>
										<input type="text" class="form-control date-picker-alt" id="termination_date" name="termination_date" placeholder="<?php esc_attr_e( 'Enter Employee End Date', 'hrp' ); ?>" value="<?php echo esc_attr( $termination_date ); ?>" data-date-format="mm/dd/yyyy">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label" for="date_of_birth"><?php esc_html_e( 'Date Of Birth', 'hrp' ); ?></label>
									<div class="form-control-wrap">
										<div class="form-icon form-icon-left"><em class="icon ni ni-calendar"></em></div>
										<input type="text" class="form-control date-picker-alt" id="date_of_birth" name="date_of_birth" placeholder="<?php esc_attr_e( 'Enter Date Of Birth', 'hrp' ); ?>" value="<?php echo esc_attr( $date_of_birth ); ?>" data-date-format="mm/dd/yyyy">
									</div>
								</div>
							</div>

						</div>
					</div>

					<div class="data-head">
						<h6 class="overline-title"><?php esc_html_e( 'Employee Work Details', 'hrp' ); ?></h6>
					</div>


					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="Department"><?php esc_html_e( 'Department', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<select class="form-select js-select2" id="Department" name="department">
									<?php foreach ( $department_list as $department ) : ?>
										<option value="<?php echo esc_attr( $department->ID ); ?>" <?php selected( $department->ID, $department_id ); ?>> <?php echo esc_html( ucwords( $department->title ) ); ?> </option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>


					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="designation"><?php esc_html_e( 'Designation', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<select class="form-select js-select2" id="designation" name="designation">
									<?php foreach ( $designation_list as $designation ) : ?>
										<option value="<?php echo esc_attr( $designation->ID ); ?>" <?php selected( $designation->ID, $designation_id ); ?>> <?php echo esc_html( ucwords( $designation->title ) ); ?> </option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="Shift"><?php esc_html_e( 'Shift', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<select class="form-select js-select2" id="shift" name="shift_id">
									<?php foreach ( $shift_list as $shift ) : ?>
										<option value="<?php echo esc_attr( $shift->ID ); ?>" <?php selected( $shift->ID, $shift_id ); ?>> <?php echo esc_html( ucwords( $shift->title ) ); ?> </option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="location"><?php esc_html_e( 'Location', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="location" name="location" placeholder="<?php esc_attr_e( 'Enter Location', 'hrp' ); ?>" value="<?php echo esc_attr( $location ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="source_of_hire"><?php esc_html_e( 'Source Of Hire', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="source_of_hire" name="source_of_hire" placeholder="<?php esc_attr_e( 'Enter Source Of Hire', 'hrp' ); ?>" value="<?php echo esc_attr( $source_of_hire ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="pay_rate"><?php esc_html_e( 'Pay Rate', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="pay_rate" name="pay_rate" placeholder="<?php esc_attr_e( 'Enter Pay Rate', 'hrp' ); ?>" value="<?php echo esc_attr( $pay_rate ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="Pay_type"><?php esc_html_e( 'Pay Type', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<select class="form-select js-select2" id="pay_type" name="pay_type">
									<?php foreach ( $pay_type_list as $key => $pay_type ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $pay_type_id ); ?>><?php echo esc_html( $pay_type ); ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>


					<div class="data-head">
						<h6 class="overline-title"><?php esc_html_e( 'Employee Personal Details', 'hrp' ); ?></h6>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="father_name"><?php esc_html_e( 'Father\'s Name', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="father_name" name="father_name" placeholder="<?php esc_attr_e( 'Enter Father\'s Name', 'hrp' ); ?>" value="<?php echo esc_attr( $father_name ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="mother_name"><?php esc_html_e( 'Mother\'s Name', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="<?php esc_attr_e( 'Enter Mother\'s Name', 'hrp' ); ?>" value="<?php echo esc_attr( $mother_name ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="spouse_name"><?php esc_html_e( 'Spouse\'s Name', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="spouse_name" name="spouse_name" placeholder="<?php esc_attr_e( 'Enter Spouse\'s Name', 'hrp' ); ?>" value="<?php echo esc_attr( $spouse_name ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="Blood_group"><?php esc_html_e( 'Blood Group', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<select class="form-select js-select2" id="blood_group" name="blood_group">
									<?php foreach ( $blood_group_list as $key => $blood_group ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $employee_blood_group ); ?>><?php echo esc_html( $blood_group ); ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="mobile"><?php esc_html_e( 'Mobile', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="mobile" name="mobile" placeholder="<?php esc_attr_e( 'Enter Mobile', 'hrp' ); ?>" value="<?php echo esc_attr( $mobile ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="nationality"><?php esc_html_e( 'Nationality', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="nationality" name="nationality" placeholder="<?php esc_attr_e( 'Enter Nationality', 'hrp' ); ?>" value="<?php echo esc_attr( $nationality ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="Gender"><?php esc_html_e( 'Gender', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<select class="form-select js-select2" id="Gender" name="gender">
									<?php foreach ( $gender_list as $key => $gender ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $employee_gender ); ?>><?php echo esc_html( $gender ); ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="marital_status"><?php esc_html_e( 'Marital Status', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="marital_status" name="marital_status" placeholder="<?php esc_attr_e( 'Enter Marital Status', 'hrp' ); ?>" value="<?php echo esc_attr( $marital_status ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="driving_license"><?php esc_html_e( 'Driving License', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="driving_license" name="driving_license" placeholder="<?php esc_attr_e( 'Enter Driving License', 'hrp' ); ?>" value="<?php echo esc_attr( $driving_license ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="address_1"><?php esc_html_e( 'Address 1', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="address_1" name="address_1" placeholder="<?php esc_attr_e( 'Enter Address 1', 'hrp' ); ?>" value="<?php echo esc_attr( $address_1 ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="address_2"><?php esc_html_e( 'Address 2', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="address_2" name="address_2" placeholder="<?php esc_attr_e( 'Enter Address 2', 'hrp' ); ?>" value="<?php echo esc_attr( $address_2 ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="city"><?php esc_html_e( 'City', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="city" name="city" placeholder="<?php esc_attr_e( 'Enter City', 'hrp' ); ?>" value="<?php echo esc_attr( $city ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="country"><?php esc_html_e( 'Country', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="country" name="country" placeholder="<?php esc_attr_e( 'Enter Country', 'hrp' ); ?>" value="<?php echo esc_attr( $country ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="state"><?php esc_html_e( 'State', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="state" name="state" placeholder="<?php esc_attr_e( 'Enter State', 'hrp' ); ?>" value="<?php echo esc_attr( $state ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label" for="zip_code"><?php esc_html_e( 'Zip Code', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="<?php esc_attr_e( 'Enter Zip Code', 'hrp' ); ?>" value="<?php echo esc_attr( $zip_code ); ?>">
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label class="form-label" for="bio"><?php esc_html_e( 'Bio', 'hrp' ); ?></label>
							<div class="form-control-wrap">
								<textarea class="form-control no-resize" name="bio" id="bio" spellcheck="false" data-ms-editor="true" placeholder="<?php esc_attr_e( 'Enter Bio', 'hrp' ); ?>"><?php echo esc_attr( $bio ); ?></textarea>
							</div>
						</div>
					</div>

					<div class="col-sm-12">
						<ul class="preview-list ">
							<li class="preview-item">
								<button type="submit" class="btn btn-primary hrp-save-btn" id="hrp-save-btn">
									<span><?php esc_html_e( 'Submit', 'hrp' ); ?></span>
								</button>
							</li>
						</ul>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
