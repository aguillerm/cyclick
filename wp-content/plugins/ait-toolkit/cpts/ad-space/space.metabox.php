<?php

return array(
	'image' => array(
		'label'   => __('Image', 'ait-toolkit'),
		'type'    => 'image',
		'default' => '',
		'help'    => __('URL of image displayed in advertising space, use valid URL format with http://', 'ait-toolkit'),
	),
	'content' => array(
		'label'    => __('Description', 'ait-toolkit'),
		'type'     => 'editor',
		'default'  => '',
		'settings' => array(),
		'help'     => __('Text displayed in advertising space', 'ait-toolkit'),
	),
	'link' => array(
		'label'   => __('Link', 'ait-toolkit'),
		'type'    => 'url',
		'default' => '',
		'help'    => __('Link used for image in advertising space', 'ait-toolkit'),
	),
);
