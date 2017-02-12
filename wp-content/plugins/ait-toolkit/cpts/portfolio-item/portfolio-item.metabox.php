<?php

return array(

	'type' => array(
		'label'   => __('Type', 'ait-toolkit'),
		'type'    => 'radio',
		'checked' => 'image',
		'default' => array(
			'image'   => __('Large Image', 'ait-toolkit'),
			'website' => __('Website', 'ait-toolkit'),
			'video'   => __('Video', 'ait-toolkit'),
		),
		'help' => __('Select type of item', 'ait-toolkit'),
	),

	'cropFromPosition' => array(
		'label'    => __('Crop From Position', 'ait-toolkit'),
		'type'     => 'select',
		'selected' => 'center,center',
		'default'  => array(
			'top,left'      => __('top left', 'ait-toolkit'),
			'top,center'    => __('top center', 'ait-toolkit'),
			'top,right'     => __('top right', 'ait-toolkit'),
			'center,center' => __('center center', 'ait-toolkit'),
			'bottom,left'   => __('bottom left', 'ait-toolkit'),
			'bottom,center' => __('bottom center', 'ait-toolkit'),
			'bottom,right'  => __('bottom right', 'ait-toolkit'),
		),
		'help' => __('Anchor point of the source image, used when cropping image', 'ait-toolkit'),
	),

	array('section' => array('id' => 'website')),


	'websiteUrl' => array(
		'label' => __('Link To Website', 'ait-toolkit'),
		'type'  => 'url',
		'help'  => __('Link to website used with Website type, use valid URL format with http://', 'ait-toolkit'),
	),

	'linkTarget' => array(
		'label'   => __('Open In New Window', 'ait-toolkit'),
		'type'    => 'on-off',
		'default' => 'on',
		'help'    => __('Open links in new window or tab', 'ait-toolkit'),
	),


	array('section' => array('id' => 'video')),


	'videoUrl' => array(
		'label' => __('Link To Video', 'ait-toolkit'),
		'type'  => 'url',
		'help'  => __('Link to video used with Video type, use valid URL format with http://', 'ait-toolkit'),
	),

	'videoRatio' => array(
		'label'    => __('Video Format', 'ait-toolkit'),
		'type'     => 'select',
		'selected' => '1:1',
		'default' => array(
			'1:1'  => '1:1',
			'2:1'  => '2:1',
			'4:3'  => '4:3',
			'16:9' => '16:9',
		),
		'help' => __('Select format of video', 'ait-toolkit'),
	),

	'informations' => array(
		'label' => __('Information', 'ait-toolkit'),
		'type' => 'clone',
		'max' => 10,
		'help' => '',
		'items' => array(
			'title' => array(
				'label' => __('Title', 'ait-toolkit'),
				'type' => 'text',
				'help' => '',
			),
			'description' => array(
				'label' => __('Description', 'ait-toolkit'),
				'type' => 'text',
				'help' => '',
			),
		),
		'default' => array(),
	),

	'pictures' => array(
		'label' => __('Item gallery', 'ait-toolkit'),
		'type'  => 'clone',
		'max'   => 99,
		'items' => array(
			'title' => array(
				'label' => __('Title', 'ait-toolkit'),
				'type'  => 'text',
				'help'  => '',
			),
			'image' => array(
				'label'   => __('Image', 'ait-toolkit'),
				'type'    => 'image',
				'defualt' => '',
			),
			'link' => array(
				'label'   => __('Link', 'ait-toolkit'),
				'type'    => 'url',
				'default' => '',
			),
		),
		'default' => array(),
	),
);
