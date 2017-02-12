<?php

return array(
	'public' => true,


	'cpt' => array(
		'labels' => array(
			'name'               => _x('Job Offers', 'post type general name', 'ait-toolkit'),
			'singular_name'      => _x('Job Offer', 'post type singular name', 'ait-toolkit'),
			'menu_name'          => _x('Job Offers', 'post type menu name', 'ait-toolkit'),
			'add_new'            => _x('Add New', 'job offer', 'ait-toolkit'),
			'add_new_item'       => __('Add New Job Offer', 'ait-toolkit'),
			'edit_item'          => __('Edit Job Offer', 'ait-toolkit'),
			'new_item'           => __('New Job Offer', 'ait-toolkit'),
			'view_item'          => __('View Job Offer', 'ait-toolkit'),
			'search_items'       => __('Search Job Offers', 'ait-toolkit'),
			'not_found'          => __('No Job Offers found', 'ait-toolkit'),
			'not_found_in_trash' => __('No Job Offers found in Trash', 'ait-toolkit'),
			'all_items'          => __('All Job Offers', 'ait-toolkit'),
			'enterTitleHere'     => __('Enter job offer title here', 'ait-toolkit'),
		),

		'args' => array(
			'supports' => array(
				'title',
				'editor',
				'excerpt',
				'page-attributes',
				'thumbnail',
				'comments',
			),
			'capabilities' => array(
				'edit_post'              => 'ait_toolkit_job-offer_edit_post',
				'read_post'              => 'ait_toolkit_job-offer_read_post',
				'delete_post'            => 'ait_toolkit_job-offer_delete_post',
				'edit_posts'             => 'ait_toolkit_job-offer_edit_posts',
				'edit_others_posts'      => 'ait_toolkit_job-offer_edit_others_posts',
				'publish_posts'          => 'ait_toolkit_job-offer_publish_posts',
				'read_private_posts'     => 'ait_toolkit_job-offer_read_private_posts',
				'read'                   => 'ait_toolkit_job-offer_read_posts',
				'delete_posts'           => 'ait_toolkit_job-offer_delete_posts',
				'delete_private_posts'   => 'ait_toolkit_job-offer_delete_private_posts',
				'delete_published_posts' => 'ait_toolkit_job-offer_delete_published_posts',
				'delete_others_posts'    => 'ait_toolkit_job-offer_delete_others_posts',
				'edit_private_posts'     => 'ait_toolkit_job-offer_edit_private_posts',
				'edit_published_posts'   => 'ait_toolkit_job-offer_edit_published_posts',
			),
		),
	),

	'taxonomies' => array(
		'offers' => array(
			'labels' => array(
				'name'              => _x('Job Offers Categories', 'taxonomy general name', 'ait-toolkit'),
				'menu_name'         => _x('Categories', 'taxonomy menu name', 'ait-toolkit'),
				'singular_name'     => _x('Category', 'taxonomy singular name', 'ait-toolkit'),
				'search_items'      => __('Search Categories', 'ait-toolkit'),
				'all_items'         => __('All Categories', 'ait-toolkit'),
				'parent_item'       => __('Parent Category', 'ait-toolkit'),
				'parent_item_colon' => __('Parent Cateogry:', 'ait-toolkit'),
				'edit_item'         => __('Edit Category', 'ait-toolkit'),
				'view_item'         => __('View Category', 'ait-toolkit'),
				'update_item'       => __('Update Category', 'ait-toolkit'),
				'add_new_item'      => __('Add New Category', 'ait-toolkit'),
				'new_item_name'     => __('New Category Name', 'ait-toolkit'),
			),
		),
	),


	'metaboxes' => array(
		'offer-data' => array(
			'title' => _x('Job Offer Options', 'custom metabox title', 'ait-toolkit'),
			'config' => 'offer-data',
		),
	),
);
