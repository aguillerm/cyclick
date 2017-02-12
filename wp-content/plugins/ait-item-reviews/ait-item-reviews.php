<?php


/*
Plugin Name: AIT Item Reviews
Plugin URI: http://ait-themes.club
Description: Adds rating and reviews functionality for City Guide Wordpress Theme
Version: 1.29
Author: AitThemes.Club
Author URI: http://ait-themes.club
Text Domain: ait-item-reviews
Domain Path: /languages
License: GPLv2 or later
*/

/* trunk@r305 */

define('AIT_REVIEWS_ENABLED', true);

AitItemReviews::init();

class AitItemReviews {

	protected static $currentTheme;

	protected static $elementCompatibleThemes = array( 'skeleton', 'businessfinder2');

	protected static $paths;

	public static function init(){

		self::$currentTheme = sanitize_key(get_stylesheet());

		self::$paths = array(
			'config' => dirname( __FILE__ ).'/config',
			'templates' => dirname( __FILE__ ).'/templates',
		);


		register_activation_hook( __FILE__, array(__CLASS__, 'onActivation') );
		register_deactivation_hook(  __FILE__, array(__CLASS__, 'onDeactivation') );

		add_action('plugins_loaded', array(__CLASS__, 'onLoaded'));
		add_action('ait-after-framework-load', array(__CLASS__, 'onAfterFwLoad'));

		add_action('init', array(__CLASS__, 'onInit'));
		add_action('init', array(__CLASS__, 'addItemMetaboxOptions'), 12, 0);


		//add_action( 'admin_menu', array(__CLASS__, 'reviewRemoveMenuAddButton'), 999);
		// disabled due to capablilities error .. in admin menu there must be at least 2 menu items ale the user will have no capabilities
		add_action( "admin_init", array(__CLASS__, 'reviewDisablePostNewAction') );

		add_action('load-edit.php', array(__CLASS__, 'reviewCheckStatus'));
		add_action('trashed_post', array(__CLASS__, 'ratingTrashed'));
		add_action('untrashed_post', array(__CLASS__, 'ratingUntrashed'));

		// Item Columns
		add_filter('manage_ait-review_posts_columns', array(__CLASS__, 'reviewChangeColumns'), 10, 2);
		add_action('manage_posts_custom_column', array(__CLASS__, 'reviewCustomColumns'), 10, 2);

		// Category Actions / Custom Fields
		add_action('ait-reviews_add_form_fields', array(__CLASS__, 'reviewsAddFormFields'), 10, 2);
		add_action('ait-reviews_edit_form_fields', array(__CLASS__, 'reviewsEditFormFields'), 10, 2);
		add_action("edited_ait-reviews", array(__CLASS__, 'reviewsSaveFormFields'), 10, 2);
		add_action("created_ait-reviews", array(__CLASS__, 'reviewsSaveFormFields'), 10, 2);
		add_filter('manage_edit-ait-reviews_columns', array(__CLASS__, 'reviewsChangeColumns'));

		// Templates
		add_filter('wplatte-get-template-part', array(__CLASS__, 'getTemplate'), 10, 3);

		// Scripts, Styles, etc
		add_action( 'wp_enqueue_scripts', array(__CLASS__, 'enqueueFrontendScripts') );
		add_action( 'admin_enqueue_scripts', array(__CLASS__, 'adminEnqueueScripts') );
		add_action( 'admin_print_scripts', array(__CLASS__, 'adminPrintScripts'), 99, 0);
		add_action( 'admin_print_styles', array(__CLASS__, 'adminPrintStyles'), 99, 0);

		// Ajax
		add_action( 'wp_ajax_publishReview', array(__CLASS__, 'ajaxPublishReview'));
		add_action( 'wp_ajax_nopriv_publishReview', array(__CLASS__, 'ajaxPublishReview'));

		add_action( 'wp_ajax_loadReviews', array(__CLASS__, 'ajaxLoadReviews'));
		add_action( 'wp_ajax_nopriv_loadReviews', array(__CLASS__, 'ajaxLoadReviews'));

		add_action( 'wp_ajax_getReviews', array(__CLASS__, 'ajaxGetReviews'));
		add_action( 'wp_ajax_nopriv_getReviews', array(__CLASS__, 'ajaxGetReviews'));
	}

	public static function onActivation(){
		flush_rewrite_rules();

		AitItemReviews::updateThemeOptions();
		if(class_exists('AitCache')){
			AitCache::clean();
		}
	}

	public static function onDeactivation(){
		flush_rewrite_rules();
		if(class_exists('AitCache')){
			AitCache::clean();
		}
	}


