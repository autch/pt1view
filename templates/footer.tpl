<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>

<script src="js/libs/bootstrap/transition.js"></script>
<script src="js/libs/bootstrap/collapse.js"></script>
<script src="js/libs/bootstrap/dropdown.js"></script>
<script src="js/libs/bootstrap/alert.js"></script>

<script src="js/script.js"></script>

<script>
{if !empty($command)}
$('#command').alert();
{/if}
{if !empty($errors)}
$('#errors').alert();
{/if}
</script>
</body>
</html>
