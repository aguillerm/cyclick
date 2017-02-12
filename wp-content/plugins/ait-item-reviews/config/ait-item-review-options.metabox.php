<?php

return array(
	'reviewCategory' => array(
		'label' 	=> __('Review Category', 'ait-item-reviews'),
		'type'		=> 'categories',
		'taxonomy'	=> 'ait-reviews',
		'args' => array(
			'hide_empty' => false,
			'show_count' => false,
		),		
		'default'	=> '0',
	),
);