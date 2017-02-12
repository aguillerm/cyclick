<?php
	
/*
Plugin Name: Booked Payments with WooCommerce
Plugin URI: http://getbooked.io/booked-woocommerce/
Description: Adds the ability to accept payments for appointments using WooCommerce.
Version: 1.3.1
Author: Boxy Studio
Author URI: https://boxystudio.com
Text Domain: booked-woocommerce-payments
*/

// Include the required class for plugin updates.
require_once('updates/plugin-update-checker.php');
$BookedWC_BoxyUpdateChecker = PucFactory::buildUpdateChecker('http://boxyupdates.com/get/?action=get_metadata&slug=booked-woocommerce-payments', __FILE__, 'booked-woocommerce-payments');

if ( class_exists('Booked_WC') ) {
	return;
}

// Global constants
define('BOOKED_WC_PLUGIN_PREFIX', 'booked_wc_');
define('BOOKED_WC_POST_TYPE', 'booked_appointments');
define('BOOKED_WC_TAX_CALENDAR', 'booked_custom_calendars');
define('BOOKED_WC_APPOINTMENTS_PAGE', 'booked-appointments');
define('BOOKED_WC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BOOKED_WC_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('BOOKED_WC_PLUGIN_AJAX_URL', admin_url('admin-ajax.php'));

// Plugin WooCommerce Libraries
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/woocommerce/class-wc-prevent-purchasing.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/woocommerce/class-wc-meta-box-product.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/woocommerce/class-wc-product.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/woocommerce/class-wc-variation.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/woocommerce/class-wc-order.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/woocommerce/class-wc-order-item.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/woocommerce/class-wc-cart.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/woocommerce/class-wc-helper.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/woocommerce/class-woocommerce.php');

// Default Plugin Libraries
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/class-settings.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/class-wp-cron.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/class-post-status.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/class-fragments.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/class-admin-notices.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/class-enqueue-scripts.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/class-wp-ajax.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/class-json-response.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/class-custom-fields.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/class-static-functions.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/class-appointment.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/class-appointment-payment-status.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/class-cleanup.php');
require_once(BOOKED_WC_PLUGIN_DIR . 'lib/core.php');

// setup the plugin
add_action('init',  array('Booked_WC', 'setup'));

// Localization
function bookedwc_local_init(){
	$locale = apply_filters('plugin_locale', get_locale(), 'booked-woocommerce-payments');
	load_textdomain('booked-woocommerce-payments', WP_LANG_DIR.'/plugins/booked-woocommerce-payments-'.$locale.'.mo');
	load_plugin_textdomain('booked-woocommerce-payments', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
}
add_action('after_setup_theme', 'bookedwc_local_init');
/*remove_filter('woocommerce_cart_item_name',array('Booked_WC_Cart_Hooks', 'woocommerce_cart_item_name'), 10);
add_filter('woocommerce_cart_item_name', 'my_woocommerce_cart_item_name',20,3);

function my_woocommerce_cart_item_name($product_title, $cart_item, $cart_item_key){
        $app_id_key = BOOKED_WC_PLUGIN_PREFIX . 'appointment_id';

        if ( !isset($cart_item[$app_id_key]) ) {
                return $product_title;
        }

        $appt_id = intval($cart_item[$app_id_key]);

        try {

                if (isset($cart_item['variation_id']) && $cart_item['variation_id']):
                        $variation = wc_get_product($cart_item['variation_id']);
                        $variation_text = '<br>'.$variation->get_formatted_variation_attributes();
                else:
                        $variation_text = '';
                endif;

                $appointment = Booked_WC_Appointment::get($appt_id);

                // remove product title link, so the visitors can't acess the product details page
                $product_title = preg_replace('~<a[^>]+>([^<]+)</a>~i', '$1', $product_title).$variation_text;

                $product_title .= '<div class="booked-wc-checkout-section"><em><small>' . $appointment->timeslot_text . '</small></em></div>';

                if ( !empty($appointment->calendar) && !empty($appointment->calendar->calendar_obj) ) {
                        $product_title .= '<div class="booked-wc-checkout-section"><small><b>' . __('Calendar', 'booked-woocommerce-payments') . ':</b><br/>' . $appointment->calendar->calendar_obj->name . '</small></div>';

                        // check for a booking agent
                        $term_meta = get_option( "taxonomy_{$appointment->calendar->calendar_obj->term_id}" );
                        $assignee_email = $term_meta['notifications_user_id'];
                        if ( $assignee_email && ($usr=get_user_by('email', $assignee_email)) ) {
                                $product_title .= '<div class="booked-wc-checkout-section"><small><b>' . __('Booking Agent', 'booked-woocommerce-payments') . ':</b><br/>' . $usr->display_name . '</small></div>';
                        }
                }

                $custom_fields = (array) $appointment->custom_fields;
                foreach ($custom_fields as $field_label => $field_value) {
                        $product_title .= '<div class="booked-wc-checkout-section"><small><b>' . $field_label . ':</b><br/>' . $field_value . '</small></div>';
                }
        } catch (Exception $e) {
                $text = __('The appointment no longer exists. Please double check your order.', 'booked-woocommerce-payments');
                $product_title .= '<div class="booked-wc-checkout-section"><em><small>' . $text . '</small></em></div>';
        }
        
        if ( !is_admin() ) {
                $product_title .= '<b>' . __('Quantity', 'booked-woocommerce-payments') . ':</b>';
        }

        return $product_title;
}*/