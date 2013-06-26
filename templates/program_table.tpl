<table class="table table-bordered">
  <thead>
    <tr>
      <th>チャンネル</th>
      <th>放送時間</th>
      <th>番組名</th>
      <th>番組内容</th>
      <th>アクション</th>
    </tr>
  </thead>
  <tbody>
  {foreach from=$channels item=ch}
  <tr id="ch{$ch.channel|escape}" class="ch" onclick="setChannel('{$ch.channel}')">
    <td class="ch_disc">{$ch.channel_disc|escape}<br/>{$ch.name|escape}</td>
    <td class="prog_time">{$ch.starttime|reltime|escape}-{$ch.endtime|reltime:$ch.starttime|escape}</td>
    <th class="prog_title">{$ch.title|escape}</th>
    <td class="prog_desc">{$ch.description|escape|nl2br}</td>
    <td class="prog_action">
      <a class="btn" href="#" onclick="setChannel('{$ch.channel}');$('#tcp').click();">TCP</a>
      <a class="btn" href="#" onclick="setChannel('{$ch.channel}');$('#udp').click();">UDP</a>
    </td>
  </tr>
  {/foreach}
  </tbody>
</table>
