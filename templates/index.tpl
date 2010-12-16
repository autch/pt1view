{include file="header.tpl"}
<script type="text/javascript">{literal}
function setPhysChannel(ch)
{
  var sel = $('#phys_channel');
  var pch = $('#phys_channel option[value="' + ch +  '"]');

  if(pch) sel.attr('value', pch.attr('value'));
}

function setHighlight(ch)
{
  $('tr[class*="selected"]').toggleClass('selected', false);
  $('#ch' + ch).toggleClass("selected", true);
  $('#ch' + ch + '_2').toggleClass("selected", true);
}

function setChannel(ch)
{
  $('#ch').attr('value', ch).toggleClass('selected', true);
  $('text,[name="ch"]').attr('value', ch).toggleClass('selected', true);

  setPhysChannel(ch);
  setHighlight(ch);
}

{/literal}</script>

<div><a href="?">更新</a></div>

<div/>

{if !empty($command)}
<div class="command" id="command">
以下のコマンドを実行：<button onclick="$('#command').hide(200);">隠す</button><br/>
<pre>
{foreach from=$command item=cmd}
{$cmd|escape}
{/foreach}
</pre>
</div>
{/if}

{if !empty($errors)}
<div class="errors" id="errors">
以下のエラーが発生：<button onclick="$('#errors').hide(200);">隠す</button>
<ul>
{foreach from=$errors item=error}
<li>{$error|escape}</li>
{/foreach}
</ul>
</div>
{/if}

{include file="running_processes.tpl"}
{include file="start_streaming.tpl"}
{include file="program_table.tpl"}
{include file="footer.tpl"}
