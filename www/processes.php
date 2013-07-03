<?php

require_once '../boot.inc.php';

$proesses = RecPT1::findRecPT1();

header("Content-Type: application/json; charset=UTF-8");

$jp = new JSONP();
$jp->begin();
echo json_encode($proesses, JSON_HEX_APOS | JSON_HEX_QUOT);
$jp->end();
