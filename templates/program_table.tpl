<ul class="thumbnails">
  {foreach from=$channels item=ch}
  <li class="span3">
    <div id="ch{$ch.channel|escape}" class="ch thumbnail" onclick="setChannel('{$ch.channel}')">
      <div class="ch_desc">{$ch.channel_disc|escape}: {$ch.name|mb_convert_kana:"a"|escape}</div>
      <div class="title">{$ch.title|mb_convert_kana:"a"|escape}
	<span class="time">{$ch.starttime|reltime|escape}-{$ch.endtime|reltime:$ch.starttime|escape}</span>
      </div>
      <div class="btn-group">
	<button class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown" href="#">開始
	  <span class="caret"></span>
	</button>
	<a class="btn btn-mini btn-info" rel="popover" title="{$ch.title|mb_convert_kana:"a"|escape}" data-content="{$ch.description|mb_convert_kana:"a"|escape}">詳細</a>
	<ul class="dropdown-menu">
	  <li><a href="#" onclick="setChannel('{$ch.channel}');$('#tcp').click();">TCP</a></li>
	  <li><a href="#" onclick="setChannel('{$ch.channel}');$('#udp').click();">UDP</a></li>
	</ul>
      </div>
    </div>
  </li>
  {/foreach}
</ul>
