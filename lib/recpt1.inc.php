<?php

class RecPT1
{
    private $pt1 = array();

    public function __construct()
    {
        $this->pt1 = self::getDefault();
    }
    
    public static function getDefault()
    {
        return array(
            'device' => NULL,
            'addr' => $_SERVER['REMOTE_ADDR'],
            'port' => 1234,
            'tcp_port' => 11234,
            'b25' => TRUE,
            'strip' => TRUE
            );
    }

    private function buildArgs()
    {
        $args = array(RECPT1_PATH);
        if($this->pt1['b25'])
            $args[] = '--b25';
        if($this->pt1['b25'] && $this->pt1['strip'])
            $args[] = '--strip';
        if(!empty($this->pt1['udp']))
            $args = array_merge($args, array('--udp',
                                             '--addr', escapeshellarg($this->pt1['addr']),
                                             '--port', escapeshellarg($this->pt1['port'])));
        if(!empty($this->pt1['device']))
            $args = array_merge($args, array('--device', escapeshellarg($this->pt1['device'])));
        $args = array_merge($args, array(escapeshellarg($this->pt1['ch']), '-'));
        if(!empty($this->pt1['output']))
            $args[] = escapeshellarg($this->pt1['output']);
        else
            $args[] = "/dev/null";
        
        return join(' ', $args);
    }

    public function update($upd)
    {
        $this->pt1 = array_merge($this->pt1, $upd);
    }

    public function buildForUDP($upd = array())
    {
        $this->update($upd);
        $this->pt1['udp'] = TRUE;
        $cmd = $this->buildArgs();
        $sh_cmd = sprintf("sh -c '%s >/dev/null 2>&1 &'", $cmd);
        return $sh_cmd;
    }

    public function buildForTCP($upd = array())
    {
        $this->update($upd);
        $this->pt1['output'] = '-';
        $cmd = $this->buildArgs();
        $sh_cmd = sprintf("%s -p %d -- %s", TSSERV_PATH, $this->pt1['tcp_port'], $cmd);
        return $sh_cmd;
    }

    public static function findRecPT1()
    {
        $cmd = sprintf("LANG=ja_JP.UTF-8 ps -C recpt1 -o ruser=,pid=,ppid=,args=");
        $output = shell_exec($cmd);
        $lines = explode("\n", $output);

        $procs = array();
        foreach($lines as $l) {
            if(trim($l) === '') continue;
            list($user, $pid, $ppid, $args) = preg_split('/\s+/', $l, 4);
            $proc = array('user' => $user,
                          'pid' => intval($pid),
                          'ppid' => intval($ppid),
                          'args' => $args);
            $proc['tsserv'] = self::findTSServ($proc);
            $procs[] = $proc;
        }

        return $procs;
    }

    public static function findTSServ($recpt1_proc)
    {
        $cmd = sprintf("LANG=ja_JP.UTF-8 ps -C tsserv -o ruser=,pid=,ppid=,args=");
        $output = shell_exec($cmd);
        $lines = explode("\n", $output);

        foreach($lines as $l) {
            if(trim($l) === '') continue;
            list($user, $pid, $ppid, $tsserv, $args) = preg_split('/\s+/', $l, 5);
            if($recpt1_proc['ppid'] == intval($pid)) {
                preg_match('/^(.*)\s+--\s+(.*)$/', $args, $matches);
                list($tsserv_args, $recpt1_args) = $matches;

                if(preg_match('/-p\s+(\d+)/', $tsserv_args, $matches))
                    $port = intval($matches[1]);
                else if(preg_match('/--port(\s+|=)(\d+)/', $tsserv_args, $matches))
                    $port = intval($matches[2]);

                $proc = array('user' => $user,
                              'pid' => intval($pid),
                              'ppid' => intval($ppid),
                              'tsserv' => $tsserv,
                              'port' => intval($port),
                              'args' => $recpt1_args);
                return $proc;
            }
        }

        return FALSE;
    }
}



