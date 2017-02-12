<?php

return array(
	'position' => array(
		'label' => _x('Position', 'job position', 'ait-toolkit'),
		'type'  => 'text',
		'help'  => __('Position of member in the company, community, etc', 'ait-toolkit'),
	),

	'about' => array(
		'label' => __('About member', 'ait-toolkit'),
		'type'  => 'textarea',
		'rows'  => 8,
		'help'  => __('Information about member', 'ait-toolkit'),
	),

	'icons' => array(
		'label' => __('Social Icons', 'ait-toolkit'),
		'type' => 'clone',
		'max' => 10,
		'default' => array(),
		'items' => array(
			'title' => array(
				'label' => __('Title', 'ait-toolkit'),
				'type'  => 'text',
				'help'  => __('Social icon title', 'ait-toolkit'),
			),
			'image' => array(
				'label' => __('Image', 'ait-toolkit'),
				'type'  => 'image',
				'help'  => __('Social icon image', 'ait-toolkit'),
			),
			'url' => array(
				'label' => __('Link', 'ait-toolkit'),
				'type'  => 'url',
				'help'  => __('Social icon link, use valid URL format with http://', 'ait-toolkit'),
			),
		),
		'help' => __('Add new Social Icon by click on "+ Add New Item" link, or remove existing Social Icon by click on red cross. Click on "Remove All Items" link to remove all existing Social Icons.', 'ait-toolkit'),
	),
);
