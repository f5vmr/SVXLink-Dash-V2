<?php

define('DIR_BASE', dirname( dirname(__FILE__) ));
define('DIR_CLASSES', DIR_BASE . '/classes/');

// this is specific to the site
define('DIR_SITE', DIR_BASE . '/yoursite/');
define('SITE_NAME', 'yoursite');

// these would be specific to this site only
define('DIR_SITE_VIEWS', DIR_SITE . 'views/' );
define('DIR_SITE_COMPONENTS', DIR_SITE . 'components/');

define('BASE_URL', 'http://'.$_SERVER['SERVER_NAME']);
define('SITE_URL', BASE_URL . '/yoursite');

// static content for the site
define('DIR_STATIC', SITE_URL . '/static');
define('DIR_JS', DIR_STATIC . '/js');
define('DIR_CSS', DIR_STATIC . '/css');
define('DIR_SITE_IMG', SITE_URL . '/images');
?>