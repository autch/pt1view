<?php

require_once '../boot.inc.php';

$config = get_epgrec_config();
$db = open_epgrec_db($config);
if(!$db) die("Cannot open database");

$sql = sprintf("SELECT c.channel, c.channel_disc, c.name, p.title, p.description, "
               ."  UNIX_TIMESTAMP(p.starttime) starttime, UNIX_TIMESTAMP(p.endtime) endtime, t.name_jp "
               ."FROM %1\$sprogramTbl p, %1\$schannelTbl c, %1\$scategoryTbl t "
               ."WHERE p.channel_disc = c.channel_disc AND p.category_id = t.id "
               ."AND c.type = 'GR' AND c.skip = 0 AND p.starttime <= now() AND p.endtime >= now() "
               ."ORDER BY c.channel_disc ASC",
               $config->tbl_prefix);

$channels = array();

if(($result = $db->query($sql)) !== FALSE)
{
    while(($row = $result->fetch_array()) !== NULL)
    {
        $channels[] = $row;
    }

    header("Content-Type: application/json");

    $cb = get_jsonp_callback();
    echo jsonp_begin($cb);
    echo json_encode($channels, JSON_HEX_APOS | JSON_HEX_QUOT);
    echo jsonp_end($cb);

    $result->close();
}
else
{
    die($db->error);
}

$db->close();