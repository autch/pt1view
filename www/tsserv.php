<?php

ini_set("display_errors", 0);
ini_set("log_errors", 1);

if(isset($_REQUEST['port']))
  $port = intval($_REQUEST['port']);
else
  $port = 11234;
if(isset($_REQUEST['type']))
  $type = urlencode($_REQUEST['type']);
else
  $type = "video/mpeg";


$fp = fsockopen("localhost", $port, $errno, $errstr);
if($fp)
{
  header(sprintf("Content-Type: %s", $type));

  fpassthru($fp);
  fclose($fp);
}
else
{
  header("Status: 500 Internal Server Error");
  header("Content-Type: text/plain");

  echo $errstr;
}

