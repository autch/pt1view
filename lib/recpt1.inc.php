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

    public function buildForRTSP($upd = array())
    {
        $this->update($upd);
        $this->pt1['output'] = '-';
        $cmd = $this->buildArgs();
        $url_rtsp = sprintf(URL_RTSP, $this->pt1['ch']);
        $sh_cmd = sprintf("sh -c \"%s | ffmpeg -v 0 -i /dev/stdin -vcodec libx264 -vprofile baseline -vb 2000k -vf \"yadif=0:-1,scale=iw/2:-1\" -acodec libfaac -ab 128k -threads 4 -f flv %s >/dev/null 2>&1 &\"", $cmd, $url_rtsp);
        return $sh_cmd;
    }

    public static function args2hash($args)
    {
        $opts = array();
        $opts_end = FALSE;

        $av = array_values($args); // clone
        array_shift($av); // $argv[0]
        while(!empty($av) && !$opts_end) {
            $i = array_shift($av);
            switch($i) {
            case '--b25':
                $opts['b25'] = TRUE;
                break;
            case '--round':
                $opts['round'] = intval(array_shift($av));
                break;
            case '--strip':
                $opts['strip'] = TRUE;
                break;
            case '--EMM':
                $optps['EMM'] = TRUE;
                break;
            case '--udp':
                $opts['udp'] = TRUE;
                break;
            case '--addr':
                $opts['addr'] = array_shift($av);
                break;
            case '--port':
                $opts['port'] = intval(array_shift($av));
                break;
            case '--device':
                $opts['device'] = array_shift($av);
                break;
            case '--lnb':
                $opts['lnb'] = array_shift($av);
                break;
            case '--sid':
                $opts['sid'] = array_shift($av);
                break;
            default:
                $opts_end = TRUE;
                array_unshift($av, $i);
                break;
            }
        }
        $opts['ch'] = array_shift($av);
        $opts['duration'] = array_shift($av);
        $opts['outfile'] = array_shift($av);
        return $opts;
    }

    public static function findRecPT1()
    {
        $cmd = sprintf("ps -C recpt1 -o pid=");
        $output = shell_exec($cmd);
        $lines = explode("\n", $output);

        $procs = array();
        foreach($lines as $l) {
            if(trim($l) === '') continue;
            $pid = intval($l);

            $cmdline = preg_split("/\\0/", file_get_contents(sprintf("/proc/%d/cmdline", $pid)));
            $opts = self::args2hash($cmdline);

            $proc = array('pid' => intval($pid),
                          'args' => join(" ", $cmdline),
                          'opts' => $opts,
                          'ch' => $opts['ch'],
                          'URL_HLS' => sprintf(URL_HLS, $opts['ch']),
                          'URL_DASH' => sprintf(URL_DASH, $opts['ch'])
                );
            $procs[] = $proc;
        }

        return $procs;
    }
}



