<?php

// http://docs.woothemes.com/wc-apidocs/class-WC_Cart.html
class Booked_WC_Cart {

	// doesn't work on INIT, => use template_redirect
	public static function add_appointment( $app_id=null ) {
		$app_id = intval($app_id);

		$appointment = Booked_WC_Appointment::get($app_id);
		if ( !$appointment->products ) {
			$message = sprintf(__('Appointment with ID %1$d does not have any products assigned to it.', 'booked-woocommerce-payments'), $post_id);
			throw new Exception($message);
		}

		$cart = WC()->cart;
		if ( !method_exists($cart, 'add_to_cart') ) {
			return;
		}

		$cart_appointments = self::get_cart_appointments();

		foreach ($appointment->products as $product) {
			$product_id = intval($product->product_id);

			if ( !isset($product->variation_id) || !intval($product->variation_id) ) {
				$variation_id = false;
			} else {
				$variation_id = intval($product->variation_id);
			}

			$check_key = "{$app_id}::{$product_id}::" . intval($variation_id);
			// check if the product is added to the cart and if it isn't, add it
			// we need that in case a specific appointment has more than one product and one of the is removed from the cart by accident
			if ( in_array($check_key, $cart_appointments['extended']) ) {
				continue;
			}

			$additional_item_data = array(
				BOOKED_WC_PLUGIN_PREFIX . 'appointment_id' => $app_id
			);

			// add calendar name as part of the item data
			if ( !empty($appointment->calendar->calendar_obj) ) {
				$additional_item_data[BOOKED_WC_PLUGIN_PREFIX . 'appointment_cal_name'] = $appointment->calendar->calendar_obj->name;
			}
			// <---

			// add calendar assignee
			if ( !empty($appointment->calendar->calendar_obj) ) {
				$term_meta = get_option( "taxonomy_{$appointment->calendar->calendar_obj->term_id}" );
				$assignee_email = $term_meta['notifications_user_id'];

				if ( $assignee_email && ($usr=get_user_by('email', $assignee_email)) ) {
					$additional_item_data[BOOKED_WC_PLUGIN_PREFIX . 'appointment_assignee_name'] = $usr->display_name;
				}
			}
			// <---

			// add timerange name as part of the item data
			$additional_item_data[BOOKED_WC_PLUGIN_PREFIX . 'appointment_timerange'] = $appointment->timeslot_text;
			// <---

			// add the custom field information as part of the item data
			$i = 0;
			$separator = '<!--sep-->';
			$meta_key = BOOKED_WC_PLUGIN_PREFIX . 'custom_field';
			$custom_fields = (array) $appointment->custom_fields;
			foreach ($custom_fields as $field_label => $field_value) {
				$key = $meta_key . strval($i);
				$value = $field_label . ': ' . $separator . $field_value;

				$additional_item_data[$key] = $value;

				$i++;
			}
			// <---

			$quantity = 1;

			$variation_attributes = array();

			$cart->add_to_cart(
				$product_id,
				$quantity,
				$variation_id,
				$variation_attributes,
				$additional_item_data
			);
		}

		return true;
	}

	public static function empty_cart( $clear_persistent_cart=true ) {
		// empty current cart session
		$cart = WC()->cart;

		if ( method_exists($cart, 'empty_cart') ) {
			$cart->empty_cart($clear_persistent_cart);
		}
	}

	public static function get_cart_appointments() {
		$cart_items = WC()->cart->get_cart();
		$cart_apps = array(
			'extended' => array(), // app_id::product_id::variation_id
			'ids' => array()
		);

		$app_id_key = BOOKED_WC_PLUGIN_PREFIX . 'appointment_id';

		foreach ($cart_items as $cart_item) {
			if ( !isset($cart_item[$app_id_key]) ) {
				continue;
			}

			$app_id = $cart_item[$app_id_key];
			$product_id = intval($cart_item['product_id']);
			$variation_id = intval($cart_item['variation_id']);

			$cart_apps['ids'][] = $app_id;
			$cart_apps['extended'][] = "{$app_id}::{$product_id}::{$variation_id}"; // app_id::product_id::variation_id
		}

		return $cart_apps;
	}
}

class Booked_WC_Cart_Hooks {

	// change the product title on the cart page if it is assigned to a appointment
	public static function woocommerce_cart_item_name($product_title, $cart_item, $cart_item_key) {
		
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
				//$product_title .= '<div class="booked-wc-checkout-section"><small><b>' . __('Calendar', 'booked-woocommerce-payments') . ':</b><br/>' . $appointment->calendar->calendar_obj->name . '</small></div>';

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
                /*
		if ( !is_admin() ) {
			$product_title .= '<b>' . __('Quantity', 'booked-woocommerce-payments') . ':</b>';
		}*/

		return $product_title;
	}

	// removed the missing appointments from the cart
	public static function woocommerce_remore_missing_appointment_products() {

		$cart = WC()->cart;
		if ( !method_exists($cart, 'remove_cart_item') || !method_exists($cart, 'get_cart') ) {
			return;
		}

		$cart_items = $cart->get_cart();

		$app_id_key = BOOKED_WC_PLUGIN_PREFIX . 'appointment_id';

		foreach ($cart_items as $cart_item_key => $cart_item) {
			if ( !isset($cart_item[$app_id_key]) ) {
				continue;
			}

			$app_id = $cart_item[$app_id_key];

			if ( get_post($app_id) ) {
				continue;
			}

			$cart->remove_cart_item($cart_item_key);
		}
	}
}
