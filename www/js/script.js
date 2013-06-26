function setPhysChannel(ch)
{
    var pch = $('#phys_channel option[value="' + ch +  '"]');
    
    if(pch) {
	$('#phys_channel').val(pch.val());
	$('#phys_channel2').val(pch.val());
    }
}

function setHighlight(ch)
{
    $('tr[class*="selected"]').toggleClass('selected', false);
    $('#ch' + ch).toggleClass("selected", true);
}

function setChannel(ch)
{
    $('#ch').val(ch).toggleClass('selected', true);
    $('text,[name="ch"]').val(ch).toggleClass('selected', true);

    setPhysChannel(ch);
    setHighlight(ch);
}

