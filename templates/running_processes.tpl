<table cellpadding="0" cellspacing="1" style="">
<tr><th>PID</th><th>コマンド</th><th>操作</th><th>視聴</th></tr>
{foreach from=$processes item=proc}
<tr>
  <td class="pid">{$proc.pid|escape}</td><td class="args">{$proc.args|escape}</td>
  <td>
    <form method="POST" action="">
      <input type="hidden" name="pid" value="{$proc.pid|escape}"/>
      <input type="text" name="ch" maxlength="2" size="2" value=""/>
      <input type="submit" name="change" value="CH変更"/>
      <input type="submit" name="kill" value="停止"/>
    </form>
  </td>
  <td>
    {if $proc.tsserv !== FALSE}
    <a href="tsserv.php?port={$proc.tsserv.port|escape}">観る</a>
    {/if}
  </td>
</tr>
{foreachelse}
<tr><td></td><td>ストリーミング中の recpt1 プロセスはありません</td><td></td><td></td></tr>
{/foreach}
</table>
