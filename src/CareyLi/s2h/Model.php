<?php

namespace CareyLi\s2h;
	
use \utilphp\util;

class Model
{

	private $db;
	private $spotify_api;
	private $spotify_user;

    public function __construct()
    {
    	if (!file_exists("s2h.db")) {
    		$this->db = new \PDO("sqlite:s2h.db");
    		$schema = file_get_contents("schema.sql");
    		$this->db->exec($schema);
    	} else {
    		$this->db = new \PDO("sqlite:s2h.db");
    	}

    	if ($this->spotifyAccessTokensExist()) {

    		$tokens = $this->getSpotifyClientDetails();
    		
    		$session = new \SpotifyWebAPI\Session($tokens['client_id'], $tokens['client_secret'], 'http://' . $_SERVER['HTTP_HOST'] . "/spotify_callback/");
			$this->spotify_api = new \SpotifyWebAPI\SpotifyWebAPI();

			$session->refreshAccessToken($tokens['refresh_token']);

			$accessToken = $session->getAccessToken();

			$this->spotify_api->setAccessToken($accessToken);

			$this->spotify_user = $this->spotify_api->me();
    	}
    }

    public function executePreparedInsert($sql, $params) {
    	$handle = $this->db->prepare($sql);

    	return $handle->execute($params);
    }

    public function executePreparedSelect($sql, $params = array()) {
    	$handle = $this->db->prepare($sql);
    	$handle->execute($params);

    	return $handle->fetch(\PDO::FETCH_ASSOC);
    }

    public function isFirstRun() {
    	$check = $this->db->prepare('SELECT count(*) FROM api_data');
    	$check->execute();

    	if ($check->fetchColumn() === "0") {
    		return true;
    	}

    	return false;
    }

    public function updateSettings($params) {

		$sql = "INSERT INTO api_data (client_id, client_secret, headphones_key, headphones_host) VALUES (:client_id, :client_secret, :headphones_key, :headphones_host);";

		$this->db->beginTransaction();

		$this->executePreparedInsert($sql, array(
			":client_id" => $params['client_id'],
			":client_secret"  => $params['client_secret'],
			":headphones_key" => $params['headphones_key'],
			":headphones_host" => $params['headphones_host']
		));

		$this->db->commit();

		return true;

    }

    /*
     *
     * Spotify related functions.
     * ::: Authorization & Administration :::
     *
     */

    public function spotifyAccessTokensExist() {

    	$data = $this->executePreparedSelect("SELECT * FROM api_data");

    	if ($data['access_token'] == null || $data['refresh_token'] == null) {
    		return false;
    	}

    	return true;
    }

    private function getSpotifyClientDetails() {
    	$sql = "SELECT client_id, client_secret, access_token, refresh_token FROM api_data";

    	try {
    		return $this->executePreparedSelect($sql);
    	} catch	(Exception $e) {
    		throw new \Exception("Could not retrieve spotify keys.");
    	}
    }

    public function getSpotifyAuthorizeUrl() {
    	$client = $this->getSpotifyClientDetails();

    	$session = new \SpotifyWebAPI\Session($client['client_id'], $client['client_secret'], 'http://' . $_SERVER['HTTP_HOST'] . "/spotify_callback/");

    	$scopes = array(
		    'playlist-read-private',
		    'user-read-private'
		);

		$authorizeUrl = $session->getAuthorizeUrl(array(
		    'scope' => $scopes
		));

		return $authorizeUrl;
    }

    public function setSpotifyAccessToken($code) {
    	$client = $this->getSpotifyClientDetails();

    	$session = new \SpotifyWebAPI\Session($client['client_id'], $client['client_secret'], 'http://' . $_SERVER['HTTP_HOST'] . "/spotify_callback/");

		$this->spotify_api = new \SpotifyWebAPI\SpotifyWebAPI();

		$session->requestAccessToken($code);
		$accessToken = $session->getAccessToken();

		$this->spotify_api->setAccessToken($accessToken);

		$sql = "UPDATE api_data SET access_token=:access_token, refresh_token=:refresh_token WHERE client_id=:client_id;";

		$this->db->beginTransaction();

		$this->executePreparedInsert($sql, array(
			":access_token" => $accessToken,
			":refresh_token" => $session->getRefreshToken(),
			":client_id" => $client['client_id']
		));

		$this->db->commit();

		return true;
    }

    /*
     *
     * Spotify related functions.
     * ::: User data retrieval :::
     *
     * __function() --> Raw spotify ingest data
     */

    private function __getUserPlaylists() {
    	return $this->spotify_api->getMyPlaylists();
    }

    private function __getPlaylistSongs($user, $id, $index = 0) {
    	return $this->spotify_api->getUserPlaylistTracks($user, $id, array(
            "offset" => $index
        ));
    }

    public function getUserPlaylists() { //return $this->__getUserPlaylists();
    	$ingest_data = $this->__getUserPlaylists();
    	$playlists = array();

    	foreach ($ingest_data->items as $playlist) {

    		$playlists[] = array(
    			"name" => $playlist->name,
    			"id"   => $playlist->id,
    			"owner" => $playlist->owner->id,
    			"owner_uri" => $playlist->owner->uri,
                "owner_id" => $playlist->owner->id,
    			"uri"  => $playlist->uri,
    			"image" => count($playlist->images) === 0 ? "data:image/gif;base64,R0lGODlhAQABAIAAAMLCwgAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" : $playlist->images[0]->url
    		);
    	}

    	return $playlists;
    }

    public function getPlaylistSongs($user, $id) {
        $ingest_data = $this->__getPlaylistSongs($user, $id);
        $songs = array();
        $index = 0;

        get_additional:
        if ($ingest_data->total - $index > 100) {
            $index += 100;
            $additional_data = $this->__getPlaylistSongs($user, $id, $index);

            $ingest_data->items = array_merge($ingest_data->items, $additional_data->items);

            goto get_additional;
        } elseif (($ingest_data->total - $index) < 100 && ($ingest_data->total - $index) !== 0) {
            $index = $ingest_data->total;

            $additional_data = $this->__getPlaylistSongs($user, $id, $index);
            $ingest_data->items = array_merge($ingest_data->items, $additional_data->items);

            goto get_additional;
        }

        foreach ($ingest_data->items as $song) {
            $songs[] = array(
                "id" => $song->track->id,
                "name" => $song->track->name,
                "artist" => $song->track->artists[0]->name,
                "artist_id" => $song->track->artists[0]->id,
                "album" => $song->track->album->name,
                "album_id" => $song->track->album->id,
                "preview" => $song->track->preview_url
            );
        }

        return $songs;
    }

    /*
     *
     * Headphones related functions
     * ::: Authorization & Administration :::
     *
     */

    public function getHpClientDetails() {
        $sql = "SELECT headphones_key, headphones_host FROM api_data";

        try {
            return $this->executePreparedSelect($sql);
        } catch (Exception $e) {
            throw new \Exception("Could not retrieve headphones details.");
        }
    }

}