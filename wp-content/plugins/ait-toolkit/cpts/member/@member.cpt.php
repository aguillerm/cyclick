<?php

return array(
	'cpt' => array(
		'labels' => array(
			'name'               => _x('Members', 'post type general name', 'ait-toolkit'),
			'singular_name'      => _x('Member', 'post type singular name', 'ait-toolkit'),
			'menu_name'          => _x('Members', 'post type menu name', 'ait-toolkit'),
			'add_new'            => _x('Add New', 'member', 'ait-toolkit'),
			'add_new_item'       => __('Add New Member', 'ait-toolkit'),
			'edit_item'          => __('Edit Member', 'ait-toolkit'),
			'new_item'           => __('New Member', 'ait-toolkit'),
			'view_item'          => __('View Member', 'ait-toolkit'),
			'search_items'       => __('Search Members', 'ait-toolkit'),
			'not_found'          => __('No Members found', 'ait-toolkit'),
			'not_found_in_trash' => __('No Members found in Trash', 'ait-toolkit'),
			'all_items'          => __('All Members', 'ait-toolkit'),
			'enterTitleHere'     => __('Enter member\'s name here', 'ait-toolkit'),
		),

		'args' => array(
			'supports' => array(
				'title',
				'thumbnail',
				'page-attributes',
			),
			'capabilities' => array(
				'edit_post'              => 'ait_toolkit_member_edit_post',
				'read_post'              => 'ait_toolkit_member_read_post',
				'delete_post'            => 'ait_toolkit_member_delete_post',
				'edit_posts'             => 'ait_toolkit_member_edit_posts',
				'edit_others_posts'      => 'ait_toolkit_member_edit_others_posts',
				'publish_posts'          => 'ait_toolkit_member_publish_posts',
				'read_private_posts'     => 'ait_toolkit_member_read_private_posts',
				'read'                   => 'ait_toolkit_member_read_posts',
				'delete_posts'           => 'ait_toolkit_member_delete_posts',
				'delete_private_posts'   => 'ait_toolkit_member_delete_private_posts',
				'delete_published_posts' => 'ait_toolkit_member_delete_published_posts',
				'delete_others_posts'    => 'ait_toolkit_member_delete_others_posts',
				'edit_private_posts'     => 'ait_toolkit_member_edit_private_posts',
				'edit_published_posts'   => 'ait_toolkit_member_edit_published_posts',
			),
		),
	),

	'taxonomies' => array(
		'members' => array(
			'labels' => array(
				'name'              => _x('Members Categories', 'taxonomy general name', 'ait-toolkit'),
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
		'member' => array(
			'title'  => _x('Member\'s Additional Info', 'custom metabox title', 'ait-toolkit'),
			'config' => 'member',
		),
	),


	'featuredImageMetabox' => array(
		'labels' => array(
			'title'           => _x('Member\'s Photo', 'featured image metabox', 'ait-toolkit'),
			'linkSetTitle'    => _x('Set Member\'s Photo', 'featured image metabox', 'ait-toolkit'),
			'linkRemoveTitle' => _x('Remove Member\'s Photo', 'featured image metabox', 'ait-toolkit'),
		),
	),
);