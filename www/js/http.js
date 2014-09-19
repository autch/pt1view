jQuery(function() {
    var getUrlVars = function() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
    }

    var query = getUrlVars();

    if(query["url"]) {
        var url = query["url"];

        if(/\.m3u8$/.test(url)) {
            $('video').attr("src", url);
        } else if(/\.mpd$/.test(url)) {
            var context, player;

            context = new Dash.di.DashContext();
            player = new MediaPlayer(context);
            player.startup();
            player.attachView($("video").get(0));
            player.setAutoPlay(false);
            player.attachSource(url);
        }
    }
});
