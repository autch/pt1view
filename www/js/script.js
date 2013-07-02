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

(function() {
    var updatePrograms = function() {
	$.getJSON("programs.php", function(data, status, xhr) {
	    var $target = $('#programs tbody');
	    var reltime = function(start, end) {
		var from = new Date(1000 * parseInt(start));
		var now;
		if(end) {
		    now = new Date(1000 * parseInt(end));
		} else {
		    now = new Date();
		}
		var v = [from.getHours(), ":", from.getMinutes()];
		if(now.getDay() != from.getDay()) {
		    v.unshift("日 ");
		    v.unshift(from.getDay());
		}
		return v.join("");
	    };

	    $target.empty();
	    for(var i = 0; i < data.length; i++) {
		var item = data[i];
		var $tr = $('<tr>');

		$('<td>').text(item.channel_disc + ": " + item.name).appendTo($tr);
		$('<td>').text(reltime(item.starttime) + "-" + reltime(item.endtime, item.starttime)).appendTo($tr);
		$('<td>').text(item.title).appendTo($tr);
		$('<td>').text(item.description).addClass("prog_desc").appendTo($tr);
		$('<td>').append(
		    $('<a>').attr({
			"href": "#new-stream",
			"data-ch": item.channel,
			"role": "button",
			"data-toggle": "modal",
		    }).addClass("btn btn-primary").text("開始")
		).appendTo($tr);

		$tr.attr({
		    "id": "ch" + item.channel,
		    "data-ch": item.channel,
		}).appendTo($target);
	    }
	});
    };

    var updateProcesses = function() {
	$.getJSON("processes.php", function(data, status, xhr) {
	    var $target = $('#processes tbody');
	    $target.empty();
	    for(var i = 0; i < data.length; i++) {
		var item = data[i];
		var $tr = $('<tr>');

		$('<td>').text(item.pid).appendTo($tr);
		$('<td>').text(item.args).addClass("args").appendTo($tr);
		$('<td>').append(
		    $('<a>').attr({
			"href": "#",
			"data-pid": item.pid,
			"data-action": "kill",
		    }).addClass("btn btn-danger").text("停止")
		).appendTo($tr);
		if(item.tsserv)
		    $("<td>").append($("<a>").attr("href", "tsserv.php?port=" + item.tsserv.port).text("観る")).appendTo($tr);
		else
		    $('<td>').appendTo($tr);
		
		$tr.attr({
		    "id": "pid" + item.pid,
		    "data-pid": item.pid,
		}).appendTo($target);
	    }
	});
    };

    var updatePeriodic = function() {
	updateProcesses();
	updatePrograms();
    };
    updatePeriodic();
    window.setInterval(updatePeriodic, 1000 * 60 * 5);

    var showCommand = function(args) {
	var $target = $('#command');
	var $args = $target.find("code");

	$args.text(args);
	$target.show();
    };
    var showErrors = function(args) {
	var $target = $('#errors');
	var $ul = $target.find("ul");

	$ul.empty();
	for(var i = 0; i < args.length; i++) {
	    $('<li>').text(args[i]).appendTo($ul);
	}

	$target.show();
    };
    
    var showCommandResult = function(data, status, xhr) {
	if(data.errors.length > 0) {
	    showErrors(data.errors);
	} else if(data.commands.length > 0) {
	    showCommand(data.commands[0]);
	}
	updateProcesses();
    };

    jQuery.getJSON("channels.php", function(data, status, xhr) {
	var $sel = $("#ch");
	$sel.empty();
	for(var ch in data) {
	    $("<option>").attr("value", ch).text(data[ch]).appendTo($sel);
	}
    });

    $('#programs tbody').on("click", "tr", function(e) {
	var $self = $(e.currentTarget);
	var ch = $self.attr("data-ch");

	setChannel(ch);
    });
    $('#programs tbody').on("click", "a[data-action='new']", function(e) {
	var $self = $(e.currentTarget);
	var ch = $self.attr("data-ch");

	setChannel(ch);
	$('#new-stream').popover("show");
    });
    $('#processes tbody').on("click", "a[data-action='kill']", function(e) {
	var $self = $(e.currentTarget);
	var pid = $self.attr("data-pid");
	var request = { action: "kill", pid: pid };

	$.getJSON("command.php", request, showCommandResult);
	return false;
    });

    $('#new-stream').on("click", "#start_tcp,#start_udp", function(e) {
	var $self = $(e.currentTarget);
	var action = $self.attr("id") == "start_tcp" ? "tcp" : "udp";
	var request = {
	    action: action,
	    device: $('#device').val(),
	    addr: $('#addr').val(),
	    port: $('#port').val(),
	    tcp_port: $('#tcp_port').val(),
	    ch: $('#ch').val(),
	    b25: $('#b25').val(),
	    strip: $('#strip').val(),
	};
	$.getJSON("command.php", request, showCommandResult);
//	$('#new-stream').dialog("close");
	return false;
    });
})();
