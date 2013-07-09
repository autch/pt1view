<?php
/* bootloader */

ini_set("display_errors", 1);
ini_set("log_errors", 1);

require_once(dirname(__FILE__) . '/config.inc.php');

$path = explode(PATH_SEPARATOR, ini_get('include_path'));
array_unshift($path, TNST_LIB);
array_unshift($path, EPGREC_PATH);
ini_set('include_path', implode(PATH_SEPARATOR, $path));

require_once 'Settings.class.php';
require_once 'epgrec.inc.php';
require_once 'recpt1.inc.php';
require_once 'jsonp.inc.php';

