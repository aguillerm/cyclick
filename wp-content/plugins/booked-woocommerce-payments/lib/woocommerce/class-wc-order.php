<?php

class Booked_WC_Order {

	private static $orders = array();

	public $order;
	public $order_id;
	public $appointments = array();
	public $products;
	public $items = array();

	private function __construct( $order_id ) {
		$this->order_id = $order_id;
		$this->get_data();
		$this->get_items();
		$this->get_appointments();
		$this->get_status_text();
	}

	public static function get( $order_id=null ) {
		if ( !is_integer($order_id) ) {
			$message = sprintf(__('Booked_WC_Order::get($order_id) integer expected when %1$s given.', 'booked-woocommerce-payments'), gettype($order_id));
			throw new Exception($message);
		} else if ( $order_id===0 ) {
			self::$orders[$order_id] = false;
		} else if ( !isset(self::$orders[$order_id]) ) {
			self::$orders[$order_id] = new self($order_id);
		}

		return self::$orders[$order_id];
	}

	protected function get_data() {
		$order_id = absint($this->order_id);
		$this->order = new WC_Order($order_id);
		return $this;
	}

	protected function get_status_text() {
		$status = $this->order->post_status;
		$statuses = wc_get_order_statuses();

		$this->order->post_status_text = isset($statuses[$status]) ? $statuses[$status] : $status;

		return $this;
	}

	protected function get_items() {
		$this->items = $this->order->get_items();
		return $this;
	}

	protected function get_appointments() {
		$this->appointments = get_post_meta($this->order_id, '_' . BOOKED_WC_PLUGIN_PREFIX . 'order_appointments', true);
		return $this;
	}
}

class Booked_WC_Order_Hooks {

	// woocommerce_order_status_refunded
	// woocommerce_order_status_cancelled
	// delete appointments on refunded or cancelled
	public static function woocommerce_order_remove_appointment( $order_id ) {
		
		$order_id = (int) $order_id;
		
		$this_post = get_post($order_id);
		if (!$this_post || $this_post->post_type!=='shop_order') {
			return;
		}
		
		$order = Booked_WC_Order::get($order_id);

		$appointments = $order->appointments;
		if ( !$appointments ) {
			return;
		}

		$deleted = array();
		foreach ($appointments as $app_id) {
			if (!in_array($app_id, $deleted) && !get_post($app_id)) {
				return;
			}

			$deleted[] = $app_id;

			try {
				do_action('booked_appointment_cancelled',$app_id);
				wp_delete_post($app_id, true);
			} catch (Exception $e) {
				//
			}
		}
	}
	
	public static function woocommerce_order_complete( $order_id ) {
		
		$order_id = (int) $order_id;
		
		$this_post = get_post($order_id);
		if (!$this_post || $this_post->post_type!=='shop_order') {
			return;
		}
		
		$order = Booked_WC_Order::get($order_id);

		$appointments = $order->appointments;
		if ( !$appointments ) {
			return;
		}

		$completed = array();
		foreach ($appointments as $appt_id) {
			if (!in_array($appt_id, $completed) && !get_post($appt_id)) {
				return;
			}

			$completed[] = $appt_id;
			
			$title = get_post_meta($appt_id,'_appointment_title',true);
			$timeslot = get_post_meta($appt_id,'_appointment_timeslot',true);
			$timestamp = get_post_meta($appt_id,'_appointment_timestamp',true);
			$cf_meta_value = get_post_meta($appt_id,'_cf_meta_value',true);
			$timeslots = explode('-',$timeslot);
			$time_format = get_option('time_format');
			$date_format = get_option('date_format');
			$hide_end_times = get_option('booked_hide_end_times',false);
			
			$timestamp_start = strtotime(date_i18n('Y-m-d',$timestamp).' '.$timeslots[0]);
			$timestamp_end = strtotime(date_i18n('Y-m-d',$timestamp).' '.$timeslots[1]);
			$current_timestamp = current_time('timestamp');
			
			if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
				$timeslotText = __('All day','booked');
			else :
				$timeslotText = date_i18n($time_format,$timestamp_start).(!$hide_end_times ? '&ndash;'.date_i18n($time_format,$timestamp_end) : '');
			endif;
			
			$appt = get_post( $appt_id );
			$appt_author = $appt->post_author;
			$guest_name = get_post_meta($appt_id,'_appointment_guest_name',true);
			$guest_email = get_post_meta($appt_id,'_appointment_guest_email',true);
			
			if ($guest_name):
				$user_name = $guest_name;
				$email = $guest_email;
			else:
				$user_name = booked_get_name( $appt_author );
				$user_data = get_userdata( $appt_author );
				$email = $user_data->user_email;
			endif;
			
			$appointment_calendar_id = get_the_terms( $appt_id,'booked_custom_calendars' );
			if (!empty($appointment_calendar_id)):
				foreach($appointment_calendar_id as $calendar):
					$calendar_id = $calendar->term_id;
					break;
				endforeach;
			else:
				$calendar_id = false;
			endif;
					
			if (!empty($calendar_id)): $calendar_term = get_term_by('id',$calendar_id,'booked_custom_calendars'); $calendar_name = $calendar_term->name; else: $calendar_name = false; endif;
			
			$day_name = date('D',$timestamp);
			$timeslotText = apply_filters('booked_emailed_timeslot_text',$timeslotText,$timestamp_start,$timeslot,$calendar_id);
			
			// Add Booked WC confirmation email actions
			add_action('booked_wc_confirmation_email', 'booked_mailer', 10, 3);
			add_action('booked_wc_admin_confirmation_email', 'booked_mailer', 10, 3);
			
			// Send a confirmation email to the User?
			$email_content = get_option('booked_appt_confirmation_email_content');
			$email_subject = get_option('booked_appt_confirmation_email_subject');
			if ($email_content && $email_subject):
				$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%','%title%');
				$replacements = array($user_name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email,$title);
				$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
				$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
				$email_subject = str_replace($tokens,$replacements,$email_subject);
				do_action( 'booked_wc_confirmation_email', $email, $email_subject, $email_content );
			endif;
		
			// Send an email to the Admin?
			$email_content = get_option('booked_admin_appointment_email_content');
			$email_subject = get_option('booked_admin_appointment_email_subject');
			if ($email_content && $email_subject):
				$admin_email = booked_which_admin_to_send_email($calendar_id);
				$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%','%title%');
				$replacements = array($user_name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email,$title);
				$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
				$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
				$email_subject = str_replace($tokens,$replacements,$email_subject);
				do_action( 'booked_wc_admin_confirmation_email', $admin_email, $email_subject, $email_content );
			endif;
			
			// Remove Booked WC confirmation email actions
			remove_action('booked_wc_confirmation_email', 'booked_mailer', 10);
			remove_action('booked_wc_admin_confirmation_email', 'booked_mailer', 10);
			
		}
		
	}

