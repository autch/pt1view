<?php

require_once '../boot.inc.php';

$pt1 = get_default_pt1_params();

$smarty = new TNSmarty();
$smarty->assign('pt1', $pt1);
$smarty->display("index.tpl");
