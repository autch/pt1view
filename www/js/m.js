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

        $.mobile.loading("show");

        jQuery.getJSON("status.php?t=prog&callback=?", function(data, status, xhr) {
            var $ul = $('#program');
            var $sel = $("#ch");

            $sel.empty();
            $ul.data("program", data);
            for(var i = 0; i < data.length; i++) {
                var item = data[i];
                $('<option>').attr("value", item.channel).text(item.channel + ": " + item.name).appendTo($sel);
                item.starttime_rel = reltime(item.starttime);
                item.endtime_rel = reltime(item.endtime, item.starttime);
            }

            var template = $ul.data('template');
            $ul.html(template(data));

            if($ul.hasClass("ui-listview"))
                $ul.listview("refresh");
            else 
                $ul.trigger("create");

            $.mobile.loading("hide");
        });
    };

    var template = Handlebars.compile($('#hb-program').html());
    $('#program').data('template', template);
    $('#program-detail-content').data('template', Handlebars.compile($('#hb-program-detail').html()));
    $('#process-table tbody').data('template', Handlebars.compile($('#hb-process').html()));
    
    window.setInterval(updatePrograms, 1000 * 60 * 5); // 5 minutes
    $('#main').on("click", "a#reload-programs", function(e) { updatePrograms(); });
    
    updatePrograms();

    $.getJSON("command.php?callback=?", { action: "default" }, function(data, status, xhr) {
        var defs = data.defaults;

        $('#device').val(defs['device']);
        $('#addr').val(defs['addr']);
        $('#port').val(defs['port']);
        $('#tcp_port').val(defs['tcp_port']);
        $('#b25').val(defs['b25']);
        $('#strip').val(defs['strip']);
    });

    $('#program').on("click", "a[href='#program-detail']", function(e) {
        var $self = $(e.currentTarget);
        var $li = $self.parents("li");
        var ch = $self.attr("data-channel");
        var prog = $('#program').data("program").filter(function(i) { return i.channel == ch })[0];

        var $div = $('#program-detail-content');
        var prog_start = new Date(1000 * parseInt(prog.starttime));
        var prog_end = new Date(1000 * parseInt(prog.endtime));
        var localFormat = function(d) {
            var v = [d.getFullYear(), "/", d.getMonth(), "/", d.getDay(), " ", 
                     d.getHours(), ":", d.getMinutes()];
            return v.join("");
        };

        prog.prog_start = localFormat(prog_start);
        prog.prog_end = localFormat(prog_end);
        
        var template = $div.data('template');
        $div.html(template(prog));
    });

    $('#program').on("click", "a[href='#new-stream']", function(e) {
        var $self = $(e.currentTarget);
        var ch = $self.attr("data-channel");

        $('#ch').val(ch);
        window.setTimeout(function() { $('#ch').selectmenu("refresh"); }, 0);
    });

    $('#main').on("click", "a[href='#processes']", function(e) {
        $.mobile.loading("show");
        jQuery.getJSON("status.php?t=proc&callback=?", function(data, status, xhr) {
            var $tb = $("#process-table tbody");

            var template = $tb.data("template");
            $tb.html(template(data));

            $("#process-table").table("refresh");
            $("#main").trigger("create");
            $.mobile.loading("hide");
            $("#processes").popup("open");
        });
        return false;
    });

    $('#messages').on("click", "a", function() { $('#messages').hide(); });

    var showCommandResult = function(data, status, xhr) {
        var meta = function(text) {
            var $target = $('#messages');
            var $h3 = $target.find("h3");
            
            $target.hide();
            $h3.text(text).appendTo($target);
            $target.show();
            $.mobile.silentScroll(0);
        };
        if(data.errors.length > 0) {
            meta("エラー：" + data.errors[0]);
        } else if(data.commands.length > 0) {
            meta("コマンドを実行：" + data.commands[0]);
        }
    };

    $('#processes').on("click", "a[data-action='kill']", function(e) {
        var $self = $(e.currentTarget);
        var pid = $self.attr("data-pid");
        var request = { action: "kill", pid: pid };

        $.getJSON("command.php?callback=?", request, showCommandResult);

        $('#processes').popup("close");
    });

    $('#new-stream').on("click", "a[data-action='start']", function(e) {
        var $self = $(e.currentTarget);
        var request = {
            action: $self.attr("data-method"),
            device: $('#device').val(),
            addr: $('#addr').val(),
            port: $('#port').val(),
            ch: $('#ch').val(),
            b25: $('#b25').val(),
            strip: $('#strip').val(),
        };
        $.getJSON("command.php?callback=?", request, showCommandResult);
        $('#new-stream').popup("close");
        return false;
    });
});
