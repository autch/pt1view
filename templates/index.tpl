<!DOCTYPE html>{* -*- mode: html; -*- *}
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<title>pt1view</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<link rel="stylesheet" href="css/bootstrap.css"/>
<style>
  body {
  padding-top: 60px;
  padding-bottom: 40px;
  }
</style>
<link rel="stylesheet" href="css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="css/style.css"/>
<script src="js/libs/modernizr-2.5.3-respond-1.1.0.min.js"></script>
</head>
<body>

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="brand" href="#">pt1view</a>
    </div>
  </div>
</div>

<div class="container">
  <div id="new-stream" class="modal hide" role="dialog" aria-labelledby="new-stream-label" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="new-stream-label">新規ストリーミング</h3>
    </div>
    <div class="modal-body">
      <form class="form-horizontal">
	<div class="control-group">
	  <label class="control-label" for="device">PT1/2 デバイス</label>
	  <div class="controls">
	    <input type="text" name="device" id="device" maxlength="16" size="16" class="input-small"
		   value="{$pt1.device|default:""|escape}" placeholder="空欄で自動検出" />
	  </div>
	</div>
	<div class="control-group">
	  <label class="control-label" for="ch">物理チャンネル番号</label>
	  <div class="controls">
	    <select id="ch" name="ch"></select>
	  </div>
	</div>
	<div class="control-group">
	  <label class="control-label">オプション</label>
	  <div class="controls">
	    <label class="checkbox inline"><input type="checkbox" name="b25" id="b25"
						  {if $pt1.b25}checked="checked"{/if}/>B25 復号</label>
	    <label class="checkbox inline"><input type="checkbox" name="strip" id="strip"
						  {if $pt1.strip}checked="checked"{/if}/>NULL 除去</label>
	  </div>
	</div>
	<div class="control-group">
	  <label class="control-label" for="tcp_port">TCP</label>
	  <div class="controls">
	    <div class="inline">
	      <input type="text" name="tcp_port" id="tcp_port" maxlength="5" size="5" class="input-mini"
		     value="{$pt1.tcp_port|default:"11234"|escape}"/>
	      <span class="add-on">/tcp</span>
	    </div>
	  </div>
	</div>
	<div class="control-group">
	  <label class="control-label" for="addr">UDP</label>
	  <div class="controls">
	    <div class="inline">{strip}
	      <input type="text" name="addr" id="addr" class="input-small"
		     value="{$pt1.addr|default:$smarty.server.REMOTE_ADDR|escape}"/>
	      <span class="add-on">:</span>
	      <input type="text" name="port" id="port" maxlength="5" size="5" class="input-mini"
		     value="{$pt1.port|default:"1234"|escape}"/>
	      <span class="add-on">/udp</span>
	      {/strip}</div>
	  </div>
	</div>
      </form>
    </div>
    <div class="modal-footer">
      <a href="#" class="btn" data-dismiss="modal">キャンセル</a>
      <a href="#" id="start_tcp" class="btn btn-primary" data-dismiss="modal">TCP で開始</a>
      <a href="#" id="start_udp" class="btn btn-primary" data-dismiss="modal">UDP で開始</a>
    </div>
  </div>

  <div style="display: none;" class="alert alert-success fade in" id="command">
    <a class="close" data-dismiss="alert">&times;</a>
    <strong>コマンドを実行：</strong>
    <code></code>
  </div>

  <div style="display: none;" class="alert alert-block alert-error fade in" id="errors">
    <a class="close" data-dismiss="alert">&times;</a>
    <h4 class="alert-heading">エラー</h4>
    <ul>
    </ul>
  </div>

  <table id="processes" class="table table-bordered table-striped table-condensed">
    <thead>
      <tr><th>PID</th><th>コマンド</th><th>操作</th><th>視聴</th></tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  <table id="programs" class="table table-bordered">
    <thead>
      <tr><th>チャンネル</th><th>放送時間</th><th>番組名</th><th>番組内容</th><th>アクション</th></tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>

<script src="js/libs/bootstrap/transition.js"></script>
<script src="js/libs/bootstrap/alert.js"></script>
<script src="js/libs/bootstrap/modal.js"></script>
<script src="js/script.js"></script>

</body>
</html>
