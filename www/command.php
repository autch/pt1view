<?php

require_once '../boot.inc.php';

if(!defined('SIGTERM')) define('SIGTERM', 15);

$pt1 = get_default_pt1_params();
$command = array();
$errors = array();

$action = empty($_REQUEST['action']) ? FALSE : trim($_REQUEST['action']);

switch($action) {
case 'udp':
    if(!empty($_REQUEST['device'])) $pt1['device'] = trim($_REQUEST['device']);
    if(!empty($_REQUEST['addr'])) $pt1['addr'] = trim($_REQUEST['addr']);
    if(!empty($_REQUEST['port'])) $pt1['port'] = intval($_REQUEST['port']);
    if(!empty($_REQUEST['ch'])) $pt1['ch'] = intval($_REQUEST['ch']);
    if(empty($_REQUEST['b25'])) $pt1['b25'] = FALSE;
    if(empty($_REQUEST['strip'])) $pt1['strip'] = FALSE;
      
    if(!preg_match('/^(192\.168\.0\.)|(127\.)|(224\.)/', $pt1['addr'])) {
        $errors[] = "リモートアドレスがローカルサブネット内にありません。UDPストリーミングを拒否します。";
    } else {
        $sh_cmd = build_recpt1_for_udp($pt1);
        $command[] = $sh_cmd;
        system($sh_cmd);
    }
    break;
case 'tcp':
    if(!empty($_REQUEST['device'])) $pt1['device'] = trim($_REQUEST['device']);
    if(!empty($_REQUEST['tcp_port'])) $pt1['tcp_port'] = intval($_REQUEST['tcp_port']);
    if(!empty($_REQUEST['ch'])) $pt1['ch'] = intval($_REQUEST['ch']);
    if(empty($_REQUEST['b25'])) $pt1['b25'] = FALSE;
    if(empty($_REQUEST['strip'])) $pt1['strip'] = FALSE;
          
    $sh_cmd = build_recpt1_for_tcp($pt1);
    $command[] = $sh_cmd;
    system($sh_cmd);
    break;
case 'kill':
    $target_pid = 0;
    if(!empty($_REQUEST['pid'])) $target_pid = intval($_REQUEST['pid']);

    $processes = find_recpt1_process();
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
    if(!empty($_REQUEST['ch'])) $ch = intval($_REQUEST['ch']);

    $processes = find_recpt1_process();
    foreach($processes as $proc) {
        if($proc['pid'] === $target_pid) {
            $cmd = sprintf("%s --pid %d --channel %d", RECPT1CTL_PATH, intval($proc['pid']), intval($ch));
            $sh_cmd = sprintf("sh -c '%s >/dev/null 2>&1 &'", $cmd);
            $command[] = $sh_cmd;
            system($sh_cmd);
        }
    }
    break;
default:
    header("Status: 400 Bad Request");
    die("Invalid action");
}

header("Content-type: application/json");

$result = array(
    "commands" => $command,
    "errors" => $errors,
    );

$cb = get_jsonp_callback();
echo jsonp_begin($cb);
echo json_encode($result, JSON_HEX_APOS | JSON_HEX_QUOT);
echo jsonp_end($cb);
