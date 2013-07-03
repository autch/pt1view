<?php

require_once '../boot.inc.php';

$pt1 = RecPT1::getDefault();

$smarty = new TNSmarty();
$smarty->assign('pt1', $pt1);
$smarty->display(isset($_REQUEST['m']) ? 'm.tpl' : "index.tpl");
