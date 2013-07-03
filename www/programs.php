<?php

require_once '../boot.inc.php';

$db = new EpgrecDB();

$channels = $db->selectPrograms();

header("Content-Type: application/json; charset=UTF-8");

$jp = new JSONP();
$jp->begin();
echo json_encode($channels, JSON_HEX_APOS | JSON_HEX_QUOT);
$jp->end();

