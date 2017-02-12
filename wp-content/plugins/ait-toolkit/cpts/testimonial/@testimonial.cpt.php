<?php

return array(

	'cpt' => array(
		'labels' => array(
			'name'               => _x('Testimonials', 'post type general name', 'ait-toolkit'),
			'singular_name'      => _x('Testimonial', 'post type singular name', 'ait-toolkit'),
			'menu_name'          => _x('Testimonials', 'post type menu name', 'ait-toolkit'),
			'add_new'            => _x('Add New', 'testimonial', 'ait-toolkit'),
			'add_new_item'       => __('Add New Testimonial', 'ait-toolkit'),
			'edit_item'          => __('Edit Testimonial', 'ait-toolkit'),
			'new_item'           => __('New Testimonial', 'ait-toolkit'),
			'view_item'          => __('View Testimonial', 'ait-toolkit'),
			'search_items'       => __('Search Testimonials', 'ait-toolkit'),
			'not_found'          => __('No Testimonials found', 'ait-toolkit'),
			'not_found_in_trash' => __('No Testimonials found in Trash', 'ait-toolkit'),
			'all_items'          => __('All Testimonials', 'ait-toolkit'),
		),
		'args' => array(
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'page-attributes',
			),
			'capabilities' => array(
				'edit_post'              => 'ait_toolkit_testimonial_edit_post',
				'read_post'              => 'ait_toolkit_testimonial_read_post',
				'delete_post'            => 'ait_toolkit_testimonial_delete_post',
				'edit_posts'             => 'ait_toolkit_testimonial_edit_posts',
				'edit_others_posts'      => 'ait_toolkit_testimonial_edit_others_posts',
				'publish_posts'          => 'ait_toolkit_testimonial_publish_posts',
				'read_private_posts'     => 'ait_toolkit_testimonial_read_private_posts',
				'read'                   => 'ait_toolkit_testimonial_read_posts',
				'delete_posts'           => 'ait_toolkit_testimonial_delete_posts',
				'delete_private_posts'   => 'ait_toolkit_testimonial_delete_private_posts',
				'delete_published_posts' => 'ait_toolkit_testimonial_delete_published_posts',
				'delete_others_posts'    => 'ait_toolkit_testimonial_delete_others_posts',
				'edit_private_posts'     => 'ait_toolkit_testimonial_edit_private_posts',
				'edit_published_posts'   => 'ait_toolkit_testimonial_edit_published_posts',
			),
		),
	),

	'taxonomies' => array(
		'testimonials' => array(
			'labels' => array(
				'name'              => _x('Testimonials Categories', 'taxonomy general name', 'ait-toolkit'),
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
		'testimonial-options' => array(
			'title'  => _x('Testimonial Options', 'custom metabox title', 'ait-toolkit'),
			'config' => 'testimonial-options',
		),
	),

	'featuredImageMetabox' => array(
		'labels' => array(
			'title'           => _x('Testimonial Icon', 'featured image metabox', 'ait-toolkit'),
			'linkSetTitle'    => _x('Set Testimonial Icon', 'featured image metabox', 'ait-toolkit'),
			'linkRemoveTitle' => _x('Remove Testimonial Icon', 'featured image metabox', 'ait-toolkit'),
		),
	),
);
