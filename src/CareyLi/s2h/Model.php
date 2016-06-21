<?php

namespace CareyLi\s2h;
	
use \utilphp\util;

class Model
{

	private $db;
	private $spotify_api;

    public function __construct()
    {
    	if (!file_exists("s2h.db")) {
    		$this->db = new \PDO("sqlite:s2h.db");
    		$schema = file_get_contents("schema.sql");
    		$this->db->exec($schema);
    	} else {
    		$this->db = new \PDO("sqlite:s2h.db");
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
    		$result = $this->executePreparedSelect($sql);
    	} catch	(Exception $e) {
    		throw new \Exception("Could not retrieve spotify keys.");
    	}

    	return array(
    		"client_id" => $result['client_id'],
    		"client_secret" => $result['client_secret']
    	);
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

		// Request a access token using the code from Spotify
		$session->requestAccessToken($code);
		$accessToken = $session->getAccessToken();

		// Set the access token on the API wrapper
		$this->spotify_api->setAccessToken($accessToken);

		// Write the tokens to persistent storage

		$sql = "INSERT INTO api_data (access_token, refresh_token) VALUES (:access_token, :refresh_token);";

		$this->db->beginTransaction();

		$this->executePreparedInsert($sql, array(
			":access_token" => $accessToken,
			":refresh_token" => $session->getRefreshToken()
		));

		$this->db->commit();
    }
}