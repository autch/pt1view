<?php

require_once 'smarty/Smarty.class.php';

class TNSmarty extends Smarty
{
  function __construct()
  {
    parent::__construct();
    
    $this->addTemplateDir(TNST_SMARTY_TEMPLATES);
    $this->compile_dir = TNST_SMARTY_TEMPLATES_C;
    $this->addPluginsDir(explode(PATH_SEPARATOR, TNST_SMARTY_PLUGINS));
    
    $this->registerPlugin('block', 'dynamic', array($this, 'smarty_block_dynamic'), FALSE);

    if(defined('TNST_SMARTY_CACHING') && constant('TNST_SMARTY_CACHING') != 0)
    {
      $this->caching = TNST_SMARTY_CACHING;
      $this->cache_dir = TNST_SMARTY_CACHE;
      $this->cache_lifetime = TNST_SMARTY_CACHE_LIFETIME;
      $this->compile_check = TNST_SMARTY_COMPILE_CHECK;
    } 
  }

  function smarty_block_dynamic($param, $content, &$smarty)
  {
    return $content;
  }
}
