<?php

require_once '../boot.inc.php';

if(empty($_GET['t'])) die('invalid t= param');

switch($_GET['t'])
{
  case 'proc':
    $result = RecPT1::findRecPT1();
    break;
  case 'prog':
    $db = new EpgrecDB();
    $result = $db->selectPrograms();
    break;
  default:
    die('invalid t= param');
}

header("Content-Type: application/json; charset=UTF-8");

$jp = new JSONP();
$jp->begin();
echo json_encode($result, JSON_UNESCAPED_UNICODE);
$jp->end();

exit();

