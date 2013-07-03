<?php

class JSONP {
    private $cb = FALSE;

    public function __construct()
    {
        $this->cb = empty($_REQUEST['callback']) ? FALSE : trim($_REQUEST['callback']);
        if($this->cb !== FALSE && !preg_match('/^[_a-zA-Z0-9]+$/', $this->cb)) {
            $this->cb = FALSE;
        }
    }

    public function begin()
    {
        if($this->cb !== FALSE)
            echo $this->cb . "(";
    }

    public function end()
    {
        if($this->cb !== FALSE) echo ");";
    }
}
