<?php
defined( 'ABSPATH' ) || exit;
$department_list  = HRP_Helper::get_departments_list();
$designation_list = HRP_Helper::get_designations_list();
?>
<div class="card-inner">
	<div class="nk-block-head nk-block-head-md">
		<div class="nk-block-between">

			<div class="nk-block-head-content">
				<h4 class="nk-block-title"><?php esc_html_e( 'Announcements', 'hrp' ); ?></h4>
			</div>
		</div>
	</div>
	<table class="nk-tb-list nk-tb-ulist is-compact" id="emp-announcements">
		<thead>
			<tr class="nk-tb-item nk-tb-head">
				<th class="nk-tb-col"><span class="sub-text"><?php esc_html_e( 'Title', 'hrp' ); ?></span></th>
				<th class="nk-tb-col"><span class="sub-text"><?php esc_html_e( 'Announcement', 'hrp' ); ?></span></th>
				<th class="nk-tb-col "><span class="sub-text"><?php esc_html_e( 'Sent To', 'hrp' ); ?></span></th>
				<th class="nk-tb-col "><span class="sub-text"><?php esc_html_e( 'Created At', 'hrp' ); ?></span></th>
				<th class="nk-tb-col nk-tb-col-tools">
					<ul class="nk-tb-actions gx-1 my-n1">
						<a href="#" class="dropdown-toggle btn btn-icon btn-trigger"><em class="icon ni ni-filter-alt"></em></a>
					</ul>
				</th>
			</tr>
		</thead>
	</table>
</div>
