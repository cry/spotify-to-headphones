<?php

namespace CareyLi\s2h;

use \utilphp\util;

class AjaxController
{

    private $model;
    private $view;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->view = new \League\Plates\Engine("./src/api_templates");
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

        echo $this->view->render("song_table", array(
            "songs" => $data
        ));
    }

    public function queue_album($artist, $album) {
        $hp_data = $this->model->getHpClientDetails();

        $base_url = $hp_data['headphones_host'] . "/api/?apikey=" . $hp_data['headphones_key'];

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

        //$this->return_json($search_results);

        foreach ($search_results as $result) {
            if (stristr($result->uniquename, $artist) && stristr($result->title, $album)) {

                //$addResponse = file_get_contents($base_url . "&cmd=addAlbum&id=" . urlencode($result->rgid));

                //sleep(5); //Because no callbacks derp.

                $queueResponse = file_get_contents($base_url . "&cmd=queueAlbum&id=" . urlencode($result->albumid));

                if ($queueResponse == "OK") {
                    $this->return_json("Sent to Headphones!");
                }

                //$this->return_json($base_url . "&cmd=queueAlbum&id=" . urlencode($result->rgid));

                exit;
            }        
        }
    }

}