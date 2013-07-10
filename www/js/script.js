$(function() {
    var updatePrograms = function() {
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
	$.getJSON("programs.php?callback=?", function(data, status, xhr) {
	    var $target = $('#programs tbody');
	    var $sel = $("#ch");

	    $sel.empty();

	    for(var i = 0; i < data.length; i++) {
		var item = data[i];
		$('<option>').attr("value", item.channel).text(item.channel_disc + ": " + item.name).appendTo($sel);
		item.starttime_rel = reltime(item.starttime);
		item.endtime_rel = reltime(item.endtime, item.starttime);
	    }

	    var template = $target.data('template');
	    $target.html(template(data));
	});
    };

    var hb_program = Handlebars.compile($('#hb-program').html());
    $('#programs tbody').data('template', hb_program);
    var hb_process = Handlebars.compile($('#hb-process').html());
    $('#processes tbody').data('template', hb_process);

    var updateProcesses = function() {
	$.getJSON("processes.php?callback=?", function(data, status, xhr) {
	    var $target = $('#processes tbody');
	    var template = $target.data('template');
	    $target.html(template(data));
	});
    };

    var updatePeriodic = function() {
	updateProcesses();
	updatePrograms();
    };
    updatePeriodic();
    window.setInterval(updatePeriodic, 1000 * 60 * 5);

    $.getJSON("command.php?callback=?", { action: "default" }, function(data, status, xhr) {
	var defs = data.defaults;

	$('#device').val(defs['device']);
	$('#addr').val(defs['addr']);
	$('#port').val(defs['port']);
	$('#tcp_port').val(defs['tcp_port']);
	$('#b25').val(defs['b25']);
	$('#strip').val(defs['strip']);
    });

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

    $('#programs tbody').on("click", "a[data-action='new']", function(e) {
	var $self = $(e.currentTarget);
	var ch = $self.attr("data-ch");

	$('#ch').val(ch).toggleClass('selected', true);
	$('text,[name="ch"]').val(ch).toggleClass('selected', true);
	
	$('tr[class*="selected"]').toggleClass('selected', false);
	$('#ch' + ch).toggleClass("selected", true);

	$('#ch').val(ch);
	$('#new-stream').popover("show");
    });
    $('#processes tbody').on("click", "a[data-action='kill']", function(e) {
	var $self = $(e.currentTarget);
	var pid = $self.attr("data-pid");
	var request = { action: "kill", pid: pid };

	$.getJSON("command.php?callback=?", request, showCommandResult);
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
	$.getJSON("command.php?callback=?", request, showCommandResult);
	return false;
    });
    $("a[data-role='reload']").on("click", function(e) {
	updatePeriodic();
    });
});
