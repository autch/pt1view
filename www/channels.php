<?php

require_once '../boot.inc.php';

$config = get_epgrec_config();
$db = open_epgrec_db($config);
if(!$db) die("Cannot open database");

$sql = sprintf('SELECT channel_disc, channel, name FROM %1$schannelTbl ORDER BY channel ASC', $config->tbl_prefix);
$channels = array();

if(($result = $db->query($sql)) !== FALSE)
{
    while(($row = $result->fetch_array()) !== NULL)
    {
        $channels[$row['channel']] = sprintf("%s: %s", $row['channel_disc'], $row['name']);
    }
    $result->close();
    
    header("Content-Type: application/json");

    $cb = get_jsonp_callback();
    echo jsonp_begin($cb);
    echo json_encode($channels, JSON_HEX_APOS | JSON_HEX_QUOT);
    echo jsonp_end($cb);
}
else
{
    die($db->error);
}

$db->close();
