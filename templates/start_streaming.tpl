<form method="post" action="">
<table cellpadding="0" cellspacing="1">
  <tr><th colspan="4">新規ストリーミング</th></tr>
  <tr><th>PT1/2 デバイス</th>
    <td><input type="text" name="device" id="device" maxlength="16" size="16"
	       value="{$pt1.device|default:""|escape}"/>（空欄で自動検出）</td></tr>
  <tr><th>物理チャンネル番号</th>
    <td><input type="text" name="ch" id="ch" maxlength="2" size="2"
	       value="{$pt1.ch|default:""|escape}" onblur="setPhysChannel($(this).attr('value'))"/>
      {html_options name="phys_channel" id="phys_channel"
      options=$phys_channels onchange="setChannel($(this).attr('value'))"}</td></tr>
  <tr><th>オプション</th>
    <td><label><input type="checkbox" name="b25" id="b25"
		      {if $pt1.b25}checked="checked"{/if}/>B25 復号</label>
      <label><input type="checkbox" name="strip" id="strip"
		    {if $pt1.strip}checked="checked"{/if}/>NULL 除去</label></td></tr>
  <tr><th>TCP</th>
    <td><input type="text" name="tcp_port" id="tcp_port" maxlength="5" size="5"
	       value="{$pt1.tcp_port|default:"11234"|escape}"/>/tcp で
      <input type="submit" name="tcp" id="tcp" value="開始"/></td></tr>
  <tr><th>UDP</th>
    <td><input type="text" name="addr" id="addr"
	       value="{$pt1.addr|default:$smarty.server.REMOTE_ADDR|escape}"/>{strip}
      :{/strip}<input type="text" name="port" id="port" maxlength="5" size="5"
		      value="{$pt1.port|default:"1234"|escape}"/>/udp で
      <input type="submit" name="udp" id="udp" value="開始"/></td></tr>
</table>

