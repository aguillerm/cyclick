<?php

return array(

	'location' => array(
		'label' => __('Location', 'ait-toolkit'),
		'type' => 'multimarker-map',
		'default' => array(
			'address' => '',
		),
		'related' => 'checkpoints',
	),

	'checkpoints' => array(
		'label' => __('Checkpoints', 'ait-toolkit'),
		'type' => 'clone',
		'max' => 'infinity',
		'default' => array(),
		'items' => array(
			'title' => array(
				'label' => __('Title', 'ait-toolkit'),
				'type' => 'text',
			),
			'desc' => array(
				'label' => __('Description', 'ait-toolkit'),
				'type' => 'multiline-code',
			),
			'lat' => array(
				'type' => 'hidden',
				'default' => '',
			),
			'lng' => array(
				'type' => 'hidden',
				'default' => '',
			),
			'icon' => array(
				'label' => __('Checkpoint Icon', 'ait-toolkit'),
				'type' => 'image',
				'default' => '/design/img/tour-pin.png',
			),
			'align' => array(
				'label' => __('Align', 'ait-toolkit'),
				'type' => 'select',
				'selected' => 'center',
				'default' => array(
					'center' => __('Center', 'ait-toolkit'),
					'bottom' => __('Bottom', 'ait-toolkit'),
				),
				'help' => __('Set alignment for icons ("center" for round symetric icons and "bottom" for pins or flags)', 'ait-toolkit'),
			),
		),
	),

	'strokeColor' => array(
		'label'    => __('Path Color', 'ait-toolkit'),
		'type'     => 'color',
		'default'  => '#fdbc3f',
		'required' => true,
	),

	'showAllMarkers' => array(
		'label'   => __('Display All Checkpoints', 'ait-toolkit'),
		'type'    => 'on-off',
		'default' => 'false',
		'help'    => __('Checkpoints without Title and Description will not be displayed on the map', 'ait-toolkit'),
	),


	array('section' => array('title' => __('Additional information', 'ait-toolkit'))),

	'startDate' => array(
		'label'  => __('Date From', 'ait-toolkit'),
		'type'   => 'date',
		'format' => 'D, d M yy',
		'help'   => __('Starting date for tour', 'ait-toolkit'),
	),

	'endDate' => array(
		'label'  => __('Date To', 'ait-toolkit'),
		'type'   => 'date',
		'format' => 'D, d M yy',
		'help'   => __('Ending date for tour', 'ait-toolkit'),
	),

	'difficulty' => array(
		'label'   => __('Difficulty', 'ait-toolkit'),
		'type'    => 'range',
		'min'     => 0,
		'max'     => 10,
		'step'    => 1,
		'default' => 0,
		'help'    => __('Set 0 to ignore this option', 'ait-toolkit'),
	),

	'difficultyTitle' => array(
		'label'   => __('Difficulty Title', 'ait-toolkit'),
		'type'    => 'code',
		'default' => __('Difficulty for this tour',  'ait-toolkit'),
	),


	'details' => array(
		'label' => __('Additional Information', 'ait-toolkit'),
		'type' => 'clone',
		'max' => '20',
		'help' => __('Add custom additional information', 'ait-toolkit'),
		'default' => array(),
		'items' => array(
			'title' => array(
				'label' => __('Title', 'ait-toolkit'),
				'type' => 'text',
			),
			'value' => array(
				'label' => __('Value', 'ait-toolkit'),
				'type' => 'text'
			),
			'desc' => array(
				'label' => __('Description', 'ait-toolkit'),
				'type' => 'multiline-code',
			),
		),
	),

	array('section' => array('title' => __('Galleries', 'ait-toolkit'))),

	'videoGallery' => array(
		'label'   => __('Video Gallery', 'ait-toolkit'),
		'type'    => 'clone',
		'max'     => 'infinity',
		'default' => array(),
		'items' => array(
			'type' => array(
				'label' => __('Type', 'ait-toolkit'),
				'type'  => 'select',
				'help'  => __('Select video source', 'ait-toolkit'),
				'default' => array(
					'youtube' => 'YouTube',
					'vimeo'   => 'Vimeo',
				),
			),
			'link' => array(
				'label'   => __('Link', 'ait-toolkit'),
				'type'    => 'url',
				'default' => '',
				'help'    => __('URL of video displayed on page, use valid URL format with http://', 'ait-toolkit'),
			),
			'description' => array(
				'label'   => __('Description', 'ait-toolkit'),
				'type'    => 'multiline-string',
				'default' => '',
			),
		),
	),

	'gallery' => array(
		'label' => __('Picture Gallery', 'ait-toolkit'),
		'type'  => 'clone',
		'max'   => 'infinity',
		'items' => array(
			'title' => array(
				'label' => __('Title', 'ait-toolkit'),
				'type' => 'text'
			),
			'image' => array(
				'label' => __('Image', 'ait-toolkit'),
				'type' => 'image'
			),
		),
		'default' => array(),
	),
);
