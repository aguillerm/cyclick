<?php

return array(
	'cpt' => array(
		'labels' => array(
			'name'               =>  _x('Questions and Answers', 'post type general name', 'ait-toolkit'),
			'singular_name'      =>  _x('Question and Answer', 'post type singular name', 'ait-toolkit'),
			'menu_name'          =>  _x('FAQ', 'post type menu name', 'ait-toolkit'),
			'add_new'            =>  _x('Add New', 'question and answer', 'ait-toolkit'),
			'add_new_item'       =>  __('Add New Question and Answer', 'ait-toolkit'),
			'edit_item'          =>  __('Edit Question and Answer', 'ait-toolkit'),
			'new_item'           =>  __('New Question and Answer', 'ait-toolkit'),
			'view_item'          =>  __('View Question and Answer', 'ait-toolkit'),
			'search_items'       =>  __('Search Questions and Answers', 'ait-toolkit'),
			'not_found'          =>  __('No Questions and Answers found', 'ait-toolkit'),
			'not_found_in_trash' =>  __('No Questions and Answers found in Trash', 'ait-toolkit'),
			'all_items'          =>  __('All Questions and Answers', 'ait-toolkit'),
			'enterTitleHere'     =>  __('Enter descriptive title for this question here', 'ait-toolkit'),
		),

		'args' => array(
			'supports' => array(
				'title',
				'page-attributes',
			),
			'capabilities' => array(
				'edit_post'              =>  'ait_toolkit_faq_edit_post',
				'read_post'              =>  'ait_toolkit_faq_read_post',
				'delete_post'            =>  'ait_toolkit_faq_delete_post',
				'edit_posts'             =>  'ait_toolkit_faq_edit_posts',
				'edit_others_posts'      =>  'ait_toolkit_faq_edit_others_posts',
				'publish_posts'          =>  'ait_toolkit_faq_publish_posts',
				'read_private_posts'     =>  'ait_toolkit_faq_read_private_posts',
				'read'                   =>  'ait_toolkit_faq_read_posts',
				'delete_posts'           =>  'ait_toolkit_faq_delete_posts',
				'delete_private_posts'   =>  'ait_toolkit_faq_delete_private_posts',
				'delete_published_posts' =>  'ait_toolkit_faq_delete_published_posts',
				'delete_others_posts'    =>  'ait_toolkit_faq_delete_others_posts',
				'edit_private_posts'     =>  'ait_toolkit_faq_edit_private_posts',
				'edit_published_posts'   =>  'ait_toolkit_faq_edit_published_posts',
			),
		),
	),

	'taxonomies' => array(
		'faqs' => array(
			'labels' => array(
				'name'              =>  _x('FAQ Categories', 'taxonomy general name', 'ait-toolkit'),
				'menu_name'         =>  _x('Categories', 'taxonomy menu name', 'ait-toolkit'),
				'singular_name'     =>  _x('Category', 'taxonomy singular name', 'ait-toolkit'),
				'search_items'      =>  __('Search Categories', 'ait-toolkit'),
				'all_items'         =>  __('All Categories', 'ait-toolkit'),
				'parent_item'       =>  __('Parent Category', 'ait-toolkit'),
				'parent_item_colon' =>  __('Parent Cateogry:', 'ait-toolkit'),
				'edit_item'         =>  __('Edit Category', 'ait-toolkit'),
				'view_item'         =>  __('View Category', 'ait-toolkit'),
				'update_item'       =>  __('Update Category', 'ait-toolkit'),
				'add_new_item'      =>  __('Add New Category', 'ait-toolkit'),
				'new_item_name'     =>  __('New Category Name', 'ait-toolkit'),
			),
		),
	),

	'metaboxes' => array(
		'faq-data' => array(
			'title'  =>  _x('Question and Answer', 'custom metabox title', 'ait-toolkit'),
			'config' =>  'faq-data',
		),
	),
);
