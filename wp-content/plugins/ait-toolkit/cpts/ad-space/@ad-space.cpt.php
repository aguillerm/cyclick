<?php

return array(
	'cpt' => array(
		'labels' => array(
			'name'               => _x('Advertisements', 'post type general name', 'ait-toolkit'),
			'singular_name'      => _x('Advertisement', 'post type singular name', 'ait-toolkit'),
			'menu_name'          => _x('Advertisements', 'post type menu name', 'ait-toolkit'),
			'add_new'            => _x('Add New', 'Advertisement', 'ait-toolkit'),
			'add_new_item'       => __('Add New Advertisement', 'ait-toolkit'),
			'edit_item'          => __('Edit Advertisement', 'ait-toolkit'),
			'new_item'           => __('New Advertisement', 'ait-toolkit'),
			'view_item'          => __('View Advertisement', 'ait-toolkit'),
			'search_items'       => __('Search Advertisements', 'ait-toolkit'),
			'not_found'          => __('No Advertisements found', 'ait-toolkit'),
			'not_found_in_trash' => __('No Advertisements found in Trash', 'ait-toolkit'),
			'all_items'          => __('All Advertisements', 'ait-toolkit'),
		),
		'args' => array(
			'supports' => array(
				'title',
				'page-attributes',
			),
			'capabilities' => array(
				'edit_post'              => 'ait_toolkit_ad-space_edit_post',
				'read_post'              => 'ait_toolkit_ad-space_read_post',
				'delete_post'            => 'ait_toolkit_ad-space_delete_post',
				'edit_posts'             => 'ait_toolkit_ad-space_edit_posts',
				'edit_others_posts'      => 'ait_toolkit_ad-space_edit_others_posts',
				'publish_posts'          => 'ait_toolkit_ad-space_publish_posts',
				'read_private_posts'     => 'ait_toolkit_ad-space_read_private_posts',
				'read'                   => 'ait_toolkit_ad-space_read_posts',
				'delete_posts'           => 'ait_toolkit_ad-space_delete_posts',
				'delete_private_posts'   => 'ait_toolkit_ad-space_delete_private_posts',
				'delete_published_posts' => 'ait_toolkit_ad-space_delete_published_posts',
				'delete_others_posts'    => 'ait_toolkit_ad-space_delete_others_posts',
				'edit_private_posts'     => 'ait_toolkit_ad-space_edit_private_posts',
				'edit_published_posts'   => 'ait_toolkit_ad-space_edit_published_posts',
			),
		),
	),

	'taxonomies' => array(
		'spaces' => array(
			'labels' => array(
				'name'              => _x('Advertisements Categories', 'taxonomy general name', 'ait-toolkit'),
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
		'space' => array(
			'title' => _x('Advertisement Options', 'custom metabox title', 'ait-toolkit'),
			'config' => 'space',
		),
	),
);
