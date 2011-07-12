{*
<table class="program" cellpadding="0" cellspacing="1">
<tr><th rowspan="2">物理チャンネル</th><th>番組時間</th><th>番組名</th><th rowspan="2">視聴</th></tr>
<tr><th colspan="2">番組説明</th></tr>
{foreach from=$channels item=ch}
<tr >
<td class="ch" rowspan="2">{$ch.channel_disc|escape}: {$ch.name|escape}</td>
<td class="time">{$ch.starttime|reltime|escape}-{$ch.endtime|reltime:$ch.starttime|escape}</td>
<td class="title"><a href="javascript:setChannel('{$ch.channel}');">{$ch.title|escape}</a></td>
<td rowspan="2">
  <button class="inline" onclick="setChannel('{$ch.channel}');$('#tcp').click();">TCP</button>
  <button class="inline" onclick="setChannel('{$ch.channel}');$('#udp').click();">UDP</button>
</td></tr>
<tr id="ch{$ch.channel|escape}_2" onclick="setChannel('{$ch.channel}')">
<td class="description" colspan="2">{$ch.description|escape}</td>
</tr>
{/foreach}
</table>
*}

<div class="program">
{foreach from=$channels item=ch}
<div id="ch{$ch.channel|escape}" class="ch" onclick="setChannel('{$ch.channel}')">
<div class="ch_desc">{$ch.channel_disc|escape}: {$ch.name|mb_convert_kana:"a"|escape}</div>
<div style="padding: 4px;">
<div style="height: 4em;"><span class="title">{$ch.title|mb_convert_kana:"a"|escape}</span>
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
