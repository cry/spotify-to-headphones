"use strict;"

toastr.options.progressBar = true;
toastr.options.timeOut = 10000;
toastr.options.extendedTimeOut = 20000;

var s2h = new Object;

    s2h.showSongs = function(_id, _name, _owner_id) {
        let id = atob(_id),
            name = atob(_name),
            owner_id = atob(_owner_id);

        console.warn("Action: Retrieve HTML song list");
        console.info("ID: " + id);
        console.info("Name: " + name);
        console.info("Owner ID: " + owner_id);
        console.info("AJAX Url: " + "ajax/playlist_songs/" + owner_id + "/" + id + "/html");

        $("body").addClass("loading");

        $.get("ajax/playlist_songs/" + owner_id + "/" + id + "/html", function(data) {
            $("#modal-body").html(data);

            $('#modal-title').text(name);

            $("body").removeClass("loading");
        
            $('#modal').modal('toggle');
        });
    }

    s2h.downloadAlbum = function(_album, _artist) {
        let album = atob(_album),
            artist = atob(_artist);

        console.warn("Action: Queue album for download");
        console.info("Album: " + album + " | " + _album);
        console.info("Artist: " + artist + " | " + _artist);

        console.info("AJAX Url: " + "ajax/queue_album/" + encodeURIComponent(_artist) + "/" + encodeURIComponent(_album));

        toastr.info('Attempting to send ' + album + ' - ' + artist);

        $.get("ajax/queue_album/" + encodeURIComponent(_artist) + "/" + encodeURIComponent(_album), function(data) {
            console.log(data);
            if (data.result) {
                toastr.success(data.msg);
            } else {
                toastr.error(data.msg);
            };
        });

    }