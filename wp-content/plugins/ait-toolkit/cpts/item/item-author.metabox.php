<?php

return array(
	'author' => array(
		'label'        => __('Author', 'ait-toolkit'),
		'type'         => 'select-dynamic',
		'dataFunction' => 'AitItemCpt::fillAuthorMetabox',
		'default'      => array(),
		'capabilities' => true,
	),
);