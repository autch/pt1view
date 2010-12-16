<?php

require_once(EPGREC_PATH . '/config.php');

function get_epgrec_config()
{
  return Settings::factory();
}

function open_epgrec_db(&$settings)
{
  $mysqli = new mysqli($settings->db_host, $settings->db_user, $settings->db_pass,
		       $settings->db_name);
  $mysqli->query("SET NAMES utf8");
  return $mysqli;
}

function select_channel_list(&$db, &$config)
{
  $sql = sprintf('SELECT channel_disc, channel, name FROM %1$schannelTbl ORDER BY id ASC', $config->tbl_prefix);
  $channels = array();

  if(($result = $db->query($sql)) !== FALSE)
  {
    while(($row = $result->fetch_array()) !== NULL)
    {
      $channels[$row['channel']] = sprintf("%s: %s", $row['channel_disc'], $row['name']);
    }
    $result->close();
  }

  return $channels;
}
