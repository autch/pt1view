<?php

require_once '../boot.inc.php';

$proesses = find_recpt1_process();

header("Content-Type: application/json");

$cb = get_jsonp_callback();
echo jsonp_begin($cb);
echo json_encode($proesses, JSON_HEX_APOS | JSON_HEX_QUOT);
echo jsonp_end($cb);
