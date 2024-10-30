<?php
defined( 'ABSPATH' ) || die();

$page_action = '';
if ( isset( $_GET['action'] ) && ! empty( $_GET['action'] ) ) {
	$page_action = sanitize_text_field( wp_unslash( $_GET['action'] ) );
}

if ( 'save' === $page_action ) {
	require_once HRP_PLUGIN_DIR . 'admin/inc/holidays/save.php';
} else {
	require_once HRP_PLUGIN_DIR . 'admin/inc/holidays/holidays.php';
}
