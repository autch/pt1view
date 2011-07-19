<div class="program">
{foreach from=$channels item=ch}
<div id="ch{$ch.channel|escape}" class="ch" onclick="setChannel('{$ch.channel}')">
<div class="ch_desc">{$ch.channel_disc|escape}: {$ch.name|mb_convert_kana:"a"|escape}</div>
<div style="padding: 4px;">
<div class="title">{$ch.title|mb_convert_kana:"a"|escape}</span>
<span class="time">{$ch.starttime|reltime|escape}-{$ch.endtime|reltime:$ch.starttime|escape}</span></div>
<div class="description">{$ch.description|mb_convert_kana:"a"|escape}</div>
<div>
  <button class="inline" onclick="setChannel('{$ch.channel}');$('#tcp').click();">TCP</button>
  <button class="inline" onclick="setChannel('{$ch.channel}');$('#udp').click();">UDP</button>
</div>
</div>
</div>
{/foreach}
</div>

<br style="clear: both; height: 0px;"/>
