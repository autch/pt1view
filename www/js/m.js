$(function() {
    jQuery.getJSON("channels.php", function(data, status, xhr) {
	var $sel = $("#ch");
	$sel.empty();
	for(var ch in data) {
	    $("<option>").attr("value", ch).text(data[ch]).appendTo($sel);
	}
    });

    var loadPrograms = function() {
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
	jQuery.getJSON("programs.php", function(data, status, xhr) {
	    var $ul = $('#program');

	    $ul.empty();
	    for(var i = 0; i < data.length; i++) {
		var $li = $("<li>");
		var $a_ns = $("<a>");
		var ch = data[i];

		$("<h1>").text(ch.title).appendTo($a_ns);
		$("<p>").text(ch.channel_disc + ": ").append($("<strong>").text(ch.name)).appendTo($a_ns);
		$("<p>").text(ch.description).appendTo($a_ns);
		$("<p>").addClass("ui-li-aside").text(reltime(ch.starttime) + "-" + reltime(ch.endtime, ch.starttime)).appendTo($a_ns);

		$a_ns.attr({ "href": "#new-stream", "data-channel": ch.channel}).appendTo($li);
		$("<a>").attr({
		    "href": "#program-detail",
		    "data-channel": ch.channel,
		    "data-rel": "popup",
		    "data-position-to": "window",
		    "data-transition": "pop"
		}).text("番組詳細").appendTo($li);

		$li.attr("id", "ch" + ch.channel).appendTo($ul);
	    }

	    /*window.setTimeout(function() {*/ $ul.listview("refresh");// }, 0);
	});
    };
    loadPrograms();
    window.setTimeout(loadPrograms, 1000 * 60 * 5); // 5 minutes
    
    $("#new-stream").on("pageinit", function() {
    });

    $('#program').on("click", "a[href='#program-detail']", function(e) {
	var $self = $(e.currentTarget);
	var ch = $self.attr("data-channel");

	jQuery.getJSON("programs.php", function(data, status, xhr) {
	    var prog = data.filter(function(elem, idx, arr) {
		return elem.channel === ch;
	    })[0];

	    var $div = $('#program-detail-content');
	    var prog_start = new Date(1000 * parseInt(prog.starttime));
	    var prog_end = new Date(1000 * parseInt(prog.endtime));
	    var localFormat = function(d) {
		var v = [d.getFullYear(), "/", d.getMonth(), "/", d.getDay(), " ", 
			 d.getHours(), ":", d.getMinutes()];
		return v.join("");
	    };
	    
	    $div.empty();
	    $("<h4>").text(prog.title).appendTo($div);
	    $("<div>").text(prog.channel_disc + ": " + prog.name).appendTo($div);
	    $("<div>").text(localFormat(prog_start) + " - " + localFormat(prog_end)).appendTo($div);
	    $("<p>").text(prog.description).appendTo($div);
	    $("#program-detail").popup("open");
	});

	return false;
    });

    $('#program').on("click", "a[href='#new-stream']", function(e) {
	var $self = $(e.currentTarget);
	var ch = $self.attr("data-channel");

	$('#ch').val(ch);
	window.setTimeout(function() { $('#ch').selectmenu("refresh"); }, 0);
    });

    $('#new-stream').on("pagebeforeshow", function(e) {
	$('#ch').selectmenu("refresh");
    });

    $('#main').on("click", "a[href='#processes']", function(e) {
	jQuery.getJSON("processes.php", function(data, status, xhr) {
	    var $tb = $("#process-table tbody");

	    $tb.empty();
	    for(var i = 0; i < data.length; i++) {
		var proc = data[i];
		var $tr = $("<tr>");
		var $a = $("<a>");

		$a.attr({ 
		    "href": "command.php?action=kill&pid=" + proc.pid,
		    "data-action": "kill",
		    "data-pid": proc.pid,
		    "data-role": "button",
		    "data-mini": "true",
		    "data-inline": "true",
		    "data-icon": "delete",
		    "data-iconpos": "notext",
		    "data-theme": "e",
		}).text("停止");

		$("<td>").text(proc.pid).appendTo($tr);
		$("<td>").append($("<code>").text(proc.args)).appendTo($tr);
		$("<td>").append($a).appendTo($tr);
		if(proc.tsserv) {
		    $("<td>").append($("<a>").attr("href", "tsserv.php?port=" + proc.tsserv.port).text("観る")).appendTo($tr);
		} else {
		    $("<td>").appendTo($tr);
		}
		$tr.appendTo($tb);
	    }
	    $("#process-table").table("refresh");
	    $("#main").trigger("create");
	    $("#processes").popup("open");
	});
	return false;
    });

    var showCommandResult = function(data, status, xhr) {
	var meta = function(array, $target) {
	    var $ul = $target.find("ul");
	    
	    $ul.empty();
	    for(var i = 0; i < array.length; i++) {
		var $li = $('<li>');
		$li.text(array[i]).appendTo($ul);
	    }
	    $ul.listview("refresh");
	    $('#main').trigger("create");
	    window.setTimeout(function() {
		$target.popup("open", { positionTo: "window" });
	    }, 0);
	};
	if(data.errors.length > 0) {
	    meta(data.errors, $('#errors'));
	} else if(data.commands.length > 0) {
	    meta(data.commands, $('#result'));
	}
    };

    $('#main').on("click", "a[data-action='kill']", function(e) {
	var $self = $(e.currentTarget);
	var pid = $self.attr("data-pid");
	var request = { action: "kill", pid: pid };

	$.getJSON("command.php", request, showCommandResult);
	$('#processes').popup("close");
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
	$('#new-stream').dialog("close");
	return false;
    });
});
