<?php

return array(
	'public' => 'true',

	'cpt' => array(
		'labels' => array(
			'name'               => _x('Obituaries', 'post type general name', 'ait-toolkit'),
			'singular_name'      => _x('Obituary', 'post type singular name', 'ait-toolkit'),
			'menu_name'          => _x('Obituaries', 'post type menu name', 'ait-toolkit'),
			'add_new'            => _x('Add New', 'Obituary', 'ait-toolkit'),
			'add_new_item'       => __('Add New Obituary', 'ait-toolkit'),
			'edit_item'          => __('Edit Obituary', 'ait-toolkit'),
			'new_item'           => __('New Obituary', 'ait-toolkit'),
			'view_item'          => __('View Obituary', 'ait-toolkit'),
			'search_items'       => __('Search Obituaries', 'ait-toolkit'),
			'not_found'          => __('No Obituaries found', 'ait-toolkit'),
			'not_found_in_trash' => __('No Obituaries found in Trash', 'ait-toolkit'),
			'all_items'          => __('All Obituaries', 'ait-toolkit'),
		),

		'args' => array(
			'supports' => array(
				'title',
				'thumbnail',
				'editor',
				'page-attributes',
				'excerpt',
				'comments',
			),
			'capabilities' => array(
				'edit_post'              => 'ait_toolkit_obituary_edit_post',
				'read_post'              => 'ait_toolkit_obituary_read_post',
				'delete_post'            => 'ait_toolkit_obituary_delete_post',
				'edit_posts'             => 'ait_toolkit_obituary_edit_posts',
				'edit_others_posts'      => 'ait_toolkit_obituary_edit_others_posts',
				'publish_posts'          => 'ait_toolkit_obituary_publish_posts',
				'read_private_posts'     => 'ait_toolkit_obituary_read_private_posts',
				'read'                   => 'ait_toolkit_obituary_read_posts',
				'delete_posts'           => 'ait_toolkit_obituary_delete_posts',
				'delete_private_posts'   => 'ait_toolkit_obituary_delete_private_posts',
				'delete_published_posts' => 'ait_toolkit_obituary_delete_published_posts',
				'delete_others_posts'    => 'ait_toolkit_obituary_delete_others_posts',
				'edit_private_posts'     => 'ait_toolkit_obituary_edit_private_posts',
				'edit_published_posts'   => 'ait_toolkit_obituary_edit_published_posts',
			),
		),
	),


	'metaboxes' => array(
		'obituary-data' => array(
			'title'  => _x('Obituary Options', 'custom metabox title', 'ait-toolkit'),
			'config' => 'obituary-data',
		),
	),

	'featuredImageMetabox' => array(
		'labels' => array(
			'title'           => _x('Obituary Image', 'featured image metabox', 'ait-toolkit'),
			'linkSetTitle'    => _x('Set Obituary Image', 'featured image metabox', 'ait-toolkit'),
			'linkRemoveTitle' => _x('Remove Obituary Image', 'featured image metabox', 'ait-toolkit'),
		),
		'context' => 'normal',
		'priority' => 'default',
	),
);
