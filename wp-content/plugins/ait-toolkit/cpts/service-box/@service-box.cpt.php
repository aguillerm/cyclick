<?php

return array(

	'cpt' => array(
		'labels' => array(
			'name'               => _x('Services', 'post type general name', 'ait-toolkit'),
			'singular_name'      => _x('Service', 'post type singular name', 'ait-toolkit'),
			'menu_name'          => _x('Services', 'post type menu name', 'ait-toolkit'),
			'add_new'            => _x('Add New', 'service', 'ait-toolkit'),
			'add_new_item'       => __('Add New Service', 'ait-toolkit'),
			'edit_item'          => __('Edit Service', 'ait-toolkit'),
			'new_item'           => __('New Service', 'ait-toolkit'),
			'view_item'          => __('View Service', 'ait-toolkit'),
			'search_items'       => __('Search Services', 'ait-toolkit'),
			'not_found'          => __('No Services found', 'ait-toolkit'),
			'not_found_in_trash' => __('No Services found in Trash', 'ait-toolkit'),
			'all_items'          => __('All Services', 'ait-toolkit'),
		),
		'args' => array(
			'supports' => array(
				'title',
				'page-attributes',
			),
			'capabilities' => array(
				'edit_post'              => 'ait_toolkit_service-box_edit_post',
				'read_post'              => 'ait_toolkit_service-box_read_post',
				'delete_post'            => 'ait_toolkit_service-box_delete_post',
				'edit_posts'             => 'ait_toolkit_service-box_edit_posts',
				'edit_others_posts'      => 'ait_toolkit_service-box_edit_others_posts',
				'publish_posts'          => 'ait_toolkit_service-box_publish_posts',
				'read_private_posts'     => 'ait_toolkit_service-box_read_private_posts',
				'read'                   => 'ait_toolkit_service-box_read_posts',
				'delete_posts'           => 'ait_toolkit_service-box_delete_posts',
				'delete_private_posts'   => 'ait_toolkit_service-box_delete_private_posts',
				'delete_published_posts' => 'ait_toolkit_service-box_delete_published_posts',
				'delete_others_posts'    => 'ait_toolkit_service-box_delete_others_posts',
				'edit_private_posts'     => 'ait_toolkit_service-box_edit_private_posts',
				'edit_published_posts'   => 'ait_toolkit_service-box_edit_published_posts',
			),
		),
	),

	'taxonomies' => array(
		'boxes' => array(
			'labels' => array(
				'name'              => _x('Services Categories', 'taxonomy general name', 'ait-toolkit'),
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
		'box-data' => array(
			'title' => _x('Service Options', 'custom metabox title', 'ait-toolkit'),
			'config' => 'box-data',
		),
	),
);