	public static function onAfterFwLoad()
	{
		// add element only for compatible themes
		if ( defined('AIT_THEME_CODENAME') && in_array(AIT_THEME_CODENAME, self::$elementCompatibleThemes) ) {

			// Elements functions
			add_action('ait-theme-run', array(__CLASS__, 'elementExternalClassFile'));
			add_filter('ait-elements-config', array(__CLASS__, 'elementConfig') , 13);
			add_filter('ait-element-options-file', array(__CLASS__, 'elementOptionsFile') , 13, 2);
			add_filter('ait-element-options-filename', array(__CLASS__, 'elementOptionsFileName') , 13, 2);

			add_filter('ait-theme-configuration', function($themeConfiguration){
				array_push($themeConfiguration['ait-theme-support']['elements'], 'item-reviews');
				return $themeConfiguration;
			}, 13);
		}

		if (is_admin()) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php' );
			$aitPluginData = get_option('ait-plugin-item-reviews', array());
			$wpPluginData = get_plugin_data( __FILE__ );
			$wpVersion = $wpPluginData['Version'];

			if (empty($aitPluginData) || empty($aitPluginData['version'])) {
				// there is nothing stored in the database for this plugin
				// add initial version
				$aitPluginData['version'] = '0.1';
				update_option('ait-plugin-item-reviews', $aitPluginData);
			}
			/* !!!!!!!!!! ADD UPDATE FUNCTIONS HERE BEFORE VERSION IS UPDATED !!!!!!!!!!!*/
			if (version_compare($aitPluginData['version'], '1.23', '<')) {
				// self::update_1_22();
			}


			/* !!!!!!!!!! UPDATE PLUGIN VERSION !!!!!!!!!!!*/
			$aitPluginData['version'] = $wpVersion;
			update_option('ait-plugin-item-reviews', $aitPluginData);
		}

	}

	public static function onInit(){
		AitItemReviews::registerCpt();
		AitItemReviews::registerTax();

		AitItemReviews::registerAdminCapabilities();
		AitItemReviews::registerUserCapabilities();
	}

	public static function onLoaded(){
		load_plugin_textdomain('ait-item-reviews', false,  dirname(plugin_basename(__FILE__ )) . '/languages');

		add_filter('ait-theme-config', array('AitItemReviews', 'prepareThemeConfig'));
		add_filter('ait-theme-config', array('AitItemReviews', 'addDefaultOrderToThemeConfig'));

		add_filter('ait-theme-config', function($config){
			$config['packages']['options']['packageTypes']['items']["capabilityReviewsAuthorManage"] = array(
				"label" => __('Item author approve Reviews ', 'ait-item-reviews'),
				"type"  => "on-off",
				"less"  => false,
				"help"	=> "Allow registered users approve received Reviews",
			);
			return $config;
		});

		add_filter( 'ait_add_package_feature', function($features, $package){
			if (!isset($features['ait_item_reviews_approve_review'])) {
				$features['ait_item_reviews_approve_review'] = isset($package['capabilityReviewsAuthorManage']) ? $package['capabilityReviewsAuthorManage'] : false;
			}
			return $features;
		}, 10, 2 );
	}



	public static function loadPluginConfig($type = 'raw'){
		$config = include self::$paths['config'].'/theme-options.php';
		if(file_exists(self::$paths['config'].'/theme-options-'.self::$currentTheme.'.php')){
			$config = include self::$paths['config'].'/theme-options-'.self::$currentTheme.'.php';
		}
		return $config[$type];
	}

	public static function prepareThemeConfig($config = array()){
		$plugin = AitItemReviews::loadPluginConfig();

		if(count($config) == 0){
			$theme = self::$currentTheme;
			$config = get_option("_ait_{$theme}_theme_opts", array());
			$plugin = AitItemReviews::loadPluginConfig('defaults');
		}

		return array_merge($config, $plugin);
	}

	public static function addDefaultOrderToThemeConfig($config){
		if (isset($config['items']['options']['sortingDefaultOrderBy'])) {
			$config['items']['options']['sortingDefaultOrderBy']['default']['rating'] = __("Rating", 'ait-item-reviews');
		} else {

			if (isset($config['sorting']['options']['sortingDefaultOrderBy'])) {
				// eventguide theme
				$config['sorting']['options']['sortingDefaultOrderBy']['default']['rating'] = __("Rating", 'ait-item-reviews');
			}

		}

		return $config;
	}

	public static function updateThemeOptions(){
		// check if the settings already exists
		$theme = self::$currentTheme;
		$themeOptions = get_option("_ait_{$theme}_theme_opts");
		if(!isset($themeOptions['itemReviews'])){
			$updatedConfig = AitItemReviews::prepareThemeConfig();
			$theme = self::$currentTheme;
			update_option("_ait_{$theme}_theme_opts", $updatedConfig);
		}
	}

	public static function registerCpt(){
		$labels = array(
			'name'					=> _x( 'Reviews', 'post type general name', 'ait-item-reviews' ),
			'singular_name'			=> _x( 'Review', 'post type singular name', 'ait-item-reviews' ),
			'menu_name'				=> _x( 'Item Reviews', 'admin menu', 'ait-item-reviews' ),
			'name_admin_bar'		=> _x( 'Review', 'add new on admin bar', 'ait-item-reviews' ),
			'add_new'				=> _x( 'Add New', 'review', 'ait-item-reviews' ),
			'add_new_item'			=> __( 'Add New Review', 'ait-item-reviews' ),
			'new_item'				=> __( 'New Review', 'ait-item-reviews' ),
			'edit_item'				=> __( 'Edit Review', 'ait-item-reviews' ),
			'view_item'				=> __( 'View Review', 'ait-item-reviews' ),
			'all_items'				=> __( 'All Reviews', 'ait-item-reviews' ),
			'search_items'			=> __( 'Search Reviews', 'ait-item-reviews' ),
			'parent_item_colon'		=> __( 'Parent Reviews:', 'ait-item-reviews' ),
			'not_found'				=> __( 'No reviews found.', 'ait-item-reviews' ),
			'not_found_in_trash'	=> __( 'No reviews found in Trash.', 'ait-item-reviews' )
		);

		$capabilities = array(
			'edit_post'					=> "ait_item_reviews_edit_post",
			'read_post'					=> "ait_item_reviews_read_post",
			'delete_post'				=> "ait_item_reviews_delete_post",
			'edit_posts'				=> "ait_item_reviews_edit_posts",
			'edit_others_posts'			=> "ait_item_reviews_edit_others_posts",
			'publish_posts'				=> "ait_item_reviews_publish_posts",
			'read_private_posts'		=> "ait_item_reviews_read_private_posts",
			'delete_posts'				=> "ait_item_reviews_delete_posts",
			'delete_private_posts'		=> "ait_item_reviews_delete_private_posts",
			'delete_published_posts'	=> "ait_item_reviews_delete_published_posts",
			'delete_others_posts'		=> "ait_item_reviews_delete_others_posts",
			'edit_private_posts'		=> "ait_item_reviews_edit_private_posts",
			'edit_published_posts'		=> "ait_item_reviews_edit_published_posts",
		);

		$supports = array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'excerpt',
			'comments'
		);

		$args = array(
			'labels'				=> $labels,
			'public'				=> false,
			'publicly_queryable'	=> true,
			'show_ui'				=> true,
			'show_in_menu'			=> true,
			'query_var'				=> true,
			'rewrite'				=> array( 'slug' => 'review' ),
			'capabilities'			=> $capabilities,
			'has_archive'			=> true,
			'hierarchical'			=> false,
			'menu_position'			=> null,
			'menu_icon'				=> plugins_url( 'design/img/icon.png', __FILE__ ),
			'supports'				=> $supports
		);

		register_post_type( 'ait-review', $args );
	}

	public static function registerTax(){
		$labels = array(
			'name'				=> _x( 'Review Categories', 'taxonomy general name', 'ait-item-reviews'),
			'singular_name'		=> _x( 'Category', 'taxonomy singular name', 'ait-item-reviews' ),
			'search_items'		=> __( 'Search Categories', 'ait-item-reviews' ),
			'all_items'			=> __( 'All Categories', 'ait-item-reviews' ),
			'parent_item'		=> __( 'Parent Category', 'ait-item-reviews' ),
			'parent_item_colon'	=> __( 'Parent Category:', 'ait-item-reviews' ),
			'edit_item'			=> __( 'Edit Category', 'ait-item-reviews' ),
			'update_item'		=> __( 'Update Category', 'ait-item-reviews' ),
			'add_new_item'		=> __( 'Add New Category', 'ait-item-reviews' ),
			'new_item_name'		=> __( 'New Category Name', 'ait-item-reviews' ),
			'menu_name'			=> __( 'Category', 'ait-item-reviews' ),
		);

		$capabilities = array(
			'manage_terms'		=> "ait_item_reviews_category_manage_terms",
			'edit_terms'		=> "ait_item_reviews_category_edit_terms",
			'delete_terms'		=> "ait_item_reviews_category_delete_terms",
			'assign_terms'		=> "ait_item_reviews_category_assign_terms",
		);

		$args = array(
			'hierarchical'		=> false,
			'labels'			=> $labels,
			'show_ui'			=> true,
			'show_admin_column'	=> true,
			'query_var'			=> true,
			'rewrite'			=> array( 'slug' => 'reviews' ),
			'capabilities'		=> $capabilities,
		);

		register_taxonomy( 'ait-reviews', 'ait-review', $args );
	}

	public static function registerAdminCapabilities(){
		$capabilities = array(
			"ait_item_reviews_edit_post",
			"ait_item_reviews_read_post",
			"ait_item_reviews_delete_post",
			"ait_item_reviews_edit_posts",
			"ait_item_reviews_edit_others_posts",
			"ait_item_reviews_publish_posts",
			"ait_item_reviews_read_private_posts",
			"ait_item_reviews_delete_posts",
			"ait_item_reviews_delete_private_posts",
			"ait_item_reviews_delete_published_posts",
			"ait_item_reviews_delete_others_posts",
			"ait_item_reviews_edit_private_posts",
			"ait_item_reviews_edit_published_posts",
			"ait_item_reviews_category_manage_terms",
			"ait_item_reviews_category_edit_terms",
			"ait_item_reviews_category_delete_terms",
			"ait_item_reviews_category_assign_terms",
			// custom capabilities
			"ait_item_reviews_approve_review"
		);

		$role = get_role('administrator');
		foreach($capabilities as $val){
			$role->add_cap($val);
		}
	}

	public static function registerUserCapabilities(){
		$capabilities_to_add = array(
			"ait_item_reviews_edit_posts",
			"ait_item_reviews_delete_posts",
			"ait_item_reviews_delete_published_posts",
		);
		$capabilities_to_remove = array(
			"ait_item_reviews_read_post",
			"ait_item_reviews_edit_post",
			"ait_item_reviews_publish_posts",
			"ait_item_reviews_edit_published_posts",
		);
		$packages = AitItemReviews::getCurrentRegisteredPackages();
		if(count($packages) > 0){
			foreach(AitItemReviews::getCurrentRegisteredPackages() as $key => $role){
				foreach ($capabilities_to_add as $value) {
					$role->add_cap( $value );
				}
				foreach ($capabilities_to_remove as $value) {
					$role->remove_cap( $value );
				}
			}
		}
	}

	public static function getCurrentRegisteredPackages(){
		global $wp_roles;
		$registeredPackages = array();
		foreach($wp_roles->role_objects as $key => $role){
			if(strpos($key, 'cityguide_') !== FALSE){
				$registeredPackages[$key] = $role;
			}
		}
		return $registeredPackages;
	}

	public static function reviewRemoveMenuAddButton(){
		remove_submenu_page('edit.php?post_type=ait-review','post-new.php?post_type=ait-review');
	}

	public static function reviewDisablePostNewAction(){
		global $pagenow, $current_user;
		if($pagenow == 'post-new.php'){
			if(!empty($_REQUEST['post_type']) && $_REQUEST['post_type'] == "ait-review"){
				// redirect back to edit
				wp_safe_redirect( admin_url( 'edit.php?post_type=ait-review' ) );
				exit();
			}
		}
	}

	public static function reviewChangeColumns($cols){
		if (isCityguideUser()) {
			$cols = array(
				'title'         => __('Name', 'ait-item-reviews'),
				'review-post-id'=> __('Review for', 'ait-item-reviews'),
				'review-value'  => __('Rating', 'ait-item-reviews'),
				'content'		=> __('Review', 'ait-item-reviews'),
				'date'          => __('Date', 'ait-item-reviews'),
				'review-status' => __('Status', 'ait-item-reviews'),
			);
		} else {
			$cols = array(
				'cb'			=> '<input type="checkbox" />',
				'title'         => __('Name', 'ait-item-reviews'),
				'review-post-id'=> __('Review for', 'ait-item-reviews'),
				'review-value'  => __('Rating', 'ait-item-reviews'),
				'content'		=> __('Review', 'ait-item-reviews'),
				'date'          => __('Date', 'ait-item-reviews'),
				'review-status' => __('Status', 'ait-item-reviews'),
			);
		}
		return $cols;
	}

	public static function reviewCustomColumns($column, $ratingId){
		switch($column){
			case "review-name":
				$post = get_post($ratingId);
				echo $post->post_title;
				break;
			case "review-post-id":
				$postId = get_post_meta($ratingId, 'post_id', true);
				$post = get_post($postId);
				if($post != null){
					$postLink = get_permalink($postId);
					echo '<strong><a href="'.$postLink.'" target="_blank">'.$post->post_title.'</a></strong>';
				} else {
					echo __("Item doesn't exist", 'ait-item-reviews');
				}
				break;
			case "review-value":
				$ratings = json_decode(get_post_meta($ratingId, 'ratings', true));
				if(!empty($ratings)){
					foreach($ratings as $index => $rating){
						echo '<div class="review-rating" data-rating-id="'.$index.'">';
						echo '<span class="review-rating-question">'.$rating->question.'</span>';
						echo '<span class="review-rating-stars" data-score="'.$rating->value.'"></span>';
						echo '</div>';
					}
					$rating_mean_rounded = intval(get_post_meta($ratingId, 'rating_mean_rounded', true));
					echo '<div class="review-rating">';
					echo '<span class="review-rating-question">'.__('Average', 'ait-item-reviews').'</span>';
					echo '<span class="review-rating-stars" data-score="'.$rating_mean_rounded.'"></span>';
					echo '</div>';
				} else {
					echo __('No rating', 'ait-item-reviews');
				}
				break;
			case "content":
				$post = get_post($ratingId);
				echo $post->post_content;
				break;
			case "review-status":
				$post = get_post($ratingId);
				if ($post->post_status == 'publish') {
					echo "<div style='color:green;'>".__("Approved","ait-item-reviews")."</div>";
				} elseif ($post->post_status == 'pending') {
					// only approvable by admin
					if(current_user_can( 'ait_item_reviews_approve_review' )){
						echo "<a href='".admin_url('edit.php?post_type=ait-review&rating-approve=do&rating-id='.$ratingId)."' class='button'>".__("Approve","ait-item-reviews")."</a>";
					} else {
						echo "<div style='color:#d05756;'>".__("Pending Approval","ait-item-reviews")."</div>";
					}
				}
				break;
		}
	}

	public static function reviewsChangeColumns($columns){
		unset($columns['posts']);
		return $columns;
	}

	public static function reviewCheckStatus(){
		if(isset($_GET['post_type']) && $_GET['post_type'] === 'ait-review'){

			if (isset($_GET['rating-approve']) && !empty($_GET['rating-id'])) {
				$ratingId = intval($_GET['rating-id']);
				// admin can approve all ratings
				if (current_user_can('manage_options')) {
					AitItemReviews::reviewApprove($ratingId);
					$redirect = admin_url('edit.php?post_type=ait-review&ait-notice=review-approved');
					wp_safe_redirect( $redirect );
					exit();
				} else {
					global $current_user;
					$itemId = intval(get_post_meta($ratingId, 'post_id', true));
					$item = get_post($itemId);
					if (isset($current_user) && ($current_user->ID == intval($item->post_author))) {
						AitItemReviews::reviewApprove($ratingId);
						$redirect = admin_url('edit.php?post_type=ait-review&ait-notice=review-approved');
						wp_safe_redirect( $redirect );
						exit();
					}
				}
			}
		}
	}

	/*public static function reviewSyncData(){
		global $post;
		if('ait-review' == $post->post_type) {
			AitItemReviews::updateItemRating($post->ID, false);
		}
	}

	public static function reviewSyncData($postId, ){
		$post = get_post($postId);
		if(isset($post)){
			if('ait-review' == $post->post_type) {
				AitItemReviews::updateItemRating($post->ID);
			}
		}
	}*/

	public static function ratingTrashed($postId){
		$post = get_post($postId);
		if(isset($post)){
			if('ait-review' == $post->post_type) {
				AitItemReviews::updateItemRating($post->ID, false);
			}
		}
	}

	public static function ratingUntrashed($postId){
		$post = get_post($postId);
		if(isset($post)){
			if('ait-review' == $post->post_type) {
				AitItemReviews::updateItemRating($post->ID);
			}
		}
	}

	public static function updateItemRating($ratingId, $approve = true){
		$item = get_post(intval(get_post_meta($ratingId,'post_id', true)));
		// test if the item referenced by the review exists
		if($item != null){
			// get all ratings
			$item_rating_count = get_post_meta($item->ID, "rating_count", true) === "" ? 0 : get_post_meta($item->ID, "rating_count", true);
			$item_rating_count_unrated = get_post_meta($item->ID, "rating_count_unrated", true) === "" ? 0 : get_post_meta($item->ID, "rating_count_unrated", true);

			$item_rating_max = get_post_meta($item->ID, "rating_max", true) === "" ? 0 : get_post_meta($item->ID, "rating_max", true);
			$item_rating_mean = get_post_meta($item->ID, "rating_mean", true) === "" ? 0 : get_post_meta($item->ID, "rating_mean", true);
			$item_rating_mean_rounded = get_post_meta($item->ID, "rating_mean_rounded", true) === "" ? 0 : get_post_meta($item->ID, "rating_mean_rounded", true);

			// get current rating
			$current_mean = get_post_meta($ratingId, "rating_mean", true);
			$current_mean_rounded = get_post_meta($ratingId, "rating_mean_rounded", true);

			if($approve){
				if($current_mean == 0){
					$item_rating_count_unrated += 1;
				} else {
					$item_rating_count += 1;
				}
				$item_rating_max += $current_mean;
			} else {
				if($current_mean == 0){
					$item_rating_count_unrated -= 1;
				} else {
					$item_rating_count -= 1;
				}
				$item_rating_max -= $current_mean;
			}

			if($item_rating_count > 0){
				$item_rating_mean = $item_rating_max / $item_rating_count;
				$item_rating_mean_rounded = round($item_rating_mean);
			} else {
				$item_rating_count = 0;
				$item_rating_max = 0;
				$item_rating_mean = 0;
				$item_rating_mean_rounded = 0;
			}

			update_post_meta($item->ID, 'rating_count', $item_rating_count);
			update_post_meta($item->ID, 'rating_count_unrated', $item_rating_count_unrated);

			update_post_meta($item->ID, 'rating_max', $item_rating_max);
			update_post_meta($item->ID, 'rating_mean', $item_rating_mean);
			update_post_meta($item->ID, 'rating_mean_rounded', $item_rating_mean_rounded);
		}
	}

	public static function reviewApprove($ratingId){
		//global $ratingMessages;
		$rating = get_post($ratingId,'ARRAY_A');
		$rating['post_status'] = 'publish';
		$chStatus = wp_insert_post($rating, true);
		/*if(is_wp_error($chStatus)){
			$ratingMessages = $chStatus->get_error_message();
		} else {
			$ratingMessages = __('Rating was approved!','ait');
		}*/
		// notify user
		$item = get_post(intval(get_post_meta($ratingId,'post_id', true)));
		$message = sprintf(__('New rating for <a href="%s">%s</a> has been approved by administrator','ait-item-reviews'), get_permalink( $item->ID ), $item->post_title);
		AitItemReviews::notifyOwner($item->post_author, __('New Rating','ait-item-reviews'), $message);

		AitItemReviews::updateItemRating($ratingId);
	}

	public static function getCurrentItemReviews($post_id, $query_args = array()){
		$args = array(
			'post_type' => 'ait-review',
			'post_status' => 'publish',
			'nopaging' => true,
			'meta_query' => array(
				array(
					'key' => 'post_id',
					'value' => $post_id
				)
			)
		);
		$args = array_merge($args, $query_args);
		$ratings = new WP_Query($args);

		wp_reset_query();
		return $ratings;
	}

	/* REVIEW CATEGORIES */
	public static function reviewsAddFormFields($tag){
		?>
		<div class="form-field form-required">
			<label for="ait-reviews[question1]"><?php _e('Question 1', 'ait-item-reviews'); ?></label>
			<input name="ait-reviews[question1]" id="ait-reviews[question1]" type="text" value="" size="40" aria-required="true">
		</div>
		<div class="form-field form-required">
			<label for="ait-reviews[question2]"><?php _e('Question 2', 'ait-item-reviews'); ?></label>
			<input name="ait-reviews[question2]" id="ait-reviews[question2]" type="text" value="" size="40" aria-required="true">
		</div>
		<div class="form-field form-required">
			<label for="ait-reviews[question3]"><?php _e('Question 3', 'ait-item-reviews'); ?></label>
			<input name="ait-reviews[question3]" id="ait-reviews[question3]" type="text" value="" size="40" aria-required="true">
		</div>
		<div class="form-field form-required">
			<label for="ait-reviews[question4]"><?php _e('Question 4', 'ait-item-reviews'); ?></label>
			<input name="ait-reviews[question4]" id="ait-reviews[question4]" type="text" value="" size="40" aria-required="true">
		</div>
		<div class="form-field form-required">
			<label for="ait-reviews[question5]"><?php _e('Question 5', 'ait-item-reviews'); ?></label>
			<input name="ait-reviews[question5]" id="ait-reviews[question5]" type="text" value="" size="40" aria-required="true">
		</div>
		<?php
	}

	public static function reviewsEditFormFields($tag, $taxonomy){
		$id = $tag->term_id;
		$extraFields = get_option( "ait-reviews_category_{$id}" );
		?>
		<tr class="form-field form-required">
			<th scope="row">
				<label for="ait-reviews[question1]"><?php _e('Question 1', 'ait-item-reviews'); ?></label>
			</th>
			<td>
				<input name="ait-reviews[question1]" id="ait-reviews[question1]" type="text" value="<?php echo isset($extraFields["question1"]) ? $extraFields["question1"] : '' ?>" size="40" aria-required="true">
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row">
				<label for="ait-reviews[question2]"><?php _e('Question 2', 'ait-item-reviews'); ?></label>
			</th>
			<td>
				<input name="ait-reviews[question2]" id="ait-reviews[question2]" type="text" value="<?php echo isset($extraFields["question2"]) ? $extraFields["question2"] : '' ?>" size="40" aria-required="true">
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row">
				<label for="ait-reviews[question3]"><?php _e('Question 3', 'ait-item-reviews'); ?></label>
			</th>
			<td>
				<input name="ait-reviews[question3]" id="ait-reviews[question3]" type="text" value="<?php echo isset($extraFields["question3"]) ? $extraFields["question3"] : '' ?>" size="40" aria-required="true">
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row">
				<label for="ait-reviews[question4]"><?php _e('Question 4', 'ait-item-reviews'); ?></label>
			</th>
			<td>
				<input name="ait-reviews[question4]" id="ait-reviews[question4]" type="text" value="<?php echo isset($extraFields["question4"]) ? $extraFields["question4"] : '' ?>" size="40" aria-required="true">
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row">
				<label for="ait-reviews[question5]"><?php _e('Question 5', 'ait-item-reviews'); ?></label>
			</th>
			<td>
				<input name="ait-reviews[question5]" id="ait-reviews[question5]" type="text" value="<?php echo isset($extraFields["question5"]) ? $extraFields["question5"] : '' ?>" size="40" aria-required="true">
			</td>
		</tr>
		<?php
	}

	public static function reviewsSaveFormFields($term_id){

		if ( isset( $_POST['ait-reviews'] ) ) {
			$extraFields = get_option( "ait-reviews_category_{$term_id}");
			$keys = array_keys($_POST['ait-reviews']);
			foreach ($keys as $key){
				$extraFields[$key] = $_POST['ait-reviews'][$key];
			}
			update_option("ait-reviews_category_{$term_id}", $extraFields);
		}
	}
	/* REVIEW CATEGORIES */

	public static function contains($haystack, $needle)
	{
		return strpos($haystack, $needle) !== FALSE;
	}

	/* TEMPLATES */
	public static function getTemplate($templates, $slug, $name){

		$ok = true;
		foreach(glob(self::$paths['templates'] . '/*.php') as $file){
			$filename = basename($file, '.php');
			if(!self::contains($slug, $filename)){
				$ok = false;
			}else{
				$ok = true;
				break;
			}
		}

		if(!$ok){
			return $templates;
		}



		// create name of file
		// e.g. 'parts/entry-date-format-<NAME>-loop.php'
		// e.g. 'parts/entry-date-format-<NAME>.php'
		if($name){
			//if(!is_singular()) $templates[] = "{$slug}-{$name}-loop.php";
			$templates[] = "{$slug}-{$name}.php";
		}

		// e.g. 'parts/entry-date-format-loop.php'
		// e.g. 'parts/entry-date-format.php'
		//if(!is_singular()) $templates[] = "{$slug}-loop.php";
			$templates[] = "{$slug}.php";

		$locatedInTheme = locate_template($templates, false, false);


		$pluginDir = self::$paths['templates'];

		if(!$locatedInTheme){ // in theme file does not exist, load it from plugin
			$newTemplate = '';
			foreach($templates as $tmpl){
				$tmpl = basename($tmpl);

				if(file_exists("$pluginDir/$tmpl")){
					$newTemplate = "$pluginDir/$tmpl";
				}else{
					continue;
				}
			}
			if(!$newTemplate){
				trigger_error("Template '$tmpl' does not exist in plugin dir nor theme dir");
			}
			return $newTemplate;
		} else {
			return $locatedInTheme; // exist in theme
		}
	}

	public static function renderLatteTemplate($slug, $params = array(), $initWpLatte = true)
	{
		if ($initWpLatte) {
			AitWpLatte::init();
		}
		$path = self::findTemplate($slug);
		ob_start();
		WpLatte::render($path, $params);
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}



	public static function findTemplate($slug)
	{
		$templateFile = aitPath('theme', "/parts/{$slug}.php");

		$pluginTemplatesDir = self::$paths['templates'];

		if(!$templateFile and file_exists("{$pluginTemplatesDir}/{$slug}.php")){
			$templateFile = "{$pluginTemplatesDir}/{$slug}.php";
		}

		if(!$templateFile){
			trigger_error("Template '{$slug}.php' does not exist in plugin dir nor theme dir");
		}

		return $templateFile;
	}
	/* TEMPLATES */

	/* SCRIPTS, STYLES, ETC */
	public static function adminPrintScripts(){
		// raty init script
		if(isset($_GET['post_type'])){
			if($_GET['post_type'] === 'ait-review'){
				echo "<script type='text/javascript'>\n";
				echo "jQuery(document).ready(function(){";
					echo "jQuery('.review-value .review-rating-stars').raty({";
						echo "font: true,";
						echo "readOnly:true,";
						echo "halfShow:false,";
						echo "starHalf:'fa-star-half-o',";
						echo "starOff:'fa-star-o',";
						echo "starOn:'fa-star',";
						echo "score: function() {";
						echo "return $(this).attr('data-score');";
						echo "},";
					echo "});";
				echo "});";
				echo "\n</script>";
			}
		}
	}

	public static function adminPrintStyles(){
		echo '<style type="text/css"> ul#adminmenu a[href="post-new.php?post_type=ait-review"] {display: none} .post-type-ait-review div#wpbody a.add-new-h2 {display: none} .post-type-ait-review div#wpbody a.page-title-action {display: none} </style>';
	}

	public static function itemHasReviews($post_id){
		$query = AitItemReviews::getCurrentItemReviews($post_id);
		return count($query->posts) > 0 ? 'has-reviews' : '';
	}

	public static function enqueueFrontendScripts(){
		wp_enqueue_script(
			'ait-jquery-reviews-stars',
			plugins_url( '/design/js/stars.js', __FILE__ ),
			array( 'jquery' )
		);
	}

	public static function adminEnqueueScripts(){
		wp_register_style(
			'ait-style-reviews-stars',
			plugins_url( '/design/css/admin.css', __FILE__ ),
			false,
			'1.0.0'
		);
		wp_enqueue_style( 'ait-style-reviews-stars' );
	}
	/* SCRIPTS, STYLES, ETC */

	/* CITYGUIDE ITEM ADD OPTIONS TO METABOX */
	public static function addItemMetaboxOptions(){
		if(!class_exists('AitToolkit')) return;
		$manager = AitToolkit::getManager('cpts');
		$allCpts = $manager->getAll();

		$params = array(
			'title' => __('Item Reviews', 'ait-item-reviews'),
			'config' => self::$paths['config'].'/ait-item-review-options.metabox.php',
		);

		foreach($allCpts as $cpt){
			if($cpt->getId() === 'item'){
				$cpt->addMetabox('review-options', $params);
			}
		}
	}
	/* CITYGUIDE ITEM ADD OPTIONS TO METABOX */
	public static function setWpMailContentType(){
		return 'text/html';
	}

	public static function sendEmailNotification($user, $subject, $message){
		$themeOptions = aitOptions()->getOptionsByType('theme');
		$itemReviewsOptions = (object)$themeOptions['itemReviews'];

		if($itemReviewsOptions->notifications){
			if(is_numeric($user)){
				$user = get_user_by('id', $user);
			}
			add_filter('wp_mail_content_type', array(__CLASS__, 'setWpMailContentType'));
			wp_mail($user->data->user_email, $subject, $message);
			remove_filter('wp_mail_content_type', array(__CLASS__, 'setWpMailContentType'));
		}
	}

	public static function notifyAdmin($subject, $message){
		// admin always
		$admins = get_users(array('role' => 'administrator'));
		if(!empty($admins)){
			foreach ($admins as $index => $user) {
				AitItemReviews::sendEmailNotification($user, $subject, $message);
			}
		}
	}

	public static function notifyOwner($user, $subject, $message){
		AitItemReviews::sendEmailNotification($user, $subject, $message);
	}


	/************************************************/
	/********************** ELEMENTS ****************/
	public static function elementConfig($localConfig)
	{
		$elementConfig = include dirname(__FILE__).'/elements/item-reviews/item-reviews.php';
		$localConfig['item-reviews'] = $elementConfig;
		return $localConfig;
	}



	public static function elementExternalClassFile()
	{
		include dirname(__FILE__).'/elements/item-reviews/AitItemReviewsElement.php';
	}



	public static function elementOptionsFile($file, $elementId)
	{
		if($elementId === 'item-reviews'){
			$file = dirname(__FILE__).'/elements/item-reviews/item-reviews.options.neon';
		}
		return $file;
	}



	public static function elementOptionsFileName($filename, $elementId)
	{
		if($elementId === 'item-reviews'){
			$filename = '/elements/item-reviews/item-reviews.options.neon';
		}
		return $filename;
	}
	/********************** ELEMENTS - END **********/
	/************************************************/
	public static function getPluginUrl($path)
	{
		$url = plugins_url( $path , __FILE__ );
		return $url;
	}

	/* HELPERS */
	// php 5.3 json encode fix ...
	public static function raw_json_encode($input) {

		return preg_replace_callback(
			'/\\\\u([0-9a-zA-Z]{4})/',
			function ($matches) {
				return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');
			},
			json_encode($input)
		);

	}

	public static function getItemsIds($category)
	{
		$args = array(
			'post_type'      => 'ait-item',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'lang'           => AitLangs::getCurrentLanguageCode(),
			// 'tax_query' => array(
			//     'taxonomy' => 'ait-items',
			//     'field' => 'term_id',
			//     'terms' => $category
			// ),
			'fields' => 'ids'
		);

		$query = new Wp_Query($args);

		return $query->posts;
	}

	public static function update_1_22()
	{
		// this version adds new element and we need to clear cache in order to activate element
		if(class_exists('AitCache')){
			AitCache::clean();
		}
	}

	public static function getThemeOptions(){
		return (object)aitOptions()->getOptionsByType('theme');
	}

	public static function getPluginThemeOptions(){
		$themeOptions = AitItemReviews::getThemeOptions();
		return $themeOptions->itemReviews;
	}

	public static function getReviewQuestions($post_id){
		$pluginOptions = AitItemReviews::getPluginThemeOptions();

		$result = array(
			'question1' => AitLangs::getCurrentLocaleText($pluginOptions['question1']),
			'question2' => AitLangs::getCurrentLocaleText($pluginOptions['question2']),
			'question3' => AitLangs::getCurrentLocaleText($pluginOptions['question3']),
			'question4' => AitLangs::getCurrentLocaleText($pluginOptions['question4']),
			'question5' => AitLangs::getCurrentLocaleText($pluginOptions['question5']),
		);

		// questions category override
		$reviewMeta = get_post_meta($post_id, '_ait-item_review-options', true);
		if(!empty($reviewMeta)){
			$reviewQuestions = get_option("ait-reviews_category_".$reviewMeta['reviewCategory'], array());
			$result = array_merge($result, $reviewQuestions);
		}
		
		return $result;
	}

	public static function hasReviewQuestions($post_id, $questions = null){
		if(!is_array($questions)){
			// if we dont send the questions array load it .. this prevents duplicate request on the templates
			$questions = AitItemReviews::getReviewQuestions($post_id);
		}
		
		//return count(array_filter($questions, function($var){ return $var != ""; }, ARRAY_FILTER_USE_BOTH)) > 0 ? true : false; 
		return count(array_filter($questions, function($var){ return $var != ""; })) > 0 ? true : false; 
	}

	public static function getReviewCount($post_id){
		$rating_count_rated = AitItemReviews::getRatingCount($post_id);
		$rating_count_unrated = intval(get_post_meta($post_id, 'rating_count_unrated', true));
		return $rating_count_rated + $rating_count_unrated;
	}

	public static function getRatingCount($post_id){
		return intval(get_post_meta($post_id, 'rating_count', true));
	}

	public static function willRatingsDisplay($data = array()){
		$result = false;

		if(is_array($data) && !empty($data)){
			//$result = count(array_filter($data, function($var){ return $var->question != ""; }, ARRAY_FILTER_USE_BOTH)) > 0 ? true : false;
			$result = count(array_filter($data, function($var){ return $var->question != ""; })) > 0 ? true : false;
		}

		return $result;
	}
	/* HELPERS */

	/* AJAX */
	public static function ajaxPublishReview(){
		if(isset($_POST)){
			$ajaxData = $_POST['data'];

			// debug

			// get the rated post
			$item = get_post($ajaxData['rating-for']);

			// prepare new rating
			$review = array(
				'post_type'			=> 'ait-review',
				'post_title'		=> $ajaxData['rating-name'],
				'post_content'		=> $ajaxData['rating-desc'],
				'post_author'		=> $item->post_author,
				'post_status'		=> 'pending',
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed'
			);

			// create new rating
			$review_id = wp_insert_post($review);

			// update review post meta
			update_post_meta($review_id, 'post_id' , $item->ID);

			if(!empty($ajaxData['rating-values'])){
				if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
					update_post_meta($review_id, 'ratings' , json_encode($ajaxData['rating-values'], JSON_UNESCAPED_UNICODE));
				} else {
					update_post_meta($review_id, 'ratings' , AitItemReviews::raw_json_encode($ajaxData['rating-values']));
				}
			}

			// rating mean
			$rating_sum = 0;
			$rating_count = 0;
			if(!empty($ajaxData['rating-values'])){
				foreach($ajaxData['rating-values'] as $rating){
					$rating_sum += intval($rating['value']);
					$rating_count += 1;
				}
				$rating_mean = $rating_sum / $rating_count;
				update_post_meta($review_id, 'rating_mean' , $rating_mean);
				update_post_meta($review_id, 'rating_mean_rounded' , round($rating_mean));
			} else {
				// no star ratings
				update_post_meta($review_id, 'rating_mean' , 0);
				update_post_meta($review_id, 'rating_mean_rounded' , round(0));
			}

			$bulkMessage = sprintf(__('Review <a href="%s">#%d</a> for item <a href="%s">%s</a> is waiting for your moderation', 'ait-item-reviews'), admin_url('edit.php?post_type=ait-review'), $review_id, admin_url('post.php?post='.$item->ID.'&action=edit'), $item->post_title);
			AitItemReviews::notifyAdmin(__('Review pending moderation','ait-item-reviews'), $bulkMessage);

			/* Send Mail to item author when capabilityReviewsAuthorManage is set to true in package */
			$user = get_user_by("ID", $item->post_author);
			if($user->has_cap('ait_item_reviews_approve_review')){
				$bulkMessage = sprintf(__('Review <a href="%s">#%d</a> for item <a href="%s">%s</a> is waiting for your moderation', 'ait-item-reviews'), admin_url('edit.php?post_type=ait-review'), $review_id, admin_url('post.php?post='.$item->ID.'&action=edit'), $item->post_title);
				AitItemReviews::notifyOwner($user, __('Review pending moderation','ait-item-reviews'), $bulkMessage);
			}
			/* Send Mail to item author when capabilityReviewsAuthorManage is set to true in package */

			$response = array(
				'status' => '200',
				'message' => 'OK',
			);

			// normally, the script expects a json respone
			header( 'Content-Type: application/json; charset=utf-8' );
			echo json_encode( $response );
		}

		exit; // important
	}

	public static function ajaxLoadReviews(){
		if(isset($_POST)){
			$ajaxData = $_POST['data'];

			$query_args = array();
			if(isset($ajaxData['query'])){
				$query_args = $ajaxData['query'];

				if(isset($query_args['offset'])){
					$query_args['offset'] = intval($query_args['offset']);
				}
				if(isset($query_args['nopaging'])){
					$query_args['nopaging'] = filter_var($query_args['nopaging'], FILTER_VALIDATE_BOOLEAN);
				}
				if(isset($query_args['posts_per_page'])){
					$query_args['posts_per_page'] = intval($query_args['posts_per_page']);
				}
			}

			$options = (object)aitOptions()->getOptionsByType('theme');
			$post = get_post($ajaxData['post_id']);

			$query = AitItemReviews::getCurrentItemReviews($ajaxData['post_id'], $query_args);
			$html = "";

			if(count($query->posts) > 0){
				foreach($query->posts as $review){
					$rating_overall = get_post_meta($review->ID, 'rating_mean', true);
					$rating_data = (array)json_decode(get_post_meta($review->ID, 'ratings', true));

					$ratingsDisplayed = AitItemReviews::willRatingsDisplay($rating_data) ? 'ratings-shown' : 'ratings-hidden';

					$dateFormat = get_option('date_format');
					$timeFormat = get_option('time_format');

					$postTimestamp = strtotime($review->post_date);
					$postDate = date($dateFormat, $postTimestamp);
					$postTime = date($timeFormat, $postTimestamp);

					$template = '
					<div class="review-container review-ajax-loaded review-hidden {ratingsDisplayed}">
						<div class="review-info">
							<span class="review-name">'.$review->post_title.'</span>
							<span class="review-time">
								<span>'.$postDate.'</span>&nbsp;<span>'.$postTime.'</span>
							</span>
							{ratings}
						</div>
						<div class="content"><p>'.$review->post_content.'</p></div>
					</div>';

					$ratings = '';
					if(is_array($rating_data) && count($rating_data) > 0){
						$ratings = '<div class="review-stars"><span class="review-rating-overall" data-score="'.$rating_overall.'"></span><div class="review-ratings">{questions}</div></div>';
						$questions = '';
						foreach ($rating_data as $index => $rating) {
							$questions .= $rating->question != "" ? '<div class="review-rating"><span class="review-rating-question">'.$rating->question.'</span><span class="review-rating-stars" data-score="'.$rating->value.'"></span></div>' : '';
						}
						$ratings = str_replace('{questions}', $questions, $ratings);
					}

					$template = str_replace("{ratingsDisplayed}", $ratingsDisplayed, $template);
					$template = str_replace("{ratings}", $ratings, $template);

					$html .= $template;
				}
			}

			$response = array(
				'html' => $html,
				'raw' => $query->posts,
			);

			// normally, the script expects a json respone
			header( 'Content-Type: application/json; charset=utf-8' );
			echo json_encode( $response );
		}

		exit;
	}

	public static function ajaxGetReviews()
	{
		if(isset($_POST)){
			header( 'Content-Type: application/json; charset=utf-8' );

			// firstly get ids of items from current language
			// this is necessary because review doesn't have information about language
			$args = array(
				'post_type'      => 'ait-item',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'lang'           => AitLangs::getCurrentLanguageCode(),
				'fields'         => 'ids'
			);
			$query = new Wp_Query($args);
			$itemIds = $query->posts;

			$orderby = array();
			$metaQuery = array(
				'relation' => 'AND',
				'post_id_clause' => array(
					'key'     => 'post_id',
					'value'   => $itemIds,
					'compare' => 'IN',
				),
			);

			if (isset($_POST['requestData']['optionalArgs'])) {
				$optionalArgs = $_POST['requestData']['optionalArgs'];

				if ($optionalArgs['orderby'] == 'rating') {
					$metaQuery['rating_clause'] = array(
						'key'     => 'rating_mean',
					);
					$orderby['rating_clause'] = $optionalArgs['order'];
				} elseif ($optionalArgs['orderby'] == 'date') {
					$orderby['date'] = $optionalArgs['order'];
				} else {
					$orderby['rand'] = $optionalArgs['order'];
				}
			}

			$defaultArgs = array(
				'post_type'      => 'ait-review',
				'posts_per_page' => 1,
				'post_status'    => 'publish',
				'offset'         => 0,
				'meta_query'     => $metaQuery,
				'orderby'		 => $orderby
			);

			$reviews = array();
			$queryArgs = wp_parse_args( $_POST['requestData']['args'], $defaultArgs );
			$query = new WpLatteWpQuery($queryArgs);


			foreach (new WpLatteLoopIterator($query) as $post) {
				$itemId = get_post_meta($post->id, 'post_id', true);
				// $postLink = get_permalink($postId);
				$item = get_post($itemId);
				$rating = get_post_meta($post->id, 'rating_mean', true);
				$html = trim(self::renderLatteTemplate('review-container', array('post' => $post, 'item' => $item, 'rating' => $rating)));
				array_push($reviews, $html);
			}

			$result = array(
				'status'     => true,
				'reviews'     => $reviews,
				'request'    => $_POST,
				'foundPosts' => $query->found_posts,
			);

			echo json_encode( $result );
			exit();
		}
	}

	/* AJAX */
}