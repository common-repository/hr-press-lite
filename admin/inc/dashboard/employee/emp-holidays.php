<?php defined( 'ABSPATH' ) || exit;
$holidays = HRP_Helper::get_current_year_holidays();
?>
<div class="card-inner">
	<div class="nk-block-head nk-block-head-sm">
		<div class="nk-block-between">
			<div class="nk-block-head-content">
				<h4 class="nk-block-title"><?php esc_html_e( 'Holidays', 'hrp' ); ?></h4>
			</div>
		</div>
	</div>
	<div class="nk-block">
		<table class="table table-ulogs">
			<thead class="table-light">
				<tr>
					<th class="tb-col-os"><span class="overline-title"><?php esc_html_e( 'Holidays Title ', 'hrp' ); ?><span class="d-sm-none">/ <?php esc_html_e( 'Days', 'hrp' ); ?></span></span></th>
					<th class="tb-col-ip"><span class="overline-title"><?php esc_html_e( 'Days', 'hrp' ); ?></span></th>
					<th class="tb-col-time"><span class="overline-title"><?php esc_html_e( 'Start Date', 'hrp' ); ?></span></th>
					<th class="tb-col-time"><span class="overline-title"><?php esc_html_e( 'End Date', 'hrp' ); ?></span></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $holidays as $holiday ) : ?>
					<tr>
						<td class="tb-col-os"><?php echo esc_html( $holiday->title ); ?></td>
						<td class="tb-col-ip"><span class="sub-text"><?php echo esc_html( HRP_Helper::days_diff( $holiday->start_date, $holiday->end_date ) ); ?></span></td>
						<td class="tb-col-time"><span class="sub-text"><?php echo esc_html( HRP_Helper::display_date( $holiday->start_date ) ); ?></span></td>
						<td class="tb-col-time"><span class="sub-text"><?php echo esc_html( HRP_Helper::display_date( $holiday->end_date ) ); ?></span></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
