<?php

require_once(EPGREC_PATH . '/config.php');

class EpgrecDB
{
    private $config = NULL;
    private $db = NULL;

    public function __construct()
    {
        $this->config = Settings::factory(); // epgrec/config.php
        $this->open($this->config);
    }

    public function __destruct()
    {
        if($this->db !== NULL) {
            $this->db->close();
        }
    }

    private function open($config)
    {
        $this->db = new mysqli($config->db_host, $config->db_user, $config->db_pass,
                               $config->db_name);
        $this->db->query("SET NAMES utf8");
        return $this->db;
    }

    public function selectChannelNames()
    {
        $sql = sprintf('SELECT channel_disc, channel, name FROM %1$schannelTbl ' .
                       'ORDER BY channel ASC', $this->config->tbl_prefix);
        $channels = array();
        
        if(($result = $this->db->query($sql)) !== FALSE) {
            while(($row = $result->fetch_array()) !== NULL) {
                $channels[$row['channel']] = sprintf("%s: %s",
                                                     $row['channel_disc'], $row['name']);
            }
            $result->close();
        }
        
        return $channels;
    }

    public function selectPrograms()
    {
        $sql = "SELECT c.channel, c.channel_disc, c.name, p.title, p.description, "
            ."  UNIX_TIMESTAMP(p.starttime) starttime, UNIX_TIMESTAMP(p.endtime) endtime, t.name_jp, c.type "
            ."FROM %1\$sprogramTbl p, %1\$schannelTbl c, %1\$scategoryTbl t "
            ."WHERE p.channel_disc = c.channel_disc AND p.category_id = t.id "
            ."AND c.skip = 0 AND p.starttime <= now() AND p.endtime >= now() "
            ."ORDER BY c.type, c.channel, c.name ASC";
        $sql = sprintf($sql, $this->config->tbl_prefix);

        $channels = array();

        if(($result = $this->db->query($sql)) !== FALSE) {
            while(($row = $result->fetch_array()) !== NULL) {
                $channels[] = $row;
            }

            return $channels;
        } else {
            throw new Exception($this->db->error);
        }
    }
}
