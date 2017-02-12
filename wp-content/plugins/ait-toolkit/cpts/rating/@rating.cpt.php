<?

return array(

	'public' => true,

	'cpt' => array(
		'labels' => array(
			'name'          => _x('Ratings', 'post type general name', 'ait-toolkit'),
			'singular_name' => _x('Rating', 'post type singular name', 'ait-toolkit'),
			'menu_name'     => _x('Ratings', 'post type menu name', 'ait-toolkit'),
		),
		'args' => array(
			'supports' => array(
				'title',
				'editor',
				'page-attributes',
			),
		),
	),

	'metaboxes' => array(
		'rating-data' => array(
			'title' => _x('Rating Options', 'custom metabox title', 'ait-toolkit'),
			'config' => 'rating-data',
		),
	),
);
