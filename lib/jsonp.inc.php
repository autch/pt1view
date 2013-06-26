<?php

function get_jsonp_callback()
{
    $callback = empty($_REQUEST['callback']) ? FALSE : trim($_REQUEST['callback']);
    if($callback !== FALSE && !preg_match('/^[_a-zA-Z0-9]+$/', $callback))
    {
        $callback = FALSE;
    }
    return $callback;
}

function jsonp_begin($callback)
{
    if($callback !== FALSE)
        return $callback . "(";
    return "";
}

function jsonp_end($callback)
{
    if($callback !== FALSE) return ");";
    return "";
}
