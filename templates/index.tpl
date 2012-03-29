{include file="header.tpl"}

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
      </a>

      <a class="brand" href="?">pt1view</a>

      <div class="nav-collapse">
	<form class="navbar-form pull-right" action="">
	  {html_options name="phys_channel" class="phys_channel" id="phys_channel2"
	  options=$phys_channels onchange="setChannel($(this).attr('value'))"}

	  <button class="btn" onclick="setChannel('{$ch.channel}');$('#tcp').click();">TCP</button>
	  <button class="btn" onclick="setChannel('{$ch.channel}');$('#udp').click();">UDP</button>

	</form>
      </div>
    </div>
  </div>
</div>

<div class="container">

{if !empty($command)}
<div class="alert alert-success fade in" id="command">
<a class="close" data-dismiss="alert">&times;</a>
<strogn>コマンドを実行：</strong>
<code>
{foreach from=$command item=cmd}
{$cmd|escape}
{/foreach}
</code>
</div>
{/if}

{if !empty($errors)}
<div class="alert alert-block alert-error fade in" id="errors">
<a class="close" data-dismiss="alert">&times;</a>
<h4 class="alert-heading">エラー</h4>
<ul>
{foreach from=$errors item=error}
<li>{$error|escape}</li>
{/foreach}
</ul>
</div>
{/if}

{include file="running_processes.tpl"}
{include file="program_table.tpl"}
{include file="start_streaming.tpl"}

</div>

{include file="footer.tpl"}
