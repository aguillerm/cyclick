<?php


define('AIT_REVSLIDER_ENABLED', true);
define('AIT_REVSLIDER_PACKAGE', 'developer');



add_action('plugins_loaded', 'aitRevsliderOverrides');

function aitRevsliderOverrides()
{

	if(get_option('revslider-valid') === 'false'){
		update_option('revslider-valid', 'true');
	}
}

