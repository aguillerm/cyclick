<?php

class Booked_WC_Appointment_Payment_Status {
	public $app_id;
	public $order_id;
	public $order_obj = null;

	public $is_paid;
	public $payment_status;
	public $payment_status_text;

	public function __construct($app_id) {
		if ( !is_integer($app_id) ) {
			$message = sprintf(__('new Booked_WC_Appointment_Payment_Status::get($app_id) integer expected when %1$s given.', 'booked-woocommerce-payments'), gettype($app_id));
			throw new Exception($message);
		} else if ( $app_id===0 ) {
			$message = __('new Booked_WC_Appointment_Payment_Status::get($app_id) invalid ID is given. $app_id=0', 'booked-woocommerce-payments');
			throw new Exception($message);
		}

		$this->app_id = $app_id;

		// set default status values
		$this->is_paid = false;
			$this->payment_status = 'awaiting_checkout';
		$this->payment_status_text = __('Awaiting Payment', 'booked-woocommerce-payments');

		$this->get_order();
		$this->set_statuses();
	}

	public function get_order() {
		$this->order_id = get_post_meta($this->app_id, '_' . BOOKED_WC_PLUGIN_PREFIX . 'appointment_order_id', true);
		if ( $this->order_id && $this->order_id != 'manual') {
			$this->order_id = (int) $this->order_id;
			$this->order_obj = Booked_WC_Order::get($this->order_id);
		}

		return $this;
	}

	public function set_statuses() {
		
		if ( $this->order_id && $this->order_id === 'manual' || $this->order_id && $this->order_obj->order->post_status === 'wc-completed' ) {
			$this->is_paid = true;
			$this->payment_status = 'paid';
			$this->payment_status_text = __('Order Paid', 'booked-woocommerce-payments');
		} elseif ( $this->order_id ) {
			$this->is_paid = $this->order_obj->order->post_status === 'wc-completed';
			$this->payment_status = $this->order_obj->order->post_status;
			$this->payment_status_text = __('Order ', 'booked-woocommerce-payments') . $this->order_obj->order->post_status_text;
		}

		return $this;
		
	}
}
