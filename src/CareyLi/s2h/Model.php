<?php

namespace CareyLi\s2h;
	
use \utilphp\util;

class Model
{

	private $db;
	private $spotify_api;

    /**
     * Init of DB
     */
    public function __construct()
    {
    	if (!file_exists("s2h")) {
    		$this->db = new \PDO("sqlite:s2h");
    		$schema = file_get_contents("schema.sql");
    		$this->db->exec($schema);
    	} else {
    		$this->db = new \PDO("sqlite:s2h");
    	}

    	if (!$this->isFirstRun()) {
    		/*
	    	 * Get spotify API hook
	    	 */

	    	$spotify_keys = $this->getSpotifyKeys();

	    	$this->spotify_api = new \SpotifyWebAPI\SpotifyWebAPI();

	    	$spotify_session = new \SpotifyWebAPI\Session($spotify_keys['client_id'], $spotify_keys['client_secret'], 'http://localhost:8081/');

	    	$scopes = array(
			    'playlist-read-private',
			    'user-read-private'
			);

			$spotify_session->requestCredentialsToken($scopes);
			$accessToken = $spotify_session->getAccessToken();
			$this->spotify_api->setAccessToken($accessToken);	
    	}

    }

    public function executePreparedInsert($sql, $params) {
    	$handle = $this->db->prepare($sql);

    	return $handle->execute($params);
    }

    public function executePreparedSelect($sql, $params = array()) {
    	$handle = $this->db->prepare($sql);
    	$handle->execute($params);

    	return $handle->fetch();
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

		$sql = "INSERT INTO api_data (client_id, client_secret, headphones_key) VALUES (:client_id, :client_secret, :headphones_key);";

		$this->db->beginTransaction();

		$this->executePreparedInsert($sql, array(
			":client_id" => $params['client_id'],
			":client_secret"  => $params['client_secret'],
			":headphones_key" => $params['headphones_key']
		));

		$this->db->commit();

		return true;

    }

    public function getSpotifyKeys() {
    	$sql = "SELECT client_id, client_secret FROM api_data";

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
}