<?php

return array(
	'author' => array(
		'label'   => __('Author', 'ait-toolkit'),
		'type'    => 'text',
		'default' => '',
		'help'    => __('Text displayed as author name', 'ait-toolkit'),
	),

	'rating' => array(
		'label'   => __('Rating', 'ait-toolkit'),
		'type'    => 'range',
		'min'     => 0,
		'max'     => 100,
		'step'    => 5,
		'default' => 0,
	),
);