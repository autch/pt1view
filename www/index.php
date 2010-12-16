<?php

require_once '../boot.inc.php';

if(!defined('SIGTERM')) define('SIGTERM', 15);

$pt1 = array(
  'device' => NULL,
  'addr' => $_SERVER['REMOTE_ADDR'],
  'port' => 1234,
  'tcp_port' => 11234,
  'b25' => TRUE,
  'strip' => TRUE
);
$command = array();
$errors = array();

if(!empty($_POST))
{
  if(!empty($_POST['udp']))
  {
    if(!empty($_POST['device'])) $pt1['device'] = trim($_POST['device']);
    if(!empty($_POST['addr'])) $pt1['addr'] = trim($_POST['addr']);
    if(!empty($_POST['port'])) $pt1['port'] = intval($_POST['port']);
    if(!empty($_POST['ch'])) $pt1['ch'] = intval($_POST['ch']);
    if(empty($_POST['b25'])) $pt1['b25'] = FALSE;
    if(empty($_POST['strip'])) $pt1['strip'] = FALSE;
      
    if(!preg_match('/^(192\.168\.0\.)|(127\.)/', $pt1['addr']))
    {
      $errors[] = "リモートアドレスがローカルサブネット内にありません。ストリーミングを拒否します。";
    }
    else
    {
      $sh_cmd = build_recpt1_for_udp($pt1);
      $command[] = $sh_cmd;
      system($sh_cmd);
    }
  }
  if(!empty($_POST['tcp']))
  {
    if(!empty($_POST['device'])) $pt1['device'] = trim($_POST['device']);
    if(!empty($_POST['tcp_port'])) $pt1['tcp_port'] = intval($_POST['tcp_port']);
    if(!empty($_POST['ch'])) $pt1['ch'] = intval($_POST['ch']);
    if(empty($_POST['b25'])) $pt1['b25'] = FALSE;
    if(empty($_POST['strip'])) $pt1['strip'] = FALSE;
          
    $sh_cmd = build_recpt1_for_tcp($pt1);
    $command[] = $sh_cmd;
    system($sh_cmd);
  }

  if(!empty($_POST['kill']))
  {
    $target_pid = 0;
    if(!empty($_POST['pid'])) $target_pid = intval($_POST['pid']);

    $processes = find_recpt1_process();
    foreach($processes as $proc)
    {
      if($proc['pid'] === $target_pid)
      {
        $command[] = sprintf("kill -SIGTERM %d", $proc['pid']);
        posix_kill($proc['pid'], SIGTERM);
        if(function_exists("pcntl_waitpid") == TRUE)
          pcntl_waitpid($proc['pid'], $wait_status, 0);
        else
          sleep(1);
      }
    }
  }

  if(!empty($_POST['change']))
  {
    $target_pid = 0;
    $ch = 0;
    if(!empty($_POST['pid'])) $target_pid = intval($_POST['pid']);
    if(!empty($_POST['ch'])) $ch = intval($_POST['ch']);

    $processes = find_recpt1_process();
    foreach($processes as $proc)
    {
      if($proc['pid'] === $target_pid)
      {
        $cmd = sprintf("/usr/local/bin/recpt1ctl --pid %d --channel %d", intval($proc['pid']), intval($ch));
        $sh_cmd = sprintf("sh -c '%s >/dev/null 2>&1 &'", $cmd);
        $command[] = $sh_cmd;
        system($sh_cmd);
      }
    }
  }
}

$config = get_epgrec_config();
$db = open_epgrec_db($config);
if(!$db) die("Cannot open database");

$sql = sprintf("SELECT c.channel, c.channel_disc, c.name, p.title, p.description, "
	       ."  UNIX_TIMESTAMP(p.starttime) starttime, UNIX_TIMESTAMP(p.endtime) endtime, t.name_jp "
	       ."FROM %1\$sprogramTbl p, %1\$schannelTbl c, %1\$scategoryTbl t "
	       ."WHERE p.channel_disc = c.channel_disc AND p.category_id = t.id "
	       ."AND c.type = 'GR' AND c.skip = 0 AND p.starttime <= now() AND p.endtime >= now() "
	       ."ORDER BY c.id ASC",
	       $config->tbl_prefix);

$channels = array();

if(($result = $db->query($sql)) !== FALSE)
{
  while(($row = $result->fetch_array()) !== NULL)
  {
    $channels[] = $row;
  }
  
  $result->close();
}
else
{
  echo $db->error();
}

$phys_channels = select_channel_list($db, $config);
$db->close();

// reload process activities
$proesses = find_recpt1_process();

$smarty = new TNSmarty();
$smarty->assign('errors', $errors);
$smarty->assign('command', $command);
$smarty->assign('processes', $proesses);
$smarty->assign('channels', $channels);
$smarty->assign('phys_channels', $phys_channels);
$smarty->assign('pt1', $pt1);
$smarty->display("index.tpl");
