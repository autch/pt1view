<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<title>pt1view</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<link rel="stylesheet" href="css/bootstrap.css"/>
<style>
  body {
  padding-top: 60px;
  padding-bottom: 40px;
  }
</style>
<link rel="stylesheet" href="css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="css/style.css"/>
<script src="js/libs/modernizr-2.5.3-respond-1.1.0.min.js"></script>
<script type="text/javascript">{literal}
function setPhysChannel(ch)
{
  var pch = $('#phys_channel option[value="' + ch +  '"]');

  if(pch) {
  $('#phys_channel').attr('value', pch.attr('value'));
  $('#phys_channel2').attr('value', pch.attr('value'));
  }
}

function setHighlight(ch)
{
  $('div[class*="selected"]').toggleClass('selected', false);
  $('#ch' + ch).toggleClass("selected", true);
}

function setChannel(ch)
{
  $('#ch').attr('value', ch).toggleClass('selected', true);
  $('text,[name="ch"]').attr('value', ch).toggleClass('selected', true);

  setPhysChannel(ch);
  setHighlight(ch);
}

{/literal}</script>
</head>
<body>
