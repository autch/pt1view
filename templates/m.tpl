<!DOCTYPE html> 
<html>
  <head>
    <meta charset="UTF-8"/>
    <title>pt1view</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/jquery.mobile-1.3.1.min.css" />
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/jquery.mobile-1.3.1.min.js"></script>
    <link rel="stylesheet" href="css/m.css" />
    <script src="js/m.js"></script>
  </head>
  <body>
    <div data-role="page" id="main">
      <div data-role="header">
	<h1>pt1view</h1>
	<a href="#processes" data-rel="popup" data-position-to="origin" class="ui-btn-right">プロセス</a>
      </div><!-- /header -->
      <div data-role="content">
	<ul data-role="listview" id="program" data-split-theme="d" data-split-icon="info">
	</ul>
      </div><!-- /content -->
      <div data-role="popup" id="program-detail" data-theme="d" data-overlay-theme="b" class="ui-content">
	<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-left">閉じる</a>
	<h3>番組詳細</h3>
	<div id="program-detail-content">
	</div>
      </div>
      <div data-role="popup" id="processes" data-theme="d" data-overlay-theme="b" class="ui-content">
	<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-left">閉じる</a>
	<h3>実行中のプロセス</h3>
	<div id="process-detail-content">
	  <table data-role="table" id="process-table" data-mode="reflow" class="ui-responsive table-stroke">
	    <thead>
	      <tr><th>PID</th><th>コマンド</th><th>操作</th><th>視聴</th></tr>
	    </thead>
	    <tbody>
	    </tbody>
	  </table>
	</div>
      </div>
      <div data-role="popup" id="result" data-theme="d" class="ui-content">
	<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-left">閉じる</a>
	<ul data-role="listview">
	</ul>
      </div>
      <div data-role="popup" id="errors" data-theme="e" data-overlay-theme="a" class="ui-content">
	<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-left">閉じる</a>
	<ul data-role="listview" data-theme="e">
	</ul>
      </div>
    </div>

    <div data-role="dialog" id="new-stream">
      <div data-role="header">
	<h1>ストリームを開始</h1>
      </div><!-- /header -->

      <div data-role="content">
	<form method="POST">
	  <ul data-role="listview">
	    <li data-role="fieldcontain">
	      <label for="device">PT1/2 デバイス</label>
	      <input type="text" name="device" id="device" value="{$pt1.device|default:""|escape}" placeholder="自動検出">
	    </li>
	    <li data-role="fieldcontain">
	      <label for="ch">物理チャンネル</label>
	      <select name="ch" id="ch"></select>
	    </li>
	    <li data-role="fieldcontain">
	      <fieldset data-role="controlgroup" data-type="horizontal">
		<legend>オプション</legend>
		<label for="b25">B25 復号</label>
		<input type="checkbox" name="b25" id="b25"{if $pt1.b25} checked{/if}>
		<label for="strip">NULL 除去</label>
		<input type="checkbox" name="strip" id="strip" {if $pt1.strip} checked{/if}>
	      </fieldset>
	    </li>
	    <li data-role="fieldcontain">
	      <label for="tcp_port">TCP ポート番号</label>
	      <input type="number" name="tcp_port" id="tcp_port" value="{$pt1.tcp_port|default:"11234"|escape}" placeholder="TCP ポート番号">
	    </li>
	    <li data-role="fieldcontain" id="udp_peer">
	      <label for="addr">UDP ホスト:ポート</label>
	      <input type="text" name="addr" id="addr" value="{$pt1.addr|default:$smarty.server.REMOTE_ADDR|escape}" placeholder="UDP ホスト">
	      <input type="number" name="port" id="port" value="{$pt1.port|default:"1234"|escape}" placeholder="UDP ポート番号">
	    </li>
	    <li>
	      <fieldset class="ui-grid-a">
		<div class="ui-block-a"><button id="start_tcp">TCP</button></div>
		<div class="ui-block-b"><button id="start_udp">UDP</button></div>
	      </fieldset>
	    </li>
	  </ul>
	</form>
      </div>
    </div>
  </body>
</html>
