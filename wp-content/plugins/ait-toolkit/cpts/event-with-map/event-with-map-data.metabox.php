<?php

return array(
	'dateFrom' => array(
		'label'  => __('Date From', 'ait-toolkit'),
		'type'   => 'date',
		'format' => 'D, d M yy',
		'help'   => __('Starting date of event', 'ait-toolkit'),
	),
	'dateTo' => array(
		'label'  => __('Date To', 'ait-toolkit'),
		'type'   => 'date',
		'format' => 'D, d M yy',
		'help'   => __('Ending date of event', 'ait-toolkit'),
	),
	'location' => array(
		'label' => __('Location', 'ait-toolkit'),
		'type'  => 'text',
		'help'  => __('Location of event', 'ait-toolkit'),
	),
);
