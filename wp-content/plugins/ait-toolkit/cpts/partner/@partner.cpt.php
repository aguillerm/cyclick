<?php

return array(
	'translatable' => false,

	'cpt' => array(
		'labels' => array(
			'name'               => _x('Partners', 'post type general name', 'ait-toolkit'),
			'singular_name'      => _x('Partner', 'post type singular name', 'ait-toolkit'),
			'menu_name'          => _x('Partners', 'post type menu name', 'ait-toolkit'),
			'add_new'            => _x('Add New', 'partner', 'ait-toolkit'),
			'add_new_item'       => __('Add New Partner', 'ait-toolkit'),
			'edit_item'          => __('Edit Partner', 'ait-toolkit'),
			'new_item'           => __('New Partner', 'ait-toolkit'),
			'view_item'          => __('View Partner', 'ait-toolkit'),
			'search_items'       => __('Search Partners', 'ait-toolkit'),
			'not_found'          => __('No Partners found', 'ait-toolkit'),
			'not_found_in_trash' => __('No Partners found in Trash', 'ait-toolkit'),
			'all_items'          => __('All Partners', 'ait-toolkit'),
		),

		'args' => array(
			'supports' => array(
				'title',
				'thumbnail',
				'page-attributes',
			),
			'capabilities' => array(
				'edit_post'              => 'ait_toolkit_partner_edit_post',
				'read_post'              => 'ait_toolkit_partner_read_post',
				'delete_post'            => 'ait_toolkit_partner_delete_post',
				'edit_posts'             => 'ait_toolkit_partner_edit_posts',
				'edit_others_posts'      => 'ait_toolkit_partner_edit_others_posts',
				'publish_posts'          => 'ait_toolkit_partner_publish_posts',
				'read_private_posts'     => 'ait_toolkit_partner_read_private_posts',
				'read'                   => 'ait_toolkit_partner_read_posts',
				'delete_posts'           => 'ait_toolkit_partner_delete_posts',
				'delete_private_posts'   => 'ait_toolkit_partner_delete_private_posts',
				'delete_published_posts' => 'ait_toolkit_partner_delete_published_posts',
				'delete_others_posts'    => 'ait_toolkit_partner_delete_others_posts',
				'edit_private_posts'     => 'ait_toolkit_partner_edit_private_posts',
				'edit_published_posts'   => 'ait_toolkit_partner_edit_published_posts',
			),
		),
	),

	'taxonomies' => array(
		'partners' => array(
			'labels' => array(
				'name'              => _x('Partners Categories', 'taxonomy general name', 'ait-toolkit'),
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
		'partner-options' => array(
			'title'  => _x('Partner Options', 'custom metabox title', 'ait-toolkit'),
			'config' => 'partner-options',
		),
	),

	'featuredImageMetabox' => array(
		'labels' => array(
			'title'           => _x('Partner Logo', 'featured image metabox', 'ait-toolkit'),
			'linkSetTitle'    => _x('Set Partner Logo', 'featured image metabox', 'ait-toolkit'),
			'linkRemoveTitle' => _x('Remove Partner Image', 'featured image metabox', 'ait-toolkit'),
		),
		'context' => 'normal',
		'priority' => 'high',
	),
);
