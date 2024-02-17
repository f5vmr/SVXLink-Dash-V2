<?php
// site_template.php
// template for adding a new page directly under main directory of this site
require_once('site_config.php');

$pgtitle = "Title of the Page";
include_once(DIR_SITE_VIEWS . 'site_header_begin.php');

// custom css/javascript goes here
include_once(DIR_SITE_VIEWS . 'site_header_end.php');
?>
<?php
// main content of the page goes here
?>
<?php
include_once(DIR_SITE_VIEWS . 'site_footer.php');
?>