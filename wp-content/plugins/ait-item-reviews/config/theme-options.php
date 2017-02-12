<?php

return array(
	'raw' => array(
		'itemReviews' => array(
			'title' => __('Item Reviews', 'ait-item-reviews'),
			'options' => array(
				'notifications' => array(
					'label'		=> __('Email notifications', 'ait-item-reviews'),
					'type'		=> 'on-off',
					'default'	=> true,
				),
				'question1' => array(
					'label' 	=> __('Question 1', 'ait-item-reviews'),
					'type'		=> 'text',
					'default'	=> 'Price',
				),
				'question2' => array(
					'label' 	=> __('Question 2', 'ait-item-reviews'),
					'type'		=> 'text',
					'default'	=> 'Location',
				),
				'question3' => array(
					'label' 	=> __('Question 3', 'ait-item-reviews'),
					'type'		=> 'text',
					'default'	=> 'Staff',
				),
				'question4' => array(
					'label' 	=> __('Question 4', 'ait-item-reviews'),
					'type'		=> 'text',
					'default'	=> 'Services',
				),
				'question5' => array(
					'label' 	=> __('Question 5', 'ait-item-reviews'),
					'type'		=> 'text',
					'default'	=> 'Food',
				),

			),
		),
	),
	'defaults' => array(
		'itemReviews' => array(
			'notifications'	=> true,
			'maxShownReviews' => 1,
			'question1' => 'Price',
			'question2' => 'Location',
			'question3' => 'Staff',
			'question4' => 'Services',
			'question5' => 'Food',
		)
	)
);