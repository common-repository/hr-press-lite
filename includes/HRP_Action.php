<?php
defined( 'ABSPATH' ) || die();
require_once HRP_PLUGIN_DIR . 'includes/constants.php';

class HRP_Action {
	public static function save_department() {
		try {
			ob_start();
			global $wpdb;
			$department_id = isset( $_POST['department_id'] ) ? absint( $_POST['department_id'] ) : 0;
			if ( ! wp_verify_nonce( $_POST['save-department'], 'save-department' ) ) {
				die();
			}

			if ( $department_id ) {
				$department = hrp_Helper::get_department( $department_id );
				if ( ! $department ) {
					throw new Exception( esc_html__( 'Department does not found!.', 'hrp' ) );
				}
			}

			$title       = ! empty( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
			$description = ! empty( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';
			$employee_id = ! empty( $_POST['employee_id'] ) ? absint( $_POST['employee_id'] ) : null;

			// Validate input data.
			$errors = array();
			if ( ! $title ) {
				$errors['title'] = esc_html__( 'Please enter department title.', 'hrp' );
			} else {
				if ( strlen( $title ) > 190 ) {
					$errors['title'] = esc_html__( 'Maximum length cannot exceed 191 characters.', 'hrp' );
				}
			}
			// Checks if department already exists with Title.
			if ( ! $department_id ) {
				$department_exist = hrp_Helper::get_department_by_title( $title );
				if ( $department_exist ) {
					$errors['title'] = esc_html__( 'Department already exists with the title "' . $title . '"', 'hrp' );
				}
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// Update or insert table.
				$data = array(
					'title'       => $title,
					'description' => $description,
					'employee_id' => $employee_id,
				);

				// Checks if update or insert.
				if ( $department_id ) {
					$success = $wpdb->update( DEPARTMENTS, $data, array( 'ID' => $department_id ) );
					$message = esc_html__( 'Department Successfully Updated.', 'hrp' );
				} else {
					$success = $wpdb->insert( DEPARTMENTS, $data );
					$message = esc_html__( 'Department Successfully Added.', 'hrp' );
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );

	}

	public static function fetch_departments() {
		global $wpdb;
		$query        = HRP_Helper::get_departments();
		$query_filter = $query;

		// Grouping.
		$group_by      = ' ' . 'GROUP BY d.ID';
		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition    .= '' . '(d.title LIKE "%' . $search_value . '%")';
				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'd.title', 'd.description' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY d.title DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = HRP_Helper::get_departments_row_count();
		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' WHERE (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit    = $wpdb->get_results( $query_filter . $limit );
		$departments_page_url = HRP_Helper::get_page_url( DEPARTMENTS );
		$data                 = array();
		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				if ( ! empty( $row->employee_id ) ) {
					$manager_name = HRP_Helper::get_employee( $row->employee_id );
					$manager_name = $manager_name->name;
				} else {
					$manager_name = '';
				}
				$number_of_employees = ( $row->ID ) ? HRP_Helper::get_employee_count_by_department( $row->ID ) : '0';
				$data[]              = array(
					'<div class="custom-control custom-control-sm custom-checkbox notext">
						<input type="checkbox" class="custom-control-input bulk-select" id="' . $row->ID . '">
						<label class="custom-control-label" for="' . $row->ID . '"></label>
					</div>',
					'<a href="' . esc_url( $departments_page_url . '&action=save&id=' . $row->ID ) . '">' . esc_html( ucwords( $row->title ) ) . '</a>',
					esc_html( wp_trim_words( $row->description, 12 ) ),
					esc_html( $number_of_employees ),
					'<span class="badge badge-dim bg-outline-primary">' . esc_html( ucwords( $manager_name ) ) . '</span>',
					'<ul class="nk-tb-actions gx-1">
						<li>
							<div class="dropdown">
								<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
								<div class="dropdown-menu dropdown-menu-end">
									<ul class="link-list-opt no-bdr">
										<li>
											<a href="' . esc_url( $departments_page_url . '&action=save&id=' . $row->ID ) . '"><em class="icon ni ni-edit"></em><span>'. esc_html__( 'Edit', 'hrp' ).'</span></a>
										</li>
										<li>
											<a href="#" data-id="' . $row->ID . '" data-nonce="' . esc_attr( wp_create_nonce( 'delete-department' ) ) . '" class="delete-department" ><em class="icon ni ni-trash-empty"></em></em><span>'. esc_html__( 'Delete', 'hrp' ).'</span></a>
										</li>
									</ul>
								</div>
							</div>
						</li>
					</ul>',
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);
		echo json_encode( $output );
		die;
	}

	public static function delete_department() {
		try {
			ob_start();
			global $wpdb;

			$department_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'delete-department' ) ) {
				die();
			}

			// if department exists.
			$department = HRP_Helper::get_department( $department_id );
			if ( ! $department ) {
				throw new Exception( esc_html__( 'Department not found.', 'hrp' ) );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( DEPARTMENTS, array( 'ID' => $department_id ) );
			$message = esc_html__( 'Department deleted successfully.', 'hrp' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function save_designation() {
		try {
			ob_start();
			global $wpdb;
			$designation_id = isset( $_POST['designation_id'] ) ? absint( $_POST['designation_id'] ) : 0;
			if ( ! wp_verify_nonce( $_POST['save-designation'], 'save-designation' ) ) {
				die();
			}

			if ( $designation_id ) {
				$designation = hrp_Helper::get_designation( $designation_id );
				if ( ! $designation ) {
					throw new Exception( esc_html__( 'Designation does not found!.', 'hrp' ) );
				}
			}

			$title       = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
			$description = isset( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';

			// Validate input data.
			$errors = array();
			if ( ! $title ) {
				$errors['title'] = esc_html__( 'Please enter designation title.', 'hrp' );
			} else {
				if ( strlen( $title ) > 190 ) {
					$errors['title'] = esc_html__( 'Maximum length cannot exceed 191 characters.', 'hrp' );
				}
			}
			// Checks if designation already exists with Title.
			$designation_exist = hrp_Helper::get_designation_by_title( $title );
			if ( $designation_exist && ! $designation_id ) {
				$errors['title'] = esc_html__( 'Designation already exists with the title "' . $title . '"', 'hrp' );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// Update or insert table.
				$data = array(
					'title'       => $title,
					'description' => $description,
				);

				// Checks if update or insert.
				if ( $designation_id ) {
					$success = $wpdb->update( DESIGNATIONS, $data, array( 'ID' => $designation_id ) );
					$message = esc_html__( 'Designation Successfully Updated.', 'hrp' );
				} else {
					$success = $wpdb->insert( DESIGNATIONS, $data );
					$message = esc_html__( 'Designation Successfully Added.', 'hrp' );
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );

	}

	public static function fetch_designation() {
		global $wpdb;
		$query        = HRP_Helper::get_designations();
		$query_filter = $query;

		// Grouping.
		$group_by      = ' ' . 'GROUP BY d.ID';
		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition    .= '' . '(d.title LIKE "%' . $search_value . '%")';
				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'd.title', 'd.description' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY d.title DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = HRP_Helper::get_designations_row_count();
		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' WHERE (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit     = $wpdb->get_results( $query_filter . $limit );
		$designations_page_url = HRP_Helper::get_page_url( DESIGNATIONS );
		$data                  = array();
		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				$emp_count = HRP_Helper::get_employee_count_by_designation( $row->ID );
				$data[]    = array(
					'<div class="custom-control custom-control-sm custom-checkbox notext">
						<input type="checkbox" class="custom-control-input bulk-select" id="' . $row->ID . '">
						<label class="custom-control-label" for="' . $row->ID . '"></label>
					</div>',
					'<a href="' . esc_url( $designations_page_url . '&action=save&id=' . $row->ID ) . '">' . esc_html( ucwords( $row->title ) ) . '</a>',
					esc_html( wp_trim_words( $row->description, 12 ) ),
					esc_html( $emp_count ),
					'<ul class="nk-tb-actions gx-1">
						<li>
							<div class="dropdown">
								<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
								<div class="dropdown-menu dropdown-menu-end">
									<ul class="link-list-opt no-bdr">
										<li><a href="' . esc_url( $designations_page_url . '&action=save&id=' . $row->ID ) . '"><em class="icon ni ni-edit"></em><span>'. esc_html__( 'Edit', 'hrp' ).'</span></a></li>
										<li><a href="#" data-id="' . $row->ID . '" data-nonce="' . esc_attr( wp_create_nonce( 'delete-designation' ) ) . '" class="delete-designation" ><em class="icon ni ni-trash-empty"></em></em><span>'. esc_html__( 'Delete', 'hrp' ).'</span></a></li>
									</ul>
								</div>
							</div>
						</li>
					</ul>',
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);
		echo json_encode( $output );
		die;
	}

	public static function delete_designation() {
		try {
			ob_start();
			global $wpdb;

			$designation_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'delete-designation' ) ) {
				die();
			}

			// if designation exists.
			$designation = HRP_Helper::get_designation( $designation_id );
			if ( ! $designation ) {
				throw new Exception( esc_html__( 'Designation not found.', 'hrp' ) );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( DESIGNATIONS, array( 'ID' => $designation_id ) );
			$message = esc_html__( 'Designation deleted successfully.', 'hrp' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function save_shift() {
		try {
			ob_start();
			global $wpdb;
			$shift_id = isset( $_POST['shift_id'] ) ? absint( $_POST['shift_id'] ) : 0;
			if ( ! wp_verify_nonce( $_POST['save-shift'], 'save-shift' ) ) {
				die();
			}

			if ( $shift_id ) {
				$shift = hrp_Helper::get_shift( $shift_id );
				if ( ! $shift ) {
					throw new Exception( esc_html__( 'Shift does not found!.', 'hrp' ) );
				}
			}

			$title      = ! empty( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
			$start_time = ! empty( $_POST['start_time'] ) ? sanitize_text_field( $_POST['start_time'] ) : '';
			$end_time   = ! empty( $_POST['end_time'] ) ? sanitize_text_field( $_POST['end_time'] ) : '';
			$holidays   = ! empty( $_POST['holidays'] ) ? ( $_POST['holidays'] ) : '';
			$status     = ! empty( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';

			// Validate input data.
			$errors = array();
			if ( ! $title ) {
				$errors['title'] = esc_html__( 'Please enter shift title.', 'hrp' );
			} else {
				if ( strlen( $title ) > 190 ) {
					$errors['title'] = esc_html__( 'Maximum length cannot exceed 191 characters.', 'hrp' );
				}
			}

			$start_time_timestamp  = strtotime( $start_time );
			$end_time_timestamp    = strtotime( $end_time );
			$date_period_timestamp = $start_time_timestamp + 86400;

			if ( $start_time_timestamp >= $end_time_timestamp ) {
				$end_time_timestamp += 86400;
			}

			if ( $end_time_timestamp >= $date_period_timestamp ) {
				throw new Exception( esc_html__( 'Time range for the shift is invalid. Please choose shift length less than 24hrs', 'hrp' ) );
			}
			// Checks if shift already exists with Title.
			$shift_exist = hrp_Helper::get_shift_by_title( $title );
			if ( $shift_exist && ! $shift_id ) {
				$errors['title'] = esc_html__( 'Shift already exists with the title "' . $title . '"', 'hrp' );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// Update or insert table.
				$data = array(
					'title'      => $title,
					'start_time' => date( 'H:i:s', $start_time_timestamp ),
					'end_time'   => date( 'H:i:s', $end_time_timestamp ),
					'holidays'   => serialize( $holidays ),
					'status'     => $status,
				);

				// Checks if update or insert.
				if ( $shift_id ) {
					$success = $wpdb->update( SHIFTS, $data, array( 'ID' => $shift_id ) );
					$message = esc_html__( 'Shift Successfully Updated.', 'hrp' );
				} else {
					$success = $wpdb->insert( SHIFTS, $data );
					$message = esc_html__( 'Shift Successfully Added.', 'hrp' );
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );

	}

	public static function fetch_shift() {
		global $wpdb;
		$query        = HRP_Helper::get_shifts();
		$query_filter = $query;

		// Grouping.
		$group_by      = ' ' . 'GROUP BY sf.ID';
		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition    .= '' . '(sf.title LIKE "%' . $search_value . '%")';
				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'sf.title' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY sf.title DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = HRP_Helper::get_shifts_row_count();
		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' WHERE (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );
		$shifts_page_url   = HRP_Helper::get_page_url( SHIFTS );
		$data              = array();
		if ( count( $filter_rows_limit ) ) {

			foreach ( $filter_rows_limit as $row ) {
				// Table columns.

				// unserialize array and capitalize words
				$holidays      = array_map( 'ucwords', array_map( 'strtolower', unserialize( $row->holidays ) ) );
				$holidays_list = implode( ', ', ( $holidays ) );

				// shift Duration.
				$duration = round( abs( strtotime( $row->start_time ) - strtotime( $row->end_time ) ) / 3600, 1 );
				$data[]   = array(
					'<div class="custom-control custom-control-sm custom-checkbox notext">
						<input type="checkbox" class="custom-control-input bulk-select" id="' . $row->ID . '">
						<label class="custom-control-label" for="' . $row->ID . '"></label>
					</div>',
					'<a href="' . esc_url( $shifts_page_url . '&action=save&id=' . $row->ID ) . '">' . esc_html( ucwords( $row->title ) ) . '</a>',
					esc_html( HRP_Helper::time_format( $row->start_time ) ),
					esc_html( HRP_Helper::time_format( $row->end_time ) ),
					esc_html( $duration ) . ' ' . esc_html( 'H', 'hrp' ),
					'<span class="badge badge-dim bg-outline-info">' . esc_html( ( $holidays_list ) ) . '</span>',
					'<ul class="nk-tb-actions gx-1">
						<li>
							<div class="dropdown">
								<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
								<div class="dropdown-menu dropdown-menu-end">
									<ul class="link-list-opt no-bdr">
										<li><a href="' . esc_url( $shifts_page_url . '&action=save&id=' . $row->ID ) . '"><em class="icon ni ni-edit"></em><span>'. esc_html__( 'Edit', 'hrp' ).'</span></a></li>
										<li><a href="#" data-id="' . $row->ID . '" data-nonce="' . esc_attr( wp_create_nonce( 'delete-shift' ) ) . '" class="delete-shift" ><em class="icon ni ni-trash-empty"></em></em><span>'. esc_html__( 'Delete', 'hrp' ).'</span></a></li>
									</ul>
								</div>
							</div>
						</li>
					</ul>',
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);
		echo json_encode( $output );
		die;
	}

	public static function delete_shift() {
		try {
			ob_start();
			global $wpdb;

			$shift_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'delete-shift' ) ) {
				die();
			}

			// if shift exists.
			$shift = HRP_Helper::get_shift( $shift_id );
			if ( ! $shift ) {
				throw new Exception( esc_html__( 'Shift not found.', 'hrp' ) );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( SHIFTS, array( 'ID' => $shift_id ) );
			$message = esc_html__( 'Shift deleted successfully.', 'hrp' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function save_attendance() {
		try {
			ob_start();
			global $wpdb;
			$attendance_id = isset( $_POST['attendance_id'] ) ? absint( $_POST['attendance_id'] ) : 0;
			if ( ! wp_verify_nonce( $_POST['save-attendance'], 'save-attendance' ) ) {
				die();
			}

			if ( $attendance_id ) {
				$attendance = hrp_Helper::get_attendance( $attendance_id );
				if ( ! $attendance ) {
					throw new Exception( esc_html__( 'Attendance does not found!.', 'hrp' ) );
				}
			}

			$date        = ! empty( $_POST['date'] ) ? DateTime::createFromFormat( HRP_Helper::date_default_format(), sanitize_text_field( $_POST['date'] ) ) : null;
			$employee_id = ! empty( $_POST['employee_id'] ) ? sanitize_text_field( $_POST['employee_id'] ) : '';
			$checkin     = ! empty( $_POST['checkin'] ) ? sanitize_text_field( $_POST['checkin'] ) : '';
			$checkout    = ! empty( $_POST['checkout'] ) ? sanitize_text_field( $_POST['checkout'] ) : '';
			$comment     = ! empty( $_POST['comment'] ) ? sanitize_text_field( $_POST['comment'] ) : '';

			// Validate input data.
			$errors = array();
			if ( ! $date ) {
				$errors['date'] = esc_html__( 'Please enter date.', 'hrp' );
			}

			$date = ( ! empty( $date ) ) ? $date->format( 'Y-m-d' ) : null;

			// Checks if attendance already exists with Date.
			$attendance_exist = hrp_Helper::get_attendance_by_date( $date, $employee_id );
			if ( $attendance_exist && ! $attendance_id ) {
				$errors['date'] = esc_html__( 'Attendance already exists with the date "' . $date . '"', 'hrp' );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// Update or insert table.
				$data = array(
					'date'        => $date,
					'employee_id' => $employee_id,
					'checkin'     => date( 'H:i:s', strtotime( $checkin ) ),
					'checkout'    => date( 'H:i:s', strtotime( $checkout ) ),
					'comment'     => $comment,
					'status'      => 1,
				);

				// Checks if update or insert.
				if ( $attendance_id ) {
					$success = $wpdb->update( ATTENDANCES, $data, array( 'ID' => $attendance_id ) );
					$message = esc_html__( 'Attendance Successfully Updated.', 'hrp' );
				} else {
					$success = $wpdb->insert( ATTENDANCES, $data );
					$message = esc_html__( 'Attendance Successfully Added.', 'hrp' );
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );

	}

	public static function fetch_attendance_employees() {
		global $wpdb;
		$query        = HRP_Helper::get_employees();
		$query_filter = $query;

		// Grouping.
		$group_by      = ' ' . 'GROUP BY emp.ID';
		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition    .= '' . '(emp.name LIKE "%' . $search_value . '%")';
				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'emp.name' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY emp.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = HRP_Helper::get_employees_row_count();
		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' WHERE (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit    = $wpdb->get_results( $query_filter . $limit );
		$attendances_page_url = HRP_Helper::get_page_url( ATTENDANCES );
		$data                 = array();
		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				$data[] = array(
					'<a href="' . esc_url( $attendances_page_url . '&action=view&id=' . $row->ID ) . '"><span>' . esc_html( HRP_Helper::employee_id_prefix( $row->employee_id ) ) . '</span></a>',
					esc_html( ucwords( $row->name ) ),
					esc_html( ucwords( $row->shift ) ),
					HRP_Helper::get_status( $row->status ),
					'<ul class="nk-tb-actions gx-1">
						<li>
							<div class="dropdown">
								<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
								<div class="dropdown-menu dropdown-menu-end">
									<ul class="link-list-opt no-bdr">
										<li><a href="' . esc_url( $attendances_page_url . '&action=view&id=' . $row->ID ) . '"><em class="icon ni ni-edit"></em><span>'. esc_html__( 'View', 'hrp' ).'</span></a></li>
										<li><a href="#" data-id="' . $row->ID . '" data-nonce="' . esc_attr( wp_create_nonce( 'delete-attendance' ) ) . '" class="delete-attendance" ><em class="icon ni ni-trash-empty"></em></em><span>'. esc_html__( 'Delete', 'hrp' ).'</span></a></li>
									</ul>
								</div>
							</div>
						</li>
					</ul>',
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);
		echo json_encode( $output );
		die;
	}

	public static function fetch_attendances() {

		if ( ! wp_verify_nonce( $_POST['nonce'], 'attendances' ) ) {
			die(); }

		$employee_id = ! empty( $_POST['employee_id'] ) ? sanitize_text_field( $_POST['employee_id'] ) : '';
		$month       = ! empty( $_POST['month'] ) ? sanitize_text_field( $_POST['month'] ) : '';
		$year        = ! empty( $_POST['year'] ) ? sanitize_text_field( $_POST['year'] ) : '';

		global $wpdb;
		$query = HRP_Helper::get_attendances_with_employee_id( $employee_id, $month, $year );

		// Filtered limit rows.
		$rows                 = $wpdb->get_results( $query );
		$attendances_page_url = HRP_Helper::get_page_url( ATTENDANCES );
		$holidays             = HRP_Helper::get_holidays_list();
		$data                 = array();
		if ( count( $rows ) ) {
			foreach ( $rows as $row ) {
				// Table columns.
				if ( $row->ID ) {
					$action = '<ul class="nk-tb-actions gx-1">
								<li>
									<a href="' . esc_url( $attendances_page_url . '&action=save&id=' . $row->ID ) . '"><em class="icon ni ni-link-alt"></em></a>
								</li>
							</ul>';
				} else {
					$action = '';
				}

				if ( ! empty( $row->checkin ) && ! empty( $row->checkout ) ) {
					$hours = HRP_Helper::seconds_to_hour_minute( abs( strtotime( $row->checkin ) - strtotime( $row->checkout ) ) );
				} else {
					$hours = '--:--';
				}

				$data[] = array(
					esc_html( HRP_Helper::display_date( $row->Days ) ),
					HRP_Helper::time_format( $row->checkin ),
					HRP_Helper::time_format( $row->checkout ),
					$hours,
					HRP_Helper::time_format( $row->breakin ),
					HRP_Helper::time_format( $row->breakout ),
					'<span class="badge badge-dim bg-outline-primary">' . esc_html( $row->ip_address ) . '</span>',
					HRP_Helper::get_holiday_status( $holidays, $row->Days, $row->status ),
					$action,
				);
			}
		}

		$output = array(
			'data' => $data,
		);
		echo json_encode( $output );
		die;
	}

	public static function delete_attendance() {
		try {
			ob_start();
			global $wpdb;

			$attendance_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'delete-attendance' ) ) {
				die();
			}

			// if attendance exists.
			$attendance = HRP_Helper::get_attendance( $attendance_id );
			if ( ! $attendance ) {
				throw new Exception( esc_html__( 'Attendance not found.', 'hrp' ) );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( ATTENDANCES, array( 'ID' => $attendance_id ) );
			$message = esc_html__( 'Attendance deleted successfully.', 'hrp' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function save_holiday() {
		try {
			ob_start();
			global $wpdb;
			$holiday_id = isset( $_POST['holiday_id'] ) ? absint( $_POST['holiday_id'] ) : 0;
			if ( ! wp_verify_nonce( $_POST['save-holiday'], 'save-holiday' ) ) {
				die();
			}

			if ( $holiday_id ) {
				$holiday = hrp_Helper::get_holiday( $holiday_id );
				if ( ! $holiday ) {
					throw new Exception( esc_html__( 'Holiday does not found!.', 'hrp' ) );
				}
			}

			$title       = ! empty( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
			$start_date  = isset( $_POST['start_date'] ) ? DateTime::createFromFormat( HRP_Helper::date_default_format(), sanitize_text_field( $_POST['start_date'] ) ) : null;
			$end_date    = isset( $_POST['end_date'] ) ? DateTime::createFromFormat( HRP_Helper::date_default_format(), sanitize_text_field( $_POST['end_date'] ) ) : null;
			$description = ! empty( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';

			// Validate input data.
			$errors = array();
			if ( ! $title ) {
				$errors['title'] = esc_html__( 'Please enter holiday title.', 'hrp' );
			} else {
				if ( strlen( $title ) > 190 ) {
					$errors['title'] = esc_html__( 'Maximum length cannot exceed 191 characters.', 'hrp' );
				}
			}

			$start_date = ( ! empty( $start_date ) ) ? $start_date->format( 'Y-m-d' ) : null;
			$end_date   = ( ! empty( $end_date ) ) ? $end_date->format( 'Y-m-d' ) : null;

			// Checks if holiday already exists with Title.
			$holiday_exist = hrp_Helper::get_holiday_by_title( $title );
			if ( $holiday_exist && ! $holiday_id ) {
				$errors['title'] = esc_html__( 'Holiday already exists with the title "' . $title . '"', 'hrp' );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// Update or insert table.
				$data = array(
					'title'            => $title,
					'start_date'       => $start_date,
					'end_date'         => $end_date,
					'description'      => $description,
					'added_by_user_id' => get_current_user_id(),
				);

				// Checks if update or insert.
				if ( $holiday_id ) {
					$success = $wpdb->update( HOLIDAYS, $data, array( 'ID' => $holiday_id ) );
					$message = esc_html__( 'Holiday Successfully Updated.', 'hrp' );
				} else {
					$success = $wpdb->insert( HOLIDAYS, $data );
					$message = esc_html__( 'Holiday Successfully Added.', 'hrp' );
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );

	}

	public static function fetch_holiday() {
		global $wpdb;
		$query        = HRP_Helper::get_holidays();
		$query_filter = $query;

		// Grouping.
		$group_by      = ' ' . 'GROUP BY h.ID';
		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition    .= '' . '(h.title LIKE "%' . $search_value . '%")';
				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'h.title' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY h.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = HRP_Helper::get_holidays_row_count();
		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' WHERE (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );
		$holidays_page_url = HRP_Helper::get_page_url( HOLIDAYS );
		$data              = array();
		if ( count( $filter_rows_limit ) ) {

			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				$user_info = get_userdata( $row->added_by_user_id );
				$duration  = ( new DateTime( $row->start_date ) )->diff( new DateTime( $row->end_date ) );
				$data[]    = array(
					'<div class="custom-control custom-control-sm custom-checkbox notext">
						<input type="checkbox" class="custom-control-input bulk-select" id="' . $row->ID . '">
						<label class="custom-control-label" for="' . $row->ID . '"></label>
					</div>',
					'<a href="' . esc_url( $holidays_page_url . '&action=save&id=' . $row->ID ) . '">' . esc_html( ucwords( $row->title ) ) . '</a>',
					esc_html( HRP_Helper::display_date( $row->start_date ) ),
					esc_html( HRP_Helper::display_date( $row->end_date ) ),
					esc_html( HRP_Helper::days_diff( $row->start_date, $row->end_date ) ),
					esc_html( wp_trim_words( $row->description, 12 ) ),
					'<span class="badge badge-dim bg-outline-info">' . esc_html( ucwords( $user_info->display_name ) ) . '</span>',
					'<ul class="nk-tb-actions gx-1">
						<li>
							<div class="dropdown">
								<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
								<div class="dropdown-menu dropdown-menu-end">
									<ul class="link-list-opt no-bdr">
										<li><a href="' . esc_url( $holidays_page_url . '&action=save&id=' . $row->ID ) . '"><em class="icon ni ni-edit"></em><span>'. esc_html__( 'Edit', 'hrp' ).'</span></a></li>
										<li><a href="#" data-id="' . $row->ID . '" data-nonce="' . esc_attr( wp_create_nonce( 'delete-holiday' ) ) . '" class="delete-holiday" ><em class="icon ni ni-trash-empty"></em></em><span>'. esc_html__( 'Delete', 'hrp' ).'</span></a></li>
									</ul>
								</div>
							</div>
						</li>
					</ul>',
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);
		echo json_encode( $output );
		die;
	}

	public static function delete_holiday() {
		try {
			ob_start();
			global $wpdb;

			$holiday_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'delete-holiday' ) ) {
				die();
			}

			// if holiday exists.
			$holiday = HRP_Helper::get_holiday( $holiday_id );
			if ( ! $holiday ) {
				throw new Exception( esc_html__( 'Holiday not found.', 'hrp' ) );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( HOLIDAYS, array( 'ID' => $holiday_id ) );
			$message = esc_html__( 'Holiday deleted successfully.', 'hrp' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function save_employee() {
		try {
			ob_start();
			global $wpdb;

			$employee_id = isset( $_POST['employee_id'] ) ? absint( $_POST['employee_id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST['save-employee'], 'save-employee' ) ) {
				die();
			}

			if ( $employee_id ) {
				$employee = hrp_Helper::get_employee( $employee_id );
				$user_id  = $employee->user_id;

				if ( ! $employee ) {
					throw new Exception( esc_html__( 'Employee does not found!.', 'hrp' ) );
				}
			}

			$first_name         = ! empty( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
			$middle_name        = ! empty( $_POST['middle_name'] ) ? sanitize_text_field( $_POST['middle_name'] ) : '';
			$last_name          = ! empty( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
			$employee_id_number = ! empty( $_POST['employee_id_number'] ) ? sanitize_text_field( $_POST['employee_id_number'] ) : '';
			$email              = ! empty( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
			$employee_type      = ! empty( $_POST['employee_type'] ) ? sanitize_text_field( $_POST['employee_type'] ) : '';
			$employee_status    = ! empty( $_POST['employee_status'] ) ? sanitize_text_field( $_POST['employee_status'] ) : '';
			$date_of_hire       = isset( $_POST['date_of_hire'] ) ? DateTime::createFromFormat( HRP_Helper::date_default_format(), sanitize_text_field( $_POST['date_of_hire'] ) ) : null;
			$date_of_birth      = isset( $_POST['date_of_birth'] ) ? DateTime::createFromFormat( HRP_Helper::date_default_format(), sanitize_text_field( $_POST['date_of_birth'] ) ) : null;
			$termination_date   = isset( $_POST['termination_date'] ) ? DateTime::createFromFormat( HRP_Helper::date_default_format(), sanitize_text_field( $_POST['termination_date'] ) ) : null;

			$department     = ! empty( $_POST['department'] ) ? sanitize_text_field( $_POST['department'] ) : '';
			$designation    = ! empty( $_POST['designation'] ) ? sanitize_text_field( $_POST['designation'] ) : '';
			$shift_id       = ! empty( $_POST['shift_id'] ) ? sanitize_text_field( $_POST['shift_id'] ) : '';
			$location       = ! empty( $_POST['location'] ) ? sanitize_text_field( $_POST['location'] ) : '';
			$source_of_hire = ! empty( $_POST['source_of_hire'] ) ? sanitize_text_field( $_POST['source_of_hire'] ) : '';
			$pay_rate       = ! empty( $_POST['pay_rate'] ) ? sanitize_text_field( $_POST['pay_rate'] ) : '';
			$pay_type       = ! empty( $_POST['pay_type'] ) ? sanitize_text_field( $_POST['pay_type'] ) : '';
			$photo_id       = ! empty( $_POST['photo_id'] ) ? sanitize_text_field( $_POST['photo_id'] ) : '';

			$father_name     = ! empty( $_POST['father_name'] ) ? sanitize_text_field( $_POST['father_name'] ) : '';
			$mother_name     = ! empty( $_POST['mother_name'] ) ? sanitize_text_field( $_POST['mother_name'] ) : '';
			$spouse_name     = ! empty( $_POST['spouse_name'] ) ? sanitize_text_field( $_POST['spouse_name'] ) : '';
			$blood_group     = ! empty( $_POST['blood_group'] ) ? sanitize_text_field( $_POST['blood_group'] ) : '';
			$mobile          = ! empty( $_POST['mobile'] ) ? sanitize_text_field( $_POST['mobile'] ) : '';
			$nationality     = ! empty( $_POST['nationality'] ) ? sanitize_text_field( $_POST['nationality'] ) : '';
			$gender          = ! empty( $_POST['gender'] ) ? sanitize_text_field( $_POST['gender'] ) : '';
			$marital_status  = ! empty( $_POST['marital_status'] ) ? sanitize_text_field( $_POST['marital_status'] ) : '';
			$driving_license = ! empty( $_POST['driving_license'] ) ? sanitize_text_field( $_POST['driving_license'] ) : '';
			$address_1       = ! empty( $_POST['address_1'] ) ? sanitize_text_field( $_POST['address_1'] ) : '';
			$address_2       = ! empty( $_POST['address_2'] ) ? sanitize_text_field( $_POST['address_2'] ) : '';
			$city            = ! empty( $_POST['city'] ) ? sanitize_text_field( $_POST['city'] ) : '';
			$country         = ! empty( $_POST['country'] ) ? sanitize_text_field( $_POST['country'] ) : '';
			$state           = ! empty( $_POST['state'] ) ? sanitize_text_field( $_POST['state'] ) : '';
			$zip_code        = ! empty( $_POST['zip_code'] ) ? sanitize_text_field( $_POST['zip_code'] ) : '';
			$bio             = ! empty( $_POST['bio'] ) ? sanitize_text_field( $_POST['bio'] ) : '';

			// Validate input data.
			$errors = array();
			if ( ! $first_name ) {
				$errors['first_name'] = esc_html__( 'Please enter employee first name.', 'hrp' );
			} else {
				if ( strlen( $first_name ) > 190 ) {
					$errors['first_name'] = esc_html__( 'Maximum length cannot exceed 191 characters.', 'hrp' );
				}
			}

			// Get increasing row count for emp id
			if ( empty( $employee_id_number ) ) {
				$employee_id_number = HRP_Helper::get_employee_id_number();
			}

			$date_of_hire     = ( ! empty( $date_of_hire ) ) ? $date_of_hire->format( 'Y-m-d' ) : null;
			$date_of_birth    = ( ! empty( $date_of_birth ) ) ? $date_of_birth->format( 'Y-m-d' ) : null;
			$termination_date = ( ! empty( $termination_date ) ) ? $termination_date->format( 'Y-m-d' ) : null;

			// Checks if employee already exists with Name.
			$employee_exist = hrp_Helper::get_employee( $employee_id );
			if ( $employee_exist && ! $employee_id ) {
				$errors['employee_id'] = esc_html__( 'Employee already exists with the employee ID "' . $employee_id . '"', 'hrp' );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				if ( ! $employee_id ) {
					$user_data = array(
						'user_login'   => $email,
						'user_pass'    => $email,
						'user_email'   => $email,
						'first_name'   => $first_name,
						'last_name'    => $last_name,
						'display_name' => $first_name . ' ' . $last_name,
						'role'         => 'hrp_employee',
					);

					$user_id = wp_insert_user( $user_data );
					if ( is_wp_error( $user_id ) ) {
						throw new Exception( $user_id->get_error_message() );
					}
				}

				if ( $user_id ) {
					update_user_meta( $user_id, 'middle_name', $middle_name );
					update_user_meta( $user_id, 'last_name', $last_name );
					update_user_meta( $user_id, 'father_name', $father_name );
					update_user_meta( $user_id, 'mother_name', $mother_name );
					update_user_meta( $user_id, 'spouse_name', $spouse_name );
					update_user_meta( $user_id, 'blood_group', $blood_group );
					update_user_meta( $user_id, 'nationality', $nationality );
					update_user_meta( $user_id, 'gender', $gender );
					update_user_meta( $user_id, 'address_1', $address_1 );
					update_user_meta( $user_id, 'address_2', $address_2 );
					update_user_meta( $user_id, 'city', $city );
					update_user_meta( $user_id, 'country', $country );
					update_user_meta( $user_id, 'state', $state );
					update_user_meta( $user_id, 'zip_code', $zip_code );
					update_user_meta( $user_id, 'marital_status', $marital_status );
					update_user_meta( $user_id, 'driving_license', $driving_license );
					update_user_meta( $user_id, 'bio', $bio );
				}

				// Update or insert table.
				$data = array(
					'name'             => $first_name . ' ' . $last_name,
					'type'             => $employee_type,
					'date_of_hire'     => $date_of_hire,
					'status'           => $employee_status,
					'employee_id'      => $employee_id_number,
					'termination_date' => $termination_date,
					'date_of_birth'    => $date_of_birth,
					'user_id'          => $user_id,
					'department'       => $department,
					'designation'      => $designation,
					'location'         => $location,
					'pay_rate'         => $pay_rate,
					'pay_type'         => $pay_type,
					'mobile'           => $mobile,
					'shift_id'         => $shift_id,
					'source_of_hire'   => $source_of_hire,
					'photo_id'         => $photo_id,
				);

				// Checks if update or insert.
				if ( $employee_id ) {
					$success = $wpdb->update( EMPLOYEES, $data, array( 'ID' => $employee_id ) );
					$message = esc_html__( 'Employee Successfully Updated.', 'hrp' );
				} else {
					$success = $wpdb->insert( EMPLOYEES, $data );
					$message = esc_html__( 'Employee Successfully Added.', 'hrp' );
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );

	}

	public static function fetch_employee() {
		global $wpdb;
		$query        = HRP_Helper::get_employees();
		$query_filter = $query;

		// Grouping.
		$group_by      = ' ' . 'GROUP BY emp.ID';
		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition    .= '' . '(emp.name LIKE "%' . $search_value . '%")';
				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'emp.name' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY emp.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = HRP_Helper::get_employees_row_count();
		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' WHERE (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit  = $wpdb->get_results( $query_filter . $limit );
		$employees_page_url = HRP_Helper::get_page_url( EMPLOYEES );
		$data               = array();
		if ( count( $filter_rows_limit ) ) {

			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				$user_info = get_userdata( $row->user_id );
				$email     = $user_info->user_email;

				$employee_type_list = HRP_Helper::employee_type_list();
				foreach ( $employee_type_list as $key => $value ) {
					if ( $row->type == $key ) {
						$employee_type = $value;
					}
				}
				if ( ! empty( $row->photo_id ) ) {
					$avatar = '<img class="thumb" src="' . wp_get_attachment_url( $row->photo_id ) . '" alt="photo">';
				} else {
					$avatar = '<span>' . strtoupper( substr( $row->name, 0, 2 ) ) . '</span>';
				}
				$data[] = array(
					'<div class="custom-control custom-control-sm custom-checkbox notext">
						<input type="checkbox" class="custom-control-input bulk-select" id="' . $row->ID . '">
						<label class="custom-control-label" for="' . $row->ID . '"></label>
					</div>',
					'<div class="user-card">
						<div class="user-avatar bg-dim-primary d-none d-sm-flex">
							' . $avatar . '
						</div>
						<div class="user-info">
							<span class="tb-lead" ><a href="' . esc_url( $employees_page_url . '&action=save&id=' . $row->ID ) . '"><span>' . esc_html( ucwords( $row->name ) ) . '</span></a></span >
							<span>' . $email . '</span>
						</div>
					</div>',
					esc_html( $row->mobile ),
					esc_html( ucwords( $row->designation ) ),
					esc_html( ucwords( $row->department ) ),
					esc_html( $row->pay_rate ),
					esc_html( HRP_Helper::display_date( $row->date_of_hire ) ),
					esc_html( HRP_Helper::display_date( $row->termination_date ) ),
					'<span class="badge badge-dim bg-outline-primary">' . esc_html( $employee_type ) . '</span>',
					HRP_Helper::get_status( $row->status ),
					'<ul class="nk-tb-actions gx-1">
						<li>
							<div class="dropdown">
								<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
								<div class="dropdown-menu dropdown-menu-end">
									<ul class="link-list-opt no-bdr">
										<li><a href="' . esc_url( $employees_page_url . '&action=save&id=' . $row->ID ) . '"><em class="icon ni ni-edit"></em><span>'. esc_html__( 'Edit', 'hrp' ).'</span></a></li>
										<li><a href="#" data-id="' . $row->ID . '" data-nonce="' . esc_attr( wp_create_nonce( 'delete-employee' ) ) . '" class="delete-employee" ><em class="icon ni ni-trash-empty"></em></em><span>'. esc_html__( 'Delete', 'hrp' ).'</span></a></li>
									</ul>
								</div>
							</div>
						</li>
					</ul>',
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);
		echo json_encode( $output );
		die;
	}

	public static function delete_employee() {
		try {
			ob_start();
			global $wpdb;

			$employee_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'delete-employee' ) ) {
				die();
			}

			// if employee exists.
			$employee = HRP_Helper::get_employee( $employee_id );
			if ( ! $employee ) {
				throw new Exception( esc_html__( 'Employee not found.', 'hrp' ) );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( EMPLOYEES, array( 'ID' => $employee_id ) );
			$message = esc_html__( 'Employee deleted successfully.', 'hrp' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function save_announcement() {
		try {
			ob_start();
			global $wpdb;
			$announcement_id = isset( $_POST['announcement_id'] ) ? absint( $_POST['announcement_id'] ) : 0;
			if ( ! wp_verify_nonce( $_POST['save-announcement'], 'save-announcement' ) ) {
				die();
			}

			if ( $announcement_id ) {
				$announcement = hrp_Helper::get_announcement( $announcement_id );
				if ( ! $announcement ) {
					throw new Exception( esc_html__( 'Announcement does not found!.', 'hrp' ) );
				}
			}

			$title        = ! empty( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : null;
			$send_to      = ! empty( $_POST['send_to'] ) ? sanitize_text_field( $_POST['send_to'] ) : null;
			$announcement = ! empty( $_POST['announcement'] ) ? wp_kses_post( $_POST['announcement'] ) : null;
			$status       = ! empty( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : 1;
			$email        = ! empty( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : null;

			if ( $send_to === 'by_employee' ) {
				$announced_to = ! empty( $_POST['employee'] ) && is_array( $_POST['employee'] ) ? array_map( 'absint', $_POST['employee'] ) : array();
			} elseif ( $send_to === 'by_department' ) {
				$announced_to = ! empty( $_POST['department'] ) && is_array( $_POST['department'] ) ? array_map( 'absint', $_POST['department'] ) : array();
			} elseif ( $send_to === 'by_designation' ) {
				$announced_to = ! empty( $_POST['designation'] ) && is_array( $_POST['designation'] ) ? array_map( 'absint', $_POST['designation'] ) : array();
			} else {
				$announced_to = array();
			}

			// Validate input data.
			$errors = array();
			if ( ! $title ) {
				$errors['title'] = esc_html__( 'Please enter announcement title.', 'hrp' );
			} else {
				if ( strlen( $title ) > 190 ) {
					$errors['title'] = esc_html__( 'Maximum length cannot exceed 191 characters.', 'hrp' );
				}
			}

			if ( ! $send_to ) {
				$errors['send_to'] = esc_html__( 'Please enter announcement sent announcement to.', 'hrp' );
			}

			// Checks if announcement already exists with Title.
			$announcement_exist = hrp_Helper::get_announcement_by_title( $title );
			if ( $announcement_exist && ! $announcement_id ) {
				$errors['title'] = esc_html__( 'Announcement already exists with the title "' . $title . '"', 'hrp' );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );
				$data = array(
					'title'        => $title,
					'status'       => $status,
					'send_to'      => $send_to,
					'announcement' => $announcement,
					'user_id'      => get_current_user_id(),
					'announced_to' => serialize( $announced_to ),
				);

				// Checks if update or insert.
				if ( $announcement_id ) {
					$data['updated_at'] = current_time( 'Y-m-d H:i:s' );
					$success            = $wpdb->update( ANNOUNCEMENTS, $data, array( 'ID' => $announcement_id ) );
					$message            = esc_html__( 'Announcement Successfully Updated.', 'hrp' );

				} else {
					$data['created_at'] = current_time( 'Y-m-d H:i:s' );
					$success            = $wpdb->insert( ANNOUNCEMENTS, $data );
					$message            = esc_html__( 'Announcement Successfully Added.', 'hrp' );

					if ( $email ) {
						HRP_Helper::send_announcement_email( $send_to, $title, $announcement, $announced_to );
					}
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );

	}

	public static function fetch_announcement() {
		global $wpdb;
		$query        = HRP_Helper::get_announcements();
		$query_filter = $query;

		// Grouping.
		$group_by      = ' ' . 'GROUP BY an.ID';
		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition    .= '' . '(an.title LIKE "%' . $search_value . '%")';
				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'an.title' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY an.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = HRP_Helper::get_announcements_row_count();
		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' WHERE (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit      = $wpdb->get_results( $query_filter . $limit );
		$announcements_page_url = HRP_Helper::get_page_url( ANNOUNCEMENTS );
		$data                   = array();
		if ( count( $filter_rows_limit ) ) {

			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				$announcement_type_list = HRP_Helper::announcement_type_list();
				foreach ( $announcement_type_list as $key => $value ) {
					if ( $row->send_to == $key ) {
						$announcement_type = $value; }
				}
				$send_to_list = unserialize( $row->announced_to );

				$names_list = array();
				if ( $row->send_to === 'by_employee' ) {
					foreach ( $send_to_list as $value ) {
						$employee  = HRP_Helper::get_employee( $value );
						$user_info = get_userdata( $employee->user_id );
						$name      = $user_info->name;
						array_push( $names_list, $name );
					}
					$names = rtrim( implode( ', ', $names_list ), ',' );
				} elseif ( $row->send_to === 'by_department' ) {
					foreach ( $send_to_list as $value ) {
						$department = HRP_Helper::get_department( $value );
						$name       = $department->title;
						array_push( $names_list, $name );
					}
					$names = rtrim( implode( ', ', $names_list ), ',' );
				} elseif ( $row->send_to === 'by_designation' ) {
					foreach ( $send_to_list as $value ) {
						$designation = HRP_Helper::get_designation( $value );
						$name        = $designation->title;
						array_push( $names_list, $name );
					}
					$names = rtrim( implode( ', ', $names_list ), ',' );
				} else {
					$names = 'All Employee';
				}

				$data[] = array(
					'<div class="custom-control custom-control-sm custom-checkbox notext">
						<input type="checkbox" class="custom-control-input bulk-select" id="' . $row->ID . '">
						<label class="custom-control-label" for="' . $row->ID . '"></label>
					</div>',
					'<a href="' . esc_url( $announcements_page_url . '&action=save&id=' . $row->ID ) . '">' . esc_html( ucwords( $row->title ) ) . '</a>',
					esc_html( wp_trim_words( $row->announcement, 12 ) ),
					esc_html( ucwords( $announcement_type ) ),
					esc_html( HRP_Helper::display_date( $row->created_at ) ),
					'<span class="badge badge-dim bg-outline-primary ">' . esc_html( ( $names ) ) . '</span>',
					'<ul class="nk-tb-actions gx-1">
						<li>
							<div class="dropdown">
								<a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
								<div class="dropdown-menu dropdown-menu-end">
									<ul class="link-list-opt no-bdr">
										<li><a href="' . esc_url( $announcements_page_url . '&action=save&id=' . $row->ID ) . '"><em class="icon ni ni-edit"></em><span>'. esc_html__( 'Edit', 'hrp' ).'</span></a></li>
										<li><a href="#" data-id="' . $row->ID . '" data-nonce="' . esc_attr( wp_create_nonce( 'delete-announcement' ) ) . '" class="delete-announcement" ><em class="icon ni ni-trash-empty"></em></em><span>'. esc_html__( 'Delete', 'hrp' ).'</span></a></li>
									</ul>
								</div>
							</div>
						</li>
					</ul>',
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);
		echo json_encode( $output );
		die;
	}

	public static function delete_announcement() {
		try {
			ob_start();
			global $wpdb;

			$announcement_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'delete-announcement' ) ) {
				die();
			}

			// if announcement exists.
			$announcement = HRP_Helper::get_announcement( $announcement_id );
			if ( ! $announcement ) {
				throw new Exception( esc_html__( 'Announcement not found.', 'hrp' ) );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		try {
			$wpdb->query( 'BEGIN;' );

			$success = $wpdb->delete( ANNOUNCEMENTS, array( 'ID' => $announcement_id ) );
			$message = esc_html__( 'Announcement deleted successfully.', 'hrp' );

			$exception = ob_get_clean();
			if ( ! empty( $exception ) ) {
				throw new Exception( $exception );
			}

			if ( false === $success ) {
				throw new Exception( $wpdb->last_error );
			}

			$wpdb->query( 'COMMIT;' );

			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $exception ) {
			$wpdb->query( 'ROLLBACK;' );
			wp_send_json_error( $exception->getMessage() );
		}
	}

	public static function save_company_details() {
		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'save-company-details' ) ) {
				die();
			}
			$company_name       = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
			$company_email      = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
			$company_address    = isset( $_POST['address'] ) ? sanitize_text_field( $_POST['address'] ) : '';
			$employee_id_prefix = isset( $_POST['employee_id_prefix'] ) ? sanitize_text_field( $_POST['employee_id_prefix'] ) : '';
			$company_logo       = isset( $_POST['logo'] ) ? sanitize_text_field( $_POST['logo'] ) : '';

			// validation.
			$errors = array();

			if ( ! $company_name ) {
				$errors['name'] = esc_html__( 'Please provide company name.', 'hrp' );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				$general = $wpdb->get_row( $wpdb->prepare( 'SELECT ID, setting_value FROM ' . SETTINGS . ' WHERE setting_key = %s', 'company_detail' ) );

				$data = array(
					'company_name'       => $company_name,
					'company_email'      => $company_email,
					'company_address'    => $company_address,
					'company_logo'       => $company_logo,
					'employee_id_prefix' => $employee_id_prefix,
				);

				if ( ! $general ) {
					$wpdb->insert(
						SETTINGS,
						array(
							'setting_key'   => 'company_detail',
							'setting_value' => serialize( $data ),
						)
					);
				} else {
					$wpdb->update(
						SETTINGS,
						array( 'setting_value' => serialize( $data ) ),
						array( 'ID' => $general->ID )
					);
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				$wpdb->query( 'COMMIT;' );
				$message = esc_html__( 'General Settings Saved.', 'hrp' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );

	}

	public static function save_notification_settings() {
		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['save-notification-settings'], 'save-notification-settings' ) ) {
				die();
			}
			$email_enable      = isset( $_POST['email_enable'] ) ? sanitize_text_field( $_POST['email_enable'] ) : '';
			$email_send_method = isset( $_POST['email_send_method'] ) ? sanitize_text_field( $_POST['email_send_method'] ) : '';
			$sender_name       = isset( $_POST['sender_name'] ) ? sanitize_text_field( $_POST['sender_name'] ) : '';
			$sender_address    = isset( $_POST['sender_address'] ) ? sanitize_text_field( $_POST['sender_address'] ) : '';
			$smtp_host         = isset( $_POST['smtp_host'] ) ? sanitize_text_field( $_POST['smtp_host'] ) : '';
			$smtp_username     = isset( $_POST['smtp_username'] ) ? sanitize_text_field( $_POST['smtp_username'] ) : '';
			$smtp_password     = isset( $_POST['smtp_password'] ) ? sanitize_text_field( $_POST['smtp_password'] ) : '';
			$smtp_encryption   = isset( $_POST['smtp_encryption'] ) ? sanitize_text_field( $_POST['smtp_encryption'] ) : '';
			$smtp_port         = isset( $_POST['smtp_port'] ) ? sanitize_text_field( $_POST['smtp_port'] ) : '';

			// validation.
			$errors = array();

			if ( ! $email_send_method ) {
				$errors['email_send_method'] = esc_html__( 'Please provide email send type.', 'hrp' );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				$general = $wpdb->get_row( $wpdb->prepare( 'SELECT ID, setting_value FROM ' . SETTINGS . ' WHERE setting_key = %s ', 'notification_settings' ) );

				$data = array(
					'email_enable'      => $email_enable,
					'email_send_method' => $email_send_method,
					'sender_name'       => $sender_name,
					'sender_address'    => $sender_address,
					'smtp_host'         => $smtp_host,
					'smtp_username'     => $smtp_username,
					'smtp_password'     => $smtp_password,
					'smtp_encryption'   => $smtp_encryption,
					'smtp_port'         => $smtp_port,
				);

				if ( ! $general ) {
					$wpdb->insert(
						SETTINGS,
						array(
							'setting_key'   => 'notification_settings',
							'setting_value' => serialize( $data ),
						)
					);
				} else {
					$wpdb->update(
						SETTINGS,
						array( 'setting_value' => serialize( $data ) ),
						array( 'ID' => $general->ID )
					);
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				$wpdb->query( 'COMMIT;' );
				$message = esc_html__( 'Notification Settings Saved.', 'hrp' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );

	}

	public static function save_attendance_settings() {
		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['save-attendance-settings'], 'save-attendance-settings' ) ) {
				die();
			}
			$grace_before_checkin  = ! empty( $_POST['grace_before_checkin'] ) ? sanitize_text_field( $_POST['grace_before_checkin'] ) : '';
			$attendance_threshold  = ! empty( $_POST['attendance_threshold'] ) ? sanitize_text_field( $_POST['attendance_threshold'] ) : '';
			$grace_before_checkout = ! empty( $_POST['grace_before_checkout'] ) ? sanitize_text_field( $_POST['grace_before_checkout'] ) : '';

			// validation.
			$errors = array();
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				$general = $wpdb->get_row( $wpdb->prepare( 'SELECT ID, setting_value FROM ' . SETTINGS . ' WHERE setting_key = %s', 'attendance_settings' ) );

				$data = array(
					'grace_before_checkin'  => $grace_before_checkin,
					'attendance_threshold'  => $attendance_threshold,
					'grace_before_checkout' => $grace_before_checkout,
				);

				if ( ! $general ) {
					$wpdb->insert(
						SETTINGS,
						array(
							'setting_key'   => 'attendance_settings',
							'setting_value' => serialize( $data ),
						)
					);
				} else {
					$wpdb->update(
						SETTINGS,
						array( 'setting_value' => serialize( $data ) ),
						array( 'ID' => $general->ID )
					);
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				$wpdb->query( 'COMMIT;' );
				$message = esc_html__( 'Attendance Settings Saved.', 'hrp' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );

	}

	public static function save_email_template_settings() {
		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'save-email-template-settings' ) ) {
				die();
			}
			$registration_enable  = ! empty( $_POST['registration_email_enable'] ) ? sanitize_text_field( $_POST['registration_email_enable'] ) : 0;
			$registration_message = ! empty( $_POST['registration_email_message'] ) ? wp_kses_post( stripslashes( $_POST['registration_email_message'] ) ) : '';
			$holiday_email_days   = ! empty( $_POST['holiday_email_days'] ) ? wp_kses_post( stripslashes( $_POST['holiday_email_days'] ) ) : '';

			// validation.
			$errors = array();
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );
				$general = $wpdb->get_row( $wpdb->prepare( 'SELECT ID, setting_value FROM ' . SETTINGS . ' WHERE setting_key = %s', 'email_template_setting' ) );

				$data = array(
					'registration_enable'  => $registration_enable,
					'registration_message' => $registration_message,
					'holiday_email_days'   => $holiday_email_days,
				);

				if ( ! $general ) {
					$wpdb->insert(
						SETTINGS,
						array(
							'setting_key'   => 'email_template_setting',
							'setting_value' => serialize( $data ),
						)
					);
				} else {
					$wpdb->update(
						SETTINGS,
						array( 'setting_value' => serialize( $data ) ),
						array( 'ID' => $general->ID )
					);
				}

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				$wpdb->query( 'COMMIT;' );
				$message = esc_html__( 'Notification email template settings saved.', 'hrp' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );

	}

	public static function employee_checkin() {
		try {
			ob_start();
			global $wpdb;
			$employee_id = isset( $_POST['employee_id'] ) ? absint( $_POST['employee_id'] ) : 0;
			if ( ! wp_verify_nonce( $_POST['nonce'], 'save-checkin' ) ) {
				die();
			}

			$employee_id = ! empty( $_POST['employee_id'] ) ? sanitize_text_field( $_POST['employee_id'] ) : '';
			$ip_address  = ! empty( $_POST['ip_address'] ) ? sanitize_text_field( $_POST['ip_address'] ) : '';
			$date        = date( 'Y-m-d' );
			$checkin     = current_time( 'U' );

			// Validate input data.
			$errors = array();

			$settings = HRP_Helper::get_attendance_settings();
			// check if ip restriction is Enable
			$ip_restriction = $settings['ip_restriction'];

			// Get whitelisted_ip addresses
			if ( $ip_restriction === 'on' ) {
				$whitelist_ip_address = preg_replace( '/\s+/', '', $settings['whitelisted_ip'] );
				$whitelist_ip_address = explode( ',', $whitelist_ip_address );

				// Check if ip_address exists in array
				if ( in_array( $ip_address, $whitelist_ip_address ) ) {
				} else {
					throw new Exception( esc_html__( 'Your IP ADDRESS in not whitelisted. ', 'hrp' ) );
				}
			}
			
			// Checks if attendance already exists with Date.
			$attendance_exist = hrp_Helper::get_attendance_by_date( $date, $employee_id );
			if ( $attendance_exist && ! $employee_id ) {
				throw new Exception( esc_html__( 'Attendance already exists with the date "' . $date . '"', 'hrp' ) );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// insert table.
				$data = array(
					'date'        => $date,
					'employee_id' => $employee_id,
					'checkin'     => date( 'H:i:s', ( $checkin ) ),
					'ip_address'  => $ip_address,
					'status'      => 1,
				);
				// insert.
					$success = $wpdb->insert( ATTENDANCES, $data );
					$message = esc_html__( 'Attendance Successfully Added.', 'hrp' );

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );

	}

	public static function employee_checkout() {
		try {
			ob_start();
			global $wpdb;
			$employee_id = isset( $_POST['employee_id'] ) ? absint( $_POST['employee_id'] ) : 0;
			if ( ! wp_verify_nonce( $_POST['nonce'], 'save-checkout' ) ) {
				die();
			}

			$employee_id = ! empty( $_POST['employee_id'] ) ? sanitize_text_field( $_POST['employee_id'] ) : '';
			$date        = date( 'Y-m-d' );
			$checkout    = current_time( 'U' );

			// Validate input data.
			$errors = array();

			// Checks if attendance already exists with Date.
			$attendance = hrp_Helper::get_attendance_by_date( $date, $employee_id );

			$checkin  = $attendance->checkin;
			$checkout = date( 'H:i:s', ( $checkout ) );

			$time_diff            = strtotime( $checkout ) - strtotime( $checkin );
			$time_diff            = $time_diff / 60;
			$settings             = HRP_Helper::get_attendance_settings();
			$attendance_threshold = $settings['attendance_threshold'];

			if ( $time_diff <= $attendance_threshold ) {
				throw new Exception( esc_html__( 'Can not checkout too early.', 'hrp' ) );
			}

			$attendance_id = $attendance->ID;
			if ( $attendance->checkout ) {
				throw new Exception( esc_html__( 'Already checked out', 'hrp' ) );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// update table.
				$data = array(
					'checkout' => $checkout,
				);
				// update.
					$success = $wpdb->update( ATTENDANCES, $data, array( 'ID' => $attendance_id ) );
					$message = esc_html__( 'Checkout Successfully Added.', 'hrp' );

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function employee_breakin() {
		try {
			ob_start();
			global $wpdb;
			$employee_id = isset( $_POST['employee_id'] ) ? absint( $_POST['employee_id'] ) : 0;
			if ( ! wp_verify_nonce( $_POST['nonce'], 'save-breakin' ) ) {
				die();
			}

			$employee_id = ! empty( $_POST['employee_id'] ) ? sanitize_text_field( $_POST['employee_id'] ) : '';
			$date        = date( 'Y-m-d' );
			$breakin     = current_time( 'U' );

			// Validate input data.
			$errors = array();

			// Checks if attendance already exists with Date.
			$attendance    = hrp_Helper::get_attendance_by_date( $date, $employee_id );
			$attendance_id = $attendance->ID;
			if ( $attendance->breakin ) {
				throw new Exception( esc_html__( 'Already checked out', 'hrp' ) );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// update table.
				$data = array(
					'breakin' => date( 'H:i:s', ( $breakin ) ),
				);
				// update.
					$success = $wpdb->update( ATTENDANCES, $data, array( 'ID' => $attendance_id ) );
					$message = esc_html__( 'Break-in Successfully Added.', 'hrp' );

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function employee_breakout() {
		try {
			ob_start();
			global $wpdb;
			$employee_id = isset( $_POST['employee_id'] ) ? absint( $_POST['employee_id'] ) : 0;
			if ( ! wp_verify_nonce( $_POST['nonce'], 'save-breakout' ) ) {
				die();
			}

			$employee_id = ! empty( $_POST['employee_id'] ) ? sanitize_text_field( $_POST['employee_id'] ) : '';
			$date        = date( 'Y-m-d' );
			$breakout    = current_time( 'U' );

			// Validate input data.
			$errors = array();

			// Checks if attendance already exists with Date.
			$attendance    = hrp_Helper::get_attendance_by_date( $date, $employee_id );
			$attendance_id = $attendance->ID;
			if ( $attendance->breakout ) {
				throw new Exception( esc_html__( 'Already checked out', 'hrp' ) );
			}
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}

		if ( count( $errors ) < 1 ) {
			try {
				$wpdb->query( 'BEGIN;' );

				// update table.
				$data = array(
					'breakout' => date( 'H:i:s', ( $breakout ) ),
				);
				// update.
					$success = $wpdb->update( ATTENDANCES, $data, array( 'ID' => $attendance_id ) );
					$message = esc_html__( 'Break-out Successfully Added.', 'hrp' );

				$buffer = ob_get_clean();
				if ( ! empty( $buffer ) ) {
					throw new Exception( $buffer );
				}

				if ( false === $success ) {
					throw new Exception( $wpdb->last_error );
				}

				$wpdb->query( 'COMMIT;' );
				wp_send_json_success( array( 'message' => $message ) );

			} catch ( Exception $exception ) {
				$wpdb->query( 'ROLLBACK;' );
				wp_send_json_error( $exception->getMessage() );
			}
		}
		wp_send_json_error( $errors );
	}

	public static function emp_fetch_announcements() {
		global $wpdb;
		$query        = HRP_Helper::get_announcements();
		$query_filter = $query;

		// Grouping.
		$group_by      = ' ' . 'GROUP BY an.ID';
		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition    .= '' . '(an.title LIKE "%' . $search_value . '%")';
				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'an.title' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY an.ID DESC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = HRP_Helper::get_announcements_row_count();
		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' WHERE (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );
		$data              = array();
		if ( count( $filter_rows_limit ) ) {

			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				$send_to_list = unserialize( $row->announced_to );
				$names_list   = array();
				if ( $row->send_to === 'by_employee' ) {
					foreach ( $send_to_list as $value ) {
						$employee  = HRP_Helper::get_employee( $value );
						$user_info = get_userdata( $employee->user_id );
						$name      = $user_info->first_name;
						array_push( $names_list, $name );
					}
					$names = rtrim( implode( ', ', $names_list ), ',' );
				} elseif ( $row->send_to === 'by_department' ) {
					foreach ( $send_to_list as $value ) {
						$department = HRP_Helper::get_department( $value );
						$name       = $department->title;
						array_push( $names_list, $name );
					}
					$names = rtrim( implode( ', ', $names_list ), ',' );
				} elseif ( $row->send_to === 'by_designation' ) {
					foreach ( $send_to_list as $value ) {
						$designation = HRP_Helper::get_designation( $value );
						$name        = $designation->title;
						array_push( $names_list, $name );
					}
					$names = rtrim( implode( ', ', $names_list ), ',' );
				} else {
					$names = esc_html( 'All Employee', 'hrp' );
				}

				$data[] = array(
					esc_html( ucwords( $row->title ) ),
					esc_html( wp_trim_words( $row->announcement, 12 ) ),
					'<span class="badge badge-dim bg-outline-primary ">' . esc_html( ( $names ) ) . '</span>',
					esc_html( HRP_Helper::display_date( $row->created_at ) ),
					'<ul class="nk-tb-actions gx-1">
						<ul class="pe-2">
							<li><a href="#" data-bs-toggle="modal" data-bs-target="#announce' . $row->ID . '"><em class="icon ni ni-eye"></em></a></li>
						</ul>
					</ul>

					<div class="modal fade" tabindex="-1" id="announce' . $row->ID . '">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">' . esc_html( ucwords( $row->title ) ) . '</h5>
									<a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
										<em class="icon ni ni-cross"></em>
									</a>
								</div>
								<div class="modal-body">
									<p>' . esc_html( $row->announcement ) . '</p>
								</div>
							</div>
						</div>
					</div>
					',
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);
		echo json_encode( $output );
		die;
	}
	
	public static function fetch_reports() {

		global $wpdb;
		$query = HRP_Helper::get_employees_attendance( );
		$query_filter = $query;
		$group_by      = ' ' . 'GROUP BY emp.ID';
		$query        .= $group_by;
		$query_filter .= $group_by;

		// Searching.
		$condition = '';
		if ( isset( $_POST['search']['value'] ) ) {
			$search_value = sanitize_text_field( $_POST['search']['value'] );
			if ( '' !== $search_value ) {
				$condition    .= '' . '(emp.name LIKE "%' . $search_value . '%")';
				$query_filter .= ( ' HAVING ' . $condition );
			}
		}

		// Ordering.
		$columns = array( 'emp.name' );
		if ( isset( $_POST['order'] ) && isset( $columns[ $_POST['order']['0']['column'] ] ) ) {
			$order_by  = sanitize_text_field( $columns[ $_POST['order']['0']['column'] ] );
			$order_dir = sanitize_text_field( $_POST['order']['0']['dir'] );

			$query_filter .= ' ORDER BY ' . $order_by . ' ' . $order_dir;
		} else {
			$query_filter .= ' ORDER BY emp.ID ASC';
		}

		// Limiting.
		$limit = '';
		if ( -1 != $_POST['length'] ) {
			$start  = absint( $_POST['start'] );
			$length = absint( $_POST['length'] );

			$limit = ' LIMIT ' . $start . ', ' . $length;
		}

		// Total query.
		$rows_query = HRP_Helper::get_employees_row_count();
		// Total rows count.
		$total_rows_count = $wpdb->get_var( $rows_query );

		// Filtered rows count.
		if ( $condition ) {
			$filter_rows_count = $wpdb->get_var( $rows_query . ' WHERE (' . $condition . ')' );
		} else {
			$filter_rows_count = $total_rows_count;
		}

		// Filtered limit rows.
		$filter_rows_limit = $wpdb->get_results( $query_filter . $limit );

		// Filtered limit rows.
		$attendances_page_url = HRP_Helper::get_page_url( ATTENDANCES );

		$data                 = array();
		if ( count( $filter_rows_limit ) ) {
			foreach ( $filter_rows_limit as $row ) {
				// Table columns.
				if ( $row->ID ) {
					$action = '<ul class="nk-tb-actions gx-1">
								<li>
									<a href="' . esc_url( $attendances_page_url . '&action=save&id=' . $row->ID ) . '"><em class="icon ni ni-link-alt"></em></a>
								</li>
							</ul>';
				} else {
					$action = '';
				}

				if ( ! empty( $row->checkin ) && ! empty( $row->checkout ) ) {
					$hours = HRP_Helper::seconds_to_hour_minute( abs( strtotime( $row->checkin ) - strtotime( $row->checkout ) ) );
				} else {
					$hours = '--:--';
				}

				$data[] = array(
					'<a href="' . esc_url( $attendances_page_url . '&action=view&id=' . $row->ID ) . '"><span>' . esc_html( HRP_Helper::employee_id_prefix( $row->employee_id ) ) . '</span></a>',
					esc_html( $row->name ),
					HRP_Helper::time_format( $row->checkin ),
					HRP_Helper::time_format( $row->checkout ),
					$hours,
					HRP_Helper::time_format( $row->breakin ),
					HRP_Helper::time_format( $row->breakout ),
					'<span class="badge badge-dim bg-outline-primary">' . esc_html( $row->ip_address ) . '</span>',
					'',
					$action,
				);
			}
		}

		$output = array(
			'draw'            => intval( $_POST['draw'] ),
			'recordsTotal'    => $total_rows_count,
			'recordsFiltered' => $filter_rows_count,
			'data'            => $data,
		);
		echo json_encode( $output );
		die;
	}

	public static function send_test_email() {
		try {
			ob_start();
			global $wpdb;

			if ( ! wp_verify_nonce( $_POST['nonce'], 'send-test-email' ) ) {
				die();
			}

			$template_name = ! empty( $_POST['template'] ) ? sanitize_text_field( $_POST['template'] ) : '';
			$email_to      = ! empty( $_POST['to'] ) ? sanitize_text_field( $_POST['to'] ) : '';

			if ( empty( $email_to ) ) {
				wp_send_json_error( esc_html__( 'Please provide an email.', 'school-management' ) );
			}

			if ( ! filter_var( $email_to, FILTER_VALIDATE_EMAIL ) ) {
				wp_send_json_error( esc_html__( 'Please provide a valid email.', 'school-management' ) );
			}

			$settings = HRP_Helper::get_email_template_settings();
			$subject  = 'Test Email';
			$body     = $settings[ $template_name ];

			$sent = HRP_Helper::send_email( $email_to, $subject, $body );

			if ( $sent ) {
				wp_send_json_success( array( 'message' => 'Email sent.' ) );
			}

			wp_send_json_error( esc_html__( 'Email was not sent.', 'school-management' ) );
		} catch ( Exception $exception ) {
			$buffer = ob_get_clean();
			if ( ! empty( $buffer ) ) {
				$response = $buffer;
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error( $response );
		}
	}
}
