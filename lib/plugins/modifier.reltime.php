<?php


// {$ch.starttime|date_format:"%d日 %H:%M"|escape}

function smarty_modifier_reltime($from, $to = NULL)
{
  if($to === NULL)
    $now = time();
  else
    $now = $to;
  $day_now = date("d", $now);		// date of today
  $day_pgm = date("d", $from);	// date of the program

  if($day_now != $day_pgm)
  {
    $format = "%d日 %H:%M";
  }
  else
  {
    $format = "%H:%M";
  }

  return strftime($format, $from);
}

