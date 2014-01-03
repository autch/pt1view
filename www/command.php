<?php

require_once '../boot.inc.php';

if(!defined('SIGTERM')) define('SIGTERM', 15);

$pt1 = new RecPT1();
$command = array();
$errors = array();

$action = empty($_REQUEST['action']) ? FALSE : trim($_REQUEST['action']);

switch($action) {
case 'udp':
    $upd = array();
    if(!empty($_REQUEST['device'])) $upd['device'] = trim($_REQUEST['device']);
    if(!empty($_REQUEST['addr'])) $upd['addr'] = trim($_REQUEST['addr']);
    if(!empty($_REQUEST['port'])) $upd['port'] = intval($_REQUEST['port']);
    if(!empty($_REQUEST['ch'])) $upd['ch'] = trim($_REQUEST['ch']);
    if(empty($_REQUEST['b25'])) $upd['b25'] = FALSE;
    if(empty($_REQUEST['strip'])) $upd['strip'] = FALSE;
      
    if(!preg_match('/^(192\.168\.0\.)|(127\.)|(224\.)/', $upd['addr'])) {
        $errors[] = "リモートアドレスがローカルサブネット内にありません。UDPストリーミングを拒否します。";
    } else {
        $sh_cmd = $pt1->buildForUDP($upd);
        $command[] = $sh_cmd;
        exec($sh_cmd);
    }
    break;
case 'tcp':
    $upd = array();
    if(!empty($_REQUEST['device'])) $upd['device'] = trim($_REQUEST['device']);
    if(!empty($_REQUEST['tcp_port'])) $upd['tcp_port'] = intval($_REQUEST['tcp_port']);
    if(!empty($_REQUEST['ch'])) $upd['ch'] = trim($_REQUEST['ch']);
    if(empty($_REQUEST['b25'])) $upd['b25'] = FALSE;
    if(empty($_REQUEST['strip'])) $upd['strip'] = FALSE;
          
    $sh_cmd = $pt1->buildForTCP($upd);
    $command[] = $sh_cmd;
    exec($sh_cmd);
    break;
case 'kill':
    $target_pid = 0;
    if(!empty($_REQUEST['pid'])) $target_pid = intval($_REQUEST['pid']);

    $processes = RecPT1::findRecPT1();
    foreach($processes as $proc) {
        if($proc['pid'] === $target_pid) {
            $command[] = sprintf("kill -SIGTERM %d", $proc['pid']);
            posix_kill($proc['pid'], SIGTERM);
            if(function_exists("pcntl_waitpid") == TRUE)
                pcntl_waitpid($proc['pid'], $wait_status, 0);
            else
                sleep(1);
        }
    }
    break;
case 'change':
    $target_pid = 0;
    $ch = 0;
    if(!empty($_REQUEST['pid'])) $target_pid = intval($_REQUEST['pid']);
    if(!empty($_REQUEST['ch'])) $ch = trim($_REQUEST['ch']);

    $processes = RecPT1::findRecPT1();
    foreach($processes as $proc) {
        if($proc['pid'] === $target_pid) {
            $cmd = sprintf("%s --pid %d --channel %s", RECPT1CTL_PATH, intval($proc['pid']), $ch);
            $sh_cmd = sprintf("sh -c '%s >/dev/null 2>&1 &'", $cmd);
            $command[] = $sh_cmd;
            exec($sh_cmd);
        }
    }
    break;
case 'default':
    break;
default:
    header("Status: 400 Bad Request");
    die("Invalid action");
}

header("Content-type: application/json; charset=UTF-8");

$result = array(
    "commands" => $command,
    "errors" => $errors,
    "defaults" => RecPT1::getDefault()
    );

$jp = new JSONP();
$jp->begin();
echo json_encode($result, JSON_HEX_APOS | JSON_HEX_QUOT);
$jp->end();
