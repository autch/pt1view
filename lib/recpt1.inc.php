<?php

function build_recpt1_args($pt1)
{
  $args = array('/usr/local/bin/recpt1');
  if($pt1['b25'])
    $args[] = '--b25';
  if($pt1['b25'] && $pt1['strip'])
    $args[] = '--strip';
  if(!empty($pt1['udp']))
    $args = array_merge($args, array('--udp',
                                     '--addr', escapeshellarg($pt1['addr']),
                                     '--port', escapeshellarg($pt1['port'])));
  if(!empty($pt1['device']))
    $args = array_merge($args, array('--device', escapeshellarg($pt1['device'])));
  $args = array_merge($args, array(escapeshellarg($pt1['ch']), '-'));
  if(!empty($pt1['output']))
    $args[] = escapeshellarg($pt1['output']);
  else
    $args[] = "/dev/null";

  return join(' ', $args);
}

function build_recpt1_for_udp($pt1)
{
  $pt1['udp'] = TRUE;
  $cmd = build_recpt1_args($pt1);
  $sh_cmd = sprintf("sh -c '%s >/dev/null 2>&1 &'", $cmd);
  return $sh_cmd;
}

function build_recpt1_for_tcp($pt1)
{
  $pt1['output'] = '-';
  $cmd = build_recpt1_args($pt1);
  $sh_cmd = sprintf("/usr/local/bin/tsserv -p %d -- %s", $pt1['tcp_port'], $cmd);
  return $sh_cmd;
}

function find_recpt1_process()
{
  $cmd = sprintf("LANG=ja_JP.UTF-8 ps -C recpt1 -o ruser=,pid=,ppid=,args=");
  $output = shell_exec($cmd);
  $lines = explode("\n", $output);

  $procs = array();
  foreach($lines as $l)
  {
    if(trim($l) === '') continue;
    list($user, $pid, $ppid, $args) = preg_split('/\s+/', $l, 4);
    $proc = array('user' => $user, 'pid' => intval($pid), 'ppid' => intval($ppid), 'args' => $args);
    $proc['tsserv'] = find_tsserv_process($proc);
    $procs[] = $proc;
  }

  return $procs;
}

function find_tsserv_process($recpt1_proc)
{
  $cmd = sprintf("LANG=ja_JP.UTF-8 ps -C tsserv -o ruser=,pid=,ppid=,args=");
  $output = shell_exec($cmd);
  $lines = explode("\n", $output);

  foreach($lines as $l)
  {
    if(trim($l) === '') continue;
    list($user, $pid, $ppid, $tsserv, $args) = preg_split('/\s+/', $l, 5);
    if($recpt1_proc['ppid'] == intval($pid))
    {
      preg_match('/^(.*)\s+--\s+(.*)$/', $args, $matches);
      list($tsserv_args, $recpt1_args) = $matches;

      if(preg_match('/-p\s+(\d+)/', $tsserv_args, $matches))
        $port = intval($matches[1]);
      else if(preg_match('/--port(\s+|=)(\d+)/', $tsserv_args, $matches))
        $port = intval($matches[2]);

      $proc = array('user' => $user, 'pid' => intval($pid), 'ppid' => intval($ppid),
                    'tsserv' => $tsserv, 'port' => intval($port), 'args' => $recpt1_args);
      return $proc;
    }
  }

  return FALSE;
}