	// validate cart and appointment products
	// this is needed in case a specific appointment has two or more products and the user removes any of them in order to reduce the price
	public static function woocommerce_validate_order_items( $order_id ) {
		$cart_appointments = Booked_WC_Cart::get_cart_appointments();

		$appointment_ids = array();

		foreach ($cart_appointments['ids'] as $app_id) {
			$app_id = intval($app_id);
			if ( $app_id<=0 ) {
				continue;
			}
			$appointment = Booked_WC_Appointment::get($app_id);

			if ( !$appointment->products ) {
				continue;
			}

			foreach ($appointment->products as $product) {
				$product_id = $product->product_id;
				$variation_id = isset($product->variation_id) ? $product->variation_id : 0;

				$check_key = "{$app_id}::{$product_id}::{$variation_id}";

				if ( !in_array($check_key, $cart_appointments['extended']) ) {
					$message = sprintf(__('Appointment "%1$s" and Cart products do not match. Please make sure that all Appointment products are available in the Cart.', 'booked-woocommerce-payments'), $appointment->timeslot_text);
					throw new Exception( $message );
				}
			}
		}

		// if the script above passes, link the appointments and their order
		foreach ($cart_appointments['ids'] as $app_id) {
			// link the appointment with the order
			update_post_meta($app_id, '_' . BOOKED_WC_PLUGIN_PREFIX . 'appointment_order_id', $order_id);
		}

		if ( $cart_appointments['ids'] ) {
			// link the order with all appointments appointment
			update_post_meta($order_id, '_' . BOOKED_WC_PLUGIN_PREFIX . 'order_appointments', $cart_appointments['ids']);
		}
	}

	// assign order items to their appointments in case the plugin must extend even more
	public static function woocommerce_add_order_item_meta($item_id, $values, $cart_item_key, $unique=false) {
		$prefix = BOOKED_WC_PLUGIN_PREFIX;

		$item_metas = array(
			'appointment_id',
			'appointment_cal_name',
			'appointment_assignee_name',
			// 'appointment_timerange',
		);

		// populating main metas
		foreach ($item_metas as $key) {
			$meta_key = $prefix . $key;

			if ( !isset($values[$meta_key]) ) {
				continue;
			}

			$value = $values[$meta_key];
			if ( !$value ) {
				return;
			}

			wc_add_order_item_meta($item_id, $meta_key, $value, $unique);
		}

		// populating the custom fields information
		$i = 0;
		$has_meta = true;
		do {
			$meta_key = $prefix . 'custom_field' . $i;

			if ( !isset($values[$meta_key]) ) {
				$has_meta = false;
				break;
			}

			$value = $values[$meta_key];
			if ( !$value ) {
				return;
			}

			wc_add_order_item_meta($item_id, $meta_key, $value, $unique);

			$i++;
		} while ( $has_meta===true );
	}

	public static function woocommerce_hidden_order_itemmeta( $hidden_meta ) {
		$hidden_meta[] = BOOKED_WC_PLUGIN_PREFIX . 'appointment_timerange';

		return $hidden_meta;
	}

	public static function woocommerce_order_items_meta_display($output, $order_item_meta_obj) {
		// preg_match_all('~<dt.+class="([^"]+)".*Form Field:</dt>~im', $output, $matches);

		// wrap labels in strong
		$output = preg_replace('~(custom_field\d+.+<p>)([^:]+:)(.+)(</p>)~im', '$1<small><strong>$2</strong>$3</small>$4', $output);

		// replace the form field text
		return preg_replace('~(<dt.+class="[^"]+".*)Form Field:(</dt>)~im', '$1$2', $output);
	}

}
