<?php
defined( 'ABSPATH' ) || die();

$page_action = '';
if ( isset( $_GET['action'] ) && ! empty( $_GET['action'] ) ) {
	$page_action = sanitize_text_field( wp_unslash( $_GET['action'] ) );
}

if ( 'reports' === $page_action ) {
	require_once HRP_PLUGIN_DIR . 'admin/inc/dashboard/reports.php';
} else {
	require_once HRP_PLUGIN_DIR . 'admin/inc/dashboard/dashboard.php';
}
