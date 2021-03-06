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
        $.getJSON("status.php?t=prog&callback=?", function(data, status, xhr) {
            var $target = $('#programs tbody');
            var $sel = $("#ch");

            $sel.empty();

            for(var i = 0; i < data.length; i++) {
                var item = data[i];
                $('<option>').attr("value", item.channel).text(item.channel + ": " + item.name).appendTo($sel);
                item.starttime_rel = reltime(item.starttime);
                item.endtime_rel = reltime(item.endtime, item.starttime);
            }

            var template = $target.data('template');
            $target.data('programs', data);
            $target.html(template(data));
        });
    };

    var hb_program = Handlebars.compile($('#hb-program').html());
    $('#programs tbody').data('template', hb_program);
    var hb_process = Handlebars.compile($('#hb-process').html());
    $('#processes tbody').data('template', hb_process);
    var hb_alerts = Handlebars.compile($('#alerts').html());
    $('#alerts').data('template', hb_alerts);
    var hb_selected_program = Handlebars.compile($('#hb-selected-program').html());
    $('#selected-program').data('template', hb_selected_program);

    var updateProcesses = function() {
        $.getJSON("status.php?t=proc&callback=?", function(data, status, xhr) {
            var $target = $('#processes tbody');
            var template = $target.data('template');
            console.log(data);
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

    var showAlerts = function(args, heading, klass) {
        var template = $('#alerts').data('template');
        var $target = $('#content');
        var data = {
            "heading": heading,
            "body": args[0],
            "alert_class": klass
        };
        var $div = $(template(data));

        $target.prepend($div);
    };

    var showCommandResult = function(data, status, xhr) {
        if(data.errors.length > 0) {
            showAlerts(data.errors, "エラー", "alert-danger");
        } else if(data.commands.length > 0) {
            showAlerts(data.commands, "コマンドを実行", "alert-success");
        }
        updateProcesses();
    };

    var updateProgramDetail = function(ch) {
        var template = $('#selected-program').data('template');
        var programs = $('#programs tbody').data('programs');
        var data = programs.filter(function(v, k, a) {
            return v["channel"] == ch;
        });
        $('#selected-program').html(template(data[0]));
    };

    $('#programs tbody').on("click", "a[href='#new-stream']", function(e) {
        var $self = $(e.currentTarget);
        var ch = $self.attr("data-ch");

        $('#ch').val(ch).toggleClass('selected', true);
        $('text,[name="ch"]').val(ch).toggleClass('selected', true);
        
        $('tr[class*="selected"]').toggleClass('selected', false);
        $('#ch' + ch).toggleClass("selected", true);

        $('#ch').val(ch);

        updateProgramDetail(ch);

        $('#new-stream').modal("show");
    });
    $('#processes tbody').on("click", "a[data-action='kill']", function(e) {
        var $self = $(e.currentTarget);
        var pid = $self.attr("data-pid");
        var request = { action: "kill", pid: pid };

        $.getJSON("command.php?callback=?", request, showCommandResult);
    });

    $('#new-stream').on("click", "a[data-action='start']", function(e) {
        var $self = $(e.currentTarget);
        var request = {
            action: $self.attr("data-method"),
            device: $('#device').val(),
            addr: $('#addr').val(),
            port: $('#port').val(),
            tcp_port: $('#tcp_port').val(),
            ch: $('#ch').val(),
            b25: $('#b25').val(),
            strip: $('#strip').val(),
            preset: $self.attr('data-preset')
        };
        $.getJSON("command.php?callback=?", request, showCommandResult);
        return false;
    });
    $("a[data-role='reload']").on("click", function(e) {
        updatePeriodic();
    });
    $('#ch').on("change", function(e) {
        var $self = $(e.currentTarget);
        var ch = $self.val();
        updateProgramDetail(ch);
    });
});
