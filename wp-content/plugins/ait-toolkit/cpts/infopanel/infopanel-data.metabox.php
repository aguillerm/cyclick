<?php

return array(
	'title' => array(
		'label'   => __('Title', 'ait-toolkit'),
		'type'    => 'text',
		'default' => '',
	),

	'description' => array(
		'label'   => __('Description', 'ait-toolkit'),
		'type'    => 'text',
		'default' => '',
	),
	'data' => array(
		'label' => __('Data', 'ait-toolkit'),
		'type'  => 'clone',
		'max'   => 10,
		'default' => array(),
		'items' => array(
			'name' => array(
				'label' => __('Name', 'ait-toolkit'),
				'type' => 'text',
				'default' => '',
			),
			'value' => array(
				'label' => __('Value', 'ait-toolkit'),
				'type' => 'text',
				'default' => '',
			),
		),
	),
);
