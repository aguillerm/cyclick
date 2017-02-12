<?php

return array(
	'public' => true,

	'cpt' => array(
		'labels' => array(
			'name'               => _x('Portfolio Items', 'post type general name', 'ait-toolkit'),
			'singular_name'      => _x('Portfolio Item', 'post type singular name', 'ait-toolkit'),
			'menu_name'          => _x('Portfolio', 'post type menu name', 'ait-toolkit'),
			'add_new'            => _x('Add New', 'portfolio item', 'ait-toolkit'),
			'add_new_item'       => __('Add New Portfolio Item', 'ait-toolkit'),
			'edit_item'          => __('Edit Portfolio Item', 'ait-toolkit'),
			'new_item'           => __('New Portfolio Item', 'ait-toolkit'),
			'view_item'          => __('View Portfolio Item', 'ait-toolkit'),
			'search_items'       => __('Search Portfolio Items', 'ait-toolkit'),
			'not_found'          => __('No Portfolio Items found', 'ait-toolkit'),
			'not_found_in_trash' => __('No Portfolio Items found in Trash', 'ait-toolkit'),
			'all_items'          => __('All Portfolio Items', 'ait-toolkit'),
		),

		'args' => array(
			'supports' => array(
				'title',
				'thumbnail',
				'editor',
				'excerpt',
				'page-attributes',
				'comments',
			),
			'capabilities' => array(
				'edit_post'              => 'ait_toolkit_portfolio-item_edit_post',
				'read_post'              => 'ait_toolkit_portfolio-item_read_post',
				'delete_post'            => 'ait_toolkit_portfolio-item_delete_post',
				'edit_posts'             => 'ait_toolkit_portfolio-item_edit_posts',
				'edit_others_posts'      => 'ait_toolkit_portfolio-item_edit_others_posts',
				'publish_posts'          => 'ait_toolkit_portfolio-item_publish_posts',
				'read_private_posts'     => 'ait_toolkit_portfolio-item_read_private_posts',
				'read'                   => 'ait_toolkit_portfolio-item_read_posts',
				'delete_posts'           => 'ait_toolkit_portfolio-item_delete_posts',
				'delete_private_posts'   => 'ait_toolkit_portfolio-item_delete_private_posts',
				'delete_published_posts' => 'ait_toolkit_portfolio-item_delete_published_posts',
				'delete_others_posts'    => 'ait_toolkit_portfolio-item_delete_others_posts',
				'edit_private_posts'     => 'ait_toolkit_portfolio-item_edit_private_posts',
				'edit_published_posts'   => 'ait_toolkit_portfolio-item_edit_published_posts',
			),
		),
	),

	'taxonomies' => array(
		'portfolios' => array(
			'labels' => array(
				'name'              => _x('Portfolios', 'taxonomy general name', 'ait-toolkit'),
				'menu_name'         => _x('Portfolios', 'taxonomy menu name', 'ait-toolkit'),
				'singular_name'     => _x('Portfolio', 'taxonomy singular name', 'ait-toolkit'),
				'search_items'      => __('Search Portfolios', 'ait-toolkit'),
				'all_items'         => __('All Portfolios', 'ait-toolkit'),
				'parent_item'       => __('Parent Portfolio', 'ait-toolkit'),
				'parent_item_colon' => __('Parent Cateogry:', 'ait-toolkit'),
				'edit_item'         => __('Edit Portfolio', 'ait-toolkit'),
				'view_item'         => __('View Portfolio', 'ait-toolkit'),
				'update_item'       => __('Update Portfolio', 'ait-toolkit'),
				'add_new_item'      => __('Add New Portfolio', 'ait-toolkit'),
				'new_item_name'     => __('New Portfolio Name', 'ait-toolkit'),
			),
		),
	),


	'metaboxes' => array(
		'portfolio-item' => array(
			'title'  => _x('Portfolio Item Options', 'custom metabox title', 'ait-toolkit'),
			'config' => 'portfolio-item',
		),
	),


	'featuredImageMetabox' => array(
		'labels' => array(
			'title'           => _x('Large Image', 'featured image metabox', 'ait-toolkit'),
			'linkSetTitle'    => _x('Select Large Image', 'featured image metabox', 'ait-toolkit'),
			'linkRemoveTitle' => _x('Remove Large Image', 'featured image metabox', 'ait-toolkit'),
		),
		'context' => 'normal',
		'priority' => 'high',
	),
);
