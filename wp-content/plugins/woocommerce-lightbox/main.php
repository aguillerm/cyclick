<?php
/**
 * Plugin Name:       Woocommerce LightBox
 * Plugin URI:        http://wpbean.com/plugins/
 * Description:       Highly customizable product quick view lightbox plugin for Woocommerce Store. 
 * Version:           1.05
 * Author:            wpbean
 * Author URI:        http://wpbean.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-lightbox
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * Localization
 */

function wpb_wl_textdomain() {
	load_plugin_textdomain( 'woocommerce-lightbox', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'wpb_wl_textdomain' );


/**
 * Include JS Files 
 */

function wpb_wl_adding_scripts() {
	if (!is_admin()) {
		wp_register_script('wpb-wl-magnific-popup', plugins_url('assets/js/jquery.magnific-popup.min.js', __FILE__),array('jquery'),'1.0', false);
		wp_register_script('wpb-wl-plugin-main', plugins_url('assets/js/main.js', __FILE__),array('jquery'),'1.0', true);
		wp_enqueue_script('wpb-wl-magnific-popup');
		wp_enqueue_script('wpb-wl-plugin-main');
	}
}
add_action( 'wp_enqueue_scripts', 'wpb_wl_adding_scripts' ); 


/**
 * Include css files
 */

function wpb_wl_adding_style() {
	wp_register_style('wpb-wl-magnific-popup', plugins_url('/assets/css/magnific-popup.css', __FILE__),'','1.0');
	wp_register_style('wpb-wl-main', plugins_url('/assets/css/main.css', __FILE__),'','1.0');
	wp_enqueue_style('wpb_wl_google_fonts', '//fonts.googleapis.com/css?family=Open+Sans:400,600', false);
	wp_enqueue_style('wpb-wl-magnific-popup');
	wp_enqueue_style('wpb-wl-main');
}
add_action( 'wp_enqueue_scripts', 'wpb_wl_adding_style', 11 );



/* Requred files */

require_once dirname( __FILE__ ) . '/inc/wpb_wl_hooks.php'; // Woocommerce action & filter hooks