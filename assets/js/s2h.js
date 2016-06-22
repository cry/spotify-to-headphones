"use strict;"

var s2h = new Object;

    s2h.showSongs = function(_id, _name, _owner_id) {
        let id = atob(_id),
            name = atob(_name),
            owner_id = atob(_owner_id);

        console.warn("triggered show songs");
        console.info("id: " + id);
        console.info("name: " + name);
        console.info("owner_id: " + owner_id);
        console.info("api url: " + "ajax/playlist_songs/" + owner_id + "/" + id + "/html");

        $.get("ajax/playlist_songs/" + owner_id + "/" + id + "/html", function(data) {
            $("#modal-body").html(data);

            $('#modal-title').text(name);
        
            $('#modal').modal('toggle');
        });
    }

    s2h.downloadAlbum = function(_album, _artist) {
        let album = atob(_album),
            artist = atob(_artist);

        console.warn("triggered download album");
        console.info("album: " + album + " | " + _album);
        console.info("artist: " + artist + " | " + _artist);

        console.info("api url: " + "ajax/queue_album/" + encodeURIComponent(_artist) + "/" + encodeURIComponent(_album));

        $.get("ajax/queue_album/" + encodeURIComponent(_artist) + "/" + encodeURIComponent(_album), function(data) {
            console.log(data);
        });

    }