
<button class="btn" data-toggle="collapse" data-target="#start_stream">
新規ストリーミング
</button>

<form method="post" id="start_stream" class="form-horizontal collapse" action="">
  <fieldset>
    <legend>新規ストリーミング</legend>
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
	<input type="text" name="ch" id="ch" maxlength="2" size="2" class="input-mini"
	       value="{$pt1.ch|default:""|escape}" onblur="setPhysChannel($(this).attr('value'))"/>
	{html_options name="phys_channel" id="phys_channel" class="phys_channel"
	options=$phys_channels onchange="setChannel($(this).attr('value'))"}
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
	<input type="submit" name="tcp" id="tcp" class="btn btn-primary" value="開始"/>
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
	<input type="submit" name="udp" id="udp" class="btn btn-primary" value="開始"/>
      </div>
    </div>
  </fieldset>
</form>

