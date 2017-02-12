<?php

return array(
	'public' => true,

	'cpt' => array(
		'labels' => array(
			'name'               => _x('Events', 'post type general name', 'ait-toolkit'),
			'singular_name'      => _x('Event', 'post type singular name', 'ait-toolkit'),
			'menu_name'          => _x('Events', 'post type menu name', 'ait-toolkit'),
			'add_new'            => _x('Add New', 'Event', 'ait-toolkit'),
			'add_new_item'       => __('Add New Event', 'ait-toolkit'),
			'edit_item'          => __('Edit Event', 'ait-toolkit'),
			'new_item'           => __('New Event', 'ait-toolkit'),
			'view_item'          => __('View Event', 'ait-toolkit'),
			'search_items'       => __('Search Events', 'ait-toolkit'),
			'not_found'          => __('No Events found', 'ait-toolkit'),
			'not_found_in_trash' => __('No Events found in Trash', 'ait-toolkit'),
			'all_items'          => __('All Events', 'ait-toolkit'),
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
				'edit_post'              => 'ait_toolkit_event_edit_post',
				'read_post'              => 'ait_toolkit_event_read_post',
				'delete_post'            => 'ait_toolkit_event_delete_post',
				'edit_posts'             => 'ait_toolkit_event_edit_posts',
				'edit_others_posts'      => 'ait_toolkit_event_edit_others_posts',
				'publish_posts'          => 'ait_toolkit_event_publish_posts',
				'read_private_posts'     => 'ait_toolkit_event_read_private_posts',
				'read'                   => 'ait_toolkit_event_read_posts',
				'delete_posts'           => 'ait_toolkit_event_delete_posts',
				'delete_private_posts'   => 'ait_toolkit_event_delete_private_posts',
				'delete_published_posts' => 'ait_toolkit_event_delete_published_posts',
				'delete_others_posts'    => 'ait_toolkit_event_delete_others_posts',
				'edit_private_posts'     => 'ait_toolkit_event_edit_private_posts',
				'edit_published_posts'   => 'ait_toolkit_event_edit_published_posts',
			),
		),
	),

	'taxonomies' => array(
		'events' => array(
			'labels' => array(
				'name'              => _x('Events Categories', 'taxonomy general name', 'ait-toolkit'),
				'menu_name'         => _x('Categories', 'taxonomy menu name', 'ait-toolkit'),
				'singular_name'     => _x('Category', 'taxonomy singular name', 'ait-toolkit'),
				'search_items'      => __('Search Categories', 'ait-toolkit'),
				'all_items'         => __('All Categories', 'ait-toolkit'),
				'parent_item'       => __('Parent Category', 'ait-toolkit'),
				'parent_item_colon' => __('Parent Category:', 'ait-toolkit'),
				'edit_item'         => __('Edit Category', 'ait-toolkit'),
				'view_item'         => __('View Category', 'ait-toolkit'),
				'update_item'       => __('Update Category', 'ait-toolkit'),
				'add_new_item'      => __('Add New Category', 'ait-toolkit'),
				'new_item_name'     => __('New Category Name', 'ait-toolkit'),
			),
		),
	),

	'metaboxes' => array(
		'event-data' => array(
			'title'  => _x('Event Options', 'custom metabox title', 'ait-toolkit'),
			'config' => 'event-data',
		)
	),


	'featuredImageMetabox' => array(
		'labels' => array(
			'title'           => _x('Event Image', 'featured image metabox', 'ait-toolkit'),
			'linkSetTitle'    => _x('Set Event Image', 'featured image metabox', 'ait-toolkit'),
			'linkRemoveTitle' => _x('Remove Event Image', 'featured image metabox', 'ait-toolkit'),
		),
		'context' => 'normal',
		'priority' => 'default',
	),
);
