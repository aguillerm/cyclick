<?php

return array(
	'link' => array(
		'label'   => __('Link', 'ait-toolkit'),
		'type'    => 'url',
		'default' => '',
		'help'    => __('URL of image link, use valid URL format with http://', 'ait-toolkit'),
	),
	'linkTarget' => array(
		'label'   => __('Open In New Window', 'ait-toolkit'),
		'type'    => 'on-off',
		'default' => false,
		'help'    => __('Open links in new window or tab', 'ait-toolkit'),
	),
);
