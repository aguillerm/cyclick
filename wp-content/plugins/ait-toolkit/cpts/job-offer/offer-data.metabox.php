<?php

return array(

	'skills' => array(
		'label' => __('Skills', 'ait-toolkit'),
		'type' => 'editor',
		'default' => '',
		'settings' => array(
			'textarea_rows' => 5,
		),
		'help' => __('Main text of offer', 'ait-toolkit'),
	),

	'validFrom' => array(
		'label'  => __('Date From', 'ait-toolkit'),
		'type'   => 'date',
		'format' => 'D, d M yy',
		'help'   => __('Starting date of offer validity', 'ait-toolkit'),
	),

	'validTo' => array(
		'label'  => __('Date To', 'ait-toolkit'),
		'type'   => 'date',
		'format' => 'D, d M yy',
		'help'   => __('Ending date of offer validity', 'ait-toolkit'),
	),

	'contactName' => array(
		'label'   => __('Contact Person', 'ait-toolkit'),
		'type'    => 'text',
		'default' => '',
		'help'    => __('Text displayed as contact person', 'ait-toolkit'),
	),

	'contactMail' => array(
		'label'   => __('Contact Email', 'ait-toolkit'),
		'type'    => 'text',
		'default' => '',
		'help'    => __('Text displayed as contact email', 'ait-toolkit'),
	),

	'contactPhone' => array(
		'label'   => __('Contact Phone', 'ait-toolkit'),
		'type'    => 'text',
		'default' => '',
		'help'    => __('Text displayed as contact phone', 'ait-toolkit'),
	),
);
