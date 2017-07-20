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

				'maxShownReviews' => array(
					'label'		=> __('Maximum shown reviews', 'ait-item-reviews'),
					'type'		=> 'select',
					'selected'	=> '2',
					'default'	=> array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'all' => __('All', 'ait-item-reviews'),
					),
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
			'maxShownReviews' => '2',
			'question1' => 'Price',
			'question2' => 'Location',
			'question3' => 'Staff',
			'question4' => 'Services',
			'question5' => 'Food',
		)
	)
);
