<?php

namespace CareyLi\s2h;

use \utilphp\util;

class AjaxController
{

    private $model;
    private $view;
    private $hp_keys;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->view = new \League\Plates\Engine("./src/api_templates");

        $this->hp_keys = $this->model->getHpClientDetails();
    }

    private function return_json($data) {
        header("Content-type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function playlist_songs($user, $id) {
        $data = $this->model->getPlaylistSongs($user, $id);

        //util::var_dump($data);
        $this->return_json($data);
    }

    public function html_playlist_songs($user, $id) {
        $data = $this->model->getPlaylistSongs($user, $id);
        $wanted = $this->get_wanted();

        foreach ($data as &$song) {
            foreach ($wanted as $item) {
                if (stristr($song['album'], $item) || levenshtein($song['album'], $item) < 5) {
                    $song['wanted'] = true;
                }
            }
        }

        echo $this->view->render("song_table", array(
            "songs" => $data
        ));
    }

    public function get_wanted() {

        $base_url = $this->hp_keys['headphones_host'] . "/api/?apikey=" . $this->hp_keys['headphones_key'];

        // Get wanted

        $wanted = json_decode(file_get_contents($base_url . "&cmd=getWanted"));

        $albums = [];

        foreach ($wanted as $item) {
            $albums[] = $item->AlbumTitle;
        }

        // Get downloaded

        $downloaded = json_decode(file_get_contents($base_url . "&cmd=getHistory"));

        foreach ($downloaded as $item) {
            $album = json_decode(file_get_contents($base_url . "&cmd=getAlbum&id=" . $item->AlbumID));

            $albums[] = $album->album[0]->AlbumTitle;
        }

        return $albums;

    }

    public function queue_album($artist, $album) {

        $base_url = $this->hp_keys['headphones_host'] . "/api/?apikey=" . $this->hp_keys['headphones_key'];

        // Remove any extraenous tags in album name

        $regex = array(
            "/\s?\(.*\)/",
            "/\s?\[.*\]/"
        );

        $album = preg_replace($regex, array("", ""), $album);

        $raw_search_results = file_get_contents($base_url . "&cmd=findAlbum&name=" . urlencode($album));

        if ($raw_search_results == "Incorrect API key") {
            $this->return_json(array(
                "result" => false,
                "msg" => "Incorrect API key"
            ));

            exit;
        } elseif (!$raw_search_results) {
            $this->return_json(array(
                "result" => false,
                "msg" => "Host could not be reached"
            ));

            exit;
        }

        $search_results = json_decode($raw_search_results);

        // Try precise matching

        foreach ($search_results as $result) {
            if (stristr($result->uniquename, $artist) && stristr($result->title, $album)) {

                $queueResponse = file_get_contents($base_url . "&cmd=addAlbum&id=" . urlencode($result->albumid));

                $this->return_json(array(
                    "result" => true,
                    "match_type" => "stristr",
                    "msg" => "Sent " . $album . " to Headphones!"
                ));

                exit;

            }        
        }

        // Fallback to fuzzy matching

        foreach ($search_results as $result) {
            if (levenshtein($result->uniquename, $artist) < 5 && levenshtein($result->title, $album) < 5) {

                $queueResponse = file_get_contents($base_url . "&cmd=addAlbum&id=" . urlencode($result->albumid));

                $this->return_json(array(
                    "result" => true,
                    "match_type" => "levenshtein",
                    "msg" => "Sent " . $album . " to Headphones!"
                ));

                exit;
                
            }
        }

        $this->return_json(array(
            "result"  => false,
            "msg" => "No results found for ". $album,
            "debug" => $search_results
        ));
    }

}