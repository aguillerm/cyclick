<?php

return array(

	'teamAName' => array(
		'label'   => __('Team A', 'ait-toolkit'),
		'type'    => 'text',
		'default' => '',
	),

	'teamBName' => array(
		'label'   => __('Team B', 'ait-toolkit'),
		'type'    => 'text',
		'default' => '',
	),

	'date' => array(
		'label'   => __('Date', 'ait-toolkit'),
		'type'    => 'date',
		'format'  => 'D, d M yy',
		'default' => '',
	),

	'description' => array(
		'label'   => __('Description', 'ait-toolkit'),
		'type'    => 'textarea',
		'default' => '',
	),


	'teamAfinalScore' => array(
		'label'   => __('Final Score for Team A', 'ait-toolkit'),
		'type'    => 'text',
		'default' => '',
	),

	'teamBfinalScore' => array(
		'label'   => __('Final Score for Team B', 'ait-toolkit'),
		'type'    => 'text',
		'default' => '',
	),

	'link' => array(
		'label'   => __('Link', 'ait-toolkit'),
		'type'    => 'url',
		'default' => '',
	),

	'scores' => array(
		'label' => __('Scores', 'ait-toolkit'),
		'type' => 'clone',
		'max' => 25,
		'default' => array(),
		'items' => array(
			'title' => array(
				'label' => __('Title', 'ait-toolkit'),
				'type'  => 'text',
			),
			'scoreA' => array(
				'label' => __('Score A', 'ait-toolkit'),
				'type'  => 'text',
			),
			'scoreB' => array(
				'label' => __('Score B', 'ait-toolkit'),
				'type'  => 'text',
			),
		),
	),
);
