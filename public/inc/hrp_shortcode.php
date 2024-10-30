<?php
defined( 'ABSPATH' ) || die();

class HRP_Shortcode {

	public static function load_translation() {
		load_plugin_textdomain( 'hrp', false, basename( HRP_PLUGIN_DIR ) . '/languages' );
	}

}
