<?php
/*
Plugin Name: ClearSale BR
Author: Marcelo Melo
Author URI: mailto:marcelo@webtask.com.br
Description: Integração com clearsale
Version: 1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

include_once plugin_dir_path( __FILE__ ) . '/includes/wc-clearsale-class.php';
include_once plugin_dir_path( __FILE__ ) . '/includes/wc-settings-menu.php';
