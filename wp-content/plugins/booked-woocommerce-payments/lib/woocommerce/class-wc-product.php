<?php

class Booked_WC_Product {

	private static $products = array();

	public $data;
	public $post_id;
	public $type;
	public $title;
	public $currency;
	public $variations=array();

	private function __construct( $post_id ) {
		$this->post_id = $post_id;
		$this->currency = get_woocommerce_currency_symbol();
		$this->get_data();
	}

	public static function get( $post_id=null ) {
		if ( !is_integer($post_id) ) {
			$message = sprintf(__('Booked_WC_Product::get($post_id) integer expected when %1$s given.', 'booked-woocommerce-payments'), gettype($post_id));
			throw new Exception($message);
		} else if ( $post_id===0 ) {
			self::$products[$post_id] = false;
		} else if ( !isset(self::$products[$post_id]) ) {
			self::$products[$post_id] = new self($post_id);
		}

		return self::$products[$post_id];
	}

	protected function get_data() {
		$this->_get_product_data();
		$this->_get_price();
		$this->_get_type();
		$this->_get_title();
		$this->_get_variations();
	}

	protected function _get_product_data() {
		$this->data = WC()->product_factory->get_product($this->post_id);

		if ( !$this->data ) {
			$message = sprintf(__('An error has occur while retrieving product data for product with ID %1$d.', 'booked-woocommerce-payments'), $this->post_id);
			throw new Exception($message);
		}

		return $this;
	}

	protected function _get_price() {
		$this->data->get_price();
		return $this;
	}

	protected function _get_type() {
		$this->type = $this->data->product_type;
		return $this;
	}

	protected function _get_title() {
		$booked_wc_currency_symbol = get_woocommerce_currency_symbol();
		$booked_wc_currency_position = get_option( 'woocommerce_currency_pos','left' );
		if ( $this->type==='variable' ) {
			$this->title = $this->data->post->post_title;
		} else if ( $this->data->price ) {
			switch ( $booked_wc_currency_position ) {
				case 'left' :
					$this->title = $booked_wc_currency_symbol . $this->data->price . ' - ' . $this->data->post->post_title;
				break;
				case 'right' :
				 	$this->title = $this->data->price . $booked_wc_currency_symbol . ' - ' . $this->data->post->post_title;
				break;
				case 'left_space' :
				  	$this->title = $booked_wc_currency_symbol . ' ' . $this->data->price . ' - ' . $this->data->post->post_title;
				break;
				case 'right_space' :
				  	$this->title = $this->data->price . ' ' . $booked_wc_currency_symbol . ' - ' . $this->data->post->post_title;
				break;
			}
		}

		return $this;
	}

	protected function _get_variations() {
		if ( $this->type==='variable' ) {
			add_filter('woocommerce_available_variation', array('Booked_WC_Variation', 'woocommerce_available_variation'), 10, 3);
			$product_variations = $this->data->get_available_variations();
			remove_filter('woocommerce_available_variation', array('Booked_WC_Variation', 'woocommerce_available_variation'));

			// use variation IDs as keys for their values
			$variations = array();
			$all_children = $this->data->children['all'];
			foreach ($product_variations as $variation_data) {
				if ( !in_array($variation_data['variation_id'], $all_children) ) {
					continue;
				}

				$variations[$variation_data['variation_id']] = $variation_data;
			}
			$this->variations = $variations;
		}

		return $this;
	}
}
